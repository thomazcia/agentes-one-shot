<?php
// Endpoint para testar disponibilidade de um modelo
header('Content-Type: application/json');

require_once 'config.php';
require_once 'models.php';

// Apenas requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Obter model_id do POST
$input = json_decode(file_get_contents('php://input'), true);
$modelId = $input['model_id'] ?? '';

if (empty($modelId)) {
    http_response_code(400);
    echo json_encode(['error' => 'model_id is required']);
    exit;
}

// Verificar se é um modelo válido
if (!isValidModel($modelId)) {
    echo json_encode([
        'available' => false,
        'error' => 'invalid_model',
        'message' => 'Model not in priority list'
    ]);
    exit;
}

// Testar disponibilidade
$result = testModelAvailability($modelId);

echo json_encode($result);
?>