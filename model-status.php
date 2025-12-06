<?php
// Dashboard de status dos modelos
require_once 'config.php';
require_once 'models.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status dos Modelos - Agentes One-Shot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .model-card {
            transition: all 0.3s ease;
        }
        .model-card:hover {
            transform: translateY(-2px);
        }
        .status-badge {
            font-size: 0.75rem;
        }
        .category-primary { border-left: 4px solid #0d6efd; }
        .category-secondary { border-left: 4px solid #198754; }
        .category-emergency { border-left: 4px solid #ffc107; }
        .category-paid { border-left: 4px solid #dc3545; }
        .test-loading {
            display: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="h3 mb-3">
                    <i class="bi bi-cpu"></i>
                    Status dos Modelos de IA
                </h1>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0">Monitoramento em tempo real da disponibilidade dos modelos</p>
                    <button class="btn btn-primary btn-sm" onclick="testAllModels()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Testar Todos
                    </button>
                </div>
            </div>
        </div>

        <!-- Modelo Atual -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-star-fill"></i>
                            Modelo Configurado
                        </h5>
                        <?php
                        $current = getCurrentModel();
                        $info = getModelInfo($current['id']);
                        ?>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-1"><?= htmlspecialchars($info['name'] ?? 'Desconhecido') ?></h4>
                                <p class="mb-0"><small><?= htmlspecialchars($current['id']) ?></small></p>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end gap-2">
                                    <?php if ($current['is_fallback']): ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-arrow-repeat"></i>
                                            Fallback
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <?= htmlspecialchars($current['source']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i>
                                            Configurado
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Modelos -->
        <div class="row" id="modelsList">
            <?php
            $models = getModelPriorityList();
            foreach ($models as $index => $model):
                $categoryClass = 'category-' . $model['category'];
                $isCurrent = $model['id'] === $current['id'];
            ?>
            <div class="col-md-6 col-lg-4 mb-3 model-item" data-model-id="<?= htmlspecialchars($model['id']) ?>">
                <div class="card model-card h-100 <?= $categoryClass ?> <?= $isCurrent ? 'border-primary' : '' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0"><?= htmlspecialchars($model['name']) ?></h6>
                            <span class="badge status-badge bg-secondary" id="status-<?= $index ?>">
                                <i class="bi bi-question-circle"></i>
                                Não testado
                            </span>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">
                                <i class="bi bi-building"></i>
                                <?= htmlspecialchars($model['provider']) ?>
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-chat-dots"></i>
                                Max tokens: <?= number_format($model['max_tokens']) ?>
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-file-text"></i>
                                Context: <?= number_format($model['context_length']) ?>
                            </small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-hash"></i>
                                <?= htmlspecialchars($model['id']) ?>
                            </small>
                            <?php if ($isCurrent): ?>
                                <span class="badge bg-primary">
                                    <i class="bi bi-check2"></i>
                                    Atual
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="test-loading mt-2">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Testando...</span>
                            </div>
                            <small class="ms-2">Testando...</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Estatísticas -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-graph-up"></i>
                            Estatísticas
                        </h5>
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-primary" id="totalModels"><?= count($models) ?></h3>
                                    <p class="mb-0 text-muted">Total de Modelos</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-success" id="availableModels">0</h3>
                                    <p class="mb-0 text-muted">Disponíveis</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-danger" id="failedModels">0</h3>
                                    <p class="mb-0 text-muted">Falharam</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-warning" id="testedModels">0</h3>
                                    <p class="mb-0 text-muted">Testados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log de Testes -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-terminal"></i>
                            Log de Testes
                        </h5>
                        <div id="testLog" class="bg-dark text-light p-3 rounded" style="font-family: monospace; font-size: 0.875rem; max-height: 300px; overflow-y: auto;">
                            <div class="text-muted">Aguardando testes...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para testar um modelo específico
        async function testModel(modelId, index) {
            const card = document.querySelector(`[data-model-id="${modelId}"]`);
            const statusBadge = document.getElementById(`status-${index}`);
            const loading = card.querySelector('.test-loading');

            loading.style.display = 'block';
            statusBadge.className = 'badge status-badge bg-warning';
            statusBadge.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Testando...';

            logTest(`Testing ${modelId}...`);

            try {
                const response = await fetch('test-model-availability.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ model_id: modelId })
                });

                const result = await response.json();

                if (result.available) {
                    statusBadge.className = 'badge status-badge bg-success';
                    statusBadge.innerHTML = '<i class="bi bi-check-circle"></i> Disponível';
                    logTest(`✅ ${modelId} - OK`);
                } else {
                    statusBadge.className = 'badge status-badge bg-danger';
                    statusBadge.innerHTML = '<i class="bi bi-x-circle"></i> Indisponível';
                    logTest(`❌ ${modelId} - ${result.message || 'Error'}`);
                }

                updateStats();
            } catch (error) {
                statusBadge.className = 'badge status-badge bg-danger';
                statusBadge.innerHTML = '<i class="bi bi-x-circle"></i> Erro';
                logTest(`❌ ${modelId} - ${error.message}`);
                updateStats();
            } finally {
                loading.style.display = 'none';
            }
        }

        // Testar todos os modelos
        async function testAllModels() {
            const models = <?php echo json_encode(array_column($models, 'id')); ?>;
            document.getElementById('testLog').innerHTML = '';

            for (let i = 0; i < models.length; i++) {
                await testModel(models[i], i);
                // Pequena pausa entre testes para não sobrecarregar a API
                await new Promise(resolve => setTimeout(resolve, 500));
            }
        }

        // Adicionar log
        function logTest(message) {
            const log = document.getElementById('testLog');
            const time = new Date().toLocaleTimeString();
            const entry = document.createElement('div');
            entry.innerHTML = `<span class="text-muted">[${time}]</span> ${message}`;
            log.appendChild(entry);
            log.scrollTop = log.scrollHeight;
        }

        // Atualizar estatísticas
        function updateStats() {
            const badges = document.querySelectorAll('.status-badge');
            let available = 0, failed = 0, tested = 0;

            badges.forEach(badge => {
                const text = badge.textContent;
                if (text.includes('Disponível')) {
                    available++;
                    tested++;
                } else if (text.includes('Indisponível') || text.includes('Erro')) {
                    failed++;
                    tested++;
                }
            });

            document.getElementById('availableModels').textContent = available;
            document.getElementById('failedModels').textContent = failed;
            document.getElementById('testedModels').textContent = tested;
        }

        // Testar modelo atual ao carregar
        window.addEventListener('DOMContentLoaded', () => {
            const current = <?php echo json_encode($current['id']); ?>;
            const models = <?php echo json_encode(array_column($models, 'id')); ?>;
            const currentIndex = models.indexOf(current);
            if (currentIndex !== -1) {
                testModel(current, currentIndex);
            }
        });
    </script>
</body>
</html>