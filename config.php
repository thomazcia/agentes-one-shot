<?php
/**
 * Configurações do Sistema - Agentes One-Shot v1.1
 */

// Carregar variáveis de ambiente do arquivo .env
require_once 'vendor/autoload.php';

// Carregar módulo de segurança
require_once 'security.php';

// Tentar carregar variáveis de ambiente
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    // Se não encontrar .env, usar valores padrão ou continuar sem variáveis de ambiente
    error_log("Aviso: Arquivo .env não encontrado. Usando configurações do sistema.");
}

// Configurações da Open Router API com fallback seguro
define('OPENROUTER_API_KEY', $_ENV['OPENROUTER_API_KEY'] ?? 'sk-or-v1-sua-chave-api-aqui');
define('OPENROUTER_API_URL', $_ENV['OPENROUTER_API_URL'] ?? 'https://openrouter.ai/api/v1/chat/completions');
define('GROK_MODEL', $_ENV['GROK_MODEL'] ?? 'x-ai/grok-4.1-fast:free');

// Configurações do Sistema
define('AGENTS_FOLDER', __DIR__ . '/agentes/');
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Agentes One-Shot');
define('APP_VERSION', $_ENV['APP_VERSION'] ?? '2.0');

// Configurações de Segurança
define('MAX_REQUEST_SIZE', intval($_ENV['MAX_REQUEST_SIZE'] ?? 1048576));
define('MAX_PROMPT_LENGTH', intval($_ENV['MAX_PROMPT_LENGTH'] ?? 10000));
define('RATE_LIMIT_REQUESTS', intval($_ENV['RATE_LIMIT_REQUESTS'] ?? 60));
define('RATE_LIMIT_WINDOW', intval($_ENV['RATE_LIMIT_WINDOW'] ?? 60));

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

    // CSP relaxado para desenvolvimento (permitir Bootstrap debugging)
    header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data:; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; font-src 'self' https://cdn.jsdelivr.net data:; img-src 'self' data: https:; connect-src 'self' https://cdn.jsdelivr.net https://openrouter.ai; object-src 'none'; base-uri 'self';");

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
define('MAX_AGENTS', intval($_ENV['MAX_AGENTS'] ?? 100));
define('MAX_FILE_SIZE', intval($_ENV['MAX_FILE_SIZE'] ?? 1048576)); // 1MB por arquivo de agente

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
