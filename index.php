<?php
require_once 'config.php';
require_once 'agentes.php';

// Variáveis de controle
$currentAgent = null;
$executionResult = null;
$executionError = null;
$fieldValues = [];

// Handle direct agent URLs via query parameter (set by router)
$directAgent = null;
if (isset($_GET['agent'])) {
    $agentSlug = sanitizeInput($_GET['agent']);

    // Verificar se está em modo admin para incluir agentes DEV
    $isAdminMode = isset($_GET['sys']) && $_GET['sys'] === 'admin';

    if ($isAdminMode) {
        // Modo admin: busca TODOS os agentes (públicos e DEV)
        $agentes = getAdminAgents();
    } else {
        // Modo público: busca apenas agentes públicos
        $agentes = getAgents();
    }

    // Find agent by URL
    foreach ($agentes as $agente) {
        if (isset($agente['url']) && $agente['url'] === $agentSlug) {
            $directAgent = $agente;
            break;
        }
    }
}

// Processar execução de agente via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'execute_agent') {
    $agentId = sanitizeInput($_POST['agent_id'] ?? '', 'agent_id');

    if ($agentId && validateAgentId($agentId)) {
        $currentAgent = getAgent($agentId);

        if ($currentAgent) {
            // Coletar e validar valores dos campos
            foreach ($currentAgent['fields'] as $field) {
                $fieldLabel = $field['label'];
                $fieldName = isset($field['name']) ? $field['name'] : preg_replace('/[^a-zA-Z0-9_-]/', '_', $fieldLabel); // Nome seguro para HTML
                $fieldValue = $_POST[$fieldName] ?? '';

                // Validar campo obrigatório
                if ($field['required'] && empty($fieldValue)) {
                    $executionError = "O campo '{$fieldLabel}' é obrigatório.";
                    break;
                }

                // Sanitizar valor
                $fieldValues[$fieldLabel] = sanitizeInput($fieldValue, 'string');
            }

            if (!$executionError) {
                // Validar tamanho do prompt
                if (strlen($currentAgent['prompt']) > MAX_PROMPT_LENGTH) {
                    $executionError = 'Prompt muito longo.';
                }

                // Verificar conteúdo malicioso
                if (!$executionError && detectMaliciousContent($currentAgent['prompt'])) {
                    $executionError = 'Conteúdo do prompt bloqueado por segurança.';
                }

                // Executar agente se não houver erros
                if (!$executionError) {
                    // Log de execução
                    securityLog('AGENT_EXECUTED', [
                        'agent_id' => $agentId,
                        'field_count' => count($fieldValues)
                    ]);

                    $executionResult = executeAgent($agentId, $fieldValues);
                }
            }
        } else {
            $executionError = 'Agente não encontrado.';
        }
    } else {
        $executionError = 'ID do agente inválido.';
    }

    // Se temos agente direto na URL, dar prioridade a ele
    if ($directAgent) {
        $currentAgent = $directAgent;
    }
}

