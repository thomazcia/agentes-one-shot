<?php
/**
 * Sistema de Gerenciamento de Modelos com Fallback
 * Agentes One-Shot v2.0
 */

// Carregar configurações do sistema (inclui função loadEnvVar)
require_once __DIR__ . '/config.php';

/**
 * Lista de modelos em ordem de prioridade (do mais preferido para o menos preferido)
 * Apenas modelos disponíveis e verificados na Groq API
 */
function getModelPriorityList() {
    return [
        // Modelos primários Groq (extremamente rápidos e gratuitos)
        [
            'id' => 'llama-3.1-8b-instant',
            'name' => 'Llama 3.1 8B Instant',
            'provider' => 'Groq',
            'category' => 'primary',
            'max_tokens' => 8192,
            'context_length' => 131072,
            'speed' => '~500 tokens/s'
        ],
        [
            'id' => 'llama-3.3-70b-versatile',
            'name' => 'Llama 3.3 70B Versatile',
            'provider' => 'Groq',
            'category' => 'primary',
            'max_tokens' => 8192,
            'context_length' => 131072,
            'speed' => '~250 tokens/s'
        ],

        // Modelos de Alta Performance (Llama 4)
        [
            'id' => 'meta-llama/llama-4-maverick-17b-128e-instruct',
            'name' => 'Llama 4 Maverick 17B',
            'provider' => 'Meta',
            'category' => 'premium',
            'max_tokens' => 4096,
            'context_length' => 128000,
            'speed' => '~50 tokens/s',
            'note' => 'Modelo de última geração'
        ],
        [
            'id' => 'meta-llama/llama-4-scout-17b-16e-instruct',
            'name' => 'Llama 4 Scout 17B',
            'provider' => 'Meta',
            'category' => 'premium',
            'max_tokens' => 4096,
            'context_length' => 128000,
            'speed' => '~60 tokens/s',
            'note' => 'Modelo de última geração'
        ],

        // Modelos alternativos
        [
            'id' => 'moonshotai/kimi-k2-instruct',
            'name' => 'Kimi K2 Instruct',
            'provider' => 'Moonshot AI',
            'category' => 'secondary',
            'max_tokens' => 8192,
            'context_length' => 32768,
            'speed' => '~180 tokens/s'
        ],

        // Modelo de Fala (TTS/ASR)
        [
            'id' => 'whisper-large-v3-turbo',
            'name' => 'Whisper Large v3 Turbo',
            'provider' => 'OpenAI',
            'category' => 'emergency',
            'max_tokens' => 8192,
            'context_length' => 8192,
            'speed' => '~100 tokens/s',
            'note' => 'Modelo de fala/transcrição'
        ]
    ];
}

/**
 * Obtém o modelo atual com base nas configurações
 */
function getCurrentModel() {
    // Tenta obter das variáveis de ambiente primeiro (ler do .env)
    $envModel = loadEnvVar('GROQ_MODEL', null);
    if ($envModel && isValidModel($envModel)) {
        return [
            'id' => $envModel,
            'is_fallback' => false,
            'source' => 'environment'
        ];
    }

    // Fallback para o primeiro modelo da lista de prioridade
    $models = getModelPriorityList();
    if (!empty($models)) {
        return [
            'id' => $models[0]['id'],
            'name' => $models[0]['name'],
            'is_fallback' => true,
            'source' => 'priority_list',
            'fallback_reason' => 'no_env_model'
        ];
    }

    // Último recurso - modelo hardcoded
    return [
        'id' => 'llama-3.1-8b-instant',
        'name' => 'Llama 3.1 8B Instant',
        'is_fallback' => true,
        'source' => 'hardcoded',
        'fallback_reason' => 'no_models_available'
    ];
}

/**
 * Verifica se um modelo é válido (está na lista de prioridades)
 */
function isValidModel($modelId) {
    $models = getModelPriorityList();
    foreach ($models as $model) {
        if ($model['id'] === $modelId) {
            return true;
        }
    }
    return false;
}

/**
 * Obtém informações detalhadas de um modelo
 */
function getModelInfo($modelId) {
    $models = getModelPriorityList();
    foreach ($models as $model) {
        if ($model['id'] === $modelId) {
            return $model;
        }
    }
    return null;
}

/**
 * Testa se um modelo está disponível na API
 */
function testModelAvailability($modelId, $apiKey = null) {
    if (!$apiKey) {
        $apiKey = GROQ_API_KEY;
    }

    $testData = [
        'model' => $modelId,
        'messages' => [
            [
                'role' => 'user',
                'content' => 'Test'
            ]
        ],
        'max_tokens' => 1,
        'temperature' => 0.1
    ];

    $ch = curl_init(GROQ_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return [
            'available' => false,
            'error' => 'curl_error',
            'message' => $error
        ];
    }

    $responseData = json_decode($response, true);

    // HTTP 200 significa sucesso
    if ($httpCode === 200) {
        return [
            'available' => true,
            'http_code' => $httpCode,
            'response_data' => $responseData
        ];
    }

    // HTTP 404 ou 400 geralmente significa modelo não encontrado
    if ($httpCode === 404 || ($httpCode === 400 &&
        isset($responseData['error']['message']) &&
        strpos($responseData['error']['message'], 'No endpoints found') !== false)) {
        return [
            'available' => false,
            'error' => 'model_not_found',
            'http_code' => $httpCode,
            'message' => $responseData['error']['message'] ?? 'Model not found'
        ];
    }

    // Outros erros
    return [
        'available' => false,
        'error' => 'api_error',
        'http_code' => $httpCode,
        'message' => $responseData['error']['message'] ?? 'API error'
    ];
}

/**
 * Obtém o próximo modelo disponível da lista
 */
