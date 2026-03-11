# Notifications Temps Réel avec Pusher - Supply Marketplace

## 📋 Vue d'ensemble

Le système de notifications temps réel utilise **Pusher** pour notifier instantanément les utilisateurs (clients et vendeurs) des événements importants:

- 📦 **Nouvelles commandes** - Vendeurs alertés en temps réel
- 🔄 **Changements de statut** - Clients notifiés des mises à jour
- 💬 **Nouveaux messages** - Notifications de messages privés
- ✅ **Approbation vendeur** - Notification d'acceptation de compte

---

## 🚀 Configuration Requise

### 1. Compte Pusher

1. Créez un compte sur [pusher.com](https://pusher.com)
2. Créez une nouvelle application (app)
3. Notez vos identifiants:
   - **App ID** - Identifiant unique
   - **Key** - Clé publique
   - **Secret** - Clé secrète (CONFIDENTIELL)
   - **Cluster** - Région (ex: mt1)

### 2. Variables d'environnement

Mettez à jour votre `.env`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=votre_app_id
PUSHER_APP_KEY=votre_key
PUSHER_APP_SECRET=votre_secret
PUSHER_HOST=api-mt1.pusher.com
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_REGION=mt1
```

### 3. Installer les dépendances

```bash
composer require pusher/pusher-php-server
npm install --save-dev laravel-echo pusher-js
```

---

## 📂 Fichiers Créés

### Backend

1. **Événements Broadcasting** (`app/Events/`)
   - `OrderCreated.php` - Nouvelle commande
   - `OrderStatusChanged.php` - Changement de statut
   - `NewMessage.php` - Nouveau message
   - `VendorApprovalStatusChanged.php` - Approbation vendeur

2. **Contrôleur** (`app/Http/Controllers/`)
   - `RealtimeNotificationController.php` - API notifications

3. **Configuration** (`config/`)
   - `broadcasting.php` - Config Pusher

### Frontend

1. **JavaScript** (`resources/js/`)
   - `pusher-notifications.js` - Client Pusher (240+ lignes)

2. **Layouts/Views**
   - `resources/views/layouts/app.blade.php` - Inclut le script Pusher

---

## 🔌 Canaux Privés

Le système utilise des **canaux privés** pour la sécurité:

```
- vendor-notifications.{user_id} → Notifications pour vendeur
- user-notifications.{user_id} → Notifications pour client
- user-messages.{user_id} → Notifications de messages
- vendor-approval.{user_id} → Approbations vendeur
```

La route `/broadcasting/auth` de Laravel gère l'authentification automatiquement.

---

## 📡 Événements & Déclencheurs

### 1. Nouvelle Commande
**Déclenché:** Quand un client passe une commande  
**Récepteur:** Vendeur concerné  
**Événement:** `OrderCreated` → Événement `order.created`

```php
// app/Http/Controllers/CommandeController.php - ligne 224
Event::dispatch(new OrderCreated($commande));
```

### 2. Changement de Statut
**Déclenché:** Quand un vendeur met à jour le statut(confirmée, expédiée, livrée)  
**Récepteur:** Client + Vendeur  
**Événement:** `OrderStatusChanged` → Événement `order.status-changed`

```php
// app/Http/Controllers/CommandeController.php - updateCommandeStatus()
Event::dispatch(new OrderStatusChanged($commande, $oldStatus, $newStatus));
```

### 3. Nouveau Message
**Déclenché:** Quand un utilisateur envoie un message  
**Récepteur:** Destinataire du message  
**Événement:** `NewMessage` → Événement `message.received`

```php
// À intégrer dans MessageController
Event::dispatch(new NewMessage($message));
```

### 4. Approbation Vendeur
**Déclenché:** Quand un admin approuve/rejette un vendeur  
**Récepteur:** Vendeur concerné  
**Événement:** `VendorApprovalStatusChanged`

```php
// À intégrer dans VendeurController (admin)
Event::dispatch(new VendorApprovalStatusChanged($user, 'approved', $reason));
```

---

## 🧪 Test du Système

### Test 1: Initialisation
1. Ouvrez la console du navigateur (F12)
2. Vérifiez: `console.log('RealtimeNotications' en window)`
3. Devrait afficher: `class RealtimeNotifications`

### Test 2: Notification Test
```bash
# Dans tinker
php artisan tinker
>>> event(new \App\Events\OrderCreated(\App\Models\Commande::first()))
```

Une notification devrait apparaître dans le coin haut-droit du navigateur.

### Test 3: Flux Complet
1. Créez deux comptes (Client + Vendeur)
2. Client: Passe une commande
3. Vendeur: Reçoit notification en temps réel
4. Vendeur: Met à jour le statut
5. Client: Reçoit notification de mise à jour

---

## 🎨 Personnalisation

### Changer les Sons
Modifiez dans `resources/js/pusher-notifications.js`:

```javascript
playSound() {
    oscillator.frequency.value = 800; // Modifiez la fréquence (Hz)
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime); // Volume
}
```

### Changer les Icônes
```javascript
getIcon(type) {
    const icons = {
        'order.created': '📦', // Modifiez l'emoji
        'order.status-changed': '🔄',
        'message.received': '💬',
        'vendor.approval': '✅'
    };
}
```

### Changer la Durée d'Affichage
```javascript
// Dans showNotification()
setTimeout(() => {
    if (document.getElementById(notification.id)) {
        document.getElementById(notification.id).remove();
    }
}, 6000); // Changez 6000 (ms) par votre valeur
```

---

## 🔐 Sécurité

### Authentification Privée
- Les canaux privés demandent l'authentification utilisateur
- Laravel valide automatiquement via `broadcastOn()`
- Seul l'utilisateur approprié reçoit la notification

### Secret Pusher
- Ne commitez JAMAIS `PUSHER_APP_SECRET` dans Git
- Utilisez `.env.example` pour les exemples
- Régénérez les secrets si compromis

---

## 🐛 Dépannage

### "Pusher is not defined"
→ Vérifiez que `pusher.min.js` se charge depuis le CDN

### Pas de notification
1. Vérifiez les credentials `.env`
2. Ouvrez console: `window.RealtimeNotifications.isInitialized`
3. Vérifiez que l'utilisateur est connecté
4. Vérifiez les logs: `tail -f storage/logs/laravel.log`

### Erreur "Invalid channel"
→ Assurez-vous que l'utilisateur est authentifié sur `/broadcasting/auth`

---

## 📊 Monitoring Pusher

Dans le dashboard Pusher:
1. Onglet "Connections" → Users connectés
2. Onglet "Messages" → Événements en temps réel
3. Onglet "Debug Console" → Test d'événements

---

## ✅ Checklist Déploiement

- [ ] Compte Pusher créé
- [ ] Credentials dans `.env`
- [ ] `.env.example` mise à jour (sans secrets)
- [ ] Dépendances installées (`composer require`, `npm install`)
- [ ] Tests locaux réussis
- [ ] Webhook Pusher configuré (optionnel)
- [ ] Logs vérifiés après déploiement

---

## 💡 Prochaines Améliorations

- [ ] Historique des notifications (sauvegarde DB)
- [ ] Centre de notifications (page dédiée)
- [ ] Préférences notifications (on/off par type)
- [ ] Email de secours si Pusher échoue
- [ ] Notifications desktop (Web Push API)
- [ ] Notification toast intégrée à la navbar

---

## 📚 Ressources

- [Pusher Documentation](https://pusher.com/docs)
- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)
- [Pusher Laravel Integration](https://pusher.com/docs/channels/using_channels/laravel)