// Se temos agente direto na URL (mas não execução via POST)
if ($directAgent && !$executionResult) {
    $currentAgent = $directAgent;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getConfig('app_name'); ?> v<?php echo getConfig('app_version'); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-custom {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }

        .agent-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
        }

        .agent-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,.1);
        }

        .agent-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .agent-detail-layout {
            display: none;
        }

        .form-section {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e9ecef;
            height: fit-content;
        }

        .response-section {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e9ecef;
            min-height: 400px;
        }

        .btn-execute {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
        }

        .btn-execute:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-1px);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 12px 16px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .response-content {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0 20px;
            min-height: 300px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }

        /* Container da resposta mantendo formatação completa */
        .response-content .response-content_output {
            margin: 0;
            padding: 20px 0;
            font-size: 15px;
            color: #333;
            line-height: 1.7;
        }

        /* Estilos para conteúdo Markdown */
        .markdown-content {
            line-height: 1.7;
            color: #333;
        }

        .markdown-content h1,
        .markdown-content h2,
        .markdown-content h3,
        .markdown-content h4,
        .markdown-content h5,
        .markdown-content h6 {
            color: #2c3e50;
            font-weight: 600;
            margin-top: 24px;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .markdown-content h1 { font-size: 28px; border-bottom: 2px solid #e9ecef; padding-bottom: 8px; }
        .markdown-content h2 { font-size: 24px; border-bottom: 1px solid #e9ecef; padding-bottom: 6px; }
        .markdown-content h3 { font-size: 20px; }
        .markdown-content h4 { font-size: 18px; }
        .markdown-content h5 { font-size: 16px; }
        .markdown-content h6 { font-size: 14px; color: #6c757d; }

        .markdown-content p {
            margin-bottom: 16px;
        }

        .markdown-content ul,
        .markdown-content ol {
            margin-bottom: 16px;
            padding-left: 24px;
        }

        .markdown-content li {
            margin-bottom: 6px;
        }

        .markdown-content ol li::marker {
            /*color: #667eea;*/
            font-weight: 600;
        }

        .markdown-content blockquote {
            border-left: 4px solid #667eea;
            padding-left: 16px;
            margin: 16px 0;
            color: #6c757d;
            font-style: italic;
            background: #f8f9fa;
            padding: 12px 16px;
            border-radius: 0 6px 6px 0;
        }

        .markdown-content code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            color: #c7254e;
        }

        .markdown-content pre {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 16px;
            overflow-x: auto;
            margin: 16px 0;
        }

        .markdown-content pre code {
            background: transparent;
            padding: 0;
            border-radius: 0;
            color: #333;
            font-size: 14px;
        }

        .markdown-content strong {
            color: #2c3e50;
            font-weight: 600;
        }

        .markdown-content em {
            color: #495057;
            font-style: italic;
        }

        .markdown-content hr {
            border: none;
            border-top: 1px solid #e9ecef;
            margin: 24px 0;
        }

        /* Tabelas (se necessário no futuro) */
        .markdown-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        .markdown-content th,
        .markdown-content td {
            border: 1px solid #e9ecef;
            padding: 8px 12px;
            text-align: left;
        }

        .markdown-content th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }

        .loading-spinner {
            display: none;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 64px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .back-btn {
            background: transparent;
            border: 1px solid #ced4da;
            padding: 8px 16px;
            border-radius: 8px;
            color: #495057;
        }

        .back-btn:hover {
            background: #f8f9fa;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .loading {
            animation: pulse 1.5s ease-in-out infinite;
        }

        .version-badge {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .form-field-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            background-color: #f8f9fa;
        }

        /* Admin Button Styles */
        .btn-admin {
            border-color: #dc3545 !important;
            color: #dc3545 !important;
            font-weight: 500;
        }

        .btn-admin:hover {
            background-color: #dc3545 !important;
            color: white !important;
            border-color: #dc3545 !important;
        }

        /* Fix dropdown overflow */
        .dropdown-menu {
            min-width: 250px;
            max-width: 300px;
        }

        .dropdown-menu-admin {
            right: 0;
            left: unset !important;
        }

      </style>
</head>
<body>
    <?php include '_inc/_header-nav.php'; // Adiciona o NAV Header ?>

    <!-- Agents List View -->
    <div id="agents-list-view" class="container mt-4" <?php echo $currentAgent ? 'style="display: none;"' : ''; ?>>
        <div class="row mb-4">
            <div class="col">
                <h2>Agentes</h2>
                <p class="text-muted">Agentes de IA para aumentar a sua produtividade.</p>
            </div>
        </div>

        <div id="agents-grid" class="row g-4">
            <?php
            // Obter agentes públicos
            $agents = getAgents();

            if (!empty($agents)) {
                foreach ($agents as $agent) {
                    $iconStyle = "background-color: " . ($agent['color'] ?? '#667eea');
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card agent-card h-100" href='<?php echo BASE_URL . ($agent['url'] ?? $agent['id']); ?>' onclick="window.location.href='<?php echo BASE_URL . ($agent['url'] ?? $agent['id']); ?>'">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="agent-icon me-3" style="<?php echo $iconStyle; ?>">
                                        <i class="bi <?php echo $agent['icon'] ?? 'bi-robot'; ?>"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1"><?php echo htmlspecialchars($agent['name']); ?></h5>
                                        <div class="badges-container">
                                            <span class="badge bg-primary"><?php echo htmlspecialchars($agent['category']); ?></span>
                                            <span class="badge bg-success"><?php echo htmlspecialchars($agent['difficulty']); ?></span>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($agent['estimated_time']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="card-text"><?php echo htmlspecialchars($agent['description']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-robot"></i>
                        <h4>Nenhum agente disponível</h4>
                        <p class="text-muted">Nenhum agente foi encontrado na pasta /agentes/.</p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <!-- Agent Detail View -->
    <?php if ($currentAgent): ?>
    <div id="agent-detail-view" class="agent-detail-layout" style="display: block;">
        <div class="container mt-4">
            <!-- Back Button -->
            <div class="row mb-3">
                <div class="col">
                    <button class="btn back-btn" onclick="window.location.href='<?php echo BASE_URL; ?>'">
                        <i class="bi bi-arrow-left me-2"></i>Voltar para Agentes
                    </button>
                </div>
                <?php
                    // Se tiver o parâmetro na url, apresenta o botão
                    if (isset($_GET['sys']) && $_GET['sys'] == 'admin') {  ?>
                    <div class="col text-end">
                        <button class="btn btn-warning back-btn" onclick="window.location.href='<?php echo BASE_URL . 'viewadmin.php?sys=admin'; ?>'" style="background-color: #ffc107 !important; border-color: #ffc107 !important;">
                            <i class="bi bi-arrow-left me-2"></i>Voltar para Admin
                        </button>
                    </div>
                <?php } ?>
            </div>

            <div class="row g-4">
                <!-- Form Section (Left) -->
                <div class="col-lg-5">
                    <div class="form-section">
                        <div class="mb-4">
                            <h4><?php echo htmlspecialchars($currentAgent['name']); ?></h4>
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($currentAgent['description']); ?></p>
                        </div>

                        <form method="POST">
                            <input type="hidden" name="action" value="execute_agent">
                            <input type="hidden" name="agent_id" value="<?php echo htmlspecialchars($currentAgent['id']); ?>">

                            <div id="agent-fields">
                                <?php
                                foreach ($currentAgent['fields'] as $field) {
                                    $fieldLabel = $field['label'];
                                    $fieldName = isset($field['name']) ? $field['name'] : preg_replace('/[^a-zA-Z0-9_-]/', '_', $fieldLabel); // Nome seguro para HTML
                                    $required = $field['required'] ? 'required' : '';
                                    $value = $fieldValues[$fieldLabel] ?? '';
                                    ?>
                                    <div class="mb-3">
                                        <label for="<?php echo $fieldName; ?>" class="form-label">
                                            <?php echo htmlspecialchars($field['label']); ?>
                                            <?php if ($field['required']): ?><span class="text-danger">*</span><?php endif; ?>
                                        </label>

                                        <?php if ($field['type'] === 'select'): ?>
                                            <select class="form-select" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" <?php echo $required; ?>>
                                                <option value="">Selecione...</option>
                                                <?php foreach ($field['options'] as $option): ?>
                                                    <option value="<?php echo htmlspecialchars($option); ?>" <?php echo ($value === $option) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($option); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                        <?php elseif ($field['type'] === 'textarea'): ?>
                                            <textarea class="form-control" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" rows="3" placeholder="<?php echo htmlspecialchars($field['placeholder']); ?>" <?php echo $required; ?>><?php echo htmlspecialchars($value); ?></textarea>

                                        <?php else: ?>
                                            <input type="<?php echo $field['type']; ?>" class="form-control" id="<?php echo $fieldName; ?>" name="<?php echo $fieldName; ?>" placeholder="<?php echo htmlspecialchars($field['placeholder']); ?>" value="<?php echo htmlspecialchars($value); ?>" <?php echo $required; ?>>

                                        <?php endif; ?>

                                        <?php if ($executionError && $field['required'] && empty($value)): ?>
                                            <div class="form-text text-danger">Este campo é obrigatório.</div>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                }
                                ?>

                                <?php if ($executionError): ?>
                                    <div class="alert alert-danger">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <?php echo htmlspecialchars($executionError); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-execute">
                                    <i class="bi bi-play-fill me-2"></i>Executar Agente
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Response Section (Right) -->
                <div class="col-lg-7">
                    <div class="response-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Resposta</h5>
                            <?php if ($executionResult && $executionResult['success']): ?>
                                <div id="response-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="copyResponse()">
                                        <i class="bi bi-clipboard me-1"></i>Copiar
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($executionResult): ?>
                            <div id="response-content" class="response-content">
                                <?php if ($executionResult['success']): ?>
                                    <div class="response-content_output markdown-content">
                                        <?php
                                        $responseText = $executionResult['data']['choices'][0]['message']['content'];
                                        // Limpa espaços no início
                                        $responseText = ltrim($responseText);
                                        $responseText = preg_replace('/^[\s\t]+/m', '', $responseText);

                                        // Converte markdown para HTML de forma segura
                                        echo parseMarkdown($responseText);
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-danger">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Erro: <?php echo htmlspecialchars($executionResult['message']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-chat-dots display-4"></i>
                                <p class="mt-3">Preencha o formulário e clique em "Executar Agente" para ver a resposta aqui</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- App JS - funções da aplicação -->
    <script src="app.js"></script>
</body>
</html>