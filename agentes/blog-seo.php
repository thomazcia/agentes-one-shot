<?php
/**
 * Agente: Blog SEO v2.0
 * Gera textos para blog otimizados para SEO com foco em palavras-chave e estrutura adequada
 */
return [
    // Informações Básicas
    'name' => 'Blog SEO',
    'description' => 'Gera textos para blog otimizados para SEO com estrutura adequada e palavras-chave',
    'icon' => 'bi-search',
    'color' => '#28a745',

    // Configurações do Prompt
    'prompt' => 'Como um especialista em SEO e conteúdo digital, crie um texto completo para blog otimizado para motores de busca sobre o seguinte assunto: [Assunto].

**NICHODEMERCADO**: [Nicho de Mercado]
**PALAVRASCHAVE**: [Palavras Chave]

DIRETRIZES SEO:
- Título otimizado (até 60 caracteres) contendo a palavra-chave principal
- Meta description atrativa (até 160 caracteres)
- Estrutura com subtítulos H2 e H3
- Densidade de palavras-chave entre 1-2%
- Texto com 800-1500 palavras
- Linguagem clara e envolvente
- Call-to-action no final

ESTRUTURA DO CONTEÚDO:
1. **Título SEO Otimizado**
2. **Meta Description**
3. **Introdução** (gancho + apresentação do assunto)
4. **Desenvolvimento** (2-3 subtópicos com H2/H3)
5. **Conclusão** (recapitulação + CTA)

FORMATO DE RESPOSTA:
---
**Título:** [Título otimizado]

**Meta Description:** [Descrição atrativa para busca]

# [Título do Artigo]

[Introdução envolvente que prenda a atenção do leitor e apresente o tema]

## [Subtítulo H2 relevante]

[Desenvolvimento do primeiro ponto com detalhes e exemplos]

### [Subtítulo H3 específico]

[Informações complementares e detalhadas]

## [Subtítulo H2 adicional]

[Continuação do conteúdo com informações valiosas]

## [Conclusão com Call-to-Action]

[Resumo dos pontos principais + incentivo para ação do usuário]

---

**Palavras-chave adicionais sugeridas:** [Lista 5-10 palavras relacionadas]

**Dica SEO:** [Recomendação adicional para otimização]',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Assunto',
            'name' => 'assunto',
            'placeholder' => 'Ex: Como melhorar o tráfego orgânico',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'Nicho de Mercado',
            'name' => 'nicho_mercado',
            'placeholder' => 'Ex: Marketing digital, e-commerce, educação',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'Palavras Chave',
            'name' => 'palavras_chave',
            'placeholder' => 'Ex: tráfego orgânico, SEO, marketing digital',
            'type' => 'text',
            'required' => true
        ]
    ],

    // Configurações Adicionais v2.0
    'category' => 'marketing',
    'tags' => ['SEO', 'blog', 'conteúdo', 'marketing digital', 'redação'],
    'difficulty' => 'intermediário',
    'estimated_time' => '10-15 min',
    'version' => '2.0'
];
?>