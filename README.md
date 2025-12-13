# Agentes One-Shot v2.2

Sistema avançado de gestão de agentes de IA com integração segura com **Groq API** e arquitetura completamente refatorada para produção.

## 🆕 Versão 2.2 - Labels Otimizados e Experiência Aprimorada

- ✅ **Labels Profissionais**: Labels com formatação correta (primeira letra maiúscula, acentuação)
- ✅ **Separação Label/Name**: Sistema separa visualização (label) de IDs técnicos (name)
- ✅ **Formulários Otimizados**: Melhor experiência do usuário com rótulos claros e profissionais
- ✅ **Documentação Atualizada**: Guia completo para criação de agentes com boas práticas
- ✅ **Compatibilidade Mantida**: Sistema funciona com agentes antigos e novos
- ✅ **Correção de Bugs**: Resolvidos problemas de validação de campos com espaços

## 📋 Versão 2.1 - Correções e Melhorias

- ✅ **Correção de Espaços em Respostas**: Resolvido problema de espaços visuais no início das respostas dos agentes
- ✅ **Otimização de Template Strings**: Corrigidas quebras de linha em JavaScript que geravam espaços desnecessários
- ✅ **Ajustes de Layout**: Melhorias no CSS para renderização sem espaços extras
- ✅ **Novo Agente**: "Ideias de Títulos de Post" para criação de títulos criativos
- ✅ **Melhorias de Performance**: Otimizações no sistema de processamento de respostas

- ✅ **Migração para Groq API**: Mais rápido e estável que Open Router
- ✅ **Sistema de Fallback de Modelos**: Troca automática entre modelos disponíveis
- ✅ **Dashboard de Modelos**: Monitoramento em tempo real da disponibilidade
- ✅ **Configurações Otimizadas**: Variáveis separadas (sensíveis vs configuração)
- ✅ **Deploy Produção Ready**: Funciona perfeitamente em Railway.app
- ✅ **Arquitetura Refatorada**: Sistema completo com maior robustez e performance
- ✅ **Framework de Agentes PHP**: Agentes como arquivos PHP totalmente configuráveis
- ✅ **Sistema de Segurança Reforçado**: Múltiplas camadas de proteção com CSP e rate limiting
- ✅ **Interface Responsiva Moderna**: Design aprimorado com Bootstrap 5
- ✅ **Sistema de Badges Avançado**: Exibe categoria, dificuldade e tempo estimado
- ✅ **URLs Exclusivas Amigáveis**: Acesso direto via URLs limpas (/contador-piada, /blog-post-generator)
- ✅ **Debug e Monitoramento**: Logs detalhados e sistema de segurança completo

## 🚀 Características

- ✅ **Agentes Pré-Configurados**: Sistema focado em uso de agentes, não criação
- ✅ **Interface Responsiva Moderna**: Design baseado em Bootstrap 5 com layout de 2 colunas
- ✅ **Execução 100% Segura**: Backend PHP protege sua chave API da Groq
- ✅ **Framework de Agentes PHP**: Cada agente como arquivo PHP independente e totalmente configurável
- ✅ **Gestão de Variáveis de Ambiente**: Configuração segura com separação clara
- ✅ **Campos Dinâmicos Completos**: Suporte a text, textarea, number, email e select
- ✅ **Visual Rico e Profissional**: Cards com badges, cores personalizadas e ícones
- ✅ **Groq API Integration**: Modelos rápidos como Llama 3.1, Llama 3.3, Llama 4
- ✅ **Sistema de Fallback Automático**: Troca automática de modelos se um falhar
- ✅ **Dashboard de Status**: Monitoramento em tempo real dos modelos
- ✅ **Content Security Policy**: Política otimizada para desenvolvimento e produção
- ✅ **Rate Limiting**: Proteção contra abuso e sobrecarga
- ✅ **Sistema de Logs**: Registro completo de eventos de segurança e auditoria
- ✅ **API REST**: Endpoints bem definidos com validação robusta

## 📋 Estrutura do Sistema v2.0

