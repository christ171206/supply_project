import express from 'express';
import http from 'http';
import { Server } from 'socket.io';
import cors from 'cors';
import axios from 'axios';

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: ["http://localhost:3000", "http://localhost:8000", "http://127.0.0.1:8000", "http://127.0.0.1:3000"],
        methods: ["GET", "POST"],
        credentials: true
    }
});

app.use(cors());
app.use(express.json());

// Stockage des utilisateurs connectés
const onlineUsers = new Map();
const userSockets = new Map();

const PORT = process.env.SOCKET_IO_PORT || 3000;

// ========== Événements WebSocket ==========

io.on('connection', (socket) => {
    console.log(`✅ Socket connecté: ${socket.id}`);

    // 1. Utilisateur se connecte à la messagerie
    socket.on('user-connect', (data) => {
        const { userId, name } = data;

        onlineUsers.set(userId, {
            socketId: socket.id,
            name: name,
            connectedAt: new Date()
        });
        userSockets.set(socket.id, userId);

        console.log(`📱 ${name} (ID: ${userId}) connecté`);
        io.emit('users-online-count', onlineUsers.size);
    });

    // 2. Rejoindre une conversation
    socket.on('join-conversation', (data) => {
        const { userId, otherUserId } = data;

        // Créer une room unique pour cette conversation
        const conversationRoom = [userId, otherUserId].sort().join('-');
        socket.join(conversationRoom);

        console.log(`👥 ${userId} rejoint la conversation: ${conversationRoom}`);

        io.to(conversationRoom).emit('user-joined', {
            userId: userId,
            otherUserId: otherUserId
        });
    });

    // 3. Envoyer un message
    socket.on('send-message', async (data) => {
        const { from_user_id, to_user_id, contenu, timestamp } = data;
        const conversationRoom = [from_user_id, to_user_id].sort().join('-');

        console.log(`💬 Message de ${from_user_id} à ${to_user_id}: ${contenu.substring(0, 50)}...`);

        try {
            // Sauvegarder le message en base de données via API
            const response = await axios.post('http://localhost:8000/api/messages/store', {
                from_user_id: from_user_id,
                to_user_id: to_user_id,
                contenu: contenu
            }, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).catch(err => {
                console.warn('⚠️ Impossible de sauvegarder le message en DB:', err.message);
                // Continuer même si la sauvegarde échoue
            });

            // Émettre le message aux deux utilisateurs
            io.to(conversationRoom).emit('receive-message', {
                from_user_id: from_user_id,
                to_user_id: to_user_id,
                contenu: contenu,
                timestamp: timestamp,
                id: response?.data?.id || Date.now()
            });

            // Notifier l'autre utilisateur qu'il a un nouveau message
            if (onlineUsers.has(to_user_id)) {
                const toUserSocket = onlineUsers.get(to_user_id);
                io.to(toUserSocket.socketId).emit('message-notification', {
                    from_user_id: from_user_id,
                    preview: contenu.substring(0, 50)
                });
            }
        } catch (error) {
            console.error('❌ Erreur lors de l\'envoi du message:', error.message);
            socket.emit('message-error', {
                error: 'Erreur lors de l\'envoi du message'
            });
        }
    });

    // 4. Indicateur de typing
    socket.on('typing', (data) => {
        const { userId, otherUserId, isTyping } = data;
        const conversationRoom = [userId, otherUserId].sort().join('-');

        socket.to(conversationRoom).emit('user-typing', {
            userId: userId,
            isTyping: isTyping
        });
    });

    // 5. Marquer messages comme lus
    socket.on('mark-as-read', (data) => {
        const { userId, otherUserId } = data;
        const conversationRoom = [userId, otherUserId].sort().join('-');

        io.to(conversationRoom).emit('conversation-read', {
            userId: userId
        });
    });

    // 6. Déconnexion
    socket.on('disconnect', () => {
        const userId = userSockets.get(socket.id);

        if (userId) {
            onlineUsers.delete(userId);
            userSockets.delete(socket.id);
            console.log(`📴 Utilisateur ${userId} déconnecté`);
            io.emit('users-online-count', onlineUsers.size);
        }
    });

    socket.on('error', (error) => {
        console.error('❌ Erreur Socket:', error);
    });
});

// ========== Routes API ==========

app.get('/api/online-users', (req, res) => {
    res.json({
        count: onlineUsers.size,
        users: Array.from(onlineUsers.entries()).map(([id, data]) => ({
            id,
            name: data.name,
            connectedAt: data.connectedAt
        }))
    });
});

app.get('/health', (req, res) => {
    res.json({ status: 'OK', timestamp: new Date() });
});

// ========== Démarrage du serveur ==========

server.listen(PORT, () => {
    console.log(`
╔════════════════════════════════════════╗
║  🚀 WebSocket Server DÉMARRÉ           ║
║  Port: ${PORT}                         ║
║  Environment: ${process.env.NODE_ENV || 'development'}                  ║
║  Time: ${new Date().toLocaleString()}   ║
╚════════════════════════════════════════╝
    `);
});

export default io;
