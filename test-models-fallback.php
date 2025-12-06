<?php
// Teste do sistema de fallback de modelos
header('Content-Type: application/json');

require_once 'config.php';
require_once 'models.php';

// Simula requisição
$_POST = [
    'agent_id' => 'contador-piada',
    'assunto' => 'inteligência artificial'
];

echo json_encode([
    'test' => 'models_fallback',
    'timestamp' => date('Y-m-d H:i:s'),
    'current_model' => getCurrentModel(),
    'priority_list_count' => count(getModelPriorityList()),
    'available_models' => array_map(function($m) {
        return [
            'id' => $m['id'],
            'name' => $m['name'],
            'category' => $m['category']
        ];
    }, getModelPriorityList())
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);