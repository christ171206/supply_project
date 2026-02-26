// Configuration Socket.io pour la messagerie temps réel
let socket = null;

export function initializeSocketIO(userId, userName) {
    const SOCKET_IO_URL = 'http://localhost:3000';

    socket = io(SOCKET_IO_URL, {
        reconnection: true,
        reconnectionDelay: 1000,
        reconnectionDelayMax: 5000,
        reconnectionAttempts: 5,
        auth: {
            userId: userId,
            userName: userName
        }
    });

    socket.on('connect', () => {
        console.log('✅ Connecté au serveur WebSocket');

        socket.emit('user-connect', {
            userId: userId,
            name: userName,
            timestamp: new Date()
        });
    });

    socket.on('disconnect', () => {
        console.log('📴 Déconnecté du serveur WebSocket');
    });

    socket.on('receive-message', (data) => {
        console.log('📨 Message reçu:', data);

        // Ajouter le message visuellement
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            const messageElement = document.createElement('div');
            messageElement.className = `flex ${data.from_user_id === userId ? 'justify-end' : 'justify-start'}`;
            messageElement.innerHTML = `
                <div class="max-w-xs lg:max-w-md ${data.from_user_id === userId ? 'bg-primary-600 text-white rounded-3xl rounded-tr-none' : 'bg-gray-100 text-gray-900 rounded-3xl rounded-tl-none'} px-5 py-3 shadow-md">
                    <p class="text-sm leading-relaxed">${escapeHtml(data.contenu)}</p>
                    <p class="text-xs ${data.from_user_id === userId ? 'text-primary-100' : 'text-gray-500'} mt-2">
                        ${data.timestamp}
                    </p>
                </div>
            `;
            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Notification
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('Nouveau message', {
                body: data.contenu.substring(0, 50) + '...',
                icon: '💬'
            });
        }
    });

    socket.on('user-typing', (data) => {
        console.log(`${data.userId} est en train d'écrire...`);

        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator && data.isTyping) {
            typingIndicator.innerHTML = '<p class="text-sm text-gray-500 italic">L\'utilisateur tape...</p>';
        } else if (typingIndicator && !data.isTyping) {
            typingIndicator.innerHTML = '';
        }
    });

    socket.on('message-error', (data) => {
        console.error('❌ Erreur:', data.error);
        alert('Erreur: ' + data.error);
    });

    socket.on('users-online-count', (count) => {
        console.log(`👥 Utilisateurs en ligne: ${count}`);
    });

    return socket;
}

export function sendMessage(toUserId, contenu, productId = null) {
    if (!socket || !socket.connected) {
        console.error('Socket non connecté');
        return false;
    }

    const timestamp = new Date().toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    });

    socket.emit('send-message', {
        from_user_id: socket.handshake.auth.userId,
        to_user_id: toUserId,
        contenu: contenu,
        timestamp: timestamp,
        productId: productId
    });

    return true;
}

export function notifyTyping(toUserId) {
    if (!socket) return;

    socket.emit('typing', {
        userId: socket.handshake.auth.userId,
        otherUserId: toUserId,
        isTyping: true
    });
}

export function stopTyping(toUserId) {
    if (!socket) return;

    socket.emit('typing', {
        userId: socket.handshake.auth.userId,
        otherUserId: toUserId,
        isTyping: false
    });
}

export function markAsRead(otherUserId) {
    if (!socket) return;

    socket.emit('mark-as-read', {
        userId: socket.handshake.auth.userId,
        otherUserId: otherUserId
    });
}

export function joinConversation(otherUserId) {
    if (!socket) return;

    socket.emit('join-conversation', {
        userId: socket.handshake.auth.userId,
        otherUserId: otherUserId
    });
}

export function getSocket() {
    return socket;
}

export function closeConnection() {
    if (socket) {
        socket.disconnect();
        socket = null;
    }
}

// Utilitaire pour éviter les injections XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
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
