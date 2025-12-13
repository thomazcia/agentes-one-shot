<?php
require_once 'config.php';
require_once 'models.php';

// Carregar biblioteca CommonMark (autoload já está no config.php)
use League\CommonMark\CommonMarkConverter;

/**
 * Converte texto Markdown para HTML de forma segura
 * @param string $markdown Texto em formato Markdown
 * @return string HTML seguro e formatado
 */
function parseMarkdown($markdown) {
    if (empty($markdown)) {
        return '';
    }

    try {
        // Pré-processar: converter quebras de linha simples em duplas para parágrafos
        $markdown = preg_replace("/([^\n])\n([^\n])/", "$1\n\n$2", $markdown);

        // Configurar conversor CommonMark (v2.0+)
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',  // Remove HTML perigoso
            'allow_unsafe_links' => false,
        ]);

        // Converter markdown para HTML
        $html = $converter->convert($markdown)->getContent();

        // Sanitização básica de segurança adicional
        $html = sanitizeMarkdownHtml($html);

        return $html;
    } catch (Exception $e) {
        error_log("Erro ao converter markdown: " . $e->getMessage());
        // Fallback para texto puro com quebras de linha em caso de erro
        return nl2br(htmlspecialchars($markdown, ENT_QUOTES, 'UTF-8'));
    }
}


/**
 * Sanitização básica do HTML gerado pelo Markdown
 * Permite apenas tags seguras para formatação de texto
 */
function sanitizeMarkdownHtml($html) {
    // Lista de tags permitidas para formatação
    $allowed_tags = '<h1><h2><h3><h4><h5><h6><p><br><strong><em><u><i><b><ul><ol><li><blockquote><code><pre><hr>';

    // Remove tags não permitidas
    $html = strip_tags($html, $allowed_tags);

    // Remove atributos potencialmente perigosos
    $html = preg_replace('/\s*on\w+="[^"]*"/i', '', $html);
    $html = preg_replace('/\s*javascript:/i', '', $html);
    $html = preg_replace('/\s*vbscript:/i', '', $html);
    $html = preg_replace('/\s*data:/i', '', $html);

    return $html;
}

/**
 * Sistema de Gestão de Agentes - Agentes One-Shot v2.2
 * Funções para CRUD de agentes usando sistema de arquivos
 */

/**
 * Obter agentes (públicos ou todos se em modo admin)
 * Lê os arquivos PHP da pasta /agentes/ filtrando por status_public
 */
function getAgents() {
    $agents = [];
    $folder = getConfig('agents_folder');

    if (!file_exists($folder)) {
        return $agents;
    }

    // Verificar se está em modo admin (verificar GET ou POST)
    $sysParam = $_GET['sys'] ?? $_POST['sys'] ?? null;
    $isAdminMode = $sysParam === 'corps';

    // Ler todos os arquivos .php da pasta agentes
    $files = glob($folder . '*.php');

    foreach ($files as $file) {
        // Ignorar arquivos que começam com . (arquivos de sistema)
        $filename = basename($file, '.php');
        if (strpos($filename, '.') === 0) {
            continue;
        }

        try {
            // Incluir o arquivo e obter o array de configuração
            $agentData = include $file;

            if (is_array($agentData)) {
                // Verificar status_public - se não existir, assume 'publico' para compatibilidade
                $statusPublic = $agentData['status_public'] ?? 'publico';

                // Se não for modo admin, apenas incluir agentes públicos
                if (!$isAdminMode && $statusPublic !== 'publico') {
                    continue;
                }

                // Adicionar ID baseado no nome do arquivo
                $agentData['id'] = $filename;
                $agentData['file'] = $file;

                // Garantir que existe URL (usar ID como fallback)
                if (!isset($agentData['url']) || empty($agentData['url'])) {
                    $agentData['url'] = $filename;
                }

                $agents[] = $agentData;
            }
        } catch (Exception $e) {
            error_log("Erro ao ler agente {$file}: " . $e->getMessage());
        }
    }

    // Ordenar por nome (alfabético)
    usort($agents, function($a, $b) {
        return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
    });

    return $agents;
}

