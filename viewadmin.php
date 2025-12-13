<?php
/**
 * Painel Administrativo - Agentes One-Shot v2.1
 * Acesso restrito com parâmetro sys=admin
 */

// Verificar se o parâmetro sys=admin está presente
if (!isset($_GET['sys']) || $_GET['sys'] !== 'admin') {
    http_response_code(403);
    echo '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="text-center">
            <i class="bi bi-shield-exclamation text-danger display-1"></i>
            <h2 class="mt-3">Acesso Restrito</h2>
            <p class="text-muted">Você não tem permissão para acessar esta página.</p>
            <a href="/" class="btn btn-primary">Voltar para Página Inicial</a>
        </div>
    </div>
</body>
</html>';
    exit;
}

require_once 'config.php';
require_once 'agentes.php';

// Obter agentes em desenvolvimento para exibir no painel
$devAgents = getAdminAgents();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - <?php echo getConfig('app_name'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .navbar-custom {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }
        .admin-badge {
            background: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Agent Card Styles - Idênticos à página inicial */
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
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .badges-container {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .badges-container .badge {
            font-size: 11px;
            padding: 3px 6px;
        }
    </style>
</head>
<body>
    <!-- Header Administrativo -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="bi bi-cpu text-primary me-2" style="font-size: 1.5rem;"></i>
                <span class="fw-bold"><?php echo getConfig('app_name'); ?></span>
                <span class="admin-badge ms-2">ADMIN</span>
            </a>

            <!-- Links Administrativos -->
            <div class="d-flex align-items-center">
                <a href="/model-status.php?sys=admin" class="btn btn-outline-primary me-2">
                    <i class="bi bi-cpu me-1"></i>
                    Status Modelos LLM
                </a>
                <a href="/" class="btn btn-outline-secondary">
                    <i class="bi bi-house me-1"></i>
                    Página Inicial
                </a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success d-flex align-items-center">
                    <i class="bi bi-shield-check me-2"></i>
                    <div>
                        <strong>Painel Administrativo</strong>
                        <p class="mb-0">Bem-vindo ao painel administrativo. Use os links no menu superior para acessar as funcionalidades<?php if (!empty($devAgents)): ?> e teste os agentes abaixo<?php endif; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($devAgents)): ?>
        <div id="agents-grid" class="row g-4">
            <?php foreach ($devAgents as $agent): ?>
                <?php
                $iconStyle = "background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);";
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card agent-card h-100" href='<?php echo ($agent['url'] ?? $agent['id']) . "?sys=admin"; ?>' onclick="window.location.href='<?php echo ($agent['url'] ?? $agent['id']) . "?sys=admin"; ?>'">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="agent-icon me-3" style="<?php echo $iconStyle; ?>">
                                    <i class="bi <?php echo $agent['icon'] ?? 'bi-robot'; ?>"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">
                                        <?php echo htmlspecialchars($agent['name']); ?>
                                        <span class="badge bg-warning ms-2">DEV</span>
                                    </h5>
                                    <div class="badges-container">
                                        <span class="badge bg-secondary">desenvolvimento</span>
                                        <span class="badge bg-warning"><?php echo htmlspecialchars($agent['difficulty'] ?? 'iniciante'); ?></span>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($agent['estimated_time'] ?? '1 min'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text"><?php echo htmlspecialchars($agent['description']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


                        







        <?php else: ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Nenhum agente em desenvolvimento encontrado.
                </div>
            </div>
        </div>
        <?php endif; ?>

        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>