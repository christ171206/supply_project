import io from 'socket.io-client';

// Configuration
const SOCKET_URL = 'http://localhost:3000';
let socket = null;
let currentUserId = null;
let currentConversationUserId = null;

/**
 * Initialiser la connexion WebSocket
 */
export function initializeSocketIO(userId, userName) {
    if (socket) return;

    currentUserId = userId;
    socket = io(SOCKET_URL, {
        reconnection: true,
        reconnectionDelay: 1000,
        reconnectionDelayMax: 5000,
        reconnectionAttempts: 5
    });

    // Connecté
    socket.on('connect', () => {
        console.log('✅ Connecté au serveur WebSocket');
        socket.emit('user_connected', { userId, name: userName });
    });

    // Recevoir un message
    socket.on('receive_message', (data) => {
        console.log('📨 Message reçu:', data);

        // Ajouter le message au DOM
        addMessageToChat(data, false);

        // Notifier l'utilisateur
        if (Notification.permission === 'granted') {
            new Notification(`Message de ${data.senderName}`, {
                body: data.message,
                icon: '💬'
            });
        }
    });

    // Utilisateur en train de taper
    socket.on('user_typing', (data) => {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.innerHTML = `<p class="text-sm italic text-gray-500">${data.senderName} est en train de taper...</p>`;
        }
    });

    // Arrêter l'indicateur de "en train de taper"
    socket.on('user_stop_typing', () => {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.innerHTML = '';
        }
    });

    // Compte d'utilisateurs en ligne
    socket.on('users_online_count', (count) => {
        const onlineCount = document.getElementById('online-users-count');
        if (onlineCount) {
            onlineCount.textContent = count;
        }
    });

    // Déconnecté
    socket.on('disconnect', () => {
        console.log('❌ Déconnecté du serveur WebSocket');
    });

    // Erreur
    socket.on('connect_error', (error) => {
        console.error('❌ Erreur de connexion:', error);
    });
}

/**
 * Envoyer un message
 */
export function sendMessage(toUserId, message, productName = null, senderName = 'Utilisateur') {
    if (!socket) {
        console.error('Socket non initialisé');
        return;
    }

    const messageData = {
        fromUserId: currentUserId,
        toUserId,
        message,
        productName,
        senderName
    };

    socket.emit('send_message', messageData);

    // Ajouter le message au chat local
    addMessageToChat({
        fromUserId: currentUserId,
        message,
        timestamp: new Date(),
        senderName: 'Vous'
    }, true);

    return true;
}

/**
 * Notifier que l'utilisateur tape
 */
export function notifyTyping(toUserId) {
    if (!socket) return;
    socket.emit('typing', {
        toUserId,
        fromUserId: currentUserId,
        senderName: document.querySelector('[data-user-name]')?.dataset.userName || 'Utilisateur'
    });
}

/**
 * Arrêter la notification de typing
 */
export function stopTyping(toUserId) {
    if (!socket) return;
    socket.emit('stop_typing', {
        toUserId,
        fromUserId: currentUserId
    });
}

/**
 * Ajouter un message au chat
 */
function addMessageToChat(data, isOwn = false) {
    const messagesContainer = document.getElementById('messages-container');
    if (!messagesContainer) return;

    const messageEl = document.createElement('div');
    messageEl.className = `flex ${isOwn ? 'justify-end' : 'justify-start'} mb-4`;

    const time = new Date(data.timestamp).toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });

    messageEl.innerHTML = `
        <div class="max-w-xs ${isOwn ? 'bg-purple-500 text-white' : 'bg-white border border-gray-200 text-gray-900'} rounded-lg p-4 shadow">
            <p class="text-sm">${escapeHtml(data.message)}</p>
            <p class="text-xs ${isOwn ? 'text-purple-100' : 'text-gray-500'} mt-2">${time}</p>
        </div>
    `;

    messagesContainer.appendChild(messageEl);

    // Scroll vers le bas
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

/**
 * Échapper les caractères HTML
 */
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

/**
 * Déconnecter
 */
export function disconnect() {
    if (socket) {
        socket.disconnect();
        socket = null;
    }
}

/**
 * Vérifier si connecté
 */
export function isConnected() {
    return socket && socket.connected;
}
