<?php
// Simulação completa da execução de um agente via requisição POST
header('Content-Type: application/json');

// Simula dados POST
$_POST = [
    'agent_id' => 'contador-piada',
    'assunto' => 'programação'
];

// Habilita todos os logs de erro
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Inicia buffer de saída para capturar qualquer erro
ob_start();

echo "=== SIMULAÇÃO COMPLETA DE EXECUÇÃO ===\n\n";

try {
    // Carrega tudo como no arquivo original
    echo "1. Carregando dependências...\n";
    require_once 'config.php';
    require_once 'agentes.php';
    echo "✓ Dependências carregadas\n\n";

    // Simula a lógica do executeAgent
    echo "2. Obtendo agente...\n";
    $agentId = $_POST['agent_id'];
    $agent = getAgent($agentId);

    if (!$agent) {
        echo "✗ Agente não encontrado: $agentId\n";
        exit;
    }
    echo "✓ Agente encontrado: {$agent['name']}\n\n";

    // Remove campos que não vamos usar na resposta
    $agentResponse = [
        'id' => $agent['id'],
        'name' => $agent['name'],
        'description' => $agent['description']
    ];

    echo "3. Construindo prompt...\n";
    $finalPrompt = $agent['prompt'];

    // Substituir placeholders
    if (!empty($agent['fields'])) {
        foreach ($agent['fields'] as $field) {
            $fieldName = $field['label'];
            $placeholder = '[' . $fieldName . ']';
            $value = $_POST[$fieldName] ?? '';
            $finalPrompt = str_replace($placeholder, $value, $finalPrompt);
        }

        // Adicionar contexto
        $context = "\n\n---\nDados informados:\n";
        foreach ($agent['fields'] as $field) {
            $fieldName = $field['label'];
            $value = $_POST[$fieldName] ?? '';
            $context .= "{$fieldName}: {$value}\n";
        }
        $finalPrompt .= $context;
    }
    echo "✓ Prompt construído (" . strlen($finalPrompt) . " caracteres)\n\n";

    echo "4. Chamando API...\n";
    $apiResponse = callOpenRouterAPI($finalPrompt);

    if ($apiResponse['success']) {
        echo "✓ API retornou sucesso\n";
        if (isset($apiResponse['data']['choices'][0]['message']['content'])) {
            $content = $apiResponse['data']['choices'][0]['message']['content'];
            echo "✓ Resposta obtida (" . strlen($content) . " caracteres)\n";
        }
    } else {
        echo "✗ Erro na API: " . $apiResponse['message'] . "\n";
    }

    // Captura qualquer saída de erro
    $errorOutput = ob_get_clean();

    // Prepara resposta final
    $response = [
        'success' => $apiResponse['success'] ?? false,
        'message' => $apiResponse['message'] ?? '',
        'agent' => $agentResponse,
        'prompt_length' => strlen($finalPrompt),
        'debug' => [
            'agent_loaded' => $agent ? true : false,
            'prompt_built' => $finalPrompt ? true : false,
            'api_called' => true,
            'error_output' => $errorOutput,
            'post_data' => $_POST
        ]
    ];

    if ($apiResponse['success'] && isset($apiResponse['data']['choices'][0]['message']['content'])) {
        $response['content'] = $apiResponse['data']['choices'][0]['message']['content'];
    }

    // Adiciona mais informações de debug
    if (function_exists('getConfig')) {
        $response['debug']['config'] = [
            'api_key_set' => getConfig('openrouter_api_key') ? true : false,
            'api_key_placeholder' => strpos(getConfig('openrouter_api_key') ?? '', 'sua-chave') !== false,
            'api_url' => getConfig('openrouter_api_url'),
            'model' => getConfig('grok_model')
        ];
    }

} catch (Exception $e) {
    $errorOutput = ob_get_clean();
    $response = [
        'success' => false,
        'message' => 'Erro: ' . $e->getMessage(),
        'debug' => [
            'exception' => $e->getTraceAsString(),
            'error_output' => $errorOutput,
            'post_data' => $_POST ?? []
        ]
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);