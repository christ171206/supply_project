# 🚀 Messagerie en Temps Réel avec Socket.io

## Installation

### 1. Installer les dépendances globales (une fois)

```bash
npm install express socket.io cors
```

Ou, utiliser le package-socketio.json :

```bash
npm install --save-dev nodemon
```

### 2. Démarrer le serveur WebSocket

Il faut avoir **deux terminaux** ouverts :

#### Terminal 1 : Serveur PHP Laravel
```bash
cd d:\wamp\www\Supply
php artisan serve
# Accès: http://127.0.0.1:8000
```

#### Terminal 2 : Serveur WebSocket Node.js
```bash
cd d:\wamp\www\Supply
node websocket-server.js
# Le serveur écoute sur http://localhost:3000
```

Ou avec nodemon pour le développement (auto-rechargement) :
```bash
npx nodemon websocket-server.js
```

## 🎯 Fonctionnalités

✅ **Messagerie en temps réel** - Les messages s'affichent instantanément  
✅ **Indicateur "en train de taper"** - Savoir quand quelqu'un tape  
✅ **Notifications** - Recevoir des notifications push  
✅ **Utilisateurs en ligne** - Voir qui est connecté  
✅ **Historique des messages** - Les anciens messages sont chargés depuis la BD  
✅ **Webhooks** - Intégration avec Laravel  

## 📡 Architecture

```
┌─────────────────┐
│ Client Browser  │
│  (Socket.io)    │
└────────┬────────┘
         │
         ├─── HTTP ───────────────┐
         │                        │
         └─ WebSocket ─┐          │
                       │          │
                  ┌────▼──┐   ┌──▼──────┐
                  │ Node  │   │ Laravel │
                  │Socket │   │ (DB)    │
                  │ Server│   └─────────┘
                  └───────┘
```

## 🔧 Configuration

Le serveur WebSocket écoute sur **port 3000** par défaut.

Pour changer le port, modifiez le fichier `websocket-server.js` :

```javascript
const PORT = process.env.PORT || 3000; // Changer 3000 ici
```

Ou via variable d'environnement :
```bash
set PORT=8080
node websocket-server.js
```

## 📱 Interface utilisateur

### Dans la page des messages (`/messages`)

1. **Sidebar** - Liste des conversations
2. **Chat** - Zone des messages avec temps réel
3. **Formulaire** - Envoyer des messages
4. **Indicateurs** - Voir qui tape un message

## 🔐 Sécurité

Pour la production, ajouter :

1. **Authentification Token** - Valider les utilisateurs via JWT
2. **CORS sécurisé** - Limiter aux domaines autorisés
3. **Rate limiting** - Limiter les messages par utilisateur
4. **Validation** - Valider les données côté serveur

Exemple :
```javascript
const io = socketIO(server, {
    cors: {
        origin: "https://votredomaine.com",
        methods: ["GET", "POST"]
    }
});
```

## 🐛 Dépannage

**Erreur: "Cannot find module 'express'"**
```bash
npm install express socket.io cors
```

**Le serveur WebSocket ne démarre pas**
```bash
# Vérifier que le port 3000 est libre
netstat -ano | findstr :3000
# Ou utiliser un autre port
set PORT=4000
node websocket-server.js
```

**Les messages ne s'affichent pas en temps réel**
- Vérifier que les deux serveurs (Laravel + Node) sont en cours d'exécution
- Vérifier la console navigateur (F12) pour les erreurs
- Vérifier que vous êtes connecté (au moins 2 utilisateurs)

## 📊 Logs

Le serveur affiche des logs utiles :
```
✅ Utilisateur connecté: abc123
📱 John Doe (ID: 1) connecté
📨 Message envoyé de 1 à 2
❌ John Doe déconnecté
```

## 🚀 Déploiement

Pour déployer en production :

1. Installer Node.js sur le serveur
2. Installer PM2 pour gérer le processus
   ```bash
   npm install -g pm2
   pm2 start websocket-server.js --name "supply-socketio"
   pm2 startup
   pm2 save
   ```
3. Configurer Nginx/Apache pour rediriger WebSocket

Exemple nginx.conf :
```nginx
upstream socketio {
    server localhost:3000;
}

server {
    listen 80;
    server_name votre-domaine.com;

    location /socket.io {
        proxy_pass http://socketio;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }
}
```

## 📚 Ressources

- [Socket.io Docs](https://socket.io/docs/)
- [Node.js](https://nodejs.org/)
- [Express.js](https://expressjs.com/)
