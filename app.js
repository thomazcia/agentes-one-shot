
/**
 * App.js - Agentes One-Shot v2.2
 * Funções JavaScript mínimas após migração para PHP
 */

function copyResponse() {
    const responseElement = document.querySelector('.response-content_output');

    // Verificar se o conteúdo é HTML (markdown) ou texto puro
    if (responseElement.innerHTML !== responseElement.innerText) {
        // É HTML formatado, extrair texto puro
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = responseElement.innerHTML;
        const responseText = tempDiv.textContent || tempDiv.innerText || '';

        navigator.clipboard.writeText(responseText).then(() => {
            alert('Resposta copiada para a área de transferência!');
        }, (err) => {
            console.error('Erro ao copiar texto: ', err);
            alert('Erro ao copiar a resposta.');
        });
    } else {
        // É texto puro
        const responseText = responseElement.innerText;
        navigator.clipboard.writeText(responseText).then(() => {
            alert('Resposta copiada para a área de transferência!');
        }, (err) => {
            console.error('Erro ao copiar texto: ', err);
            alert('Erro ao copiar a resposta.');
        });
    }
} 
