# 🚀 Guide Rapide - Démarrer Supply avec ngrok en 5 minutes

## ⚡ Démarrage ultra-rapide

### 1️⃣ Téléchargez et installez ngrok

```powershell
# Manuellement:
# 1. Allez sur https://ngrok.com/download
# 2. Téléchargez le fichier ZIP
# 3. Extrayez ngrok.exe dans C:\Program Files\ngrok\
# 4. Redémarrez PowerShell
```

### 2️⃣ Créez un compte gratuit ngrok

- Inscrivez-vous: https://ngrok.com/
- Vérifiez votre email
- Get your Authtoken: https://dashboard.ngrok.com/auth/your-authtoken

### 3️⃣ Configurez le token dans Supply

Ouvrez le fichier `.env` et trouvez cette ligne:
```env
NGROK_AUTH_TOKEN=3AzB1mNhafmA8sUwJ8Zuqi7EAfu_5SncuJKWqqXWEYPNj1nXS
```

Remplacez par votre token `ngrok_...` depuis le dashboard.

### 4️⃣ Lancez le script de démarrage

Ouvrez **PowerShell** et exécutez:

```powershell
cd D:\wamp\www\Supply
.\start-ngrok-dev.ps1
```

### 5️⃣ Attendez le message vert

```
✅ Serveur Laravel started on http://127.0.0.1:8000
✅ Vite dev server started on http://localhost:5173
✅ ngrok tunnel created: https://your-unique-id.ngrok-free.dev
```

### 6️⃣ Partagez l'URL!

Donnez cette URL à vos collègues:
```
https://your-unique-id.ngrok-free.dev
```

---

## 📍 Vos URLs d'accès

| URL | Sert à |
|-----|--------|
| `https://your-unique-id.ngrok-free.dev` | **Partagez cette URL** aux autres |
| `http://127.0.0.1:8000` | Votre accès local |
| `http://localhost:4040` | Tableau de bord ngrok (débugage) |

---

## ✅ Vérification rapide

Après le démarrage, testez:

1. **Frontend chargé?**
   - Allez sur l'URL ngrok
   - Les images et CSS doivent s'afficher ✅

2. **Uploads fonctionnent?**
   - Ajoutez une catégorie ou un produit
   - L'image doit s'optimiser et s'afficher ✅

3. **Formulaires OK?**
   - Essayez login, register, ajouter au panier
   - Tous les POST doivent fonctionner ✅

4. **Hot reload Vite?**
   - Modifiez un fichier CSS
   - Les changements s'affichent en quelques secondes ✅

---

## ❌ Problèmes courants

### "ngrok command not found"
→ Installez ngrok ou ajoutez-le au PATH

### "Token not set"  
→ Exécutez: `ngrok config add-authtoken YOUR_TOKEN`

### Les assets ne se chargent pas
→ L'URL ngrok a peut-être changé. Le script met à jour `.env` automatiquement.

### Les uploads ne s'affichent pas
→ Exécutez: `php artisan storage:link && php artisan config:cache`

---

## 🛑 Pour arrêter

Appuyez sur **Ctrl + C** dans le terminal PowerShell

L'URL ngrok devient inaccessible immédiatement.

---

## 💡 Prochaines étapes

### Sur une autre machine
- N'importe quel navigateur
- N'importe quel endroit du monde
- Entrez l'URL ngrok fournie
- C'est tout! 🎉

### Développement continu
- Modifiez les fichiers localement
- Vite recharge automatiquement le frontend
- Les uploads fonctionnent normalement
- Les bases de données restent locales

---

## 📚 Guide complet

Pour plus de détails, consultez: [NGROK_SETUP.md](./NGROK_SETUP.md)

---

## 🎯 Vous êtes prêt!

```powershell
cd D:\wamp\www\Supply
.\start-ngrok-dev.ps1
```

Profitez! 🚀
