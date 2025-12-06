<?php
/**
 * Agente: Gerador de Posts para Blog
 * Gera artigos completos para blogs com base em parâmetros personalizados
 */

return [
    // Informações Básicas
    'name' => 'Gerador de Posts para Blog',
    'description' => 'Cria artigos completos para blogs com estrutura SEO otimizada',
    'url' => 'blog-post-generator', // URL amigável para acesso direto
    'icon' => 'bi-journal-text',
    'color' => '#FF6B6B',

    // Configurações do Prompt
    'prompt' => 'Crie um artigo completo para blog sobre o tema: [assunto].

Público-alvo: [publico_alvo]
Tom de voz: [tom]
Objetivo principal: [objetivo]

Palavras-chave para SEO: [palavras_chave]

Estrutura solicitada: [estrutura]

Instruções adicionais: [instrucoes_adicionais]

IMPORTANTE:
- Artigo deve ter entre 800-1200 palavras
- Incluir título otimizado para SEO
- Usar subtítulos (H2, H3)
- Incluir meta description
- Tom consistente com o solicitado
- Foco no público-alvo definido
- Otimizado para as palavras-chave informadas',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'assunto',
            'placeholder' => 'Ex: Marketing de Conteúdo para Pequenas Empresas',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'publico_alvo',
            'placeholder' => 'Ex: Pequenos empresários, empreendedores iniciantes',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'tom',
            'placeholder' => 'Escolha o tom do artigo',
            'type' => 'select',
            'required' => true,
            'options' => [
                'Profissional e formal',
                'Informal e descontraído',
                'Técnico e especializado',
                'Inspirador e motivacional',
                'Educativo e didático'
            ]
        ],
        [
            'label' => 'objetivo',
            'placeholder' => 'Ex: Educar sobre a importância do marketing de conteúdo',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'palavras_chave',
            'placeholder' => 'Ex: marketing digital, pequenas empresas, conteúdo',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'estrutura',
            'placeholder' => 'Ex: Introdução, 3 tópicos principais, conclusão',
            'type' => 'textarea',
            'required' => false,
            'rows' => 3
        ],
        [
            'label' => 'instrucoes_adicionais',
            'placeholder' => 'Ex: Incluir exemplos práticos, focar em ROI',
            'type' => 'textarea',
            'required' => false,
            'rows' => 3
        ]
    ],

    // Configurações Adicionais
    'category' => 'conteudo',
    'tags' => ['blog', 'conteúdo', 'seo', 'escrita'],
    'difficulty' => 'iniciante', // iniciante, intermediário, avançado
    'estimated_time' => '2-3 min',
    'version' => '1.0'
];
?>