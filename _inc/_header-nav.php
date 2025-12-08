<!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="bi bi-robot me-2" style="color: #667eea;"></i>
                <strong><?php echo getConfig('app_name'); ?></strong>
                <span class="version-badge ms-2">v<?php echo getConfig('app_version'); ?></span>
            </a>
            <div class="d-flex align-items-center">
                <?php if ($isAdminMode): ?>
                <div class="dropdown">
                    <button class="btn btn-admin btn-sm dropdown-toggle"
                            type="button" id="adminDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-shield-lock me-1"></i>Visão ADMIN
                    </button>
                    <ul class="dropdown-menu dropdown-menu-admin">
                        <li><a class="dropdown-item" href="model-status.php?sys=corps">
                            <i class="bi bi-cpu me-2"></i>Model Status
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="showDevAgents()">
                            <i class="bi bi-code-square me-2"></i>Agentes em Desenvolvimento
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/?sys=corps">
                            <i class="bi bi-border-all me-2"></i>Todos agentes
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/">
                            <i class="bi bi-x-square me-2"></i>Sair
                        </a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>