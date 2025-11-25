# Agentes One-Shot v1.1

Sistema de gestão de agentes de IA com integração segura com Open Router API usando modelo Grok 4.1 Fast.

## 🆕 Versão 1.1 - Novidades

- ✅ **Sistema de Arquivos PHP**: Agentes agora configurados como arquivos PHP independentes
- ✅ **Segurança Aprimorada**: Chave API 100% protegida no backend
- ✅ **Interface Simplificada**: Foco apenas em execução de agentes
- ✅ **Campos Dinâmicos**: Suporte a text, textarea, number, email e select
- ✅ **Sistema de Badges**: Exibe categoria, dificuldade e tempo estimado
- ✅ **Debug Integrado**: Logs detalhados para facilitar diagnóstico

## 🚀 Características

- ✅ **Agentes Pré-Configurados**: Sistema focado em uso de agentes, não criação
- ✅ **Interface Intuitiva**: Design baseado em Bootstrap com layout de 2 colunas
- ✅ **Execução Segura**: Backend PHP protege 100% sua chave API da Open Router
- ✅ **Sistema de Arquivos PHP**: Cada agente como arquivo PHP independente e configurável
- ✅ **Campos Dinâmicos**: Suporte completo a text, textarea, number, email e select
- ✅ **Visual Rico**: Cards com badges, cores personalizadas e ícones
- ✅ **Grok 4.1 Fast**: Modelo rápido e eficiente da Open Router
- ✅ **Debug Integrado**: Logs detalhados para troubleshooting

## 📋 Estrutura do Sistema

```
agentes-one-shot/
├── index.php              # Frontend principal (visual)
├── api.php                # Backend API (requisições AJAX)
├── agentes.php            # Funções de gestão de agentes
├── config.php             # Configurações (chave API, etc.)
├── app.js                 # JavaScript frontend
├── agent-template.php     # Template para criar novos agentes
├── agentes/               # Pasta com arquivos dos agentes
│   ├── blog-post-generator.php    # Agente exemplo: Posts para Blog
│   ├── email-marketing.php       # Agente exemplo: E-mails Marketing
│   ├── nome-do-agente.php        # Seus agentes personalizados
│   └── ...
├── COMO-CRIAR-AGENTES.md  # Documentação para criar agentes
└── README.md
```

## ⚙️ Instalação

### 1. Requisitos

- PHP 7.4+ com cURL habilitado
- Servidor web (Apache, Nginx, etc.)
- Chave API da Open Router

### 2. Configuração

1. **Configure sua chave API Open Router**:
   Abra o arquivo `config.php` e substitua:
   ```php
   define('OPENROUTER_API_KEY', 'sk-or-v1-seu-aqui'); // ← SUA CHAVE AQUI
   ```

2. **Permissões da pasta**:
   Garanta que a pasta `/agentes` tenha permissão de escrita:
   ```bash
   chmod 755 agentes/
   ```

3. **Acesse o sistema**:
   Abra no navegador: `http://seuservidor.com/agentes-one-shot/`

## 🚀 Como Rodar Localmente

Para desenvolvimento local, você pode usar o servidor embutido do PHP (não precisa de npm/node.js):

### Opção 1: Servidor Embutido PHP (Recomendado)

```bash
cd \agentes-one-shot
php -S localhost:8000
```

Depois acesse no navegador: **http://localhost:8000**

### Opção 2: XAMPP/WAMP/MAMP

Se você já tem XAMPP/WAMP instalado:
1. Copie a pasta `agentes-one-shot` para a pasta `htdocs/` (ou `www/`)
2. Acesse: `http://localhost/agentes-one-shot/`

### Opção 3: Docker (Avançado)

Se preferir usar Docker, crie um `Dockerfile`:
```dockerfile
FROM php:7.4-apache
COPY . /var/www/html/
EXPOSE 80
```

## ⚡ Verificação de Requisitos

Antes de rodar, verifique se seu ambiente está configurado:

```bash
# Verificar versão PHP (precisa ser 7.4+)
php --version

# Verificar se cURL está habilitado
php -m | grep curl
```

**Importante**: Não precisa de npm/node.js porque:
- ✅ O sistema usa **apenas PHP** no backend
- ✅ JavaScript roda diretamente no navegador
- ✅ Bootstrap e icons via CDN
- ✅ cURL já vem embutido no PHP

## 🔧 Configurações Adicionais

### config.php

```php
// Configurações da Open Router API
define('OPENROUTER_API_KEY', 'sk-or-v1-sua-chave-aqui');
define('OPENROUTER_API_URL', 'https://openrouter.ai/api/v1/chat/completions');
define('GROK_MODEL', 'x-ai/grok-4.1-fast:free');

// Configurações do Sistema
define('AGENTS_FOLDER', __DIR__ . '/agentes/');
define('APP_NAME', 'Agentes One-Shot');
define('APP_VERSION', '1.0');
define('MAX_AGENTS', 100);
```

## 📖 Como Usar

### 1. Como Administrador (Criar Agentes)

**Importante**: O sistema NÃO tem interface para criar agentes. Os agentes são arquivos PHP.

1. **Copie o template**:
   ```bash
   cp agent-template.php agentes/novo-agente.php
   ```

2. **Edite o arquivo** `agentes/novo-agente.php`:
   - Configure nome, descrição, ícone
   - Defina o prompt principal
   - Crie campos personalizados
   - Adicione categoria e outras informações

3. **O agente aparece automaticamente** na interface

📖 **Documentação completa**: Veja `COMO-CRIAR-AGENTES.md`

### 2. Como Usuário Final (Executar Agentes)