```
agentes-one-shot/
├── 📄 index.php                    # Frontend principal (interface responsiva)
├── 📄 api.php                      # Backend REST API (AJAX endpoints)
├── 📄 agentes.php                  # Framework de gestão de agentes
├── 📄 config.php                   # Configurações com variáveis definidas no código
├── 📄 models.php                   # Sistema de gestão e fallback de modelos
├── 📄 model-status.php             # Dashboard de monitoramento de modelos
├── 📄 model-status-check-availability.php # API endpoint para testar modelos
├── 📄 security.php                 # Módulo de segurança reforçado
├── 📄 app.js                       # JavaScript frontend otimizado
├── 📄 .env                         # Apenas variáveis sensíveis (API keys)
├── 📄 .env.example                 # Template de variáveis de ambiente
├── 📄 .gitignore                   # Arquivos ignorados pelo Git
├── 📄 .htaccess                    # Configurações Apache (CSP, headers, URLs)
├── 📄 router.php                    # Router PHP para desenvolvimento local
├── 📄 agent-template.php           # Template para criar novos agentes
├── 📁 agentes/                     # Pasta com arquivos dos agentes
│   ├── 📄 blog-post-generator.php  # Agente: Posts para Blog
│   ├── 📄 email-marketing.php      # Agente: E-mails Marketing
│   ├── 📄 contador-piada.php       # Agente: Contador de Piadas
│   ├── 📄 ideias-titulos-post.php  # Agente: Ideias de Títulos de Post
│   └── 📄 [seus-agentes].php       # Seus agentes personalizados
├── 📁 agents/                      # Documentação para desenvolvedores
│   ├── 📄 ROADMAP.md               # Roadmap de desenvolvimento v2.1+
│   ├── 📄 COMO-CRIAR-AGENTES.md    # Guia para criar agentes
│   ├── 📄 TECHNICAL_GUIDE.md       # Especificações técnicas
│   ├── 📄 SECURITY_REPORT.md       # Medidas de segurança
│   └── 📄 CORES_INSTRUCOES.md      # Configurações de ambiente
├── 📁 vendor/                      # Dependências Composer (phpdotenv)
├── 📄 composer.json                # Configuração Composer
├── 📄 composer.lock                # Lock de dependências
├── 📄 CLAUDE.md                    # Instruções para Claude Code
├── 📄 SECURITY_REPORT.md           # Relatório detalhado de segurança
├── 📄 TECHNICAL_GUIDE.md           # Guia técnico completo
├── 📄 security_logs.json           # Logs de segurança (criado em runtime)
└── 📄 README.md                    # Este documento
```

## ⚙️ Instalação

### 1. Requisitos

- PHP 7.4+ com cURL habilitado
- Servidor web (Apache, Nginx, etc.)
- Chave API da Groq

### 2. Configuração

1. **Configure suas variáveis de ambiente**:
   ```bash
   cp .env.example .env
   # Edite o arquivo .env com sua chave API Groq
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

## 🌐 URLs Exclusivas

O sistema v2.0+ possui URLs amigáveis para acesso direto aos agentes:

### URLs Disponíveis:
- **Lista de Agentes**: `http://seuservidor.com/agentes-one-shot/`
- **Contador de Piadas**: `http://seuservidor.com/agentes-one-shot/contador-piada`
- **Gerador de Blog**: `http://seuservidor.com/agentes-one-shot/blog-post-generator`
- **E-mails Marketing**: `http://seuservidor.com/agentes-one-shot/email-marketing`
- **Ideias de Títulos**: `http://seuservidor.com/agentes-one-shot/ideias-titulos-post`

### Como Funciona:
- **Produção (.htaccess)**: Servidores Apache usam rewrite rules automaticamente
- **Desenvolvimento (router.php)**: Servidor PHP embutido usa `php -S localhost:8000 router.php`

### Criar Novas URLs:
Adicione `'url' => 'sua-url-aqui'` no array de configuração do agente PHP.

## 🔐 Variáveis de Ambiente

**📄 Arquivo .env** (apenas variáveis sensíveis):
```bash
# Groq API Configuration
# Apenas variáveis sensíveis ficam no ENV
GROQ_API_KEY=incluir-chave

# Demais configurações estão em config.php:
# - APP_NAME = 'Agentes One Shot'
# - APP_VERSION = '2.0'
# - GROQ_API_URL = 'https://api.groq.com/openai/v1/chat/completions'
# - GROQ_MODEL = 'llama-3.1-8b-instant'

# Security Settings
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

**📄 config.php** (variáveis não-sensíveis):
```php
// Variáveis de configuração (não-sensíveis) - definidas diretamente no código
define('APP_NAME', 'Agentes One Shot');
define('APP_VERSION', '2.0');
define('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions');
define('GROQ_MODEL', 'llama-3.1-8b-instant');