/**
 * Obter TODOS os agentes (inclusive em desenvolvimento)
 * Usado para interface admin
 */
function getAllAgents() {
    $agents = [];
    $folder = getConfig('agents_folder');

    if (!file_exists($folder)) {
        return $agents;
    }

    // Ler todos os arquivos .php da pasta agentes
    $files = glob($folder . '*.php');

    foreach ($files as $file) {
        // Ignorar arquivos que começam com . (arquivos de sistema)
        $filename = basename($file, '.php');
        if (strpos($filename, '.') === 0) {
            continue;
        }

        try {
            // Incluir o arquivo e obter o array de configuração
            $agentData = include $file;

            if (is_array($agentData)) {
                // Adicionar ID baseado no nome do arquivo
                $agentData['id'] = $filename;
                $agentData['file'] = $file;

                // Garantir que existe URL (usar ID como fallback)
                if (!isset($agentData['url']) || empty($agentData['url'])) {
                    $agentData['url'] = $filename;
                }

                // Garantir que existe status_public
                $agentData['status_public'] = $agentData['status_public'] ?? 'publico';

                $agents[] = $agentData;
            }
        } catch (Exception $e) {
            error_log("Erro ao ler agente {$file}: " . $e->getMessage());
        }
    }

    // Ordenar por nome (alfabético)
    usort($agents, function($a, $b) {
        return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
    });

    return $agents;
}

/**
 * Obter agentes em desenvolvimento (status_public = 'dev')
 */
function getAdminAgents() {
    $allAgents = getAllAgents();
    $devAgents = array_filter($allAgents, function($agent) {
        return ($agent['status_public'] ?? 'publico') === 'dev';
    });

    return array_values($devAgents);
}

/**
 * Obter um agente específico por ID
 */
function getAgent($id) {
    // Validar ID
    if (!validateAgentId($id)) {
        securityLog('INVALID_AGENT_ID', ['id' => $id]);
        return null;
    }

    // Sanitizar nome do arquivo
    $filename = sanitizeFilename($id) . '.php';
    $file = getConfig('agents_folder') . $filename;

    // Verificar se o arquivo existe e está na pasta correta
    if (!file_exists($file) || !is_file($file)) {
        return null;
    }

    try {
        // Verificar tamanho do arquivo
        if (filesize($file) > MAX_FILE_SIZE) {
            securityLog('FILE_TOO_LARGE', ['file' => $filename, 'size' => filesize($file)]);
            return null;
        }

        // Incluir arquivo de forma segura
        $agentData = include $file;

        if (is_array($agentData)) {
            // Validar estrutura básica do agente
            if (!isset($agentData['name']) || !isset($agentData['prompt'])) {
                securityLog('INVALID_AGENT_STRUCTURE', ['file' => $filename]);
                return null;
            }

            // Sanitizar dados do agente
            $agentData['id'] = $id;
            $agentData['file'] = $file;
            $agentData['name'] = sanitizeInput($agentData['name'], 'string');
            $agentData['description'] = sanitizeInput($agentData['description'] ?? '', 'string');
            $agentData['prompt'] = sanitizeInput($agentData['prompt'], 'prompt');

            return $agentData;
        }
    } catch (Exception $e) {
        error_log("Erro ao ler agente {$id}: " . $e->getMessage());
        securityLog('AGENT_READ_ERROR', ['id' => $id, 'error' => $e->getMessage()]);
    }

    return null;
}

/**
 * NOTA: Funções de salvar/editar/removidas
 * Os agentes agora são arquivos PHP criados manualmente na pasta /agentes/
 *
 * Para criar novos agentes:
 * 1. Copie agent-template.php
 * 2. Renomeie para nome-do-agente.php
 * 3. Configure as opções do array
 * 4. Coloque na pasta /agentes/
 */

