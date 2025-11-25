// Agentes One-Shot v1.0 - JavaScript Frontend
// Conecta com o backend PHP para operações seguras

// Global variables
let agents = [];
let currentAgent = null;
let currentEditingAgent = null;

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    loadAgents();
});

// API Call Helper
async function apiCall(action, data = {}) {
    console.log('🌐 API Call:', action, data);
    try {
        const formData = new FormData();
        formData.append('action', action);

        // Adicionar dados ao FormData
        Object.keys(data).forEach(key => {
            if (typeof data[key] === 'object') {
                formData.append(key, JSON.stringify(data[key]));
            } else {
                formData.append(key, data[key]);
            }
        });

        console.log('📤 Sending request to API...');
        const response = await fetch('api.php', {
            method: 'POST',
            body: formData
        });

        console.log('📥 Response status:', response.status);
        const result = await response.json();
        // console.log('📋 Response data:', result); // Aqui apresenta o retorno com o número de tokens utilizado.
        console.log('📋 Response data:', 'Sucesso no retorno');

        if (!result.success) {
            throw new Error(result.message || 'Erro na operação');
        }

        return result;

    } catch (error) {
        console.error('❌ API Error:', error);
        showAlert('error', error.message);
        throw error;
    }
}

// View Management
function showAgentsList() {
    document.getElementById('agents-list-view').style.display = 'block';
    document.getElementById('agent-detail-view').style.display = 'none';
    renderAgentsList();
}

function showAgentDetail(agentId) {
    const agent = agents.find(a => a.id === agentId);
    if (!agent) {
        showAlert('error', 'Agente não encontrado');
        return;
    }

    currentAgent = agent;
    document.getElementById('agents-list-view').style.display = 'none';
    document.getElementById('agent-detail-view').style.display = 'block';

    // Populate agent details
    document.getElementById('agent-title').textContent = agent.name;
    document.getElementById('agent-description').textContent = agent.description;

    // Generate form fields
    const fieldsContainer = document.getElementById('agent-fields');
    fieldsContainer.innerHTML = '';

    if (agent.fields && agent.fields.length > 0) {
        agent.fields.forEach(field => {
            const fieldHtml = createFieldHtml(field);
            fieldsContainer.innerHTML += fieldHtml;
        });
    }

    // Reset response
    resetResponse();
}

// Funções de criação/edição removidas
// Os agentes agora são arquivos PHP configurados manualmente

// Agent Management
async function loadAgents() {
    console.log('🔄 Carregando agentes...');
    try {
        const result = await apiCall('get_agents');
        console.log('✅ API Response:', result);
        agents = result.data;
        console.log('📊 Agents loaded:', agents.length, agents);
        renderAgentsList();
        await loadStats(); // Carregar estatísticas
    } catch (error) {
        console.error('❌ Error loading agents:', error);
        // Mostrar mensagem de erro na tela
        const container = document.getElementById('agents-grid');
        if (container) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Erro ao carregar agentes:</strong> ${error.message}
                        <br><small>Abra o console para mais detalhes.</small>
                    </div>
                </div>
            `;
        }
    }
}

// Funções saveCurrentAgent e deleteCurrentAgent removidas
// Agentes são gerenciados como arquivos PHP

// Funções de field management removidas
// Os campos agora são definidos nos arquivos PHP dos agentes

function createFieldHtml(field) {
    const fieldId = 'exec-field-' + field.label.replace(/\s+/g, '-').toLowerCase();
    let inputHtml = '';

    switch (field.type) {
        case 'textarea':
            inputHtml = `<textarea class="form-control" id="${fieldId}"
                                 placeholder="${field.placeholder}"
                                 ${field.required ? 'required' : ''} rows="${field.rows || 3}"></textarea>`;
            break;
        case 'number':
            inputHtml = `<input type="number" class="form-control" id="${fieldId}"
                               placeholder="${field.placeholder}"
                               ${field.required ? 'required' : ''}>`;
            break;
        case 'email':
            inputHtml = `<input type="email" class="form-control" id="${fieldId}"
                               placeholder="${field.placeholder}"
                               ${field.required ? 'required' : ''}>`;
            break;
        case 'select':
            // Gerar options para o select
            let optionsHtml = '';
            if (field.options && Array.isArray(field.options)) {
                field.options.forEach(option => {
                    optionsHtml += `<option value="${option}">${option}</option>`;
                });
            }
            // Adicionar opção padrão se não houver
            if (!optionsHtml) {
                optionsHtml = '<option value="">Selecione...</option>';
            }

            inputHtml = `<select class="form-select" id="${fieldId}"
                               ${field.required ? 'required' : ''}>
                <option value="" disabled selected>${field.placeholder || 'Selecione...'}</option>
                ${optionsHtml}
            </select>`;
            break;
        default:
            inputHtml = `<input type="text" class="form-control" id="${fieldId}"
                               placeholder="${field.placeholder}"
                               ${field.required ? 'required' : ''}>`;
    }

    return `
        <div class="mb-3">
            <label for="${fieldId}" class="form-label">
                ${field.label} ${field.required ? '<span class="text-danger">*</span>' : ''}
            </label>
            ${inputHtml}
        </div>
    `;
}

// UI Rendering
function renderAgentsList() {
    const container = document.getElementById('agents-grid');

    if (agents.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-robot"></i>
                    <h4>Nenhum agente disponível</h4>
                    <p class="text-muted">Nenhum agente foi encontrado na pasta /agentes/</p>
                    <p class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Os agentes são pré-configurados. Contate o administrador.
                    </p>
                </div>
            </div>
        `;
        return;
    }

    container.innerHTML = agents.map(agent => `
        <div class="col-md-6 col-lg-4">
            <div class="agent-card p-4" onclick="showAgentDetail('${agent.id}')">
                <div class="d-flex align-items-start mb-3">
                    <div class="agent-icon me-3" style="background: ${agent.color || 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'};">
                        <i class="bi ${agent.icon || 'bi-robot'}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">${agent.name}</h5>
                        <div class="d-flex gap-2 mb-1">
                            <span class="badge bg-primary small">${agent.category || 'geral'}</span>
                            ${agent.difficulty ? `<span class="badge bg-secondary small">${agent.difficulty}</span>` : ''}
                            ${agent.estimated_time ? `<span class="badge bg-info small">${agent.estimated_time}</span>` : ''}
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-3">${agent.description}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-input-cursor"></i> ${agent.fields?.length || 0} campos
                    </small>
                    <i class="bi bi-arrow-right text-primary"></i>
                </div>
            </div>
        </div>
    `).join('');
}