// Apenas API keys e dados sensíveis ficam no ENV
define('GROQ_API_KEY', loadEnvVar('GROQ_API_KEY'));
```

## 🚀 Como Rodar Localmente

### Opção 1: Servidor Embutido PHP (Recomendado)

```bash
cd agentes-one-shot
php -S localhost:8000
```

Depois acesse no navegador: **http://localhost:8000**

### Opção 2: XAMPP/WAMP/MAMP

Se você já tem XAMPP/WAMP instalado:
1. Copie a pasta `agentes-one-shot` para a pasta `htdocs/` (ou `www/`)
2. Acesse: `http://localhost/agentes-one-shot/`

## 🤖 Modelos Disponíveis

O sistema suporta os seguintes modelos **Groq.com** com fallback automático:

### Modelos Primários (Mais rápidos)
- **Llama 3.1 8B Instant** (~500 tokens/s)
- **Llama 3.3 70B Versatile** (~250 tokens/s)

### Modelos Premium (Alta performance)
- **Llama 4 Maverick 17B** (Última geração)
- **Llama 4 Scout 17B** (Alternativa premium)

### Modelos Secundários
- **Kimi K2 Instruct** (Moonshot AI)
- **Whisper Large v3 Turbo** (Transcrição de áudio)

### Dashboard de Modelos
Acesse `/model-status.php` para:
- Monitorar disponibilidade em tempo real
- Testar individualmente cada modelo
- Ver estatísticas de performance
- Identificar modelos com problemas

## 🔄 Sistema de Fallback

O sistema possui fallback automático integrado:

1. **Modelo Principal**: Usa o modelo configurado (`GROQ_MODEL`)
2. **Fallback Automático**: Se falhar, testa modelos disponíveis em ordem de prioridade
3. **Múltiplas Tentativas**: Até 3 tentativas com modelos diferentes
4. **Logs Detalhados**: Registra todas as tentativas e motivos de falha

## 🚀 Deploy em Produção (Railway.app)

### 1. Preparar Repositório

```bash
git add .
git commit -m "Deploy: Production-ready version with Groq API"
git push origin main
```

### 2. Configurar Railway.app

1. **Criar Novo Projeto** no Railway.app
2. **Conectar Repositório** GitHub
3. **Configurar Variáveis de Ambiente**:
   - `GROQ_API_KEY`: Sua chave API da Groq
   - `PHP_VERSION`: `8.1` (ou superior)
   - `WEB_ROOT`: `/`

### 3. Configurações de Build

Railway.app detecta automaticamente o projeto PHP. Não precisa de build steps.

### 4. Variáveis de Ambiente

Apenas configure:
- `GROQ_API_KEY` (obrigatório)
- `NODE_ENV` = `production`

Demais configurações já estão no código (`config.php`).

### 5. Deploy Automático

O Railway.app fará deploy automático. Após conclusão:
- Acesse: `https://seu-projeto.up.railway.app/`
- Teste o funcionamento
- Monitore os logs se houver problemas

## 🔧 Modelos Disponíveis

O sistema usa modelos da **Groq API**:

### Modelos Verificados e Disponíveis:
1. **llama-3.1-8b-instant** (Rápido, gratuito)
2. **llama-3.3-70b-versatile** (Potente, equilibrado)
3. **meta-llama/llama-4-maverick-17b-128e-instruct** (Premium)
4. **meta-llama/llama-4-scout-17b-16e-instruct** (Premium)
5. **moonshotai/kimi-k2-instruct** (Alternativo)
6. **whisper-large-v3-turbo** (Transcrição)

### Dashboard de Modelos
Acesse `/model-status.php` para:
- Ver disponibilidade em tempo real
- Testar cada modelo individualmente
- Verificar latência e performance
- Identificar problemas com modelos específicos

## 🔒 Segurança

### 🛡️ Camadas de Proteção

1. **API Key Protection**: Chaves nunca expostas ao frontend
2. **Environment Variables**: Separação clara de dados sensíveis
3. **Input Sanitization**: Validação e limpeza de todos os inputs
4. **Rate Limiting**: 60 requisições/minuto por IP
5. **CSP Headers**: Content Security Policy otimizada
6. **Suspicious Pattern Detection**: Bloqueio de ataques comuns
7. **Security Logging**: Registro completo de eventos
8. **Fallback System**: Continuidade mesmo com falhas de API

### 📋 Arquivo .gitignore

```bash
# Ambiente
.env
.env.local
.env.*.local

# Logs e Runtime
security_logs.json
cache/
logs/

# Temporários
*.tmp
*.temp
.DS_Store
Thumbs.db

# IDE
.vscode/
.idea/
*.swp
*.swo
```

## ⚡ Verificação de Requisitos

Antes de rodar, verifique se seu ambiente está configurado:

