<?php
/**
 * Agente: Contador de Piada
 * Cria piadas engraçadas personalizadas com base em um assunto ou termo informado
 */

return [
    // Informações Básicas
    'name' => 'Contador de Piada',
    'description' => 'Cria piadas engraçadas personalizadas com base no assunto informado',
    'icon' => 'bi-emoji-laughing',
    'color' => '#FFD93D',

    // Configurações do Prompt
    'prompt' => 'Crie uma piada engraçada sobre o assunto: [assunto].

REGRAS:
    - A piada deve ser leve e apropriada para todos os públicos
    - Seja criativo e original
    - Evite clichês quando possível
    - Mantenha a piada curta e direta
    - Se o assunto for difícil para piada, adapte de forma inteligente
    - Inclua um pequeno setup antes da "sacada"
    - Linguagem informal e divertida
    - Foco no assunto principal: [assunto]
    - Se já engraçado mas ofensivo, use com moderação

FORMATO:
    Conte a piada em um formato natural, como se estivesse contando para um amigo.
    Não precisa explicar que é uma piada, apenas conte-a de forma direta.

EXEMPLOS:
- Por que o livro foi preso na delegacia? Porque ele estava envolvido em muitos capítulos escuros!
- O que o gato disse ao rato? "Estou mia" - o rato respondeu: "Parece que você tem um problema de autoestima!"

IMPORTANTE:
- Máximo de 2-3 frases
- Linguagem informal e divertida
- Foco no assunto principal: [assunto]
    - Se já engraçado mas ofensivo, use com moderação',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'assunto',
            'placeholder' => 'Ex: programação, gatos, comida, futebol',
            'type' => 'text',
            'required' => true
        ]
    ],

    // Configurações Adicionais
    'category' => 'entretenimento',
    'tags' => ['piada', 'humor', 'entretenimento', 'diversão'],
    'difficulty' => 'iniciante',
    'estimated_time' => '1-2 min',
    'version' => '1.0'
];
?>