/**
 * Executar um agente
 */
function executeAgent($id, $fieldValues = []) {
    try {
        // Obter agente
        $agent = getAgent($id);
        if (!$agent) {
            return ['success' => false, 'message' => 'Agente não encontrado'];
        }

        // Construir prompt final
        $finalPrompt = $agent['prompt'];

        // Substituir placeholders pelos valores dos campos
        if (!empty($agent['fields'])) {
            foreach ($agent['fields'] as $field) {
                $fieldName = $field['label'];
                $placeholder = '[' . $fieldName . ']';
                $value = $fieldValues[$fieldName] ?? '';
                $finalPrompt = str_replace($placeholder, $value, $finalPrompt);
            }

            // Adicionar contexto dos campos
            $context = "\n\n---\nDados informados:\n";
            foreach ($agent['fields'] as $field) {
                $fieldName = $field['label'];
                $value = $fieldValues[$fieldName] ?? '';
                $context .= "{$fieldName}: {$value}\n";
            }
            $finalPrompt .= $context;
        }

        // Fazer chamada para Groq API com fallback automático
        $response = callGroqAPIWithFallback($finalPrompt, [
            'max_tokens' => 4000,
            'temperature' => 0.7,
            'timeout' => 60,
            'test_models' => false // Desabilitado para performance (testa apenas se falhar)
        ]);

        // Log da resposta com informações de fallback
        error_log("=== API RESPONSE DEBUG ===");
        if ($response['success']) {
            $modelInfo = $response['model_used'];
            error_log("SUCCESS - Model: " . $modelInfo['id']);
            error_log("Fallback: " . ($modelInfo['fallback_info']['is_fallback'] ? 'YES' : 'NO'));
            if ($modelInfo['fallback_info']['is_fallback']) {
                error_log("Fallback Level: " . ($modelInfo['fallback_info']['fallback_level'] ?? 'unknown'));
                error_log("Attempt: " . $modelInfo['attempt']);
            }

            // Cache do modelo que funcionou
            cacheWorkingModel($modelInfo['id']);
        } else {
            error_log("FAILED - Message: " . $response['message']);
            if (isset($response['failed_models'])) {
                error_log("Failed Models: " . implode(', ', $response['failed_models']));
            }
        }

        return $response;

    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Erro: ' . $e->getMessage()];
    }
}

/**
 * Função legada mantida para compatibilidade
 * @deprecated Use callGroqAPIWithFallback() instead
 */
function callOpenRouterAPI($prompt) {
    return callGroqAPIWithFallback($prompt);
}

/**
 * Gerar ID único para agente
 */
function generateAgentId() {
    return 'agent_' . time() . '_' . uniqid();
}

/**
 * Validar estrutura do agente
 */
function validateAgent($agentData) {
    $errors = [];

    if (empty($agentData['name'])) {
        $errors[] = 'Nome é obrigatório';
    }

    if (empty($agentData['description'])) {
        $errors[] = 'Descrição é obrigatória';
    }

    if (empty($agentData['prompt'])) {
        $errors[] = 'Prompt é obrigatório';
    }

    if (!empty($agentData['fields']) && is_array($agentData['fields'])) {
        foreach ($agentData['fields'] as $index => $field) {
            if (empty($field['label'])) {
                $errors[] = "Campo {$index}: nome é obrigatório";
            }
        }
    }

    return $errors;
}

/**
 * Obter categorias disponíveis dos agentes
 */
function getAgentCategories() {
    $agents = getAgents();
    $categories = [];

    foreach ($agents as $agent) {
        if (isset($agent['category']) && !in_array($agent['category'], $categories)) {
            $categories[] = $agent['category'];
        }
    }

    sort($categories);
    return $categories;
}

/**
 * Obter estatísticas dos agentes
 */
function getAgentStats() {
    $agents = getAgents();
    return [
        'total_agents' => count($agents)
    ];
}
?>