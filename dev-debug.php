<?php
// debug-env.php — REMOVA depois de usar
header('Content-Type: text/plain');

// substitua 'MINHA_CHAVE' pelo nome da variável que você quer checar
$varName = 'OPENROUTER_API_KEY';

echo "getenv('$varName'):\n";
var_export(getenv($varName));
echo "\n\n";

echo "\$_ENV['$varName']:\n";
var_export($_ENV[$varName] ?? null);
echo "\n\n";

echo "\$_SERVER['$varName']:\n";
var_export($_SERVER[$varName] ?? null);
echo "\n\n";

// lista breve de todas as variáveis de ambiente (para inspecionar)
echo "=== Todas as variáveis de ambiente (apenas as primeiras 200 linhas) ===\n";
$env = getenv();
if (is_array($env)) {
    foreach ($env as $k => $v) {
        echo "$k=$v\n";
    }
} else {
    // fallback para $_ENV
    foreach ($_ENV as $k => $v) {
        echo "$k=$v\n";
    }
}
