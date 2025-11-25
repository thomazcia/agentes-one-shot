<?php
/**
 * Configurações do Sistema - Agentes One-Shot v1.1
 */

// Carregar módulo de segurança
require_once 'security.php';

// Configurações da Open Router API
define('OPENROUTER_API_KEY', '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'); // Substitua com sua chave real
define('OPENROUTER_API_URL', 'https://openrouter.ai/api/v1/chat/completions');
define('GROK_MODEL', 'x-ai/grok-4.1-fast:free'); // Modelo Grok 4.1 Fast

// Configurações do Sistema
define('AGENTS_FOLDER', __DIR__ . '/agentes/');
define('APP_NAME', 'Agentes One-Shot');
define('APP_VERSION', '1.1');

// Configurações de Segurança
define('MAX_REQUEST_SIZE', 1048576); // 1MB
define('MAX_PROMPT_LENGTH', 10000);
define('RATE_LIMIT_REQUESTS', 60); // por minuto
define('RATE_LIMIT_WINDOW', 60); // segundos

// Configurações de segurança
define('ALLOWED_ORIGINS', [
    'http://localhost',
    'https://localhost',
    $_SERVER['HTTP_HOST'] ?? ''
]);

// Verificar padrões suspeitos antes de continuar
if (detectSuspiciousPatterns()) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Requisição bloqueada por segurança',
        'error_code' => 'SECURITY_VIOLATION'
    ]);
    securityLog('REQUEST_BLOCKED', ['reason' => 'Suspicious pattern detected']);
    exit;
}

// Rate limiting
$clientIp = getRealIp();
if (!checkRateLimit($clientIp, RATE_LIMIT_REQUESTS, RATE_LIMIT_WINDOW)) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'message' => 'Muitas requisições. Tente novamente mais tarde.',
        'error_code' => 'RATE_LIMIT_EXCEEDED'
    ]);
    securityLog('RATE_LIMIT_EXCEEDED', ['ip' => $clientIp]);
    exit;
}

// Headers de segurança (apenas para requisições web)
if (isset($_SERVER['REQUEST_METHOD'])) {
    setSecurityHeaders();

    // CORS headers
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    // Se for OPTIONS, responder apenas com os headers
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0);
    }
}

// Configurações dos agentes
define('MAX_AGENTS', 100);
define('MAX_FILE_SIZE', 1024 * 1024); // 1MB por arquivo de agente

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