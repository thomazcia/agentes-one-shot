/**
 * App.js - Agentes One-Shot v2.2
 * Funções JavaScript para interações da interface
 */

/**
 * Copia o conteúdo da resposta para a área de transferência
 * Funciona com conteúdo HTML (markdown) e texto puro
 */
function copyResponse() {
    const responseElement = document.querySelector('.response-content_output');

    if (!responseElement) {
        console.error('Elemento de resposta não encontrado');
        alert('Erro: elemento de resposta não encontrado');
        return;
    }

    let responseText;

    // Verificar se o conteúdo é HTML (markdown) ou texto puro
    if (responseElement.innerHTML !== responseElement.innerText) {
        // É HTML formatado, extrair texto puro
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = responseElement.innerHTML;
        responseText = tempDiv.textContent || tempDiv.innerText || '';
    } else {
        // É texto puro
        responseText = responseElement.innerText;
    }

    // Verificar se há conteúdo para copiar
    if (!responseText || responseText.trim() === '') {
        alert('Não há conteúdo para copiar');
        return;
    }

    // Copiar para a área de transferência
    navigator.clipboard.writeText(responseText).then(() => {
        // Feedback visual
        const button = document.querySelector('[onclick="copyResponse()"]');
        if (button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check me-1"></i>Copiado!';
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-success');

            // Restaurar texto original após 2 segundos
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-primary');
            }, 2000);
        } else {
            alert('Resposta copiada para a área de transferência!');
        }
    }).catch((err) => {
        console.error('Erro ao copiar texto: ', err);

        // Fallback para navegadores mais antigos
        try {
            const textArea = document.createElement('textarea');
            textArea.value = responseText;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);

            if (successful) {
                alert('Resposta copiada para a área de transferência!');
            } else {
                alert('Não foi possível copiar a resposta.');
            }
        } catch (fallbackErr) {
            console.error('Erro no fallback: ', fallbackErr);
            alert('Não foi possível copiar a resposta.');
        }
    });
}

// Garantir que a função esteja disponível globalmente
window.copyResponse = copyResponse;