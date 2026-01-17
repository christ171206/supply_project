# 📱 Guide Complet de Test - Messagerie en Temps Réel

## 🚀 Étape 1 : Lancer les serveurs

### Terminal 1 : Serveur Laravel
```bash
cd d:\wamp\www\Supply
php artisan serve
```
**Résultat:** ✅ `http://127.0.0.1:8000`

### Terminal 2 : Serveur WebSocket Node.js
```bash
cd d:\wamp\www\Supply
node websocket-server.js
```
**Résultat:** ✅ `🚀 Serveur WebSocket lancé sur le port 3000`

---

## 👥 Étape 2 : Créer au moins 2 comptes utilisateurs

### Option A : Utiliser les comptes existants
Vérifier que vous avez au moins 2 utilisateurs en base de données :
```bash
# Dans un navigateur, aller à:
http://127.0.0.1:8000/register
```

### Option B : Créer rapidement avec Tinker
```bash
php artisan tinker
# Puis :
App\Models\User::create(['name' => 'Alice', 'email' => 'alice@test.com', 'password' => bcrypt('password')]);
App\Models\User::create(['name' => 'Bob', 'email' => 'bob@test.com', 'password' => bcrypt('password')]);
exit
```

---

## 🧪 Étape 3 : Tester la Messagerie

### Scénario 1 : Test Simple (Même navigateur)

1. **Ouvrir deux onglets**
   - Onglet 1: `http://127.0.0.1:8000`
   - Onglet 2: `http://127.0.0.1:8000`

2. **Se connecter avec deux comptes différents**
   - Onglet 1: Se connecter avec `alice@test.com`
   - Onglet 2: Se connecter avec `bob@test.com`

3. **Aller à la messagerie**
   - Cliquer sur "💬 Messages" dans le header
   - Les deux pages se chargent

4. **Tester l'envoi de message**
   - Onglet 1 (Alice) → Cliquer sur Bob dans la sidebar
   - Taper "Bonjour Bob !"
   - Cliquer "Envoyer"
   - ✅ Le message apparaît immédiatement dans l'onglet Alice

5. **Vérifier la réception**
   - Onglet 2 (Bob) → Cliquer sur Alice
   - ✅ Le message devrait apparaître en temps réel

---

### Scénario 2 : Test avec Deux Navigateurs (Plus Réaliste)

**Navigateur 1 (Chrome)**
```
http://127.0.0.1:8000/login
Email: alice@test.com
Password: password
```
Puis: `http://127.0.0.1:8000/messages`

**Navigateur 2 (Firefox ou Edge)**
```
http://127.0.0.1:8000/login
Email: bob@test.com
Password: password
```
Puis: `http://127.0.0.1:8000/messages`

Envoyer des messages d'un navigateur à l'autre → ✅ Ils arrivent en temps réel

---

### Scénario 3 : Tester depuis la Page Produit

1. Se connecter avec Alice
2. Aller sur n'importe quel produit: `http://127.0.0.1:8000/produits/1`
3. Scroller jusqu'à "Contacter le Vendeur"
4. Remplir le formulaire:
   - **Sujet:** "Question sur le produit"
   - **Message:** "Ça marche?"
   - Cliquer "📤 Envoyer le Message"
5. ✅ Vous devriez être redirigé vers `/messages`
6. ✅ Un nouveau message devrait être envoyé au vendeur

---

## 🎯 Fonctionnalités à Tester

### ✅ 1. Envoi de Message
**Étapes:**
1. Alice écrit "Salut Bob!" dans la textarea
2. Alice clique sur "Envoyer"

**Résultat Attendu:**
- ✅ Message apparaît immédiatement dans le chat Alice
- ✅ Message apparaît en temps réel dans le chat Bob
- ✅ Le message s'ajoute à la base de données

**Vérifier:** Rafraîchir la page → les messages doivent rester

---

### ✅ 2. Indicateur "En train de taper"

**Étapes:**
1. Alice commence à taper "Bonjour..."
2. Bob regarde l'onglet des messages avec Alice

**Résultat Attendu:**
- ✅ Bob voit: "Alice est en train de taper..."
- ✅ Le texte disparaît après 1 seconde d'inactivité

**Vérifier:** Arrêter de taper pendant 1 sec → le texte disparaît

---

### ✅ 3. Badge de Messages Non Lus

