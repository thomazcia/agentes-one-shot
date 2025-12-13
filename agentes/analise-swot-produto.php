<?php
/**
 * Agente: Análise SWOT de Produto
 * Realiza análise estratégica completa SWOT com matriz de ações e prioridades
 */

return [
    // Informações Básicas
    'name' => 'Análise SWOT de Produto',
    'description' => 'Realiza análise estratégica SWOT aprofundada com matriz de ações e prioridades estratégicas',
    'url' => 'analise-swot-produto',
    'icon' => 'bi-briefcase-fill',
    'color' => '#2563eb',
    'status_public' => 'publico', // 'publico' ou 'dev'

    // Configurações do Prompt
    'prompt' => 'Você é um consultor sênior de estratégia e negócios.
Analise o produto e o negócio abaixo e gere uma Análise SWOT aprofundada, objetiva e acionável.

Dados de entrada:

Produto: [Nome do Produto]

Principal apelo do produto: [Apelo do Produto]

Empresa: [Nome da Empresa]

Segmento de mercado: [Segmento de Mercado]

Instruções obrigatórias:

Considere cenário competitivo, nível de maturidade do mercado e possíveis substitutos

Diferencie claramente fatores internos (Forças/Fraquezas) e externos (Oportunidades/Ameaças)

Seja direto, sem texto genérico ou marketing

Não presuma dados financeiros, market share ou compliance específico

Saída obrigatória (nesta ordem):

1. Análise SWOT
Forças
Fraquezas
Oportunidades
Ameaças
2. Matriz Estratégica (Ações)

Gere ações objetivas combinando os fatores:

FO (usar forças para explorar oportunidades):
FA (usar forças para mitigar ameaças):
DO (corrigir fraquezas para aproveitar oportunidades):
DA (reduzir fraquezas e evitar ameaças):

3. Prioridades Estratégicas

Liste até 5 prioridades, ordenadas por impacto no negócio.
Cada prioridade deve ter no máximo 1 linha.

4. Nível de Risco Geral

Classifique como: Baixo / Médio / Alto
Justifique em até 3 linhas/parágrafos curtos.

Tom de voz: Profissional e analítico',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Nome do Produto',
            'name' => 'nome_produto',
            'placeholder' => 'Ex: iPhone 15 Pro, Software CRM, Curso de Marketing Digital',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'Apelo do Produto',
            'name' => 'apelo_produto',
            'placeholder' => 'Ex: Câmera profissional com IA, Automação completa de vendas, Aprendizado prático com projetos reais',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'Nome da Empresa',
            'name' => 'nome_empresa',
            'placeholder' => 'Ex: Apple Inc, Salesforce, Digital Marketing School',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'Segmento de Mercado',
            'name' => 'segmento_mercado',
            'placeholder' => 'Ex: Smartphones premium, CRM para PMEs, Educação corporativa em marketing',
            'type' => 'text',
            'required' => true
        ]
    ],

    // Configurações Adicionais
    'category' => 'negocios',
    'tags' => ['swot', 'análise', 'estratégia', 'negócios', 'marketing'],
    'difficulty' => 'avançado',
    'estimated_time' => '3-5 min',
    'version' => '2.2'
];
?>