1. **Acesse o sistema**: `http://localhost:8000`
2. **Selecione um agente** da lista disponível
3. **Preencha o formulário** (lado esquerdo):
   - Campos obrigatórios marcados com *
   - Selects para opções predefinidas
   - Text areas para textos longos
4. **Clique em "Executar Agente"**
5. **Veja a resposta** no painel direito

### 3. Agentes Disponíveis

**📝 Gerador de Posts para Blog**
- Cria artigos SEO otimizados
- 7 campos personalizados
- Tempo estimado: 5-10 min

**📧 Gerador de E-mails Marketing**
- Cria e-mails persuasivos completos
- 7 campos personalizados
- Tempo estimado: 3-5 min

## 🛡️ Segurança

- ✅ **Chave API 100% Protegida**: A chave NUNCA sai do backend PHP
- ✅ **Sem Exposição Frontend**: JavaScript faz apenas chamadas AJAX
- ✅ **Validação Server-Side**: Todos os dados validados no backend
- ✅ **Sistema de Arquivos Isolado**: Cada agente em arquivo PHP separado
- ✅ **cURL Seguro**: Comunicação segura com Open Router API

## 📝 Estrutura de Agente

Cada agente é um arquivo PHP que retorna um array com a seguinte estrutura:

```php
return [
    'name' => 'Nome do Agente',
    'description' => 'Descrição do que faz',
    'icon' => 'bi-robot',
    'color' => '#667eea',
    'prompt' => 'Seu prompt com [campo] para substituição',
    'fields' => [
        [
            'label' => 'Nome do Campo',
            'placeholder' => 'Placeholder',
            'type' => 'text', // text, textarea, number, email, select
            'required' => true,
            'options' => ['Opção 1', 'Opção 2'] // para select
        ]
    ],
    'category' => 'geral',
    'difficulty' => 'iniciante',
    'estimated_time' => '5-10 min'
];
```

**📋 Tipos de Campos Suportados:**
- `text`: Campo de texto simples
- `textarea`: Área de texto grande
- `number`: Campo numérico
- `email`: Campo de e-mail
- `select`: Menu suspenso com opções predefinidas

**🎨 Personalização:**
- **Ícones**: Use [Bootstrap Icons](https://icons.getbootstrap.com/)
- **Cores**: Qualquer cor hexadecimal (#667eea, #FF6B6B, etc.)
- **Badges**: Categoria, dificuldade, tempo estimado

## 🔌 API Endpoints

O sistema usa os seguintes endpoints AJAX:

| Endpoint | Método | Descrição |
|----------|--------|-----------|
| `api.php?action=get_agents` | POST | Lista todos os agentes disponíveis |
| `api.php?action=get_agent` | POST | Obtém um agente específico |
| `api.php?action=execute_agent` | POST | Executa um agente com Open Router |
| `api.php?action=get_agent_stats` | POST | Obtém estatísticas dos agentes |
| `api.php?action=get_config` | POST | Obtém configurações do sistema |

**Nota**: Funções de criação/edição/remoção foram removidas. Agentes são gerenciados como arquivos PHP.

## 🐛 Troubleshooting

### 🔧 Problemas Comuns

**Erro: "Configure sua chave API Open Router"**
- **Solução**: Edite `config.php` e adicione sua chave real na linha 5

**Erro: "Nenhum agente disponível"**
- **Verifique**: Se existem arquivos `.php` na pasta `agentes/`
- **Verifique**: Se os arquivos têm sintaxe PHP válida
- **Comando**: `php -l agentes/seu-agente.php`

**Erro: "Uncaught SyntaxError" no JavaScript**
- **Solução**: Limpe cache do navegador (Ctrl+F5)

**Erro: "Erro na requisição cURL"**
- **Solução**: Verifique se o cURL está habilitado no PHP
- **Comando**: `php -m | grep curl`

**Erro: "Headers already sent"**
- **Verifique**: Se não há espaços ou linhas em branco antes do `<?php`

### 🐛 Debug Integrado

O sistema possui debug integrado:
1. **Abra o console** (F12 → Console)
2. **Recarregue a página** (F5)
3. **Veja os logs**:
   - 🔄 Carregando agentes...
   - 📊 Agents loaded: X
   - ✅ API Response

**Se os agentes não aparecem:**
- Console mostra erro específico
- Backend está funcionando (testado com curl)
- Problema provavelmente na comunicação frontend/backend

## 📝 Logs

O sistema registra erros no log de erros do PHP. Verifique:
```bash
tail -f /var/log/php_errors.log
```

## 🔄 Atualização

Para atualizar o sistema:

1. **Backup**: Use o recurso de exportar agentes
2. **Substitua os arquivos** (menos `config.php`)
3. **Mantenha a pasta `agentes/`**
4. **Importe seus agentes** se necessário

## 📄 Licença

Este projeto é open-source. Sinta-se livre para usar, modificar e distribuir.

## 🤝 Suporte

Para dúvidas ou problemas:
1. Verifique o troubleshooting acima
2. Consulte os logs de erro
3. Revise as configurações em `config.php`

## 🔄 Histórico de Versões

### v1.1 (Atual) - Sistema de Arquivos PHP
- ✅ Sistema de arquivos PHP para agentes
- ✅ Interface simplificada (foco em execução)
- ✅ Suporte completo a campos dinâmicos
- ✅ Badges e visual rico
- ✅ Debug integrado
- ✅ Segurança 100% aprimorada

### v1.0 - Versão Original
- Sistema de agentes com frontend de criação
- Armazenamento em JSON
- Interface completa de CRUD

---

**Versão**: 1.1
**Modelo IA**: Grok 4.1 Fast (x-ai/grok-4.1-fast:free)
**Framework**: Bootstrap 5 + PHP 7.4+
**Arquitetura**: Frontend PHP + Backend PHP + Arquivos de Configuração PHP