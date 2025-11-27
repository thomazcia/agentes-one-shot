# Agentes One-Shot v2.0

Sistema avançado de gestão de agentes de IA com integração segura com Open Router API usando modelo Grok 4.1 Fast e arquitetura completamente refatorada.

## 🆕 Versão 2.0 - Grandes Novidades

- ✅ **Arquitetura Refatorada**: Sistema completo com maior robustez e performance
- ✅ **Framework de Agentes PHP**: Agentes como arquivos PHP totalmente configuráveis
- ✅ **Sistema de Segurança Reforçado**: Múltiplas camadas de proteção com CSP e rate limiting
- ✅ **Gestão de Variáveis de Ambiente (.env)**: Configuração segura com phpdotenv
- ✅ **Interface Responsiva Moderna**: Design aprimorado com Bootstrap 5
- ✅ **Sistema de Badges Avançado**: Exibe categoria, dificuldade e tempo estimado
- ✅ **Debug e Monitoramento**: Logs detalhados e sistema de segurança completo
- ✅ **Content Security Policy**: CSP otimizado para desenvolvimento e produção
- ✅ **API REST Estruturada**: Endpoints bem definidos com validação robusta

## 🚀 Características

- ✅ **Agentes Pré-Configurados**: Sistema focado em uso de agentes, não criação
- ✅ **Interface Responsiva Moderna**: Design baseado em Bootstrap 5 com layout de 2 colunas
- ✅ **Execução 100% Segura**: Backend PHP protege sua chave API da Open Router
- ✅ **Framework de Agentes PHP**: Cada agente como arquivo PHP independente e totalmente configurável
- ✅ **Gestão .env Avançada**: Variáveis de ambiente com phpdotenv para máxima segurança
- ✅ **Campos Dinâmicos Completos**: Suporte a text, textarea, number, email e select
- ✅ **Visual Rico e Profissional**: Cards com badges, cores personalizadas e ícones
- ✅ **Grok 4.1 Fast**: Modelo rápido e eficiente da Open Router
- ✅ **Content Security Policy**: Política otimizada para desenvolvimento e produção
- ✅ **Debug e Monitoramento**: Logs detalhados e sistema de segurança completo
- ✅ **Rate Limiting**: Proteção contra abuso e sobrecarga
- ✅ **Sistema de Logs**: Registro completo de eventos de segurança e auditoria
- ✅ **API REST**: Endpoints bem definidos com validação robusta

## 📋 Estrutura do Sistema v2.0

```
agentes-one-shot/
├── 📄 index.php                 # Frontend principal (interface responsiva)
├── 📄 api.php                   # Backend REST API (AJAX endpoints)
├── 📄 agentes.php               # Framework de gestão de agentes
├── 📄 config.php                # Configurações avançadas com .env
├── 📄 security.php              # Módulo de segurança reforçado
├── 📄 app.js                    # JavaScript frontend otimizado
├── 📄 .env.example              # Template de variáveis de ambiente
├── 📄 .env                      # Variáveis reais (chave API, etc.)
├── 📄 .gitignore                # Arquivos ignorados pelo Git
├── 📄 .htaccess                 # Configurações Apache (CSP, headers)
├── 📄 agent-template.php        # Template para criar novos agentes
├── 📁 agentes/                  # Pasta com arquivos dos agentes
│   ├── 📄 blog-post-generator.php     # Agente: Posts para Blog
│   ├── 📄 email-marketing.php         # Agente: E-mails Marketing
│   ├── 📄 contador-piada.php          # Agente: Contador de Piadas
│   ├── 📄 [seus-agentes].php           # Seus agentes personalizados
│   └── ...
├── 📁 vendor/                   # Dependências Composer (phpdotenv)
├── 📄 composer.json             # Configuração Composer
├── 📄 composer.lock             # Lock de dependências
├── 📄 COMO-CRIAR-AGENTES.md      # Documentação para criar agentes
├── 📄 SECURITY_REPORT.md         # Relatório detalhado de segurança
├── 📄 TECHNICAL_GUIDE.md         # Guia técnico completo
├── 📄 CLAUDE.md                  # Instrunções para Claude Code
├── 📄 security_logs.json         # Logs de segurança (criado em runtime)
└── 📄 README.md                  # Este documento
```

## ⚙️ Instalação

### 1. Requisitos

- PHP 7.4+ com cURL habilitado
- Servidor web (Apache, Nginx, etc.)
- Chave API da Open Router

### 2. Configuração

1. **Configure suas variáveis de ambiente**:
   ```bash
   cp .env.example .env
   # Edite o arquivo .env com sua chave API Open Router e outras configurações
   ```

2. **Instale as dependências**:
   ```bash
   composer install
   ```

3. **Permissões da pasta**:
   Garanta que a pasta `/agentes` tenha permissão de escrita:
   ```bash
   chmod 755 agentes/
   ```

4. **Acesse o sistema**:
   Abra no navegador: `http://seuservidor.com/agentes-one-shot/`