**Étapes:**
1. Alice et Bob sont connectés
2. Alice envoie un message à Bob
3. Bob accède à la page des messages
4. Regarder le header: "💬 Messages"

**Résultat Attendu:**
- ✅ Un badge rouge avec "1" apparaît sur le lien Messages
- ✅ Le badge disparaît quand le message est lu

---

### ✅ 4. Notifications du Navigateur

**Étapes:**
1. Autoriser les notifications (popup du navigateur)
2. Alice envoie un message à Bob
3. Bob a onglet des messages fermé ou en arrière-plan

**Résultat Attendu:**
- ✅ Une notification "Message de Alice" apparaît
- ✅ Elle contient le message

---

### ✅ 5. Historique des Messages

**Étapes:**
1. Alice et Bob échangent 5 messages
2. Alice rafraîchit la page
3. Alice va sur `/messages/bob`

**Résultat Attendu:**
- ✅ Les 5 anciens messages sont affichés
- ✅ Mélange des anciens messages (BD) + nouveaux (Socket.io)

---

## 🔍 Vérifier dans la Console

Ouvrir **F12 → Console** pour voir les logs:

**Si tout fonctionne:**
```
✅ Connecté au serveur WebSocket
📨 Message reçu: {fromUserId: 1, message: "Bonjour", ...}
```

**En cas d'erreur:**
```
❌ Erreur de connexion: Error: Connection refused
```

Vérifier:
- ✅ Port 3000 est libre
- ✅ Serveur WebSocket est lancé
- ✅ Pas de firewall bloquant

---

## 📊 Logs du Serveur WebSocket

Dans le terminal avec le serveur Node.js, vous devriez voir:

```
✅ Utilisateur connecté: abc123def456
📱 Alice (ID: 1) connecté
📨 Message envoyé de 1 à 2
⚠️  Utilisateur 2 n'est pas en ligne
❌ Bob déconnecté
```

---

## 🐛 Dépannage

### ❌ "Socket.io n'est pas défini"
**Solution:**
```bash
npm install socket.io-client
npm run build
```

### ❌ "Impossible de se connecter au WebSocket"
**Solutions:**
1. Vérifier que le serveur Node.js est lancé
2. Vérifier le port: `netstat -ano | findstr :3000`
3. Relancer: `node websocket-server.js`

### ❌ Les messages ne s'affichent pas en temps réel
**Solutions:**
1. Ouvrir F12 (console navigateur)
2. Vérifier s'il y a des erreurs
3. Vérifier les logs du serveur WebSocket
4. Rafraîchir la page: `F5`

### ❌ Les messages arrivent mais ne s'enregistrent pas en BD
**Vérifier:**
```bash
php artisan tinker
App\Models\Message::count()
exit
```

Si le nombre n'augmente pas, le formulaire ne soumet peut-être pas via HTTP.

---

## 📈 Test de Charge

Pour tester avec plus d'utilisateurs:

```bash
# Créer 10 utilisateurs
php artisan tinker
for ($i = 1; $i <= 10; $i++) {
    App\Models\User::create([
        'name' => "User $i",
        'email' => "user$i@test.com",
        'password' => bcrypt('password')
    ]);
}
exit
```

Puis ouvrir plusieurs navigateurs/onglets pour tester la performance.

---

## ✅ Checklist de Test Complet

- [ ] Serveur Laravel lancé (port 8000)
- [ ] Serveur WebSocket lancé (port 3000)
- [ ] Au moins 2 utilisateurs créés
- [ ] Connecté avec 2 comptes différents
- [ ] Messagerie accessible (`/messages`)
- [ ] Envoi de message fonctionne
- [ ] Message reçu en temps réel
- [ ] Indicateur "en train de taper" visible
- [ ] Badge de messages non lus affiché
- [ ] Messages persiste après rafraîchissement
- [ ] Ancien historique chargé au démarrage
- [ ] Notifications du navigateur fonctionnent
- [ ] Console sans erreurs (F12)

---

## 📞 Support

Si ça ne marche pas:

1. **Vérifier les logs:**
   - Terminal Node.js
   - Console navigateur (F12)
   - Laravel logs: `storage/logs/`

2. **Recommencer:**
   ```bash
   # Terminal 1
   php artisan serve

   # Terminal 2 (nouveau)
   node websocket-server.js
   ```

3. **Relancer les assets:**
   ```bash
   npm run build
   ```

4. **Vider le cache:**
   ```bash
   php artisan optimize:clear
   ```
