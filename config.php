<?php
// Configurações do Sistema - Agentes One-Shot v1.0

// Configurações da Open Router API
define('OPENROUTER_API_KEY', 'sk-or-sua-cahve-aqui'); // Substitua com sua chave real
define('OPENROUTER_API_URL', 'https://openrouter.ai/api/v1/chat/completions');
define('GROK_MODEL', 'x-ai/grok-4.1-fast:free'); // Modelo Grok 4.1 Fast

// Configurações do Sistema
define('AGENTS_FOLDER', __DIR__ . '/agentes/');
define('APP_NAME', 'Agentes One-Shot');
define('APP_VERSION', '1.1');

// Configurações de segurança
define('ALLOWED_ORIGINS', [
    'http://localhost',
    'https://localhost',
    $_SERVER['HTTP_HOST'] ?? ''
]);

// Configurações dos agentes
define('MAX_AGENTS', 100);
define('MAX_FILE_SIZE', 1024 * 1024); // 1MB por arquivo de agente

// Headers de segurança (apenas para requisições web)
if (isset($_SERVER['REQUEST_METHOD'])) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    // Se for OPTIONS, responder apenas com os headers
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0);
    }
}

// Função para obter configuração
function getConfig($key) {
    $config = [
        'openrouter_api_key' => OPENROUTER_API_KEY,
        'openrouter_api_url' => OPENROUTER_API_URL,
        'grok_model' => GROK_MODEL,
        'agents_folder' => AGENTS_FOLDER,
        'app_name' => APP_NAME,
        'app_version' => APP_VERSION
    ];

    return $config[$key] ?? null;
}

// Verificar se a pasta de agentes existe, criar se não existir
if (!file_exists(AGENTS_FOLDER)) {
    mkdir(AGENTS_FOLDER, 0755, true);
}
?>