// Agent Execution
async function executeCurrentAgent() {
    if (!currentAgent) return;

    // Validate form
    const form = document.getElementById('agent-execution-form');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Collect field values
    const fieldValues = {};
    if (currentAgent.fields) {
        let allRequiredFilled = true;

        currentAgent.fields.forEach(field => {
            const fieldId = 'exec-field-' + field.label.replace(/\s+/g, '-').toLowerCase();
            const element = document.getElementById(fieldId);
            if (element) {
                fieldValues[field.label] = element.value;
                if (field.required && !element.value.trim()) {
                    allRequiredFilled = false;
                }
            }
        });

        if (!allRequiredFilled) {
            showAlert('error', 'Preencha todos os campos obrigatórios');
            return;
        }
    }

    try {
        showLoading();

        const result = await apiCall('execute_agent', {
            agent_id: currentAgent.id,
            field_values: fieldValues
        });

        displayResponse(result.response);

    } catch (error) {
        console.error('Error executing agent:', error);
        displayError(error.message);
    }
}

function showLoading() {
    document.getElementById('loading-spinner').style.display = 'block';
    document.getElementById('response-content').style.display = 'none';
    document.getElementById('response-actions').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loading-spinner').style.display = 'none';
    document.getElementById('response-content').style.display = 'block';
    document.getElementById('response-actions').style.display = 'flex';
}

function resetResponse() {
    document.getElementById('response-content').innerHTML = `
        <div class="text-center text-muted py-5">
            <i class="bi bi-chat-dots display-4"></i>
            <p class="mt-3">Preencha o formulário e clique em "Executar Agente" para ver a resposta aqui</p>
        </div>
    `;
    document.getElementById('response-actions').style.display = 'none';
}

function displayResponse(response) {
    hideLoading();
    document.getElementById('response-content').innerHTML = `
        <div class="response-content">
            ${escapeHtml(response)}
        </div>
    `;
}

function displayError(error) {
    hideLoading();
    document.getElementById('response-content').innerHTML = `
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Erro:</strong> ${error}
        </div>
    `;
}

function copyResponse() {
    const responseContent = document.querySelector('.response-content').textContent;
    navigator.clipboard.writeText(responseContent).then(() => {
        // Show feedback
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check me-1"></i>Copiado!';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-primary');

        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
        }, 2000);
    });
}

// Funções de import/export removidas
// Agentes agora são gerenciados como arquivos PHP

// Função para carregar estatísticas
async function loadStats() {
    try {
        const result = await apiCall('get_agent_stats');
        const statsElement = document.getElementById('stats-total');
        if (statsElement) {
            statsElement.textContent = `Total de agentes: ${result.data.total}`;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Utility Functions
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function showAlert(type, message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Insert at top of container
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}