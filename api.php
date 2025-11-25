<?php
/**
 * API Backend - Agentes One-Shot v1.0
 * Endpoint para processar requisições AJAX
 */

require_once 'config.php';
require_once 'agentes.php';

// Set headers for JSON response
header('Content-Type: application/json; charset=utf-8');

// Get the requested action
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_agents':
            // Obter todos os agentes
            $agents = getAgents();
            echo json_encode([
                'success' => true,
                'data' => $agents
            ]);
            break;

        case 'get_agent':
            // Obter um agente específico
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                throw new Exception('ID do agente não fornecido');
            }

            $agent = getAgent($id);
            if (!$agent) {
                throw new Exception('Agente não encontrado');
            }

            echo json_encode([
                'success' => true,
                'data' => $agent
            ]);
            break;

        // save_agent e delete_agent removidos - agentes agora são arquivos PHP
        case 'get_agent_stats':
            // Obter estatísticas dos agentes
            $stats = getAgentStats();
            echo json_encode([
                'success' => true,
                'data' => $stats
            ]);
            break;

        case 'execute_agent':
            // Executar agente
            $id = $_POST['agent_id'] ?? '';
            if (empty($id)) {
                throw new Exception('ID do agente não fornecido');
            }

            $fieldValues = json_decode($_POST['field_values'] ?? '{}', true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Valores dos campos inválidos');
            }

            $result = executeAgent($id, $fieldValues);
            echo json_encode($result);
            break;

        // export_agents, import_agents e clear_all removidos
        // Agentes agora são gerenciados como arquivos PHP

      case 'get_config':
            // Obter configurações (não sensíveis)
            echo json_encode([
                'success' => true,
                'data' => [
                    'app_name' => getConfig('app_name'),
                    'app_version' => getConfig('app_version'),
                    'grok_model' => getConfig('grok_model'),
                    'total_agents' => count(getAgents())
                ]
            ]);
            break;

        default:
            throw new Exception('Ação inválida: ' . $action);
    }

} catch (Exception $e) {
    // Log de erro (em produção usar log)
    error_log("API Error: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => 'API_ERROR'
    ]);
}
?>