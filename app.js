
/**
 * App.js - Agentes One-Shot v2.1
 * Funções JavaScript mínimas após migração para PHP
 */

function copyResponse() {
    const responseText = document.querySelector('.response-content_output').innerText;
    navigator.clipboard.writeText(responseText).then(() => {
        alert('Resposta copiada para a área de transferência!');
    }, (err) => {
        console.error('Erro ao copiar texto: ', err);
        alert('Erro ao copiar a resposta.');
    });
} 
