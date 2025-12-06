<?php
// Teste específico para ambiente Railway.app
header('Content-Type: text/plain; charset=utf-8');

echo "=== TESTE AMBIENTE RAILWAY ===\n\n";

// 1. Verifica se as variáveis estão disponíveis no ambiente Railway
echo "1. VERIFICAÇÃO DE VARIÁVEIS DE AMBIENTE (Raiway.app):\n";
echo "Variáveis definidas no ambiente:\n";

// Lista todas as variáveis de ambiente que começam com OPENROUTER ou GROK
foreach ($_ENV as $key => $value) {
    if (strpos($key, 'OPENROUTER') === 0 ||
        strpos($key, 'GROK') === 0 ||
        strpos($key, 'APP_') === 0) {
        $displayValue = strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value;
        echo "  $key = $displayValue\n";
    }
}

echo "\n2. TESTE DE CARREGAMENTO DIRETO (sem .env local):\n";

// Simula ambiente Railway (sem arquivo .env)
try {
    // Remove todas as variáveis de ambiente atuais para simular ambiente limpo
    $originalEnv = $_ENV;

    // Carrega apenas as variáveis do Railway (se existirem)
    $railwayVars = [
        'OPENROUTER_API_KEY' => getenv('OPENROUTER_API_KEY'),
        'OPENROUTER_API_URL' => getenv('OPENROUTER_API_URL'),
        'GROK_MODEL' => getenv('GROK_MODEL'),
        'APP_NAME' => getenv('APP_NAME'),
        'APP_VERSION' => getenv('APP_VERSION')
    ];

    echo "Variáveis Railway detectadas:\n";
    foreach ($railwayVars as $key => $value) {
        if ($value !== false) {
            echo "  ✓ $key: " . (strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value) . "\n";
        } else {
            echo "  ✗ $key: NÃO DEFINIDA\n";
        }
    }

    // Teste com phpdotenv
    echo "\n3. TESTE COM PHPDOTENV (modo Railway):\n";
    require_once 'vendor/autoload.php';

    // Tenta criar o Dotenv sem arquivo .env (comportamento Railway)
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad(); // safeLoad não lança exceção se não encontrar .env
        echo "  ✓ phpdotenv carregado (modo safe)\n";
    } catch (Exception $e) {
        echo "  - phpdotenv: " . $e->getMessage() . "\n";
    }

    // Verifica se as variáveis foram carregadas
    echo "\n4. VERIFICAÇÃO PÓS-CARREGAMENTO:\n";
    foreach ($railwayVars as $key => $expectedValue) {
        $currentValue = getenv($key);
        if ($currentValue !== false) {
            echo "  ✓ $key está disponível\n";
            if ($key === 'OPENROUTER_API_KEY') {
                $isPlaceholder = strpos($currentValue, 'sua-chave') !== false || strpos($currentValue, 'your-api-key') !== false;
                echo "    É placeholder? " . ($isPlaceholder ? 'SIM (PROBLEMA!)' : 'NÃO') . "\n";
            }
        } else {
            echo "  ✗ $key NÃO está disponível\n";
        }
    }

    echo "\n5. TESTE DE INTEGRAÇÃO:\n";

    // Define constantes como no config.php
    define('OPENROUTER_API_KEY', $_ENV['OPENROUTER_API_KEY'] ?? 'sk-or-v1-sua-chave-api-aqui');
    define('OPENROUTER_API_URL', $_ENV['OPENROUTER_API_URL'] ?? 'https://openrouter.ai/api/v1/chat/completions');
    define('GROK_MODEL', $_ENV['GROK_MODEL'] ?? 'x-ai/grok-4.1-fast:free');

    echo "Constantes definidas:\n";
    echo "  OPENROUTER_API_KEY: " . (defined('OPENROUTER_API_KEY') ? 'SIM' : 'NÃO') . "\n";
    echo "  OPENROUTER_API_URL: " . (defined('OPENROUTER_API_URL') ? 'SIM' : 'NÃO') . "\n";
    echo "  GROK_MODEL: " . (defined('GROK_MODEL') ? 'SIM' : 'NÃO') . "\n";

    if (defined('OPENROUTER_API_KEY')) {
        $key = OPENROUTER_API_KEY;
        echo "  Valor da API Key: " . (strlen($key) > 20 ? substr($key, 0, 20) . '...' : $key) . "\n";
        echo "  Parece válida? " . (strpos($key, 'sk-or-v1') === 0 && strpos($key, 'sua-chave') === false ? 'SIM' : 'NÃO') . "\n";
    }

} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n6. INFORMAÇÕES DO AMBIENTE:\n";
echo "  PHP Version: " . phpversion() . "\n";
echo "  Server API: " . php_sapi_name() . "\n";
echo "  Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "  Current Dir: " . __DIR__ . "\n";

// Verifica se estamos na Railway
if (getenv('RAILWAY_ENVIRONMENT') || getenv('RAILWAY_SERVICE_NAME')) {
    echo "  ✓ Detectado ambiente Railway\n";
    echo "  Railway Environment: " . getenv('RAILWAY_ENVIRONMENT') . "\n";
    echo "  Railway Service: " . getenv('RAILWAY_SERVICE_NAME') . "\n";
} else {
    echo "  - Ambiente Railway não detectado (local)\n";
}

echo "\n7. RECOMENDAÇÕES:\n";
echo "  1. No painel Railway.app, verifique se as variáveis estão definidas EXATAMENTE como:\n";
echo "     - OPENROUTER_API_KEY (sua chave real, começando com sk-or-v1)\n";
echo "     - OPENROUTER_API_URL = https://openrouter.ai/api/v1/chat/completions\n";
echo "     - GROK_MODEL = x-ai/grok-4.1-fast:free\n";
echo "\n  2. NÃO inclua arquivo .env no deploy para Railway\n";
echo "\n  3. Após definir as variáveis, faça um novo deploy\n";
echo "\n  4. Use este arquivo de teste para verificar: /test-railway-env.php\n";

echo "\n=== FIM DO TESTE ===\n";