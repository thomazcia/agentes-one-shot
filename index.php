<?php
require_once 'config.php';
require_once 'agentes.php';
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
            /* Mantém pre-wrap para preservar parágrafos e quebras de linha */
            white-space: pre-wrap;
            word-wrap: break-word;
            text-indent: 0;
            font-size: 15px;
            color: #333;
            /* Remove espaços no início do primeiro elemento */
            margin-top: 0;
        }

        /* Remove espaços no início do primeiro parágrafo */
        .response-content .response-content_output > *:first-child {
            margin-top: 0;
            padding-top: 0;
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
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#" onclick="showAgentsList()">
                <i class="bi bi-robot me-2" style="color: #667eea;"></i>
                <strong><?php echo getConfig('app_name'); ?></strong>
                <span class="version-badge ms-2">v<?php echo getConfig('app_version'); ?></span>
            </a>
            <div class="d-flex align-items-center">
 
            </div>
        </div>
    </nav>

    <!-- Agents List View -->
    <div id="agents-list-view" class="container mt-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Agentes</h2>
                <p class="text-muted">Agentes de IA para aumentar a sua produtividade.</p>
            </div>
        </div>

        <div id="agents-grid" class="row g-4">
            <!-- Agents will be loaded here -->
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-robot"></i>
                    <h4>Nenhum agente disponível</h4>
                    <p class="text-muted">Nenhum agente foi encontrado.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Agent Detail View -->
    <div id="agent-detail-view" class="agent-detail-layout">
        <div class="container mt-4">
            <!-- Back Button -->
            <div class="row mb-3">
                <div class="col">
                    <button class="btn back-btn" onclick="showAgentsList()">
                        <i class="bi bi-arrow-left me-2"></i>Voltar para Agentes
                    </button>
                </div>
            </div>

            <div class="row g-4">
                <!-- Form Section (Left) -->
                <div class="col-lg-5">
                    <div class="form-section">
                        <div class="mb-4">
                            <h4 id="agent-title">Título do Agente</h4>
                            <p id="agent-description" class="text-muted mb-0"></p>
                        </div>

                        <form id="agent-execution-form">
                            <div id="agent-fields">
                                <!-- Dynamic fields will be inserted here -->
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="button" class="btn btn-execute" onclick="executeCurrentAgent()">
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
                            <div id="response-actions" style="display: none;">
                                <button class="btn btn-sm btn-outline-primary" onclick="copyResponse()">
                                    <i class="bi bi-clipboard me-1"></i>Copiar
                                </button>
                            </div>
                        </div>

                        <div id="loading-spinner" class="loading-spinner text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            <p class="mt-2 text-muted">Processando sua solicitação...</p>
                        </div>

                        <div id="response-content" class="response-content">
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-chat-dots display-4"></i>
                                <p class="mt-3">Preencha o formulário e clique em "Executar Agente" para ver a resposta aqui</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="app.js"></script>
</body>
</html>