# Guia Técnico - Agentes One-Shot v1.1

## 🏗️ Arquitetura do Sistema

### Visão Geral
O Agentes One-Shot v1.1 é uma aplicação PHP moderna que combina segurança robusta com interface intuitiva para execução de agentes de IA via Open Router API.

### Stack Tecnológico
- **Backend**: PHP 7.4+ com arquitetura MVC simplificada
- **Frontend**: Bootstrap 5 + JavaScript vanilla
- **API**: Open Router com modelo Grok 4.1 Fast
- **Configuração**: Variáveis de ambiente (.env) com phpdotenv
- **Segurança**: CSP, Rate Limiting, Input Sanitização

## 📁 Estrutura de Arquivos Detalhada

```
agentes-one-shot/
├── 📄 index.php                 # Frontend principal (interface visual)
├── 📄 api.php                   # Backend REST API (AJAX endpoints)
├── 📄 agentes.php               # Lógica de gestão de agentes
├── 📄 config.php                # Configurações e carregamento .env
├── 📄 security.php              # Módulo de segurança completo
├── 📄 app.js                    # JavaScript frontend
├── 📄 .env.example              # Template de variáveis de ambiente
├── 📄 .env                      # Variáveis reais (não versionado)
├── 📄 .gitignore                # Arquivos ignorados pelo Git
├── 📄 .htaccess                 # Configurações Apache (CSP, headers)
├── 📄 agent-template.php        # Template para criar agentes
├── 📁 agentes/                  # Pasta de agentes PHP
│   ├── 📄 blog-post-generator.php
│   ├── 📄 email-marketing.php
│   └── 📄 [custom-agents].php
├── 📁 vendor/                   # Dependências Composer
├── 📄 composer.json             # Configuração Composer
├── 📄 composer.lock             # Lock de dependências
├── 📄 README.md                 # Documentação geral
├── 📄 SECURITY_REPORT.md        # Relatório de segurança
├── 📄 COMO-CRIAR-AGENTES.md      # Guia de criação de agentes
├── 📄 TECHNICAL_GUIDE.md         # Este documento
└── 📄 security_logs.json         # Logs de segurança (criado em runtime)
```

## ⚙️ Configurações e Variáveis de Ambiente

### Sistema .env Implementado
O sistema utiliza phpdotenv para gestão segura de variáveis:

```bash
# Open Router API Configuration
OPENROUTER_API_KEY=sk-or-v1-sua-chave-api-real
OPENROUTER_API_URL=https://openrouter.ai/api/v1/chat/completions
GROK_MODEL=x-ai/grok-4.1-fast:free

# System Configuration
APP_NAME=Agentes_One_Shot
APP_VERSION=1.1
AGENTS_FOLDER=__DIR__/agentes

# Security Settings
MAX_REQUEST_SIZE=1048576
MAX_PROMPT_LENGTH=10000
RATE_LIMIT_REQUESTS=60
RATE_LIMIT_WINDOW=60

# Agent Configuration
MAX_AGENTS=100
MAX_FILE_SIZE=1048576

# Environment
ENVIRONMENT=development
DEBUG=true
```

### Fluxo de Carregamento
1. **vendor/autoload.php** - Carrega Composer autoloader
2. **phpdotenv** - Processa arquivo .env com error handling
3. **config.php** - Define constantes com fallback seguro
4. **security.php** - Aplica validações e proteções

## 🔐 Sistema de Segurança

### Camadas de Proteção

#### 1. **Input Validation & Sanitization**
```php
// security.php
function sanitizeInput($input, $type = 'string') {
    if ($input === null) return null;
    $input = trim($input);
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function detectMaliciousContent($data) {
    $patterns = [
        '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
        '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
        '/javascript:/i',
        '/vbscript:/i',
        '/onload=/i',
        '/onerror=/i'
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $data)) {
            return true;
        }
    }
    return false;
}
```

#### 2. **Rate Limiting**
```php
function checkRateLimit($ip, $maxRequests = 60, $timeWindow = 60) {
    $rateFile = sys_get_temp_dir() . '/rate_limit_' . md5($ip);
    $currentTime = time();
    $requests = [];

    if (file_exists($rateFile)) {
        $requests = json_decode(file_get_contents($rateFile), true) ?: [];
        $requests = array_filter($requests, function($timestamp) use ($currentTime, $timeWindow) {
            return ($currentTime - $timestamp) < $timeWindow;
        });
    }

    $requests[] = $currentTime;

    if (count($requests) > $maxRequests) {
        return false;
    }

    file_put_contents($rateFile, json_encode($requests));
    return true;
}
```

