import express from 'express';
import http from 'http';
import { Server } from 'socket.io';
import cors from 'cors';

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

app.use(cors());

// Stockage des utilisateurs en ligne
const onlineUsers = new Map();

// Événements WebSocket
io.on('connection', (socket) => {
    console.log(`✅ Utilisateur connecté: ${socket.id}`);

    // Lorsqu'un utilisateur se connecte à sa messagerie
    socket.on('user_connected', (data) => {
        const { userId, name } = data;
        onlineUsers.set(userId, {
            socketId: socket.id,
            name: name,
            connectedAt: new Date()
        });
        console.log(`📱 ${name} (ID: ${userId}) connecté`);
        io.emit('users_online_count', onlineUsers.size);
    });

    // Lorsqu'un message est envoyé
    socket.on('send_message', (data) => {
        const { fromUserId, toUserId, message, productName } = data;

        const toUser = onlineUsers.get(parseInt(toUserId));

        if (toUser) {
            // Envoyer le message à l'utilisateur destinataire
            io.to(toUser.socketId).emit('receive_message', {
                fromUserId,
                message,
                timestamp: new Date(),
                productName,
                senderName: data.senderName
            });
            console.log(`📨 Message envoyé de ${fromUserId} à ${toUserId}`);
        } else {
            console.log(`⚠️  Utilisateur ${toUserId} n'est pas en ligne`);
        }
    });

    // Indicateur de "en train de taper"
    socket.on('typing', (data) => {
        const { toUserId, fromUserId, senderName } = data;
        const toUser = onlineUsers.get(parseInt(toUserId));

        if (toUser) {
            io.to(toUser.socketId).emit('user_typing', {
                fromUserId,
                senderName
            });
        }
    });

    // Arrêter l'indicateur de "en train de taper"
    socket.on('stop_typing', (data) => {
        const { toUserId } = data;
        const toUser = onlineUsers.get(parseInt(toUserId));

        if (toUser) {
            io.to(toUser.socketId).emit('user_stop_typing', {
                fromUserId: data.fromUserId
            });
        }
    });

    // Marquer les messages comme lus
    socket.on('mark_as_read', (data) => {
        const { conversationId } = data;
        io.emit('messages_marked_read', { conversationId });
    });

    // Lorsqu'un utilisateur se déconnecte
    socket.on('disconnect', () => {
        // Trouver l'utilisateur par socketId
        for (let [userId, user] of onlineUsers.entries()) {
            if (user.socketId === socket.id) {
                onlineUsers.delete(userId);
                console.log(`❌ ${user.name} déconnecté`);
                io.emit('users_online_count', onlineUsers.size);
                break;
            }
        }
    });

    // Erreur
    socket.on('error', (error) => {
        console.error(`❌ Erreur Socket: ${error}`);
    });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`🚀 Serveur WebSocket lancé sur le port ${PORT}`);
    console.log(`📍 http://localhost:${PORT}`);
});