function getNextAvailableModel($excludeModels = [], $apiKey = null) {
    $models = getModelPriorityList();

    foreach ($models as $model) {
        if (in_array($model['id'], $excludeModels)) {
            continue;
        }

        $test = testModelAvailability($model['id'], $apiKey);

        if ($test['available']) {
            return [
                'model' => $model,
                'is_fallback' => true,
                'fallback_level' => $model['category'],
                'tested' => true,
                'exclude_list' => $excludeModels
            ];
        }
    }

    // Se nenhum modelo passou no teste, retorna o primeiro da lista anyway
    if (!empty($models)) {
        return [
            'model' => $models[0],
            'is_fallback' => true,
            'fallback_level' => 'emergency',
            'tested' => false,
            'exclude_list' => $excludeModels,
            'note' => 'All models failed test, returning primary model as last resort'
        ];
    }

    return null;
}

/**
 * Executa chamada à API com fallback automático
 */
function callGroqAPIWithFallback($prompt, $options = []) {
    $apiKey = GROQ_API_KEY; // Usar constante definida em config.php
    $apiUrl = GROQ_API_URL; // Usar constante definida em config.php

    // Opções padrão
    $defaultOptions = [
        'max_tokens' => 4000,
        'temperature' => 0.7,
        'timeout' => 60,
        'test_models' => false // Se deve testar disponibilidade antes de usar
    ];

    $options = array_merge($defaultOptions, $options);

    // Lista de modelos que falharam
    $failedModels = [];
    $attempt = 0;
    $maxAttempts = 3;

    while ($attempt < $maxAttempts) {
        $attempt++;

        // Determina qual modelo usar
        if ($attempt === 1) {
            // Primeira tentativa: usar o modelo configurado
            $currentModel = getCurrentModel();
            $modelId = $currentModel['id'];
            $fallbackInfo = $currentModel;
        } else {
            // Tentativas seguintes: usar próximo modelo disponível
            $nextModel = getNextAvailableModel($failedModels, $apiKey);
            if (!$nextModel) {
                return [
                    'success' => false,
                    'message' => 'Nenhum modelo disponível após ' . ($attempt - 1) . ' tentativas',
                    'failed_models' => $failedModels,
                    'attempts' => $attempt - 1
                ];
            }

            $modelId = $nextModel['model']['id'];
            $fallbackInfo = array_merge($nextModel, ['attempt' => $attempt]);
        }

        // Testa modelo se solicitado
        if ($options['test_models']) {
            $test = testModelAvailability($modelId, $apiKey);
            if (!$test['available']) {
                $failedModels[] = $modelId;
                continue;
            }
        }

        // Prepara requisição
        $postData = [
            'model' => $modelId,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => $options['max_tokens'],
            'temperature' => $options['temperature']
        ];

        // Executa requisição
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Log para debug
        error_log("Groq API - Attempt $attempt - Model: $modelId - HTTP: $httpCode");

        if ($error) {
            $failedModels[] = $modelId;
            continue;
        }

        $responseData = json_decode($response, true);

        // Sucesso
        if ($httpCode === 200) {
            return [
                'success' => true,
                'data' => $responseData,
                'model_used' => [
                    'id' => $modelId,
                    'info' => getModelInfo($modelId),
                    'attempt' => $attempt,
                    'fallback_info' => $fallbackInfo
                ]
            ];
        }

        // Modelo não encontrado ou indisponível
        if ($httpCode === 404 || ($httpCode === 400 &&
            isset($responseData['error']['message']) &&
            (strpos($responseData['error']['message'], 'No endpoints found') !== false ||
             strpos($responseData['error']['message'], 'not found') !== false))) {
            $failedModels[] = $modelId;
            error_log("Model $modelId not available: " . ($responseData['error']['message'] ?? 'Unknown error'));
            continue;
        }

        // Outros erros (rate limit, etc.)
        $failedModels[] = $modelId;
        error_log("API error with model $modelId: " . ($responseData['error']['message'] ?? 'Unknown error'));

        // Se for erro de rate limit, não tenta outro modelo
        if ($httpCode === 429) {
            return [
                'success' => false,
                'message' => 'Rate limit exceeded. Tente novamente mais tarde.',
                'error_code' => 'RATE_LIMIT',
                'retry_after' => $responseData['error']['retry_after'] ?? 60,
                'model_used' => $modelId
            ];
        }
    }

    // Todas as tentativas falharam
    return [
        'success' => false,
        'message' => 'Todos os modelos falharam após ' . $maxAttempts . ' tentativas',
        'failed_models' => $failedModels,
        'attempts' => $maxAttempts
    ];
}

/**
 * Salva o modelo que funcionou para uso futuro (cache simples)
 */
function cacheWorkingModel($modelId) {
    $cacheFile = __DIR__ . '/cache/working_model.json';
    $cacheDir = dirname($cacheFile);

    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    $cacheData = [
        'model_id' => $modelId,
        'timestamp' => time(),
        'date' => date('Y-m-d H:i:s')
    ];

    file_put_contents($cacheFile, json_encode($cacheData, JSON_PRETTY_PRINT));
}

/**
 * Obtém o último modelo que funcionou (do cache)
 */
function getCachedWorkingModel($maxAge = 3600) { // 1 hora
    $cacheFile = __DIR__ . '/cache/working_model.json';

    if (!file_exists($cacheFile)) {
        return null;
    }

    $cacheData = json_decode(file_get_contents($cacheFile), true);

    // Verifica se o cache não está expirado
    if (time() - $cacheData['timestamp'] > $maxAge) {
        unlink($cacheFile);
        return null;
    }

    return $cacheData['model_id'] ?? null;
}
?>