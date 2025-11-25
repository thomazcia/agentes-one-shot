<?php
/**
 * API Backend - Agentes One-Shot v1.1
 * Endpoint para processar requisições AJAX com segurança
 */

require_once 'config.php';
require_once 'agentes.php';

// Set headers for JSON response
header('Content-Type: application/json; charset=utf-8');

// Validar requisição
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido',
        'error_code' => 'METHOD_NOT_ALLOWED'
    ]);
    securityLog('INVALID_METHOD', ['method' => $_SERVER['REQUEST_METHOD']]);
    exit;
}

// Get and validate the requested action
$action = sanitizeInput($_POST['action'] ?? $_GET['action'] ?? '', 'agent_id');

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
            $id = sanitizeInput($_GET['id'] ?? '', 'agent_id');
            if (empty($id) || !validateAgentId($id)) {
                throw new Exception('ID do agente inválido');
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
            $id = sanitizeInput($_POST['agent_id'] ?? '', 'agent_id');
            if (empty($id) || !validateAgentId($id)) {
                throw new Exception('ID do agente inválido');
            }

            // Validar e sanitizar JSON
            $fieldValuesRaw = $_POST['field_values'] ?? '{}';
            $fieldValuesJson = sanitizeInput($fieldValuesRaw, 'json');

            if (!$fieldValuesJson) {
                throw new Exception('Valores dos campos inválidos');
            }

            $fieldValues = json_decode($fieldValuesJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Formato JSON inválido');
            }

            // Validar tamanho do prompt
            $agent = getAgent($id);
            if ($agent && isset($agent['prompt'])) {
                if (strlen($agent['prompt']) > MAX_PROMPT_LENGTH) {
                    throw new Exception('Prompt muito longo');
                }

                // Verificar conteúdo malicioso
                if (detectMaliciousContent($agent['prompt'])) {
                    throw new Exception('Conteúdo do prompt bloqueado por segurança');
                }
            }

            // Log de execução
            securityLog('AGENT_EXECUTED', [
                'agent_id' => $id,
                'field_count' => count($fieldValues)
            ]);

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
            throw new Exception('Ação inválida: ' . sanitizeInput($action, 'agent_id'));
    }

} catch (Exception $e) {
    // Log de erro detalhado
    error_log("API Error: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());

    // Log de segurança para erros críticos
    securityLog('API_ERROR', [
        'message' => $e->getMessage(),
        'action' => $action,
        'trace' => $e->getTraceAsString()
    ]);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => 'API_ERROR'
    ]);
}
?>