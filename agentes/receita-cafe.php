<?php
/**
 * Agente: Receita de Café v1.0
 * Cria receitas personalizadas de café com base no tipo de café, método e volume desejado
 */
return [
    // Informações Básicas
    'name' => 'Receita de Café',
    'description' => 'Crie receitas personalizadas de café com base no tipo, método e volume desejado',
    'icon' => 'bi-cup-hot',
    'color' => '#6f4e37', // Cor marrom de café

    // Configurações do Prompt
    'prompt' => 'Como um barista especialista em café, crie uma receita completa e detalhada de café com as seguintes informações:

DADOS FORNECIDOS:
- Forma do café: [Forma]
- Tipo de moedor: [Tipo de moedor]
- Método de preparo: [Método]
- Volume final desejado: [Volume] ml

DIRETRIZES PARA RECEITA:
- Forneça instruções precisas e fáceis de seguir
- Inclua a proporção correta de café e água
- Especifique a granulometria ideal da moagem
- Indique a temperatura adequada da água
- Detalhe o tempo de extração
- Adicione dicas profissionais para obter o melhor resultado

FORMATO DA RECEITA:
☕ **RECEITA DE CAFÉ ESPECIAL**

**VOLUME FINAL:** [Volume] ml
**MÉTODO:** [Método informado ou sugerido]
**FORMA:** [Forma do café]

---

**INGREDIENTES:**
- Café: [Quantidade em gramas]
- Água: [Volume em ml]
- Temperatura da água: [Temperatura em °C]

**EQUIPAMENTOS NECESSÁRIOS:**
[Liste todos os equipamentos necessários]

**PREPARO PASSO A PASSO:**

1. **Preparação**
   [Instruções iniciais de preparo]

2. **Moagem** (se aplicável)
   [Tipo de moedor informado + instruções de moagem]
   - Granulometria ideal: [descrição detalhada]

3. **Pré-infusão** (se aplicável ao método)
   [Instruções de pré-infusão com tempo]

4. **Extração**
   [Passos detalhados de extração do método escolhido]
   - Tempo total: [X minutos/segundos]

5. **Finalização**
   [Instruções finais e como servir]

**DICAS PROFISSIONAIS:**
- [3-5 dicas valiosas para obter o melhor resultado]

**INFORMAÇÕES ADICIONAIS:**
- [Observações específicas sobre o método ou tipo de café]

**DEGUSTAÇÃO:**
[Sugestões de como apreciar o café preparado]

---

Se não foi especificado um método, sugira o método mais adequado para o tipo de café escolhido.
Se não foi informado o volume, use 250ml como padrão (uma xícara média).
Adapte a receita para proporcionar a melhor experiência de café possível!',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Forma',
            'name' => 'forma',
            'placeholder' => 'Selecione a forma do café',
            'type' => 'select',
            'required' => true,
            'options' => [
                'Grão',
                'Café moído'
            ]
        ],
        [
            'label' => 'Tipo de moedor',
            'name' => 'tipo_moedor',
            'placeholder' => 'Ex: Moedor manual, Moedor elétrico, Industrial, etc.',
            'type' => 'text',
            'required' => false
        ],
        [
            'label' => 'Método de preparo',
            'name' => 'metodo',
            'placeholder' => 'Ex: Hario V60, Prensa Francesa, Expresso, Cafeteira Italiana, etc.',
            'type' => 'text',
            'required' => false
        ],
        [
            'label' => 'Volume (ml)',
            'name' => 'volume',
            'placeholder' => 'Ex: 250 (para uma xícara média)',
            'type' => 'number',
            'required' => false,
            'min' => 30,
            'max' => 1000
        ]
    ],

    // Configurações Adicionais
    'category' => 'culinaria',
    'tags' => ['café', 'receita', 'barista', 'culinária', 'bebidas'],
    'difficulty' => 'iniciante',
    'estimated_time' => '5-10 min',
    'version' => '1.0'
];
?>
