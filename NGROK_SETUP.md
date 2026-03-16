# 🌐 Guide Complet - Supply avec ngrok

## Vue d'ensemble

Dieser guide explique comment utiliser **ngrok** pour rendre votre site Supply accessible sur d'autres machines à travers Internet, tout en pouvant effectuer toutes les opérations normales (uploads, formulaires, etc.).

---

## 🎯 Avant de commencer

### Prérequis
- ✅ PHP 8.3+ (WAMP configuré)
- ✅ Composer (dépendances installées)
- ✅ Node.js & npm (pour Vite)
- ✅ ngrok (téléchargeable gratuitement)

### Architecture du tunnel ngrok

```
┌─────────────────────────────────────────────────────────────────┐
│                    INTERNET (Autres machines)                   │
│                                                                 │
│  https://your-unique-id.ngrok-free.dev  ←→  ngrok tunnel    │
└─────────────────────────────────────────────────────────────────┘
                              ↓
                    🌐 ngrok client
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                     VOTRE MACHINE (LOCAL)                       │
│                                                                 │
│  ├─ Laravel Server (127.0.0.1:8000)                            │
│  ├─ Vite Dev Server (localhost:5173)                           │
│  ├─ MySQL (127.0.0.1:3306)                                     │
│  └─ File Storage (local disk)                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📦 Installation de ngrok

### Option 1: Télécharger directement (Recommandé)

1. **Téléchargez ngrok**
   - Allez sur: https://ngrok.com/download
   - Téléchargez la version Windows (64-bit)

2. **Extrayez le fichier**
   - Clic droit sur `ngrok-v3-windows-amd64.zip` → Extraire
   - Créez un dossier `C:\Program Files\ngrok` (si absent)
   - Déplacez `ngrok.exe` dans ce dossier

3. **Ajoutez ngrok au PATH (pour utiliser partout)**
   - Appuyez sur `Win + X` → Paramètres système avancés
   - Cliquez sur **Variables d'environnement**
   - Sous "Variables utilisateur", cliquez **Nouveau**
   - Nom: `Path`, Valeur: `C:\Program Files\ngrok`
   - Cliquez **OK** et redémarrez PowerShell

4. **Vérifiez l'installation**
   ```powershell
   ngrok --version
   ```
   Devrait afficher: `ngrok version X.X.X`

### Option 2: Via Chocolatey (si installé)

```powershell
choco install ngrok
```

### Option 3: Placer ngrok dans le répertoire Supply

Vous pouvez aussi placer `ngrok.exe` directement dans `D:\wamp\www\Supply\`

---

## 🔐 Configuration de l'authentification ngrok

### Étape 1: Créer un compte ngrok gratuit

1. Allez sur https://ngrok.com/
2. Cliquez **Sign Up** (en haut à droite)
3. Créez un compte avec votre email
4. Vérifiez l'email (vérification requise)

### Étape 2: Récupérer votre token

1. Connectez-vous à https://dashboard.ngrok.com/
2. Allez dans **Auth** (dans le menu de gauche)
3. Cliquez **Copy** à côté de votre Authtoken
4. Mémorisez ce token (format: `ngrok_...`)

### Étape 3: Configurer le token dans Supply

**Option A: Via le script PowerShell (Recommandé)**

1. Ouvrez PowerShell
2. Naviguez vers le dossier Supply:
   ```powershell
   cd D:\wamp\www\Supply
   ```
3. Exécutez le script de setup:
   ```powershell
   .\setup-ngrok-auth.ps1
   ```
4. Le script récupère le token du `.env` et le configure automatiquement

**Option B: Manuellement**

1. Ouvrez le fichier `.env` dans VS Code
2. Trouvez la ligne: `NGROK_AUTH_TOKEN=...`
3. Remplacez par votre token:
   ```
   NGROK_AUTH_TOKEN=ngrok_1234567890abcdefghijklmnop
   ```
4. Manuellement dans PowerShell:
   ```powershell
   ngrok config add-authtoken YOUR_TOKEN_HERE
   ```

---

## 🚀 Démarrage avec ngrok (Méthode Simple)

### Méthode 1: Script automatique (Recommandé)

1. **Ouvrez PowerShell en tant qu'administrateur**

2. **Naviguez vers Supply**
   ```powershell
   cd D:\wamp\www\Supply
   ```

3. **Lancez le script de démarrage**
   ```powershell
   .\start-ngrok-dev.ps1
   ```

4. **Le script:**
   - ✅ Nettoie les caches Laravel
   - ✅ Démarre le serveur Laravel (port 8000)
   - ✅ Démarre Vite dev server (avec hot reload)
   - ✅ Configure et lance ngrok
   - ✅ Récupère l'URL ngrok publique
   - ✅ Met à jour `.env` avec la nouvelle URL
   - ✅ Configure Vite HMR pour le tunnel ngrok
   - ✅ Affiche toutes les URLs d'accès

5. **Attendez le message:**
   ```
   ╔════════════════════════════════════════════════════════════════╗
   ║     🎉 SUPPLY EST PRÊT POUR LA PRODUCTION!                     ║
   ╚════════════════════════════════════════════════════════════════╝
   ```

### Méthode 2: Démarrage manuel (si script ne fonctionne pas)

**Terminal 1 - Laravel Server**
```powershell
cd D:\wamp\www\Supply
php artisan serve --port=8000
```

**Terminal 2 - Vite Dev Server**
```powershell
cd D:\wamp\www\Supply
npm run dev
```

**Terminal 3 - ngrok Tunnel**
```powershell
ngrok http 8000 --region eu
```

Quand ngrok démarre, il affiche:
```
Session Status                 online
Account                        your-email@example.com
Version                        3.0.0
Region                         Europe (eu)
Latency                        20ms
Web Interface                  http://127.0.0.1:4040
Forwarding                     https://your-unique-id.ngrok-free.dev -> http://localhost:8000
```

Copiez l'URL `https://your-unique-id.ngrok-free.dev` et **mettez à jour `.env`**:
```env
APP_URL=https://your-unique-id.ngrok-free.dev
```

