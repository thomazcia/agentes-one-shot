<?php
// Teste completo de variáveis de ambiente para Railway.app
header('Content-Type: text/plain; charset=utf-8');

echo "=== TESTE DE VARIÁVEIS DE AMBIENTE ===\n\n";

// Carrega o autoload
require_once 'vendor/autoload.php';

echo "1. Testando carregamento do phpdotenv...\n";
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    echo "✓ phpdotenv carregado com sucesso\n";
} catch (Exception $e) {
    echo "✗ Erro ao carregar phpdotenv: " . $e->getMessage() . "\n";
}

echo "\n2. Verificando variáveis de ambiente diretamente:\n";

$vars_to_check = [
    'OPENROUTER_API_KEY',
    'OPENROUTER_API_URL',
    'GROK_MODEL',
    'APP_NAME',
    'APP_VERSION'
];

foreach ($vars_to_check as $var) {
    echo "\n--- $var ---\n";

    // Método 1: getenv()
    $val1 = getenv($var);
    echo "getenv(): " . ($val1 !== false ? 'SIM' : 'NÃO') . "\n";
    if ($val1 !== false) {
        echo "  Valor: " . (strlen($val1) > 20 ? substr($val1, 0, 20) . '...' : $val1) . "\n";
    }

    // Método 2: $_ENV
    $val2 = $_ENV[$var] ?? null;
    echo "\$_ENV: " . ($val2 !== null ? 'SIM' : 'NÃO') . "\n";
    if ($val2 !== null) {
        echo "  Valor: " . (strlen($val2) > 20 ? substr($val2, 0, 20) . '...' : $val2) . "\n";
    }

    // Método 3: $_SERVER
    $val3 = $_SERVER[$var] ?? null;
    echo "\$_SERVER: " . ($val3 !== null ? 'SIM' : 'NÃO') . "\n";
    if ($val3 !== null) {
        echo "  Valor: " . (strlen($val3) > 20 ? substr($val3, 0, 20) . '...' : $val3) . "\n";
    }
}

echo "\n\n3. Verificando arquivo .env local:\n";
$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    echo "✓ Arquivo .env local existe\n";
    echo "Tamanho: " . filesize($env_file) . " bytes\n";
    $content = file_get_contents($env_file);
    $lines = count(explode("\n", $content));
    echo "Linhas: $lines\n";
} else {
    echo "✗ Arquivo .env local NÃO existe (isso é normal na Railway)\n";
}

echo "\n\n4. Testando carregamento do config.php:\n";
try {
    require_once 'config.php';
    echo "✓ config.php carregado com sucesso\n";

    echo "\nVerificando constantes definidas:\n";
    echo "OPENROUTER_API_KEY: " . (defined('OPENROUTER_API_KEY') ? 'DEFINIDA' : 'NÃO DEFINIDA') . "\n";
    if (defined('OPENROUTER_API_KEY')) {
        $key = OPENROUTER_API_KEY;
        echo "  Valor: " . (strlen($key) > 20 ? substr($key, 0, 20) . '...' : $key) . "\n";
        echo "  É placeholder? " . (strpos($key, 'sk-or-v1-sua-chave') !== false ? 'SIM' : 'NÃO') . "\n";
    }

    echo "OPENROUTER_API_URL: " . (defined('OPENROUTER_API_URL') ? OPENROUTER_API_URL : 'NÃO DEFINIDA') . "\n";
    echo "GROK_MODEL: " . (defined('GROK_MODEL') ? GROK_MODEL : 'NÃO DEFINIDA') . "\n";

} catch (Exception $e) {
    echo "✗ Erro ao carregar config.php: " . $e->getMessage() . "\n";
}

echo "\n\n5. Testando função getConfig():\n";
if (function_exists('getConfig')) {
    $config_tests = ['openrouter_api_key', 'openrouter_api_url', 'grok_model'];

    foreach ($config_tests as $key) {
        $value = getConfig($key);
        echo "$key: " . ($value ? 'SIM' : 'NÃO') . "\n";
        if ($value) {
            echo "  Valor: " . (strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value) . "\n";
        }
    }
}

echo "\n\n=== FIM DO TESTE ===\n";