<?php
/**
 * Teste de disponibilidade de modelo para o Model Status Dashboard
 * Agentes One-Shot v2.0 - Groq API Integration
 */

header('Content-Type: application/json');

// Carregar configurações
require_once 'config.php';
require_once 'models.php';

// Obter modelo da requisição
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['model_id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'available' => false,
        'message' => 'Model ID not provided'
    ]);
    exit;
}

$modelId = $input['model_id'];

// Validar se o modelo existe na nossa lista
if (!isValidModel($modelId)) {
    echo json_encode([
        'success' => true,
        'available' => false,
        'message' => 'Model not found in priority list',
        'model_id' => $modelId
    ]);
    exit;
}

// Testar disponibilidade do modelo
$test = testModelAvailability($modelId);

if ($test['available']) {
    echo json_encode([
        'success' => true,
        'available' => true,
        'model_id' => $modelId,
        'message' => 'Model available and working',
        'response_data' => $test['response_data'] ?? null
    ]);
} else {
    $errorMessages = [
        'curl_error' => 'Connection error',
        'model_not_found' => 'Model not found on API',
        'api_error' => 'API error'
    ];

    $message = $errorMessages[$test['error']] ?? $test['message'] ?? 'Unknown error';

    echo json_encode([
        'success' => true,
        'available' => false,
        'model_id' => $modelId,
        'message' => $message,
        'error_code' => $test['error'] ?? null,
        'http_code' => $test['http_code'] ?? null
    ]);
}
?>