---

## 📍 Accéder au site

### URLs disponibles

| URL | Accès | Utilisation |
|-----|-------|------------|
| `https://your-unique-id.ngrok-free.dev` | 🌍 Internet entier | **Site public** pour autres machines |
| `http://127.0.0.1:8000` | 🏠 Votre machine | Développement local |
| `http://localhost:4040` | 🔧 Votre machine | Dashboard ngrok (inspecter requêtes) |

### Exemple - Accès depuis une autre machine

Depuis **n'importe quel navigateur, n'importe où dans le monde**:
```
https://your-unique-id.ngrok-free.dev
```

---

## ✅ Vérification que tout fonctionne

### 1. Page d'accueil

Allez sur `https://your-unique-id.ngrok-free.dev`
- Les assets CSS/JS doivent se charger ✅
- Les images doivent s'afficher ✅
- Pas d'erreur CORS ✅

### 2. Formulaires et uploads

Testez un upload:
- Allez sur la page produits ou catégories
- Uploadez une image
- L'image doit être optimisée et stockée ✅
- L'image doit s'afficher correctement ✅

### 3. CSRF Token

Les formulaires POST doivent fonctionner:
- Login / Register ✅
- Ajouter au panier ✅
- Panier (ajouter/supprimer) ✅

### 4. Hot reload Vite

Quand vous modifiez le code:
- Les fichiers CSS/JS se rechargent automatiquement ✅
- Pas besoin de rafraîchir manuellement ✅

### 5. Dashboard ngrok

Allez sur `http://localhost:4040` pour:
- Voir toutes les requêtes HTTP ✅
- Inspecter les headers, body, réponses ✅
- Tester les endpoints API ✅

---

## ⚠️ Problèmes courants et solutions

### Problème 1: "ngrok command not found"

**Cause**: ngrok n'est pas dans le PATH

**Solutions**:
```powershell
# Option 1: Utilisez le chemin complet
C:\Program Files\ngrok\ngrok.exe http 8000

# Option 2: Relancez PowerShell après avoir ajouté au PATH
# (nécessite redémarrage de Windows parfois)

# Option 3: Vérifiez l'installation
Get-Command ngrok
```

### Problème 2: "Token not set"

**Cause**: Le token ngrok n'est pas configuré

**Solution**:
```powershell
ngrok config add-authtoken YOUR_TOKEN_HERE
# Vérifiez: ngrok status
```

### Problème 3: Assets se chargent mais cassent le design

**Cause**: Vite HMR mal configuré

**Solution**: Le script `start-ngrok-dev.ps1` configure automatiquement cela. Si manuel:

Dans `.env`:
```env
VITE_HMR_HOST=your-unique-id.ngrok-free.dev
VITE_HMR_PROTOCOL=https
VITE_HMR_PORT=443
```

### Problème 4: Les fichiers uploadés ne s'affichent pas

**Cause**: Le chemin des fichiers pointe vers localhost

**Solution**: 
- S'assure que `APP_URL` est à jour dans `.env`
- Laravel générera automatiquement les bonnes URLs
- Si besoin, exécutez:
  ```powershell
  php artisan storage:link
  php artisan config:cache
  ```

### Problème 5: Erreur "mixed content"

