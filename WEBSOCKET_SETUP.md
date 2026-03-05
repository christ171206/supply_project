# Configuration WebSocket pour les Notifications en Temps Réel

## Vue d'ensemble
Le système de notifications en temps réel utilise Socket.io pour mettre à jour instantanément le badge de notification quand un vendeur reçoit un message.

## Architecture
- **Serveur WebSocket**: Node.js avec Socket.io (port 3000)
- **Client**: Socket.io JavaScript (CDN)
- **Messages**: Communiqués via l'API Laravel (`/api/messages/store`)

## Installation

### 1. Dépendances Node.js
Les dépendances sont déjà installées. Vérifiez avec:
```bash
npm list socket.io express
```

### 2. Démarrer le serveur WebSocket

**Option A: En développement (avec rechargement automatique)**
```bash
npm run dev
```

**Option B: En production**
```bash
npm run build
node websocket-server.js
```

**Option C: Directement avec Node**
```bash
node websocket-server.js
```

Le serveur démarre sur le port 3000 par défaut.

## Configuration

### Variables d'environnement
Créer un fichier `.env.local` à la racine du projet (optionnel):
```
SOCKET_IO_PORT=3000
SOCKET_IO_URL=http://127.0.0.1:3000
```

### Port personnalisé
Modifier la variable `PORT` dans `websocket-server.js`:
```javascript
const PORT = process.env.SOCKET_IO_PORT || 3000;
```

## Flux de notifications

### 1. Un client envoie un message
```
Client A → API Laravel `/api/messages/store` → Base de données
```

### 2. WebSocket notifie le vendeur
```
Serveur WebSocket reçoit l'événement 'send-message'
↓
Sauvegarde en BD via API
↓
Émet 'message-notification' au socketId du vendeur B
```

### 3. Vendeur reçoit la notification
```
Badge 🔔 se met à jour
↓
Toast (notification visuelle) s'affiche en haut à droite
↓
Événement 'message-notification' déclenché
```

## API WebSocket du serveur

### Événements émis par le client

#### `user-connect`
Enregistrer un utilisateur comme connecté
```javascript
socket.emit('user-connect', {
    userId: 1,
    name: "Test Shop"
});
```

#### `send-message`
Envoyer un message
```javascript
socket.on('send-message', {
    from_user_id: 1,
    to_user_id: 2,
    contenu: "Bonjour",
    timestamp: Date.now()
});
```

#### `join-conversation`
Rejoindre une conversation
```javascript
socket.emit('join-conversation', {
    userId: 1,
    otherUserId: 2
});
```

### Événements émis par le serveur

#### `message-notification`
Notification d'un nouveau message
```javascript
socket.on('message-notification', (data) => {
    // data.from_user_id
    // data.to_user_id
    // data.preview
});
```

#### `receive-message`
Réception d'un message
```javascript
socket.on('receive-message', (data) => {
    // data.from_user_id
    // data.to_user_id
    // data.contenu
    // data.id
    // data.timestamp
});
```

#### `user-typing`
Indicateur de frappe
```javascript
socket.on('user-typing', (data) => {
    // data.userId
    // data.isTyping
});
```

## Dépannage

### Le badge ne se met pas à jour
1. **Vérifier que le serveur WebSocket est en cours d'exécution**
   ```bash
   netstat -ano | findstr :3000
   ```
   
2. **Vérifier les logs du serveur**
   - Chercher `✅ Socket connecté`
   - Chercher `🔔 Notification envoyée`

3. **Vérifier la console du navigateur** (F12)
   - Pas d'erreurs de connexion
   - Messages `✅ Connecté au serveur WebSocket`

### Port 3000 déjà utilisé
```bash
# Chercher le processus qui utilise le port
netstat -ano | findstr :3000

# Terminer le processus (remplacer PID par le numéro)
taskkill /PID <PID> /F

# Ou utiliser un port différent
SOCKET_IO_PORT=4000 node websocket-server.js
```

### Erreur CORS
S'assurer que `localhost` et `127.0.0.1:8000` sont dans les origines autorisées:
```javascript
const io = new Server(server, {
    cors: {
        origin: ["http://localhost:3000", "http://localhost:8000", "http://127.0.0.1:8000"],
        methods: ["GET", "POST"],
        credentials: true
    }
});
```

## Monitoring

### Vérifier les utilisateurs connectés
```bash
curl http://127.0.0.1:3000/api/online-users
```

### Status du serveur
```bash
curl http://127.0.0.1:3000/health
```

## Performance

### Optimisations mises en place
- Reconnexion automatique (1-5 secondes)
- Fallback sur polling si WebSocket non disponible
- Mise à jour du badge tous les 30 secondes (fallback)
- Toast cliquable avec lien direct vers messages

### Charge estimée
- Connexions simultanées: ~1000+ (dépend du serveur Node)
- Latence de notification: <100ms
- Bande passante: ~1KB par message

## Production

### Recommandations
1. **Utiliser un load balancer** (nginx)
2. **Adapter Socket.io avec Redis** pour plusieurs instances Node
3. **Monitorer les connexions** avec PM2 ou similar
4. **Configurer HTTPS/WSS** pour les environnements sécurisés

### Exemple avec PM2
```bash
npm install -g pm2
pm2 start websocket-server.js --name "socket-server"
pm2 status
pm2 logs
```

## Architecture complète de monitoring
- Badge: Recharge tous les 30 secondes (fallback)
- WebSocket: Mise à jour instantanée via `message-notification`
- Toast: Notification visuelle avec fermeture auto après 5 secondes
- API: `/vendeur/api/notifications` pour récupérer tous les types de notifications
