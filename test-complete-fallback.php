<?php
// Teste completo do sistema de fallback
header('Content-Type: application/json');

require_once 'config.php';
require_once 'models.php';

// Teste de execução com fallback
$testPrompt = "Crie uma piada curta sobre programação.";

echo "=== TESTE COMPLETO DO SISTEMA DE FALLBACK ===\n\n";

echo "1. MODELO ATUAL:\n";
$current = getCurrentModel();
echo "   ID: " . $current['id'] . "\n";
echo "   Fallback: " . ($current['is_fallback'] ? 'YES' : 'NO') . "\n";
echo "   Source: " . $current['source'] . "\n\n";

echo "2. TESTANDO MODELO ATUAL:\n";
$test = testModelAvailability($current['id']);
echo "   Disponível: " . ($test['available'] ? 'YES' : 'NO') . "\n";
if (!$test['available']) {
    echo "   Erro: " . ($test['message'] ?? 'Unknown') . "\n";
}
echo "\n";

echo "3. EXECUTANDO COM FALLBACK:\n";
$response = callOpenRouterAPIWithFallback($testPrompt, [
    'max_tokens' => 100,
    'temperature' => 0.7,
    'timeout' => 30,
    'test_models' => false
]);

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "4. ESTATÍSTICAS:\n";
if ($response['success']) {
    echo "   ✓ Sucesso na tentativa " . $response['model_used']['attempt'] . "\n";
    echo "   ✓ Modelo usado: " . $response['model_used']['id'] . "\n";
    echo "   ✓ Nome: " . ($response['model_used']['info']['name'] ?? 'N/A') . "\n";
    echo "   ✓ Provider: " . ($response['model_used']['info']['provider'] ?? 'N/A') . "\n";
} else {
    echo "   ✗ Falha em todas as tentativas\n";
    echo "   ✗ Modelos que falharam: " . (isset($response['failed_models']) ? implode(', ', $response['failed_models']) : 'N/A') . "\n";
}

echo "\n5. MODELOS NA LISTA DE PRIORIDADE:\n";
$models = getModelPriorityList();
foreach ($models as $model) {
    echo "   - " . $model['id'] . " (" . $model['category'] . ")\n";
}

echo "\n=== FIM DO TESTE ===\n";