#### 3. **Content Security Policy (CSP)**
Configurado diretamente no PHP para máxima compatibilidade:

```php
// config.php
header("Content-Security-Policy:
    default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data:;
    script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;
    style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;
    font-src 'self' https://cdn.jsdelivr.net data:;
    img-src 'self' data: https:;
    connect-src 'self' https://cdn.jsdelivr.net https://openrouter.ai;
    object-src 'none';
    base-uri 'self';");
```

#### 4. **Headers de Segurança**
```php
function setSecurityHeaders() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('ServerTokens: Prod');
}
```

## 🤖 Sistema de Agentes

### Estrutura de um Agente
Cada agente é um arquivo PHP que retorna um array estruturado:

```php
<?php
// agentes/meu-agente.php
return [
    'name' => 'Nome do Agente',
    'description' => 'Descrição detalhada do que faz',
    'icon' => 'bi-robot', // Bootstrap Icons
    'color' => '#667eea', // Cor hexadecimal
    'prompt' => 'Prompt principal com [placeholder] para substituição',
    'fields' => [
        [
            'label' => 'Nome do Campo',
            'placeholder' => 'Placeholder para usuário',
            'type' => 'text', // text, textarea, number, email, select
            'required' => true,
            'options' => ['Opção 1', 'Opção 2'] // apenas para select
        ]
    ],
    'category' => 'marketing', // Categoria para organização
    'tags' => ['tag1', 'tag2'], // Tags para busca
    'difficulty' => 'iniciante', // iniciante, intermediário, avançado
    'estimated_time' => '5-10 min',
    'version' => '1.0'
];
?>
```

### Tipos de Campos Suportados

| Tipo | Descrição | Exemplo |
|------|-----------|---------|
| `text` | Campo de texto simples | Input padrão |
| `textarea` | Área de texto grande | Descrições longas |
| `number` | Campo numérico | Quantidades, preços |
| `email` | Campo de e-mail | Validação automática |
| `select` | Menu suspenso | Opções predefinidas |

## 🔌 API Endpoints

### Arquitetura REST
Todos os endpoints usam método POST por segurança:

| Endpoint | Parâmetros | Retorno | Descrição |
|----------|------------|--------|-----------|
| `POST api.php` | `action=get_agents` | Array de agentes | Lista todos os agentes |
| `POST api.php` | `action=get_agent&id={id}` | Objeto agente | Obtém agente específico |
| `POST api.php` | `action=execute_agent&agent_id={id}&field_values={JSON}` | Resposta IA | Executa agente |
| `POST api.php` | `action=get_agent_stats` | Estatísticas | Dados dos agentes |
| `POST api.php` | `action=get_config` | Configurações | Info do sistema |

### Exemplo de Chamada AJAX
```javascript
async function executeAgent(agentId, fieldValues) {
    const response = await fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'execute_agent',
            agent_id: agentId,
            field_values: JSON.stringify(fieldValues)
        })
    });

    return await response.json();
}
```

## 🎨 Interface Frontend

### Componentes Principais

#### 1. **Agent Cards**
```html
<div class="col-md-6 col-lg-4 mb-4">
    <div class="card agent-card h-100" data-agent-id="blog-post-generator">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="agent-icon me-3" style="background-color: #FF6B6B;">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <h5 class="card-title mb-1">Gerador de Posts para Blog</h5>
                    <div class="badges-container">
                        <span class="badge bg-primary">conteúdo</span>
                        <span class="badge bg-success">iniciante</span>
                        <span class="badge bg-info">5-10 min</span>
                    </div>
                </div>
            </div>
            <p class="card-text">Cria artigos completos para blogs com estrutura SEO otimizada</p>
        </div>
        <div class="card-footer bg-transparent">
            <button class="btn btn-primary w-100">Usar Agente</button>
        </div>
    </div>
</div>
```

