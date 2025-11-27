
document.addEventListener('DOMContentLoaded', () => {
    loadAgents();
});

let currentAgent = null;

function showLoading() {
    document.getElementById('loading-spinner').style.display = 'block';
    document.getElementById('response-content').innerHTML = '';
}

function hideLoading() {
    document.getElementById('loading-spinner').style.display = 'none';
}

function escapeHtml(unsafe) {
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

async function loadAgents() {
    console.log('🔄 Carregando agentes...');
    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'get_agents' })
        });
        const result = await response.json();

        const agentsGrid = document.getElementById('agents-grid');
        if (result.success && result.data.length > 0) {
            console.log(`✅ Agentes carregados: ${result.data.length}`);
            agentsGrid.innerHTML = ''; // Limpa a mensagem de "nenhum agente"
            result.data.forEach(agent => {
                const card = `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card agent-card h-100" onclick="showAgentDetail('${agent.id}')">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="agent-icon me-3" style="background-color: ${agent.color || '#667eea'};
">
                                        <i class="bi ${agent.icon}"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">${escapeHtml(agent.name)}</h5>
                                        <div class="badges-container">
                                            <span class="badge bg-primary">${escapeHtml(agent.category)}</span>
                                            <span class="badge bg-success">${escapeHtml(agent.difficulty)}</span>
                                            <span class="badge bg-info">${escapeHtml(agent.estimated_time)}</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="card-text">${escapeHtml(agent.description)}</p>
                            </div>
                        </div>
                    </div>
                `;
                agentsGrid.innerHTML += card;
            });
        } else {
            console.warn('⚠️ Nenhum agente encontrado ou erro na API.');
            agentsGrid.innerHTML = `
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-robot"></i>
                        <h4>Nenhum agente disponível</h4>
                        <p class="text-muted">Nenhum agente foi encontrado na pasta /agentes/ ou houve um erro.</p>
                    </div>
                </div>`;
        }
    } catch (error) {
        console.error('❌ Erro ao carregar agentes:', error);
        document.getElementById('agents-grid').innerHTML = '<p class="text-danger">Erro ao carregar agentes. Verifique o console para mais detalhes.</p>';
    }
}

async function showAgentDetail(agentId) {
    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'get_agent', id: agentId })
        });
        const result = await response.json();

        if (result.success) {
            currentAgent = result.data;
            document.getElementById('agent-title').innerText = currentAgent.name;
            document.getElementById('agent-description').innerText = currentAgent.description;
            
            const fieldsContainer = document.getElementById('agent-fields');
            fieldsContainer.innerHTML = '';
            currentAgent.fields.forEach(field => {
                fieldsContainer.innerHTML += createFieldHtml(field);
            });

            document.getElementById('agents-list-view').style.display = 'none';
            document.getElementById('agent-detail-view').style.display = 'block';
            document.getElementById('response-content').innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="bi bi-chat-dots display-4"></i>
                    <p class="mt-3">Preencha o formulário e clique em "Executar Agente" para ver a resposta aqui</p>
                </div>`;
            document.getElementById('response-actions').style.display = 'none';
        } else {
            alert('Erro ao carregar detalhes do agente.');
        }
    } catch (error) {
        console.error('Erro ao buscar detalhes do agente:', error);
    }
}

function createFieldHtml(field) {
    const required = field.required ? 'required' : '';
    let fieldHtml = `
        <div class="mb-3">
            <label for="${field.label}" class="form-label">${escapeHtml(field.label)} ${field.required ? '<span class="text-danger">*</span>' : ''}</label>`;

    switch(field.type) {
        case 'select':
            fieldHtml += `
                <select class="form-select" id="${field.label}" placeholder="${field.placeholder}" ${required}>
                    <option value="">Selecione...</option>
                    ${field.options.map(opt => `<option value="${escapeHtml(opt)}">${escapeHtml(opt)}</option>`).join('')}
                </select>`;
            break;
        case 'textarea':
            fieldHtml += `<textarea class="form-control" id="${field.label}" rows="3" placeholder="${field.placeholder}" ${required}></textarea>`;
            break;
        default:
            fieldHtml += `<input type="${field.type}" class="form-control" id="${field.label}" placeholder="${field.placeholder}" ${required}>`;
    }
    fieldHtml += '</div>';
    return fieldHtml;
}

function showAgentsList() {
    document.getElementById('agents-list-view').style.display = 'block';
    document.getElementById('agent-detail-view').style.display = 'none';
    currentAgent = null;
}

async function executeCurrentAgent() {
    if (!currentAgent) return;

    const form = document.getElementById('agent-execution-form');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const fieldValues = {};
    currentAgent.fields.forEach(field => {
        const input = document.getElementById(field.label);
        fieldValues[field.label] = input.value;
    });
    
    showLoading();

    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'execute_agent',
                agent_id: currentAgent.id,
                field_values: JSON.stringify(fieldValues)
            })
        });
        const result = await response.json();

        if (result.success) {
            displayResponse(result.data.choices[0].message.content);
            document.getElementById('response-actions').style.display = 'block';
        } else {
            displayResponse('Erro: ' + result.message);
        }
    } catch (error) {
        console.error('Erro ao executar o agente:', error);
        displayResponse('Ocorreu um erro de comunicação com a API.');
    }
}

function displayResponse(response) {
    hideLoading();
    let cleanedResponse = response.trim();
    // Basic cleaning, can be improved
    cleanedResponse = cleanedResponse.replace(/^[\u202A\u202D\u202E\u202C]+/, '');
    
    // Preserve line breaks by replacing \n with <br>
    const formattedResponse = escapeHtml(cleanedResponse).replace(/\n/g, '<br>');

    document.getElementById('response-content').innerHTML = `
        <div class="response-content_output">
            ${formattedResponse}
        </div>
    `;
}

function copyResponse() {
    const responseText = document.querySelector('.response-content_output').innerText;
    navigator.clipboard.writeText(responseText).then(() => {
        alert('Resposta copiada para a área de transferência!');
    }, (err) => {
        console.error('Erro ao copiar texto: ', err);
        alert('Erro ao copiar a resposta.');
    });
}

 
