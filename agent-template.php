<?php
/**
 * Template de Agente - Agentes One-Shot v1.0
 * Copie este arquivo para criar novos agentes
 * Nome do arquivo: nome-do-agente.php
 */

return [
    // Informações Básicas
    'name' => 'Nome do Agente',
    'description' => 'Descrição do que este agente faz',
    'url' => 'url-amigavel-do-agente', // URL amigável para acesso direto (sem espaços, use hífens)
    'icon' => 'bi-robot', // Ícone Bootstrap Icons

    // Configurações do Prompt
    'prompt' => 'Seu prompt principal aqui. Use [nomedocampo] para substituição automática.',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Nome do Campo',
            'placeholder' => 'Placeholder para o usuário',
            'type' => 'text', // text, textarea, number, email, select
            'required' => true, // true ou false
            'options' => [] // Apenas para type='select' => ['Opção 1', 'Opção 2']
        ]
    ],

    // Configurações Adicionais
    'category' => 'geral', // Categoria para organização
    'color' => '#667eea', // Cor do ícone (opcional)
    'version' => '1.0'
];

// Exemplo de uso:
/**
 * Exemplo de agente de geração de conteúdo para blog
 *
 * 'prompt' => 'Crie um artigo para blog sobre o tema: [assunto].
 *             Público-alvo: [publico].
 *             Tom: [tom].
 *             Palavras-chave: [palavras_chave].',
 *
 * 'fields' => [
 *     ['label' => 'assunto', 'placeholder' => 'Ex: Marketing Digital', 'type' => 'text', 'required' => true],
 *     ['label' => 'publico', 'placeholder' => 'Ex: Iniciantes em marketing', 'type' => 'text', 'required' => true],
 *     ['label' => 'tom', 'placeholder' => 'Escolha o tom', 'type' => 'select', 'required' => true, 'options' => ['Profissional', 'Informal', 'Técnico', 'Inspirador']],
 *     ['label' => 'palavras_chave', 'placeholder' => 'Ex: marketing, vendas, negócios', 'type' => 'text', 'required' => false]
 * ]
 */
?>