```bash
# Verificar versão PHP (precisa ser 7.4+)
php --version

# Verificar se cURL está habilitado
php -m | grep curl

# Verificar se Composer está instalado
composer --version
```

**Importante**: Não precisa de npm/node.js porque:
- ✅ O sistema usa **apenas PHP** no backend
- ✅ JavaScript roda diretamente no navegador
- ✅ Bootstrap e icons via CDN
- ✅ cURL já vem embutido no PHP

## 📊 Agentes Disponíveis

### Agentes Pré-Configurados

1. **📝 Blog Post Generator**
   - Gera posts otimizados para SEO
   - Campos: Título, Palavra-chave, Tom, Comprimento
   - Categoria: Content Creation

2. **📧 Email Marketing Generator**
   - Cria e-mails de marketing persuasivos
   - Campos: Produto, Público, Objetivo, Tom
   - Categoria: Marketing

3. **😄 Contador de Piadas**
   - Gera piadas personalizadas
   - Campos: Tema, Estilo, Nível de humor
   - Categoria: Entretenimento

4. **📝 Ideias de Títulos de Post**
   - Gera títulos criativos e chamativos para posts
   - Campos: Assunto (obrigatório), Público-alvo (opcional)
   - Categoria: Marketing
   - Funcionalidades: 10 títulos com explicações e dicas bônus

### Criar Novos Agentes

Para criar um novo agente:

1. Copie `agent-template.php`
2. Renomeie para `seu-agente.php`
3. Configure os metadados e campos
4. Siga o guia em `agents/COMO-CRIAR-AGENTES.md`

## 🔧 Configurações Avançadas

### config.php - Variáveis de Configuração

O sistema separa claramente:
- **Variáveis sensíveis** → `.env` (API keys)
- **Configurações** → `config.php` (constantes)

### models.php - Sistema de Modelos

Gerencia:
- Lista de prioridade de modelos
- Sistema de fallback automático
- Teste de disponibilidade
- Cache de modelos funcionais

### security.php - Módulo de Segurança

Implementa:
- Rate limiting por IP
- Detecção de padrões suspeitos
- CSP headers
- Validação de inputs
- Logging de eventos

## 🐛 Troubleshooting

### Problemas Comuns

**1. Erro "Variáveis obrigatórias não configuradas"**
- Verifique se `GROQ_API_KEY` está configurada no ENV
- Em produção, configure as variáveis no painel do Railway.app

**2. Modelo não funciona**
- Acesse `/model-status.php` para verificar disponibilidade
- O sistema fará fallback automático para outros modelos

**3. Rate Limiting**
- Limite: 60 requisições/minuto por IP
- Aguarde 1 minuto para resetar

**4. Erro de Permissão**
- Garanta permissão 755 na pasta `/agentes`
- Verifique se `security_logs.json` pode ser criado

### Debug Mode

Para debug, adicione temporariamente:
```php
error_log("DEBUG: " . print_r($variavel, true));
```

## 📈 Monitoramento e Logs

### Security Logs
Arquivo: `security_logs.json`
```json
{
  "timestamp": "2024-01-01 12:00:00",
  "event": "API_CALL_SUCCESS",
  "ip": "192.168.1.1",
  "details": {...}
}
```

### Model Status Dashboard
URL: `/model-status.php`
- Status em tempo real
- Testes individuais
- Estatísticas de performance
- Logs de tentativas

## 🔄 Atualizações e Manutenção

### Adicionar Novos Modelos

1. Verifique disponibilidade na API Groq
2. Adicione em `models.php` na função `getModelPriorityList()`
3. Teste no dashboard `/model-status.php`
4. Faça commit das mudanças

### Atualizar Versão

1. Altere `APP_VERSION` em `config.php`
2. Atualize `README.md` com mudanças
3. Faça commit com tag de versão

## 📄 Licença

Este projeto é open-source. Sinta-se à vontade para:
- ✅ Usar em projetos comerciais
- ✅ Modificar e distribuir
- ✅ Contribuir com melhorias

## 🤝 Contribuição

1. Fork o projeto
2. Crie branch para sua feature
3. Faça commit das mudanças
4. Abra Pull Request

## 📞 Suporte

- 📧 Email: [seu-email@dominio.com]
- 🐛 Issues: [GitHub Issues](https://github.com/seu-usuario/agentes-one-shot/issues)
- 📖 Documentação: Ver arquivos na pasta `/agents/`
- 🛣️ **Roadmap**: `agents/ROADMAP.md` - Veja as próximas funcionalidades planejadas

---

**Versão**: 2.1 | **Status**: Production-Ready | **API**: Groq | **Última Atualização**: 2024