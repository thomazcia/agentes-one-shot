# Como Criar Novos Agentes

Este documento explica como criar novos agentes para o sistema Agentes One-Shot.

## 📁 Estrutura dos Arquivos

Todos os agentes ficam na pasta `/agentes/` com a seguinte estrutura:
- Nome do arquivo: `nome-do-agente.php`
- Cada arquivo retorna um array PHP com as configurações

## 🔧 Como Criar um Novo Agente

### 1. Copie o Template

```bash
cp agent-template.php agentes/seu-agente.php
```

### 2. Configure as Opções

Abra o arquivo `agentes/seu-agente.php` e edite o array:

```php
return [
    // Informações Básicas
    'name' => 'Nome do Agente',
    'description' => 'Descrição do que este agente faz',
    'icon' => 'bi-robot', // Ícone Bootstrap Icons
    'color' => '#667eea', // Cor personalizada (opcional)

    // Configurações do Prompt
    'prompt' => 'Seu prompt principal aqui. Use [nomedocampo] para substituição.',

    // Campos do Formulário
    'fields' => [
        [
            'label' => 'Nome do Campo',
            'placeholder' => 'Placeholder para o usuário',
            'type' => 'text', // text, textarea, number, email, select
            'required' => true, // true ou false
            'options' => [] // Apenas para type='select'
        ]
    ],

    // Configurações Adicionais
    'category' => 'geral',
    'tags' => ['tag1', 'tag2'],
    'difficulty' => 'iniciante', // iniciante, intermediário, avançado
    'estimated_time' => '5-10 min',
    'version' => '1.0'
];
```

## 📝 Detalhes das Configurações

### Informações Básicas
- **name**: Nome que aparece no card do agente
- **description**: Descrição breve do que o agente faz
- **icon**: Ícone do Bootstrap Icons (ex: `bi-robot`, `bi-journal-text`)
- **color**: Cor personalizada para o ícone (opcional)

### Prompt
- Use `[nomedocampo]` para marcar onde os valores do formulário serão substituídos
- Seja específico sobre o que você espera da IA

### Campos (fields)
- **label**: Nome que aparece no formulário
- **placeholder**: Texto de ajuda no campo
- **type**: Tipo de input:
  - `text`: Campo de texto simples
  - `textarea`: Área de texto grande
  - `number`: Campo numérico
  - `email`: Campo de e-mail
  - `select`: Menu suspenso (requer `options`)
- **required**: true/false se o campo é obrigatório
- **options**: Array de opções (apenas para type='select')

### Configurações Adicionais
- **category**: Categoria para organização
- **tags**: Array de tags para busca
- **difficulty**: Nível de dificuldade
- **estimated_time**: Tempo estimado de uso

## 💡 Exemplos

### Agente Simples
```php
return [
    'name' => 'Gerador de Nomes',
    'description' => 'Gera nomes criativos para empresas',
    'icon' => 'bi-lightbulb',
    'prompt' => 'Gere 10 nomes criativos para [tipo_empresa] no segmento de [segmento].',
    'fields' => [
        [
            'label' => 'tipo_empresa',
            'placeholder' => 'Ex: Startup, consultoria',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'segmento',
            'placeholder' => 'Ex: Tecnologia, saúde, educação',
            'type' => 'text',
            'required' => true
        ]
    ],
    'category' => 'criatividade',
    'difficulty' => 'iniciante'
];
```

### Agente com Select
```php
return [
    'name' => 'Gerador de Posts Instagram',
    'description' => 'Cria posts otimizados para Instagram',
    'icon' => 'bi-instagram',
    'prompt' => 'Crie um post para Instagram sobre: [assunto]. Tom: [tom]. Hashtags: [hashtags]',
    'fields' => [
        [
            'label' => 'assunto',
            'placeholder' => 'Ex: Lançamento de produto',
            'type' => 'text',
            'required' => true
        ],
        [
            'label' => 'tom',
            'placeholder' => 'Escolha o tom',
            'type' => 'select',
            'required' => true,
            'options' => [
                'Profissional',
                'Descontraído',
                'Inspirador',
                'Informativo'
            ]
        ],
        [
            'label' => 'hashtags',
            'placeholder' => 'Ex: #marketing #negocios',
            'type' => 'text',
            'required' => false
        ]
    ],
    'category' => 'social_media',
    'difficulty' => 'iniciante',
    'estimated_time' => '3-5 min'
];
```

## 🚀 Deploy

Após criar seu agente:

1. **Verifique a sintaxe PHP**:
   ```bash
   php -l agentes/seu-agente.php
   ```

2. **Teste no navegador**:
   - Acesse `http://localhost:8000`
   - Seu agente deve aparecer na lista

3. **Ajuste conforme necessário**:
   - Teste o formulário
   - Verifique se o prompt funciona como esperado
   - Ajuste campos e instruções

## 📋 Lista de Ícones Úteis

- `bi-robot` - Robô
- `bi-journal-text` - Diário/Texto
- `bi-envelope-heart` - E-mail
- `bi-chat-dots` - Chat
- `bi-code-slash` - Código
- `bi-briefcase` - Negócios
- `bi-lightbulb` - Ideia
- `bi-gear` - Configuração
- `bi-instagram` - Instagram
- `bi-facebook` - Facebook
- `bi-twitter` - Twitter
- `bi-youtube` - YouTube

## 🔧 Dicas Avançados

### Variáveis Automáticas
No prompt, você pode usar:
- `[nomedocampo]` - Valor do campo do formulário
- Adicione contexto extra se necessário

### Prompts Eficazes
- Seja específico sobre o formato desejado
- Inclua exemplos se necessário
- Defina o tom e estilo esperados
- Limite o tamanho se relevante

### Campos Otimizados
- Use labels claros e descritivos
- Forneça placeholders úteis
- Marque como obrigatório apenas o essencial
- Use selects para opções padronizadas

---

**Dúvidas**: Consulte os agentes existentes em `/agentes/` como exemplo ou entre em contato com o administrador.