# 🔐 Système de Récupération de Mot de Passe - Supply

## 📋 Vue d'ensemble
Système de réinitialisation de mot de passe sécurisé basé sur Laravel Breeze, utilisant des tokens temporaires et l'envoi d'emails.

---

## 🔄 Flow Utilisateur (Client & Vendeur)

### 1️⃣ **Étape 1 : Demande de réinitialisation**
- L'utilisateur clique sur **"🔑 Mot de passe oublié ?"** sur la page LOGIN
- Redirection vers `/forgot-password`
- Entrée de son adresse email
- Clic sur **"🔐 Envoyer le lien"**

### 2️⃣ **Étape 2 : Réception de l'email**
- Laravel envoie automatiquement un email avec :
  - ✅ Un lien unique contenant un **token sécurisé**
  - ✅ Un message personnalisé
  - ✅ Durée limitée (par défaut 60 minutes)
  - ✅ Lien de réinitialisation `/reset-password/{token}`

### 3️⃣ **Étape 3 : Réinitialisation du mot de passe**
- L'utilisateur clique sur le lien dans l'email
- Redirection vers `/reset-password/{token}`
- Affichage du formulaire avec :
  - Email (pré-rempli)
  - Nouveau mot de passe (avec toggle 👁️)
  - Confirmation mot de passe (avec toggle 👁️)
- Clic sur **"✓ Réinitialiser le mot de passe"**

### 4️⃣ **Étape 4 : Succès et reconnexion**
- Mot de passe mis à jour en base de données (hashé avec Bcrypt)
- Token annulé (inutilisable)
- Redirection automatique vers LOGIN
- Message de succès : "Mot de passe réinitialisé avec succès"
- L'utilisateur peut se connecter avec le nouveau mot de passe

---

## 📄 Pages Créées

| Page | URL | Fichier | Fonctionnalité |
|------|-----|---------|---|
| **Mot de passe oublié** | `/forgot-password` | `resources/views/auth/forgot-password.blade.php` | Entrée email + envoi du lien |
| **Réinitialisation** | `/reset-password/{token}` | `resources/views/auth/reset-password.blade.php` | Nouveau mot de passe + confirmation |

---

## 🛠️ Architecture Technique

### 📍 Routes (routes/auth.php)
```php
// Affichage du formulaire "Mot de passe oublié"
GET /forgot-password              → PasswordResetLinkController@create
// Traitement du formulaire
POST /forgot-password             → PasswordResetLinkController@store

// Affichage du formulaire de réinitialisation
GET /reset-password/{token}       → NewPasswordController@create
// Traitement de la réinitialisation
POST /reset-password              → NewPasswordController@store
```

### 🎮 Contrôleurs

#### `PasswordResetLinkController`
- **create()** : Affiche le formulaire "Mot de passe oublié"
- **store()** : 
  - Valide l'email
  - Appelle `Password::sendResetLink()`
  - Laravel envoie l'email avec le token

#### `NewPasswordController`
- **create()** : Affiche le formulaire de réinitialisation avec token pré-rempli
- **store()** :
  - Valide email + password + token
  - Appelle `Password::reset()`
  - Met à jour le mot de passe (hashé)
  - Annule le token
  - Redirige vers login

### 🔐 Sécurité (Gérée par Laravel)
✅ **Tokens sécurisés** : Générés aléatoirement et stockés en base
✅ **Durée limitée** : Token expires après 60 minutes (configurable)
✅ **One-time use** : Token annulé après utilisation
✅ **Hash sécurisé** : Mot de passe hashé avec Bcrypt (BCRYPT_ROUNDS=12)
✅ **Validation email** : Vérification que l'utilisateur existe
✅ **Rate limiting** : Protection contre les tentatives en masse

### 📧 Configuration Mail

**Fichier** : `config/mail.php` + `.env`

**Développement** (actuellement) :
```env
MAIL_MAILER=log  # Les emails sont stockés dans storage/logs/
```

