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

// Configurações da Groq API - Ler diretamente do arquivo .env
function loadEnvVar($varName, $default = null) {
    $envFile = __DIR__ . '/.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            if (strpos($trimmedLine, '=') !== false && $trimmedLine[0] !== '#') {
                list($key, $value) = explode('=', $trimmedLine, 2);
                if (trim($key) === $varName) {
                    return trim($value);
                }
            }
        }
    }

    // Se não for GROQ_API_KEY, pode retornar o default
    // Para API keys, sempre retorna null para forçar configuração
    if ($varName === 'GROQ_API_KEY') {
        return null;
    }

    return $default;
}

define('GROQ_API_KEY', loadEnvVar('GROQ_API_KEY'));
define('GROQ_API_URL', loadEnvVar('GROQ_API_URL'));
define('GROQ_MODEL', loadEnvVar('GROQ_MODEL'));

// Validação de segurança - Variáveis obrigatórias
$required_vars = ['GROQ_API_KEY', 'GROQ_API_URL', 'GROQ_MODEL'];
$missing_vars = [];

foreach ($required_vars as $var) {
    if (!constant($var)) {
        $missing_vars[] = $var;
    }
}

if (!empty($missing_vars)) {
    if (php_sapi_name() !== 'cli') {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Variáveis obrigatórias não configuradas: ' . implode(', ', $missing_vars),
            'error_code' => 'MISSING_ENV_VARS',
            'missing' => $missing_vars
        ]);
        exit;
    }
    error_log('ERRO CRÍTICO: Variáveis não configuradas: ' . implode(', ', $missing_vars));
}

// Configurações do Sistema
define('AGENTS_FOLDER', __DIR__ . '/agentes/');
define('APP_NAME', loadEnvVar('APP_NAME', 'Agentes One-Shot'));
define('APP_VERSION', loadEnvVar('APP_VERSION', '2.0'));

// Configurações de Segurança
define('MAX_REQUEST_SIZE', intval(loadEnvVar('MAX_REQUEST_SIZE', '1048576')));
define('MAX_PROMPT_LENGTH', intval(loadEnvVar('MAX_PROMPT_LENGTH', '10000')));
define('RATE_LIMIT_REQUESTS', intval(loadEnvVar('RATE_LIMIT_REQUESTS', '60')));
define('RATE_LIMIT_WINDOW', intval(loadEnvVar('RATE_LIMIT_WINDOW', '60')));

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
define('MAX_AGENTS', intval(loadEnvVar('MAX_AGENTS', '100')));
define('MAX_FILE_SIZE', intval(loadEnvVar('MAX_FILE_SIZE', '1048576'))); // 1MB por arquivo de agente

// Função para obter configuração
function getConfig($key) {
    $config = [
        'groq_api_key' => GROQ_API_KEY,
        'groq_api_url' => GROQ_API_URL,
        'groq_model' => GROQ_MODEL,
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
