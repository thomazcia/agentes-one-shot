<?php
/**
 * Agente: Gerador de E-mails Marketing
 * Cria e-mails persuasivos para campanhas de marketing
 */

return [
    // Informações Básicas
    'name' => 'Gerador de E-mails Marketing',
    'description' => 'Cria e-mails persuasivos para campanhas de marketing e vendas',
    'icon' => 'bi-envelope-heart',
    'color' => '#4ECDC4',

    // Configurações do Prompt
    'prompt' => 'Crie um e-mail de marketing persuasivo com as seguintes características:

Tipo de e-mail: [tipo_email]
Público-alvo: [publico_alvo]
Produto/Serviço: [produto]
Principal benefício: [beneficio_principal]
Call-to-action: [call_to_action]
Tom: [tom]
Informações adicionais: [info_adicionais]

ESTRUTURA OBRIGATÓRIA:
1. Assunto otimizado para alta taxa de abertura
2. Linha de pré-cabeçalho (preheader)
3. Saudação personalizada
4. Corpo do texto (2-3 parágrafos)
5. Destaque do benefício principal
6. Call-to-action claro e direto
7. Assinatura profissional

DIRETRIZES:
- Assunto com máximo de 50 caracteres
- Preheader com máximo de 100 caracteres
- Linguagem persuasiva e focada em benefícios
- Evitar spam words excessivas
- Tom consistente com o solicitado
- Call-to-action urgente mas não agressivo',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'tipo_email',
            'placeholder' => 'Escolha o tipo de e-mail',
            'type' => 'select',
            'required' => true,
            'options' => [
                'Lançamento de produto',
                'Promoção especial',
                'Newsletter informativa',
                'Recuperação de carrinho',
                'Follow-up pós-venda',
                'Convite para evento'
            ]
        ],
        [
            'label' => 'publico_alvo',
            'placeholder' => 'Ex: Clientes B2B, consumidores finais,leads',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'produto',
            'placeholder' => 'Ex: Curso de Marketing Digital, Software CRM',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'beneficio_principal',
            'placeholder' => 'Ex: Aumentar vendas em 30%, economizar tempo',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'call_to_action',
            'placeholder' => 'Ex: Comprar agora, Agendar demonstração, Saiba mais',
            'type' => 'select',
            'required' => true,
            'options' => [
                'Comprar agora',
                'Saiba mais',
                'Agendar demonstração',
                'Baixar guia gratuito',
                'Ver detalhes',
                'Fazer teste gratuito'
            ]
        ],
        [
            'label' => 'tom',
            'placeholder' => 'Escolha o tom do e-mail',
            'type' => 'select',
            'required' => true,
            'options' => [
                'Profissional e corporativo',
                'Amigável e informal',
                'Urgente e direto',
                'Inspirador e motivacional',
                'Técnico e especializado'
            ]
        ],
        [
            'label' => 'info_adicionais',
            'placeholder' => 'Ex: Desconto de 20% esta semana, bônus especial',
            'type' => 'textarea',
            'required' => false,
            'rows' => 3
        ]
    ],

    // Configurações Adicionais
    'category' => 'marketing',
    'tags' => ['email', 'marketing', 'vendas', 'comunicação'],
    'difficulty' => 'iniciante',
    'estimated_time' => '3-5 min',
    'version' => '1.0'
];
?>