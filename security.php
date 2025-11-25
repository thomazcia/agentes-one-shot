<?php
/**
 * Security Module - Agentes One-Shot v1.1
 * Funções de proteção e validação de segurança
 */

// Prevenir acesso direto
if (!defined('SECURITY_LOADED')) {
    define('SECURITY_LOADED', true);
}

/**
 * Limpa e valida entrada de dados
 */
function sanitizeInput($input, $type = 'string') {
    if ($input === null) {
        return null;
    }

    // Converter para string se for array ou objeto
    if (is_array($input) || is_object($input)) {
        $input = json_encode($input);
    }

    // Remover espaços extras
    $input = trim($input);

    // Validar tipo específico
    switch ($type) {
        case 'string':
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        case 'filename':
            // Apenas caracteres seguros para nomes de arquivo
            return preg_replace('/[^a-zA-Z0-9\-_]/', '', $input);

        case 'json':
            // Validar JSON
            json_decode($input);
            return (json_last_error() === JSON_ERROR_NONE) ? $input : null;

        case 'agent_id':
            // Validar ID de agente (apenas caracteres seguros)
            if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $input)) {
                return null;
            }
            return $input;

        case 'prompt':
            // Para prompts - limitar tamanho e remover scripts
            $input = strip_tags($input);
            return substr($input, 0, 10000); // Limitar a 10k caracteres

        default:
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Valida tamanho do conteúdo
 */
function validateSize($input, $maxLength = 10000) {
    return strlen($input) <= $maxLength;
}

/**
 * Detecta conteúdo malicioso em prompts
 */
function detectMaliciousContent($prompt) {
    $maliciousPatterns = [
        '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
        '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
        '/javascript:/i',
        '/on\w+\s*=/i',  // onclick=, onload=, etc.
        '/eval\s*\(/i',
        '/document\./i',
        '/window\./i'
    ];

    foreach ($maliciousPatterns as $pattern) {
        if (preg_match($pattern, $prompt)) {
            return true;
        }
    }

    return false;
}

/**
 * Rate limiting simples baseado em IP
 */
function checkRateLimit($ip, $maxRequests = 60, $timeWindow = 60) {
    $rateLimitFile = sys_get_temp_dir() . '/agentes_rate_limit_' . md5($ip);

    $currentTime = time();
    $requests = [];

    // Ler requisições existentes
    if (file_exists($rateLimitFile)) {
        $data = file_get_contents($rateLimitFile);
        $requests = json_decode($data, true) ?: [];
    }

    // Remover requisições antigas (fora da janela de tempo)
    $requests = array_filter($requests, function($timestamp) use ($currentTime, $timeWindow) {
        return $currentTime - $timestamp < $timeWindow;
    });

    // Verificar limite
    if (count($requests) >= $maxRequests) {
        return false;
    }

    // Adicionar requisição atual
    $requests[] = $currentTime;

    // Salvar arquivo
    file_put_contents($rateLimitFile, json_encode($requests));

    return true;
}

/**
 * Obter IP real do cliente
 */
function getRealIp() {
    $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];

    foreach ($ipKeys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/**
 * Log de segurança
 */
function securityLog($event, $details = []) {
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => getRealIp(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'event' => $event,
        'details' => $details
    ];

    $logFile = __DIR__ . '/security_logs.json';
    $logs = [];

    // Ler logs existentes
    if (file_exists($logFile)) {
        $logs = json_decode(file_get_contents($logFile), true) ?: [];
    }

    // Adicionar novo log
    $logs[] = $logEntry;

    // Manter apenas últimos 1000 logs
    if (count($logs) > 1000) {
        $logs = array_slice($logs, -1000);
    }

    // Salvar logs
    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
}

/**
 * Validar agente ID
 */
function validateAgentId($id) {
    if (empty($id) || !is_string($id)) {
        return false;
    }

    // Permitir apenas caracteres seguros
    return preg_match('/^[a-zA-Z0-9\-_]+$/', $id);
}

/**
 * Sanitizar nome de arquivo
 */
function sanitizeFilename($filename) {
    // Remover path traversal
    $filename = str_replace(['../', '..\\', '../', '..\\'], '', $filename);

    // Permitir apenas caracteres seguros
    $filename = preg_replace('/[^a-zA-Z0-9\-_.]/', '', $filename);

    return $filename;
}

/**
 * Headers de segurança
 */
function setSecurityHeaders() {
    // Prevenir clickjacking
    header('X-Frame-Options: DENY');

    // Prevenir MIME type sniffing
    header('X-Content-Type-Options: nosniff');

    // XSS Protection
    header('X-XSS-Protection: 1; mode=block');

    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Content Security Policy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; font-src 'self' https://cdn.jsdelivr.net; connect-src 'self' https://openrouter.ai; img-src 'self' data: https:;");

    // HSTS (se for HTTPS)
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

/**
 * Detectar padrões suspeitos em requisições
 */
function detectSuspiciousPatterns() {
    $suspiciousPatterns = [
        '/union\s+select/i',
        '/select\s+.*\s+from/i',
        '/insert\s+into/i',
        '/update\s+.*\s+set/i',
        '/delete\s+from/i',
        '/drop\s+table/i',
        '/create\s+table/i',
        '/alter\s+table/i',
        '/exec\s*\(/i',
        '/system\s*\(/i',
        '/eval\s*\(/i',
        '/<\?php/i',
        '/<script/i'
    ];

    // Verificar em todos os parâmetros POST e GET
    $allParams = array_merge($_GET, $_POST);

    foreach ($allParams as $key => $value) {
        if (is_string($value)) {
            foreach ($suspiciousPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    securityLog('SUSPICIOUS_PATTERN_DETECTED', [
                        'parameter' => $key,
                        'pattern' => $pattern,
                        'value' => substr($value, 0, 100)
                    ]);
                    return true;
                }
            }
        }
    }

    return false;
}
?>