**Production** (exemple SMTP) :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=noreply@supply.com
MAIL_PASSWORD=xxxxx
MAIL_FROM_ADDRESS=noreply@supply.com
MAIL_FROM_NAME="Supply"
```

---

## 🎨 Design & UX

### Cohérence Visuelle
✅ Même dégradé de fond (`from-gray-50 via-white to-blue-50/30`)
✅ Même header avec logo Supply
✅ Même style de boutons (gradient bleu + hover effect)
✅ Même gestion des erreurs (boîte rouge avec icône)
✅ Responsive design (mobile-first)

### Améliorations UX
- 👁️ Toggle de visibilité pour les mots de passe
- 📧 Message informatif clair sur chaque page
- 🔗 Lien de retour pour annuler l'action
- ✅ Validation en temps réel côté serveur
- 📱 Formulaires compacts (pas de scroll excessive)

---

## 🧪 Tester le Flow Complet

### 1. Test en mode DEV (logs)
```bash
# 1. Aller sur /forgot-password
# 2. Entrer votre email
# 3. Vérifier storage/logs/laravel.log
#    → Vous verrez le contenu de l'email avec le lien
# 4. Copier le lien et le coller dans le navigateur
# 5. Remplir le formulaire de réinitialisation
# 6. Se connecter avec le nouveau mot de passe
```

### 2. Test en production (email réel)
```
Configurer SMTP dans .env
Tester avec un vrai service email (Gmail, Mailtrap, SendGrid, etc.)
```

---

## 🔑 Points Importants pour la Soutenance

### À Dire 📢
> "Nous avons implémenté un système de récupération de mot de passe sécurisé basé sur l'envoi d'un lien par email avec token temporaire, conforme aux standards modernes de sécurité."

### Justifier les Choix 💡
- **Laravel Breeze** : Framework reconnu, sécurité éprouvée
- **Tokens temporaires** : Protection contre les accès non autorisés
- **Email unique** : Même flow pour clients et vendeurs (cohérence)
- **Hash Bcrypt** : Standard industrie pour les mots de passe
- **Rate limiting** : Protection contre les attaques par force brute

### Points Techniques 🔧
1. **Middleware `guest`** : Seuls les utilisateurs non connectés peuvent réinitialiser
2. **Validation côté serveur** : Pas de validation client seule
3. **Email logging** : En dev, les emails sont en logs (pas de serveur SMTP requis)
4. **Transactions de base** : Token → Email → Réinitialisation → Validation

---

## 📊 Table `password_reset_tokens` (auto-créée)

Laravel crée automatiquement cette table via migration :

```sql
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Comportement** :
- Un seul token par email
- Token supprimé après 60 minutes (garbage collection)
- Token invalidé après utilisation

---

## 🚀 Prochaines Étapes (Optionnel)

Si tu veux aller plus loin :

1. **Personnaliser l'email** : Créer une Mailable personnalisée
   - Ajouter le logo Supply
   - Changer le message
   - Ajouter un appel à l'action stylisé

2. **Envoyer des emails réels** : Configurer SMTP
   - Mailtrap (gratuit, recommandé pour dev)
   - SendGrid, AWS SES, etc.

3. **Ajouter des logs** : Tracer chaque tentative de réinitialisation
   - Qui demande, quand, d'où
   - Détection des abus

4. **Améliorer le message** : Notifications toast après envoi du lien

---

## ✅ Checklist Implementation

- [x] Page `/forgot-password` créée et stylisée
- [x] Page `/reset-password/{token}` créée et stylisée
- [x] Routes configurées (GET + POST pour chaque page)
- [x] Contrôleurs en place (PasswordResetLinkController + NewPasswordController)
- [x] Email configuration (log mode pour dev)
- [x] Sécurité : Tokens, Hash, Rate limiting (gérés par Laravel)
- [x] Design moderne et cohérent avec le reste du site
- [x] Toggle de visibilité des mots de passe
- [x] Messages d'erreur et de succès

---

**Créé** : 6 janvier 2026
**Framework** : Laravel 12 + Breeze
**Status** : ✅ Prêt pour production
