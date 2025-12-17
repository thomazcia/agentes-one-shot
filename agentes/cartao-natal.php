<?php
/**
 * Agente: Cartão de Natal v1.0
 * Cria cartões de natal personalizados com mensagens de amor, felicidade e emoção
 */
return [
    // Informações Básicas
    'name' => 'Cartão de Natal',
    'description' => 'Crie cartões de natal personalizados com mensagens emocionantes e cheias de carinho',
    'icon' => 'bi-gift',
    'color' => '#c41e3a', // Cor verde natalina tradicional

    // Configurações do Prompt
    'prompt' => 'Como um especialista em mensagens de natal, crie um cartão de natal emocionante e personalizado com as seguintes informações:

DADOS FORNECIDOS:
- Nome do remetente: [Seu nome]
- Destinatário: [Destinatário]
- Estilo do cartão: [Estilo do cartão]
- Mensagem adicional: [Mensagem adicional]

DIRETRIZES CRIATIVAS:
- Crie uma mensagem que transmita amor, felicidade, emoção, afeto e carinho
- Use elementos natalinos (estrelas, neve, paz, confraternização)
- Seja caloroso e genuíno na comunicação
- Adapte o tom conforme o estilo solicitado
- Inclua desejos de paz, alegria e prosperidade
- Se houver mensagem adicional, incorpore-a naturalmente no texto

FORMATO DO CARTÃO:
🎄 **FELIZ NATAL** 🎄

[COMEÇO COM SAUDAÇÃO PERSONALIZADA AO DESTINATÁRIO]

[MENSAGEM PRINCIPAL - 2-3 PARÁGRAFOS COM EMOÇÃO E CARINHO]

[MENSAGEM ADICIONAL SE FORNECIDA]

[DESEJOS DE NATAL E ANO NOVO]

[ASSINATURA FINAL COM NOME DO REMETENTE SE FORNECIDO]

❄️ **Que a magia do natal ilumine seus dias!** ❄️

ESTILOS ESPECÍFICOS:
- **Formal**: Linguagem refinada, elegante, respeitosa
- **Autêntico**: Tom sincero, pessoal, emotivo
- **Engraçado**: Humor leve, piadas natalinas, alegria descontraída
- **Normal**: Equilibrado, caloroso, universalmente apropriado

Crie um cartão que toque o coração e celebre o verdadeiro espírito do natal!',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Seu nome',
            'name' => 'seu_nome',
            'placeholder' => 'Digite seu nome',
            'type' => 'text',
            'required' => false
        ],
        [
            'label' => 'Destinatário',
            'name' => 'destinatario',
            'placeholder' => 'Nome da pessoa',
            'type' => 'text',
            'required' => false
        ],
        [
            'label' => 'Estilo do cartão',
            'name' => 'estilo_do_cartao',
            'placeholder' => 'Escolha o estilo do cartão',
            'type' => 'select',
            'required' => true,
            'options' => [
                'Normal',
                'Formal',
                'Autêntico',
                'Engraçado'
            ]
        ],
        [
            'label' => 'Mensagem adicional',
            'name' => 'mensagem_adicional',
            'placeholder' => 'Deseja incluir alguma mensagem adicional ou informação específica?',
            'type' => 'textarea',
            'required' => false
        ]
    ],

    // Configurações Adicionais
    'category' => 'festas',
    'tags' => ['natal', 'cartao', 'mensagem', 'festas', 'amor', 'feliz'],
    'difficulty' => 'iniciante',
    'estimated_time' => '2-3 min',
    'version' => '1.0'
];
?>