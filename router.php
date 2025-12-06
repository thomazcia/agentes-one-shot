<?php
/**
 * Router para URLs amigáveis - Funciona com servidor PHP embutido
 */

$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'];

// Define MIME types para arquivos estáticos
$mimeTypes = [
    'js' => 'application/javascript',
    'css' => 'text/css',
    'json' => 'application/json',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml'
];

// Se for arquivo estático, servir com MIME type correto
$extension = pathinfo($path, PATHINFO_EXTENSION);
if (isset($mimeTypes[$extension]) && file_exists(__DIR__ . '/' . $path)) {
    header('Content-Type: ' . $mimeTypes[$extension]);
    readfile(__DIR__ . '/' . $path);
    exit;
}

// Remove base path se existir
$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath !== '/' && $basePath !== '') {
    $path = str_replace($basePath, '', $path);
}

$path = trim($path, '/');

// Se for API endpoint, vai direto para api.php
if ($path === 'api.php' || $path === 'api') {
    include __DIR__ . '/api.php';
    exit;
}

// Se estiver vazia ou for a raiz, vai para index.php
if (empty($path) || $path === 'index.php') {
    include __DIR__ . '/index.php';
    exit;
}

// Verifica se é um agente
require_once __DIR__ . '/agentes.php';
$agentes = getAgents();

foreach ($agentes as $agente) {
    if (isset($agente['url']) && $agente['url'] === $path) {
        // Define o agente encontrado e vai para index.php
        $_GET['agent'] = $path;
        include __DIR__ . '/index.php';
        exit;
    }
}

// Se não encontrar o agente, redireciona para a página principal
include __DIR__ . '/index.php';
?>