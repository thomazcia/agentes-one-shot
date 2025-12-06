<?php
// Teste de execução de agente para diagnóstico
header('Content-Type: text/plain; charset=utf-8');

echo "=== TESTE DE EXECUÇÃO DE AGENTE ===\n\n";

// Carrega as dependências
require_once 'config.php';
require_once 'agentes.php';

echo "1. Verificando sistema de agentes:\n";

// Lista todos os agentes
$agents = getAgents();
echo "Número de agentes encontrados: " . count($agents) . "\n\n";

foreach ($agents as $agent) {
    echo "- {$agent['name']} (ID: {$agent['id']})\n";
}

echo "\n2. Testando carregamento do agente 'contador-piada':\n";
$agent = getAgent('contador-piada');

if ($agent) {
    echo "✓ Agente carregado com sucesso\n";
    echo "Nome: {$agent['name']}\n";
    echo "Descrição: {$agent['description']}\n";
    echo "Número de campos: " . count($agent['fields'] ?? []) . "\n";

    if (!empty($agent['fields'])) {
        foreach ($agent['fields'] as $field) {
            echo "  Campo: {$field['label']} ({$field['type']})\n";
        }
    }
} else {
    echo "✗ Falha ao carregar o agente\n";
}

echo "\n3. Testando construção do prompt:\n";
if ($agent) {
    $testValues = ['assunto' => 'gatos programadores'];
    $finalPrompt = $agent['prompt'];

    // Substituir placeholders
    if (!empty($agent['fields'])) {
        foreach ($agent['fields'] as $field) {
            $fieldName = $field['label'];
            $placeholder = '[' . $fieldName . ']';
            $value = $testValues[$fieldName] ?? '';
            $finalPrompt = str_replace($placeholder, $value, $finalPrompt);
        }

        // Adicionar contexto
        $context = "\n\n---\nDados informados:\n";
        foreach ($agent['fields'] as $field) {
            $fieldName = $field['label'];
            $value = $testValues[$fieldName] ?? '';
            $context .= "{$fieldName}: {$value}\n";
        }
        $finalPrompt .= $context;
    }

    echo "Prompt construído:\n";
    echo "-----------------\n";
    echo $finalPrompt . "\n";
    echo "-----------------\n";
}

echo "\n4. Testando configuração da API:\n";
$apiKey = getConfig('openrouter_api_key');
$apiUrl = getConfig('openrouter_api_url');
$model = getConfig('grok_model');

echo "API Key configurada: " . ($apiKey ? 'SIM' : 'NÃO') . "\n";
if ($apiKey) {
    echo "  Começa com 'sk-or-v1': " . (strpos($apiKey, 'sk-or-v1') === 0 ? 'SIM' : 'NÃO') . "\n";
    echo "  É placeholder: " . (strpos($apiKey, 'sua-chave') !== false ? 'SIM' : 'NÃO') . "\n";
}
echo "API URL: " . ($apiUrl ?: 'NÃO DEFINIDA') . "\n";
echo "Model: " . ($model ?: 'NÃO DEFINIDO') . "\n";

echo "\n5. Teste de conexão com a API (sem executar):\n";
if ($apiKey && $apiUrl && $model) {
    echo "✓ Configurações básicas presentes\n";

    // Preparar dados da requisição
    $testData = [
        'model' => $model,
        'messages' => [
            [
                'role' => 'user',
                'content' => 'Teste de conexão'
            ]
        ],
        'max_tokens' => 10,
        'temperature' => 0.7
    ];

    echo "Dados preparados:\n";
    echo "- Model: {$testData['model']}\n";
    echo "- Messages: " . count($testData['messages']) . "\n";
    echo "- Max tokens: {$testData['max_tokens']}\n";
    echo "- Temperature: {$testData['temperature']}\n";

    // Testar inicialização do cURL
    echo "\nTeste cURL:\n";
    $ch = curl_init($apiUrl);
    if ($ch) {
        echo "✓ cURL inicializado\n";

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true); // Apenas verifica conectividade
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        // Testar headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        if ($error) {
            echo "✗ Erro cURL: $error\n";
        } else {
            echo "✓ Conectividade OK (HTTP $httpCode)\n";
        }

        curl_close($ch);
    } else {
        echo "✗ Falha ao inicializar cURL\n";
    }
} else {
    echo "✗ Configurações incompletas\n";
}

echo "\n=== FIM DO TESTE ===\n";