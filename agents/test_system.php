<?php
/**
 * Testes Unitários - Agentes One-Shot v2.2
 * Verificação completa do sistema
 *
 * Propósito: Executar testes automatizados para validar o funcionamento
 * do sistema antes de deploy ou mudanças significativas.
 *
 * Como usar: php agents/test_system.php
 */

require_once '../config.php';
require_once '../agentes.php';
require_once '../models.php';

echo "=== 🔬 INICIANDO TESTES UNITÁRIOS - AGENTES ONE-SHOT v2.2 ===\n\n";

$testResults = [];
$totalTests = 0;
$passedTests = 0;
$failedTests = 0;

function runTest($testName, $testFunction) {
    global $totalTests, $passedTests, $failedTests, $testResults;
    $totalTests++;

    echo "📋 Testando: $testName\n";

    try {
        $result = $testFunction();
        if ($result) {
            echo "   ✅ PASSOU\n";
            $passedTests++;
            $testResults[$testName] = 'PASS';
        } else {
            echo "   ❌ FALHOU\n";
            $failedTests++;
            $testResults[$testName] = 'FAIL';
        }
    } catch (Exception $e) {
        echo "   ❌ ERRO: " . $e->getMessage() . "\n";
        $failedTests++;
        $testResults[$testName] = 'ERROR';
    }
    echo "\n";
}

// === TESTES DE CONFIGURAÇÃO ===
runTest("Configuração - Carregamento Constants", function() {
    return defined('APP_NAME') &&
           defined('APP_VERSION') &&
           defined('GROQ_API_URL') &&
           APP_NAME === 'Agentes One Shot' &&
           APP_VERSION === '2.1';
});

runTest("Configuração - Pasta de Agentes", function() {
    return defined('AGENTS_FOLDER') &&
           file_exists(AGENTS_FOLDER) &&
           is_dir(AGENTS_FOLDER);
});

// === TESTES DE AGENTES ===
runTest("Agentes - Carregar Agentes Públicos", function() {
    $agents = getAgents();
    return is_array($agents) && count($agents) > 0;
});

runTest("Agentes - Estrutura dos Dados", function() {
    $agents = getAgents();
    if (empty($agents)) return false;

    foreach ($agents as $agent) {
        if (!isset($agent['name']) || !isset($agent['description']) || !isset($agent['prompt'])) {
            return false;
        }
    }
    return true;
});

runTest("Agentes - Obter Agente Específico", function() {
    $agents = getAgents();
    if (empty($agents)) return true; // Skip se não há agentes

    $firstAgent = $agents[0];
    $agent = getAgent($firstAgent['id']);

    return $agent !== null &&
           $agent['name'] === $firstAgent['name'];
});

runTest("Agentes - Validação de ID Inválido", function() {
    $invalidAgent = getAgent('invalid-agent-id-<>{}');
    return $invalidAgent === null;
});

// === TESTES DE MODELOS ===
runTest("Modelos - Lista de Prioridade", function() {
    $models = getModelPriorityList();
    return is_array($models) &&
           count($models) > 0 &&
           isset($models[0]['id']) &&
           isset($models[0]['name']);
});

runTest("Modelos - Modelo Atual", function() {
    $current = getCurrentModel();
    return isset($current['id']) &&
           !empty($current['id']);
});

runTest("Modelos - Validação de Modelo", function() {
    $models = getModelPriorityList();
    if (empty($models)) return false;

    $firstModel = $models[0];
    return isValidModel($firstModel['id']);
});

// === TESTES DE SEGURANÇA ===
runTest("Segurança - Sanitização de Input", function() {
    $malicious = '<script>alert("xss")</script>';
    $clean = sanitizeInput($malicious, 'string');
    return $clean !== $malicious &&
           strpos($clean, '<script>') === false;
});

runTest("Segurança - Validação de ID de Agente", function() {
    $validId = validateAgentId('valid-agent-123');
    $invalidId = validateAgentId('invalid<>{}');
    return $validId === true && $invalidId === false;
});

runTest("Segurança - Detecção de Conteúdo Malicioso", function() {
    $malicious = '<script>alert("xss")</script>';
    return detectMaliciousContent($malicious) === true;
});

