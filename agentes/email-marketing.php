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

Tipo de e-mail: [Tipo e-mail]
Público-alvo: [Público alvo]
Produto/Serviço: [Produto]
Principal benefício: [Benefício principal]
Call-to-action: [CTA (Call to action)]
Tom de voz: [Tom de voz]
Informações adicionais: [Informações adicionais]

ESTRUTURA OBRIGATÓRIA:
1. Assunto otimizado para alta taxa de abertura
2. Linha de pré-cabeçalho (linha fina)
3. Saudação personalizada
4. Corpo do texto (2-3 parágrafos)
5. Destaque do benefício principal
6. Call-to-action claro e direto

DIRETRIZES:
- Assunto com máximo de 50 caracteres
- Linha fina com máximo de 100 caracteres
- Linguagem persuasiva e focada em benefícios
- Evitar spam words excessivas
- Tom consistente com o solicitado
- Call-to-action urgente mas não agressivo
- Apresentar texto puro, sem markdown
- Não apresentar o número de caracteres utilizado
- Não apresentar "De: pessoa@email.com / Para: Nome pessoa"<div class=""></div>
- Seguir o modelo do output abaixo

OUTPUT:
Assunto: Aqui vem o texto do assunto gerado

Linha fina: Aqui vem o texto da linha fina gerado

Aqui vem todo o conteúdo do e-mail gerado

-----------------
[ASSINATURA AQUI]
',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Tipo e-mail',
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
            'label' => 'Público alvo',
            'placeholder' => 'Ex: Clientes B2B, consumidores finais,leads',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'Produto',
            'placeholder' => 'Ex: Curso de Marketing Digital, Software CRM',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'Benefício principal',
            'placeholder' => 'Ex: Aumentar vendas em 30%, economizar tempo',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'CTA (Call to action)',
            'placeholder' => 'Selecione uma opção',
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
            'label' => 'Tom de voz',
            'placeholder' => 'Selecione uma opção',
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
            'label' => 'Informações adicionais',
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