<?php
/**
 * Agente: Ideias de Títulos de Post - Agentes One-Shot v2.0
 * Gera títulos criativos e chamativos para posts em blogs e redes sociais
 */

return [
    // Informações Básicas
    'name' => 'Ideias de Títulos de Post',
    'description' => 'Gere títulos criativos e chamativos para posts de blog, redes sociais e conteúdo digital',
    'url' => 'ideias-titulos-post',
    'icon' => 'bi-pencil-square',
    'status_public' => 'publico',

    // Configurações do Prompt
    'prompt' => 'Crie 10 títulos criativos e chamativos para posts sobre o tema: "[assunto]".

    [publico_alvo]

    Para cada título, inclua uma breve explicação (em 1-2 linhas) do porquê funciona bem.

    Organize a resposta da seguinte forma:

    TÍTULOS CRIATIVOS E CLICÁVEIS

    1. [Título 1]
       Por que funciona: [explicação]

    2. [Título 2]
       Por que funciona: [explicação]

    3. [Título 3]
       Por que funciona: [explicação]

    4. [Título 4]
       Por que funciona: [explicação]

    5. [Título 5]
       Por que funciona: [explicação]

    6. [Título 6]
       Por que funciona: [explicação]

    7. [Título 7]
       Por que funciona: [explicação]

    8. [Título 8]
       Por que funciona: [explicação]

    9. [Título 9]
       Por que funciona: [explicação]

    10. [Título 10]
        Por que funciona: [explicação]

   DICA BÔNUS:
    Inclua 3 dicas rápidas para escolher o melhor título entre as opções geradas.',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Assunto',
            'name' => 'assunto',
            'placeholder' => 'Ex: Marketing Digital, Receitas Saudáveis, Tecnologia, Moda Sustentável',
            'type' => 'text',
            'required' => true,
            'options' => []
        ],
        [
            'label' => 'Público Alvo',
            'name' => 'publico_alvo',
            'placeholder' => 'Ex: Iniciantes, Profissionais, Jovens adultos, Mães, Empreendedores',
            'type' => 'text',
            'required' => false,
            'options' => []
        ]
    ],

    // Configurações Adicionais
    'category' => 'marketing',
    'color' => '#667eea',
    'version' => '2.0',
    'difficulty' => 'iniciante',
    'estimated_time' => '2 min'
];
?>