# Configuration des Emails avec MAILTRAP 📧

Guide complet pour configurer l'envoi d'emails en développement avec **MAILTRAP**.

## Qu'est-ce que MAILTRAP?

MAILTRAP est un service gratuit qui intercepte les emails en développement. Vous pouvez voir tous les emails envoyés par votre application sans les envoyer vraiment. Parfait pour tester!

---

## 📋 Étapes d'Installation

### 1️⃣ Créer un Compte MAILTRAP

1. Allez sur **[mailtrap.io](https://mailtrap.io/)**
2. Cliquez sur **"Sign Up"**
3. Inscrivez-vous avec votre email (ou connectez-vous avec GitHub)
4. Vérifiez votre email

### 2️⃣ Créer une Boîte Aux Lettres

1. Une fois connecté au dashboard MAILTRAP
2. Cliquez sur **"Create Inbox"**
3. Donnez-lui un nom (ex: "Supply Development")
4. Sélectionnez le type: **"Transactional"**
5. Cliquez **"Create"**

### 3️⃣ Récupérer les Identifiants

1. Ouvrez votre boîte aux lettres créée
2. Cliquez sur l'onglet **"Settings"**
3. Vous verrez une section **"SMTP credentials"** avec:
   - **Host**: `sandbox.smtp.mailtrap.io`
   - **Port**: `2525`
   - **Username**: votre identifiant (UUID)
   - **Password**: votre mot de passe (UUID)

### 4️⃣ Configurer le `.env`

Ouvrez le fichier `.env` à la racine du projet et mettez à jour les variables d'email:

```dotenv
# CONFIGURATION DES EMAILS (MAILTRAP)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username_mailtrap
MAIL_PASSWORD=votre_password_mailtrap
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@supply.local"
MAIL_FROM_NAME="Supply - Boutique Informatique"
```

**Remplacez**:
- `votre_username_mailtrap` par le **Username** de MAILTRAP
- `votre_password_mailtrap` par le **Password** de MAILTRAP

### 5️⃣ Nettoyer les Caches

Après modification du `.env`, exécutez:

```bash
php artisan config:clear
php artisan cache:clear
```

### 6️⃣ Créer un Admin (Important!)

L'application a besoin d'un utilisateur admin pour envoyer les mails de notification. Exécutez:

```bash
php artisan app:create-admin-user
```

**Output attendu**:
```
✅ Admin créé avec succès!
   Email: admin@supply.local
   Mot de passe: admin123456
```

---

## 🧪 Tester les Emails

### Option 1: Via Commande Artisan

```bash
php artisan app:test-email
```

### Option 2: Via Formulaire d'Inscription

1. Allez à `http://127.0.0.1:8000/register`
2. Créez un compte **VENDEUR**
3. Soumettez le formulaire
4. Vous devriez voir les emails dans MAILTRAP:
   - Email de vérification du code
   - Email au admin (nouvelle inscription)

### Option 3: Soumettre des Documents

1. Inscrivez-vous en tant que vendeur
2. Vérifiez votre email
3. Soumettez des documents d'identité
4. MAILTRAP recevra un email au admin

---

## 📧 Emails Disponibles

### 1. Inscription Vendeur
- **Destinataire**: Admin
- **Quand**: Nouveau vendeur s'inscrit
- **Contenu**: Infos du vendeur + lien d'approbation

### 2. Vérification Email
- **Destinataire**: Nouvel utilisateur
- **Quand**: Après inscription
- **Contenu**: Code à 6 chiffres

### 3. Documents Soumis
- **Destinataire**: Admin
- **Quand**: Vendeur soumet documents d'identité
- **Contenu**: Infos + lien vérification docs

### 4. Approbation Vendeur
- **Destinataire**: Vendeur
- **Quand**: Admin approuve le compte
- **Contenu**: Message d'acceptation + lien dashboard

### 5. Rejet Vendeur
- **Destinataire**: Vendeur
- **Quand**: Admin rejette le compte
- **Contenu**: Motif du rejet

---

## 🔍 Consulter les Emails dans MAILTRAP

1. Ouvrez votre **Inbox** MAILTRAP
2. Vous verrez une liste des emails reçus
3. Cliquez sur un email pour voir:
   - **From** (expéditeur)
   - **To** (destinataire)
   - **Subject** (sujet)
   - **Body** (contenu HTML/texte)
   - **Headers** (métadonnées)

---

## ⚙️ Configuration Avancée

### Changer le Domaine D'Envoi

Dans `.env`:
```dotenv
MAIL_FROM_ADDRESS="support@supply.local"
MAIL_FROM_NAME="Support Supply"
```

### Envoyer des Vraies Mails (Production)

Quand vous êtes prêt à passer en production, changez simplement le `.env`:

```dotenv
# Utiliser un service réel (Gmail, Sendgrid, AWS SES, etc)
MAIL_MAILER=gmail
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_app
MAIL_ENCRYPTION=tls
```

---

## ✅ Vérifier que les Emails Fonctionnent

Après configuration, testez avec la commande:

```bash
php artisan app:test-email
```

**Résultat attendu:**
```
📧 Test d'envoi d'email...
   Admin: admin@supply.local
   Vendeur: lolo@gmail.com

✅ Email envoyé avec succès!
   Vérifiez MAILTRAP: https://mailtrap.io/
```

Si vous voyez ce message, **les emails fonctionnent correctement!** 🎉

---

## 🐛 Dépannage

### ❌ "No hint path defined for [mail]"
**Solution**: Exécutez les commandes suivantes:
```bash
php artisan optimize:clear
php artisan app:test-email
```

### ❌ "Connection refused"
Vérifiez que:
- Le `.env` est correctement configuré avec hostname `sandbox.smtp.mailtrap.io`
- Les identifiants MAILTRAP (Username/Password) sont corrects
- Vous avez une connexion Internet
- Le port 2525 n'est pas bloqué par votre pare-feu

### ❌ Les emails n'apparaissent pas
1. Vérifiez que l'admin existe: `php artisan app:create-admin-user`
2. Vérifiez que MAILTRAP est correctement configuré
3. Testez avec: `php artisan app:test-email`
4. Vérifiez les logs: `storage/logs/laravel.log`
5. Clair les caches: `php artisan optimize:clear`

### ❌ Les emails apparaissent dans MAILTRAP mais avec contenu vide
Assurez-vous que le `.env` a `QUEUE_CONNECTION=sync` pour les tests en développement:
```dotenv
QUEUE_CONNECTION=sync
```

---

## ✅ Checklist de Configuration

- [ ] Compte MAILTRAP créé
- [ ] Boîte aux lettres créée
- [ ] Identifiants MAILTRAP copiés
- [ ] Variables `.env` mises à jour
- [ ] `php artisan config:clear` exécuté
- [ ] Admin créé avec `php artisan app:create-admin-user`
- [ ] Test d'email réussi avec `php artisan app:test-email`
- [ ] Emails visibles dans MAILTRAP

---

## 📚 Ressources

- **MAILTRAP**: https://mailtrap.io/
- **Laravel Mail Docs**: https://laravel.com/docs/mail
- **SMTP vs API**: MAILTRAP supporte les deux (on utilise SMTP)

---

## 💡 Conseils Utiles

1. **Laissez MAILTRAP ouvert** pendant le développement pour voir les emails en temps réel
2. **Testez tous les scénarios** (inscription, approbation, rejet, etc)
3. **Vérifiez le HTML** des emails dans MAILTRAP avant de les envoyer en prod
4. **Utilisez les Inbox d'équipe** (MAILTRAP Pro) pour partager les tests avec l'équipe

---

**Besoin d'aide?** Contactez le team ou consultez les logs Laravel: `storage/logs/laravel.log`
