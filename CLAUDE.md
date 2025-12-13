# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Agentes One-Shot v2.2** - Complete dashboard/panel for one-shot agents using Groq API with advanced security features and modern architecture.

## 🧪 Testes Automatizados

### **test_system.php**
Localização: `/agents/test_system.php`

**Propósito:** Script de testes unitários automatizados para validar o funcionamento completo do sistema antes de deploy ou mudanças significativas.

**Como usar:**
```bash
php agents/test_system.php
```

**Categorias de Testes:**
- ✅ **Configuração** - Constants, variáveis, estrutura de arquivos
- ✅ **Agentes** - Carregamento, estrutura, validação de dados
- ✅ **Modelos** - Sistema de fallback, prioridade, disponibilidade
- ✅ **Segurança** - Sanitização, validação, detecção de malware
- ✅ **Performance** - Tempo de carregamento, otimizações
- ✅ **Compatibilidade** - Versão PHP, extensões necessárias

**Quando executar:**
- Antes de fazer deploy
- Após mudanças significativas
- Para validar novas funcionalidades
- Diagnóstico de problemas

## Current Architecture

- **Backend**: PHP 7.4+ with security framework
- **Frontend**: Bootstrap 5 + vanilla JavaScript
- **API**: Groq API integration with multiple models
- **Security**: Multiple layers including CSP, rate limiting, input validation
- **Configuration**: Environment variables (.env) - API keys only in ENV
- **Models**: Fallback system with multiple verified Groq models

## Key Development Rules

### Security First
- **Never** hardcode API keys in code - only use .env variables
- Use `loadEnvVar()` function for environment variables (consistent across all files)
- All user input must be sanitized and validated
- API keys are never exposed to frontend

### File Structure
- **Working Directory**: `/c/ThomazCia/Website_novo/agentes-one-shot`
- **Agents**: PHP files in `/agentes/` folder
- **Documentation**: Main docs in root, development guides in `/agents/`

### Model Management
- Current provider: **Groq API** (NOT Open Router anymore)
- Models are verified and tested via `/model-status.php`
- Available models: llama-3.1-8b-instant, llama-3.3-70b-versatile, meta-llama/llama-4-maverick-17b-128e-instruct, etc.
- Fallback system automatically switches models if one fails

## Available Documentation

### User Documentation
- `README.md` - Complete user guide and installation instructions
- `TECHNICAL_GUIDE.md` - Detailed technical documentation
- `SECURITY_REPORT.md` - Comprehensive security analysis and measures

### Development Resources
- `agents/ROADMAP.md` - Development roadmap with future features and v2.1+ plans
- `agents/COMO-CRIAR-AGENTES.md` - Guide for creating custom agents
- `agents/TECHNICAL_GUIDE.md` - Technical specifications for agent development
- `agents/SECURITY_REPORT.md` - Security best practices for agent creation
- `agents/CORES_INSTRUCOES.md` - Core configuration instructions for development

## Important Files

### Core System
- `config.php` - Main configuration with environment variables
- `models.php` - Model management and fallback system
- `agentes.php` - Main agents interface
- `model-status.php` - Model availability dashboard

### Security Files
- `security.php` - Security functions and validation
- `model-status-check-availability.php` - Model testing endpoint

## Development Guidelines

### Environment Variables
- Use **only** `loadEnvVar()` function (not `getenv()`)
- API keys MUST be in `.env` file only
- Never commit `.env` or API keys to git

### Model Updates
- Check model availability via API before adding new models
- Use correct model IDs from Groq API response
- Test all models in `/model-status.php` dashboard

### Agent Creation
- Follow patterns in `/agents/` documentation
- Use Bootstrap 5 responsive components
- Implement proper validation and security
- Test with model fallback system

## Version Information
- **Current Version**: 2.1
- **API Provider**: Groq (changed from Open Router)
- **Status**: Production-ready
- **Security**: Hardened with multiple protection layers  