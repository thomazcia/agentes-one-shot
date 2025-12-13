<?php
/**
 * Agente: Teste Interno - Analisador de Sentimento
 * Agente em desenvolvimento para testar funcionalidades do sistema admin
 */

return [
    // Informações Básicas
    'name' => 'Analisador de Sentimento',
    'description' => 'Analisa o sentimento de textos',
    'url' => 'analisador-sentimento',
    'icon' => 'bi-emoji-smile',
    'color' => '#9C27B0',
    'status_public' => 'dev', // 'publico' ou 'dev' - controle de visibilidade

    // Configurações do Prompt
    'prompt' => 'Analise o sentimento do seguinte texto: [Texto]\n\nCLASSIFICAÇÃO:\n- Positivo: Se expressa emoções boas, satisfação, otimismo\n- Negativo: Se expressa emoções ruins, insatisfação, pessimismo\n- Neutro: Se é informativo, objetivo, sem emoções claras\n\nFORMATO:\nSentimento: [POSITIVO/NEGATIVO/NEUTRO]\nConfiança: [ALTA/MÉDIA/BAIXA]\nAnálise: [breve explicação do porquê]\n\nTexto analisado: [Texto]',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Texto',
            'name' => 'texto',
            'placeholder' => 'Digite o texto para analisar o sentimento',
            'type' => 'textarea',
            'required' => true
        ]
    ],

    // Configurações Adicionais
    'category' => 'desenvolvimento',
    'tags' => ['teste', 'sentimento', 'ia', 'dev'],
    'difficulty' => 'experimental',
    'estimated_time' => '30 segundos',
    'version' => '0.1-dev'
];
?>