**Cause**: Assets chargés en HTTP depuis HTTPS

**Solution**: 
- Vérifiez que Vite utilise HTTPS:
  ```env
  VITE_HMR_PROTOCOL=https
  ```
- Le script `start-ngrok-dev.ps1` le fait automatiquement

### Problème 6: ngrok se ferme rapidement ou ngrok url change

**Normal avec ngrok gratuit!**
- L'URL gratuite change chaque fois que vous redémarrez
- Le script `start-ngrok-dev.ps1` met à jour automatiquement `.env`
- Pour une URL fixe, achetez un domaine personnalisé ngrok

---

## 🔄 Workflow de développement avec ngrok

### Chaque jour

1. **Démarrez le script**
   ```powershell
   cd D:\wamp\www\Supply
   .\start-ngrok-dev.ps1
   ```

2. **Partagez l'URL ngrok**
   - Message à un collègue: "Voici l'URL: https://your-id.ngrok-free.dev"
   - L'URL fonctionne jusqu'à ce que vous arrêtiez ngrok

3. **Développez normalement**
   - Modifiez les fichiers Laravel, Blade, CSS, JS
   - Vite hot reload fonctionne automatiquement
   - Upload, formulaires, tous les opérations fonctionnent

4. **Arrêtez quand vous avez fini**
   - Appuyez sur `Ctrl + C` dans le terminal ngrok
   - L'URL devient inaccessible
   - Les othersma machines perdent l'accès

### Si l'URL ngrok change

- Le script `start-ngrok-dev.ps1` met à jour `/env` automatiquement ✅
- Vous verrez: `✅ APP_URL mise à jour: https://new-url.ngrok-free.dev`
- Partagez la nouvelle URL

---

## 🛡️ Sécurité et meilleures pratiques

### ✅ Sûr avec ngrok gratuit
- ngrok utilise HTTPS (chiffrage)
- Les commandes ngrok dans le script filtrent le trafic
- Votre base de données est locale (jamais exposée)

### ⚠️ À faire
- ✅ Gardez `APP_DEBUG=true` local (script le gère)
- ✅ Testez en environnement local d'abord
- ✅ Vérifiez les fichiers uploadés pour malware
- ✅ Limitez l'accès avec un mot de passe si sensible

### ❌ À ne pas faire
- ❌ Ne partagez pas l'URL sur Internet public longtemps
- ❌ N'exposez pas de données sensibles (tokens, secrets)
- ❌ Ne faites pas de tunnels vers une DB directement
- ❌ Ne mettez pas en production avec un tunnel gra tuit

---

## 📊 Performance avec ngrok

### Vitesse
- Latence ajoutée: 20-50ms par requête (normal)
- Uploads: Supportés entièrement
- Streaming: Fonctionne
- WebSockets: Fonctionne avec ngrok (payant)

### Limitations ngrok gratuit
- Bande passante: Entièrement disponible (✅)
- Connexions simultanées: ~10-20 (✅ pour dev)
- Uptime: Excellent (✅)
- URL personnalisée: Payant (plan Pro: $10/mois)

---

## 🆘 Support et troubleshooting

### Logs utiles
```powershell
# Voir les logs Laravel
php artisan tinker
// ...

# Voir les logs Vite
# Regardez la console du terminal npm run dev

# Dashboard ngrok - Inspecter chaque requête
http://localhost:4040
```

### Besoin d'aide?
1. Vérifiez les logs (voir ci-dessus)
2. Consultez: https://ngrok.com/docs
3. Vérifiez le fichier `.env` - APP_URL doit être l'URL ngrok

---

## 📋 Checklist de démarrage

- [ ] ngrok est installé (`ngrok --version`)
- [ ] Token ngrok configuré (`ngrok status`)
- [ ] `.env` contient `NGROK_AUTH_TOKEN`
- [ ] Composer dependencies installées (`composer install --ignore-platform-reqs`)
- [ ] npm dependencies installées (`npm install`)
- [ ] `.env` contient une `APP_URL` valide (ou sera mise à jour par le script)
- [ ] Ports disponibles: 8000 (Laravel), 5173 (Vite), 4040 (ngrok)
- [ ] MySQL (WAMP) est en cours d'exécution

---

## 🎓 Ressources utiles

- **ngrok Documentation**: https://ngrok.com/docs
- **Laravel Documentation**: https://laravel.com/docs
- **Vite Documentation**: https://vitejs.dev/
- **Votre tableau de bord ngrok**: https://dashboard.ngrok.com/

---

## ✨ Vous êtes prêt!

Exécutez:
```powershell
cd D:\wamp\www\Supply
.\start-ngrok-dev.ps1
```

Et partagez l'URL générée! 🌐
