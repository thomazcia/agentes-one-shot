<?php
/**
 * Agente: Contador de Piada
 * Cria piadas engraçadas personalizadas com base em um assunto ou termo informado
 */

return [
    // Informações Básicas
    'name' => 'Contador de Piada',
    'description' => 'Cria piadas engraçadas personalizadas com base no assunto informado',
    'url' => 'contador-piada', // URL amigável para acesso direto
    'icon' => 'bi-emoji-laughing',
    'color' => '#FFD93D',
    'status_public' => 'publico', // 'publico' ou 'dev'

    // Configurações do Prompt
    'prompt' => 'Crie uma piada engraçada sobre o assunto: [Assunto].\n\nREGRAS:\n    - A piada deve ser leve e apropriada para todos os públicos\n    - Seja criativo e original\n    - Evite clichês quando possível\n    - Mantenha a piada curta e direta\n    - Se o assunto for difícil para piada, adapte de forma inteligente\n    - Inclua um pequeno setup antes da "sacada"\n    - Linguagem informal e divertida\n    - Foco no assunto principal: [Assunto]\n    - Se já engraçado mas ofensivo, use com moderação\n\nFORMATO:\n    Conte a piada em um formato natural, como se estivesse contando para um amigo.\n    Não precisa explicar que é uma piada, apenas conte-a de forma direta.\n\nEXEMPLOS:\n- Por que o livro foi preso na delegacia? Porque ele estava envolvido em muitos capítulos escuros!\n- O que o gato disse ao rato? "Estou mia" - o rato respondeu: "Parece que você tem um problema de autoestima!"\n\nIMPORTANTE:\n- Máximo de 2-3 frases\n- Linguagem informal e divertida\n- Foco no assunto principal: [Assunto]\n- Se já engraçado mas ofensivo, use com moderação',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Assunto',
            'name' => 'assunto',
            'placeholder' => 'Ex: programação, gatos, comida, futebol',
            'type' => 'text',
            'required' => true
        ]
    ],

    // Configurações Adicionais
    'category' => 'entretenimento',
    'tags' => ['piada', 'humor', 'entretenimento', 'diversão'],
    'difficulty' => 'iniciante',
    'estimated_time' => '1 min',
    'version' => '2.2'
];
?>