**📁 Estrutura do Ambiente (.env) - v2.0**:
```bash
# Copie de .env.example e edite com seus valores
OPENROUTER_API_KEY=sk-or-v1-sua-chave-api-real-aqui
OPENROUTER_API_URL=https://openrouter.ai/api/v1/chat/completions
GROK_MODEL=x-ai/grok-4.1-fast:free

# Configurações do Sistema
APP_NAME=Agentes_One_Shot
APP_VERSION=2.0
AGENTS_FOLDER=__DIR__/agentes

# Configurações de Segurança
MAX_REQUEST_SIZE=1048576
MAX_PROMPT_LENGTH=10000
RATE_LIMIT_REQUESTS=60
RATE_LIMIT_WINDOW=60

# Agent Configuration
MAX_AGENTS=100
MAX_FILE_SIZE=1048576

# Environment
ENVIRONMENT=production
DEBUG=false
```

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

### config.php e Variáveis de Ambiente

O sistema agora usa variáveis de ambiente via arquivo `.env` para maior segurança:

**📄 Arquivo .env**:
```bash
# Configurações da Open Router API
OPENROUTER_API_KEY=sk-or-v1-sua-chave-api-real-aqui
OPENROUTER_API_URL=https://openrouter.ai/api/v1/chat/completions
GROK_MODEL=x-ai/grok-4.1-fast:free

# Configurações do Sistema
APP_NAME=Agentes One-Shot
APP_VERSION=1.1
AGENTS_FOLDER=__DIR__/agentes

# Configurações de Segurança
MAX_REQUEST_SIZE=1048576
MAX_PROMPT_LENGTH=10000
RATE_LIMIT_REQUESTS=60
RATE_LIMIT_WINDOW=60

# Ambiente
ENVIRONMENT=development
DEBUG=true
```

**🔒 Segurança**:
- ✅ `.env` está no `.gitignore` para não enviar chaves para o repositório
- ✅ `config.php` tem fallback values para funcionar sem `.env`
- ✅ Chaves API nunca ficam expostas no frontend

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

### 3. Agentes Disponíveis v2.0

**📝 Gerador de Posts para Blog**
- Cria artigos SEO otimizados
- 7 campos personalizados
- Tempo estimado: 5-10 min
- Categoria: Conteúdo

**📧 Gerador de E-mails Marketing**
- Cria e-mails persuasivos completos
- 7 campos personalizados
- Tempo estimado: 3-5 min
- Categoria: Marketing

**😄 Contador de Piadas**
- Cria piadas personalizadas sobre qualquer assunto
- 1 campo personalizado (assunto)
- Tempo estimado: 1-2 min
- Categoria: Entretenimento

## 🛡️ Segurança v2.0

- ✅ **Chave API 100% Protegida**: A chave NUNCA sai do backend PHP
- ✅ **Múltiplas Camadas de Segurança**: Input sanitization, CSP, rate limiting
- ✅ **Sem Exposição Frontend**: JavaScript faz apenas chamadas AJAX seguras
- ✅ **Validação Server-Side Robusta**: Todos os dados validados no backend
- ✅ **Sistema de Arquivos Isolado**: Cada agente em arquivo PHP separado
- ✅ **cURL Seguro**: Comunicação segura com Open Router API
- ✅ **Content Security Policy**: Proteção contra XSS e injeção de conteúdo
- ✅ **Rate Limiting**: Proteção contra ataques de força bruta e sobrecarga
- ✅ **Sistema de Logs**: Registro completo de eventos de segurança
- ✅ **Headers de Segurança**: X-Frame-Options, X-Content-Type-Options, etc.
- ✅ **Detecção de Padrões Suspeitos**: Bloqueio automático de requisições maliciosas
- ✅ **Validação de Estrutura**: Verificação completa dos arquivos de agentes

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
- **Solução**: Crie o arquivo `.env` e configure sua chave API:
  ```bash
  cp .env.example .env
  # Edite .env com OPENROUTER_API_KEY=sk-or-v1-sua-chave-real
  composer install
  ```

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

## 📄 Licença

Este projeto é open-source. Sinta-se livre para usar, modificar e distribuir.

## 🤝 Suporte

Para dúvidas ou problemas:
1. Verifique o troubleshooting acima
2. Consulte os logs de erro
3. Revise as configurações em `config.php`

## 🔄 Histórico de Versões

### v2.0 (Atual) - Sistema Refatorado
- ✅ Arquitetura completamente refatorada com maior robustez
- ✅ Framework de segurança reforçado com múltiplas camadas
- ✅ Sistema avançado de variáveis de ambiente (.env)
- ✅ Content Security Policy (CSP) otimizado
- ✅ Rate limiting avançado e sistema de logs completo
- ✅ Interface responsiva moderna com Bootstrap 5
- ✅ API REST bem estruturada com validação robusta
- ✅ Novo agente: Contador de Piadas
- ✅ Sistema de detecção de padrões suspeitos
- ✅ Melhorias na documentação técnica

### v1.1 - Sistema de Arquivos PHP
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

**Versão**: 2.0
**Modelo IA**: Grok 4.1 Fast (x-ai/grok-4.1-fast:free)
**Framework**: Bootstrap 5 + PHP 7.4+
**Arquitetura**: Frontend PHP + Backend PHP + Arquivos de Configuração PHP + Sistema de Segurança Reforçado
**Documentação**: Guia técnico completo, relatório de segurança, guia de criação de agentes  