#### 2. **Formulário Dinâmico**
```javascript
function createFieldHtml(field) {
    switch(field.type) {
        case 'select':
            return `
                <select class="form-select" id="${field.label}" placeholder="${field.placeholder}" ${field.required ? 'required' : ''}>
                    <option value="">Selecione...</option>
                    ${field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                </select>`;
        case 'textarea':
            return `<textarea class="form-control" id="${field.label}" rows="${field.rows || 3}" placeholder="${field.placeholder}" ${field.required ? 'required' : ''}></textarea>`;
        default:
            return `<input type="${field.type}" class="form-control" id="${field.label}" placeholder="${field.placeholder}" ${field.required ? 'required' : ''}>`;
    }
}
```

## 🚀 Deploy e Produção

### Configurações de Produção

#### 1. **Environment Variables**
```bash
# Valores recomendados para produção
ENVIRONMENT=production
DEBUG=false
RATE_LIMIT_REQUESTS=30
RATE_LIMIT_WINDOW=60
```

#### 2. **CSP Restritivo**
```php
// Política CSP para produção (mais restritiva)
header("Content-Security-Policy:
    default-src 'self';
    script-src 'self' https://cdn.jsdelivr.net;
    style-src 'self' https://cdn.jsdelivr.net;
    font-src 'self' https://cdn.jsdelivr.net;
    img-src 'self' data:;
    connect-src 'self' https://openrouter.ai;
    object-src 'none';
    base-uri 'self';");
```

#### 3. **Performance**
- Cache de agentes implementado
- Rate limiting otimizado
- Headers de cache configurados
- Compressão de saída ativa

### Configuração Apache/Nginx

#### Apache (.htaccess já incluído)
```apache
<IfModule mod_php.c>
    php_flag display_errors Off
    php_flag log_errors On
    php_value memory_limit 256M
    php_value max_execution_time 30
</IfModule>

<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain text/html text/xml text/css application/javascript application/json
</IfModule>
```

#### Nginx
```nginx
location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;

    # Security headers
    add_header X-Frame-Options DENY;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";
}
```

## 🐛 Troubleshooting

### Issues Comuns

#### 1. **Erro: "Configure sua chave API Open Router"**
**Solução**: Verificar arquivo `.env`
```bash
# Testar carregamento
php -r "require_once 'vendor/autoload.php'; \$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); \$dotenv->load(); echo 'API: ' . \$_ENV['OPENROUTER_API_KEY'];"
```

#### 2. **Erros de CSP no Console**
**Verificar**: Headers CSP no browser
```javascript
// No console do navegador
console.log(document.querySelector('meta[http-equiv="Content-Security-Policy"]'));
```

#### 3. **Rate Limiting Agressivo**
**Ajustar**: `config.php`
```php
define('RATE_LIMIT_REQUESTS', 120); // Aumentar limites
define('RATE_LIMIT_WINDOW', 60);
```

#### 4. **Agentes Não Aparecem**
**Verificar**:
1. Sintaxe PHP dos arquivos: `php -l agentes/meu-agente.php`
2. Permissões da pasta: `chmod 755 agentes/`
3. Logs de erros PHP

### Debug Mode

Ativar debug detalhado:
```php
// No .env
DEBUG=true

// Ver logs
tail -f /var/log/php_errors.log
tail -f security_logs.json
```

## 📊 Monitoramento e Logs

### Security Logs
```json
{
  "timestamp": "2024-11-25 14:30:15",
  "ip": "192.168.1.100",
  "user_agent": "Mozilla/5.0...",
  "event": "AGENT_EXECUTED",
  "details": {
    "agent_id": "blog-post-generator",
    "field_count": 5
  }
}
```

### Métricas Disponíveis
- Taxa de sucesso de execuções
    - Agents mais usados
    - IPs com mais requisições
    - Tempos de resposta

## 🔄 Versionamento eAtualizações

### Changelog v1.1
- ✅ Implementado sistema .env com phpdotenv
- ✅ CSP otimizado para Bootstrap CDN
- ✅ Validação robusta de chaves API
- ✅ Sistema de rate limiting melhorado
- ✅ Interface responsiva aprimorada

### Roadmap Futuro
- [ ] Sistema de autenticação de usuários
- [ ] Dashboard administrativo
- [ ] Exportação/importação de agentes
- [ ] Integração com múltiplos modelos de IA
- [ ] Sistema de templates avançado

---

**Última Atualização**: 25/11/2024
**Versão**: v1.1
**Status**: ✅ Produção Ready