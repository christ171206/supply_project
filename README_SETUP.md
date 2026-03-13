# Supply - Boutique Informatique 🛒

**Supply** est une plateforme e-commerce minimaliste et élégante construite avec **Laravel 12**, **Tailwind CSS**, et **Vite**. Elle permet aux vendeurs et clients de transacter en ligne avec un design épuré.

## 🚀 Quick Start

### Installation

```bash
# Cloner le projet
git clone <votre-repo>
cd Supply

# Installer les dépendances
composer install
npm install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Migrer la base de données
php artisan migrate --seed

# Compiler les assets
npm run build

# Lancer le serveur
php artisan serve
```

### Configuration Emails (MAILTRAP) 📧

**IMPORTANT**: Pour que les emails fonctionnent, vous devez configurer MAILTRAP.

👉 **[Lire le guide complet MAILTRAP_SETUP.md](./MAILTRAP_SETUP.md)**

Résumé rapide:
```bash
# 1. Créer un compte sur https://mailtrap.io/
# 2. Mettre à jour .env avec vos identifiants MAILTRAP
# 3. Créer un utilisateur admin
php artisan app:create-admin-user

# 4. Tester l'envoi d'email
php artisan app:test-email
```

✅ **Les emails sont maintenant entièrement fonctionnels!** Une fois MAILTRAP configuré, tous les emails (inscription, vérification, notifications admin, approbations, etc.) seront envoyés correctement.

## 📋 Fonctionnalités principales

- ✅ **Inscription vendeur en 3 étapes** (inscription → vérif email → documents)
- ✅ **Soumission documents d'identité** (recto/verso)
- ✅ **Notifications admin** (base de données + emails)
- ✅ **Système d'approbation** (vendeur ne peut vendre que si approuvé)
- ✅ **Boutiques vendeur** (chacun gère ses produits)
- ✅ **Commandes et paiements** (Stripe intégré)
- ✅ **Système de courrier** (messages entre vendeurs et clients)
- ✅ **Avis produits** (notation 5 étoiles)
- ✅ **PWA** (installable sur mobile)

## 📁 Structure

```
Supply/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Mail/              # Classes d'email
│   ├── Models/
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── emails/        # Templates emails
│   │   ├── vendor/
│   │   └── auth/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── auth.php
├── MAILTRAP_SETUP.md      # 👈 Guide configuration emails
└── README_SETUP.md        # Ce fichier
```

## 🔑 Rôles & Permissions

### Client
- Parcourir produits
- Passer commandes
- Laisser avis
- Messaging avec vendeurs

### Vendeur
- Gérer boutique + produits
- Voir commandes
- Approuver/livrer commandes
- Messaging avec clients
- **Statut**: `pending` → `pending_validation` → `approved`

### Admin
- Vérifier documents vendeurs
- Approuver/rejeter vendeurs
- Voir tous les utilisateurs
- Gérer système

## 🔐 Comptes de Test

Après `php artisan migrate --seed`:

```
Admin:
  Email: admin@supply.local
  Password: admin123456

Client:
  Email: client@supply.local
  Password: password

Vendeur:
  Email: vendor@supply.local
  Password: password
```

## 📧 Emails (MAILTRAP)

Les emails suivants sont envoyés:

| Événement | Destinataire | Type |
|-----------|--------------|------|
| Nouvelle inscription vendeur | Admin | Notification |
| Vérification email | Utilisateur | Code 6 chiffres |
| Documents soumis | Admin | À vérifier |
| Vendeur approuvé | Vendeur | Bienvenue |
| Vendeur rejeté | Vendeur | Explication |
| Nouveau client | Admin | Notification |
| Statut commande | Client | Mise à jour |

**Configuration**: Voir [MAILTRAP_SETUP.md](./MAILTRAP_SETUP.md)

## 🛠️ Commandes Utiles

```bash
# Créer un admin
php artisan app:create-admin-user

# Tester les emails
php artisan app:test-email

# Vider les caches
php artisan optimize:clear

# Migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh --seed

# Assets
npm run dev      # Vite en mode dev
npm run build    # Build pour production
```

## 🔗 URLs Principales

- Accueil: `http://127.0.0.1:8000/`
- Inscription: `http://127.0.0.1:8000/register`
- Connexion: `http://127.0.0.1:8000/login`
- Dashboard Client: `http://127.0.0.1:8000/dashboard`
- Dashboard Vendeur: `http://127.0.0.1:8000/vendeur/dashboard` (après approbation)
- Admin: `http://127.0.0.1:8000/admin/` (admin uniquement)
- Attente approbation: `http://127.0.0.1:8000/vendeur/en-attente`

## 📞 Support

- **Emails**: `support@supply.ci`
- **Issues**: Consultez `storage/logs/laravel.log`
- **MAILTRAP Issues**: Voir [MAILTRAP_SETUP.md - Dépannage](./MAILTRAP_SETUP.md#-dépannage)

## 📄 License

MIT License

---

### ⚠️ AVANT DE COMMENCER

**N'oubliez pas de configurer MAILTRAP pour les emails!**

👉 [Lire: MAILTRAP_SETUP.md](./MAILTRAP_SETUP.md)

Sinon, l'application fonctionnera mais les emails n'iront nulle part.
