# ⚠️ CONFIGURAÇÃO DE API KEY - INSTRUÇÕES SEGUROS

## 🔐 NUNCA exponha sua API Key no código

**NÃO FAÇA ISSO:**
```php
// ❌ ERRADO - Security breach!
define('GROQ_API_KEY', 'sua_chave_real_aqui');
```

## ✅ Formas corretas de configurar

### 1. Ambiente Local (desenvolvimento)
Edite o arquivo `.env`:
```bash
# Descomente e configure suas variáveis
GROQ_API_KEY=sua_chave_real_aqui
GROQ_API_URL=https://api.groq.com/openai/v1/chat/completions
GROQ_MODEL=llama-3.1-8b-instant
```

### 2. Railway.app (produção)
No painel do Railway.app:
1. Vá para Settings → Variables
2. Adicione TODAS as variáveis:
   - `GROQ_API_KEY` = `sua_chave_real_aqui`
   - `GROQ_API_URL` = `https://api.groq.com/openai/v1/chat/completions`
   - `GROQ_MODEL` = `llama-3.1-8b-instant`
3. **NÃO** coloque as chaves no arquivo .env no repositório

### 3. Outros serviços
Configure como variável de ambiente no servidor:
```bash
export GROQ_API_KEY="sua_chave_real_aqui"
```

## 🛡️ Medidas de segurança implementadas

1. **Sem placeholders no código** - O sistema falha se não encontrar API key
2. **Validação obrigatória** - Impede execução sem API key configurada
3. **Leitura segura** - Apenas lê de variáveis de ambiente/.env
4. **Erro descritivo** - Mensagem clara se API key estiver faltando

## 🚀 Como conseguir sua API Key Groq

1. Acesse: https://console.groq.com/
2. Faça login/cadastro
3. Vá para: API Keys
4. Crie uma nova key
5. Copie a chave (começa com `gsk_`)

## 📋 Teste após configurar

Execute para validar:
```bash
curl http://localhost:8000/test-api-key.php
```

Resposta esperada:
```
✅ API Key válida e funcionando!
   Modelos disponíveis: 20
```

---

**Lembre-se:** API Keys são como senhas! Nunca as coloque no código ou commits públicos.