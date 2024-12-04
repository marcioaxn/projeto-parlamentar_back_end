// public/js/session_timeout.js

// Defina o tempo limite da sessão em segundos (por exemplo, 15 minutos).
const sessionTimeout = 70 * 60; // 120 minutos

// Função para atualizar o tempo restante em tempo real
function updateSessionTimer() {
    const currentTime = new Date().getTime() / 1000;
    const lastActivity = parseFloat(localStorage.getItem('lastActivity'));
    const sessionTimeLeftElement = document.getElementById('session-time-left');

    if (sessionTimeLeftElement) {
        if (isNaN(lastActivity)) {
            localStorage.setItem('lastActivity', currentTime);
        } else if (currentTime - lastActivity > sessionTimeout) {
            // Redirecionar para o logout
            document.getElementById("logout-form").submit();
        } else {
            const timeLeft = Math.ceil(sessionTimeout - (currentTime - lastActivity));
            // Atualize o elemento HTML com o tempo restante
            document.getElementById('session-time-left').innerText = formatTime(timeLeft);
        }
    }
}

// Função para formatar o tempo em minutos e segundos
function formatTime(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = seconds % 60;

    let result = "";

    result += 'Tempo de sessão é de ';

    if (hours > 0) {
        result += `${hours} hora${hours > 1 ? 's' : ''} `;
    }

    if (minutes > 0) {
        result += `${minutes} minuto${minutes > 1 ? 's' : ''} `;
    }

    result += `${remainingSeconds} segundo${remainingSeconds > 1 ? 's' : ''}`;

    return result;
}

// Atualize o tempo restante a cada segundo
setInterval(updateSessionTimer, 1000);

// Atualize a última atividade quando houver eventos de interação
document.addEventListener('click', function () {
    localStorage.setItem('lastActivity', new Date().getTime() / 1000);
});

// Execute a função para iniciar a contagem regressiva
updateSessionTimer();