// === TESTES DE FUNCIONALIDADES ===
runTest("Funcionalidades - Carregamento de Arquivo de Agente", function() {
    $agentFiles = glob(AGENTS_FOLDER . '*.php');
    if (empty($agentFiles)) return false;

    $agentFile = $agentFiles[0];
    $agentData = include $agentFile;
    return is_array($agentData) && isset($agentData['name']);
});

runTest("Funcionalidades - Formulários de Agente", function() {
    $agents = getAgents();
    if (empty($agents)) return true;

    foreach ($agents as $agent) {
        if (isset($agent['fields']) && is_array($agent['fields'])) {
            foreach ($agent['fields'] as $field) {
                if (!isset($field['label']) || !isset($field['type'])) {
                    return false;
                }
            }
        }
    }
    return true;
});

// === TESTES DE URL AMIGÁVEIS ===
runTest("URLs Amigáveis - Verificação de Estrutura", function() {
    $agents = getAgents();
    if (empty($agents)) return true;

    foreach ($agents as $agent) {
        if (isset($agent['url'])) {
            // URL deve ser válida (sem caracteres especiais)
            if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $agent['url'])) {
                return false;
            }
        }
    }
    return true;
});

// === TESTES DE PERFORMANCE ===
runTest("Performance - Tempo de Carregamento dos Agentes", function() {
    $start = microtime(true);
    $agents = getAgents();
    $end = microtime(true);

    $loadTime = $end - $start;
    return $loadTime < 1.0; // Deve carregar em menos de 1 segundo
});

// === TESTES DE COMPATIBILIDADE ===
runTest("Compatibilidade - Versão PHP", function() {
    return version_compare(PHP_VERSION, '7.4.0', '>=');
});

runTest("Compatibilidade - Extensões Necessárias", function() {
    return extension_loaded('curl') &&
           extension_loaded('json') &&
           function_exists('curl_init');
});

// === TESTES DE ESTRUTURA DE ARQUIVOS ===
runTest("Estrutura - Arquivos Essenciais", function() {
    $requiredFiles = [
        '../config.php',
        '../agentes.php',
        '../models.php',
        '../security.php',
        '../index.php',
        '../api.php',
        '../.htaccess',
        '../router.php'
    ];

    foreach ($requiredFiles as $file) {
        if (!file_exists($file)) {
            return false;
        }
    }
    return true;
});

runTest("Estrutura - Pasta Agentes", function() {
    return file_exists(AGENTS_FOLDER) &&
           is_dir(AGENTS_FOLDER) &&
           count(glob(AGENTS_FOLDER . '*.php')) > 0;
});

// === RELATÓRIO FINAL ===
echo "=== 📊 RELATÓRIO FINAL DE TESTES ===\n";
echo "Total de Testes: $totalTests\n";
echo "✅ Passaram: $passedTests\n";
echo "❌ Falharam: $failedTests\n";
echo "Taxa de Sucesso: " . round(($passedTests / $totalTests) * 100, 2) . "%\n\n";

echo "=== 🔍 TESTES FUNCIONAIS (MANUAIS) ===\n";
echo "Para testes funcionais completos, acesse:\n";
echo "📱 Página Principal: http://localhost:8000/\n";
echo "🔧 Painel Admin: http://localhost:8000/viewadmin.php?sys=admin\n";
echo "🤖 Dashboard Modelos: http://localhost:8000/model-status.php?sys=admin\n\n";
echo "URLs de Agentes (Exemplos):\n";
$agents = getAgents();
foreach (array_slice($agents, 0, 3) as $agent) {
    if (isset($agent['url'])) {
        echo "📄 {$agent['name']}: http://localhost:8000/{$agent['url']}\n";
    }
}

echo "\n=== 🏁 TESTES AUTOMÁTICOS CONCLUÍDOS ===\n";

if ($failedTests === 0) {
    echo "🎉 Todos os testes passaram! O sistema está pronto para uso.\n";
    exit(0);
} else {
    echo "⚠️ Houveram falhas. Verifique os detalhes acima.\n";
    exit(1);
}
?>