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
    'prompt' => 'Analise o sentimento do seguinte texto: [texto]

CLASSIFICAÇÃO:
- Positivo: Se expressa emoções boas, satisfação, otimismo
- Negativo: Se expressa emoções ruins, insatisfação, pessimismo
- Neutro: Se é informativo, objetivo, sem emoções claras

FORMATO:
Sentimento: [POSITIVO/NEGATIVO/NEUTRO]
Confiança: [ALTA/MÉDIA/BAIXA]
Análise: [breve explicação do porquê]

Texto analisado: [texto]',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'texto',
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