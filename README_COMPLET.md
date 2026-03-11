# 📦 Supply - Plateforme E-commerce Multi-Vendeurs

Supply est une **plateforme e-commerce moderne** construite avec Laravel 11, Tailwind CSS et Vue.js. Elle permet aux vendeurs de créer des boutiques, gérer des produits, traiter des commandes et communiquer avec les clients en temps réel.

---

## ✨ Fonctionnalités Principales

### 👥 Pour les Clients
- 🛍️ Parcourir et rechercher les produits
- 🛒 Gestion du panier avec synchronisation réelle
- 💳 Paiement sécurisé (IntactBank, Moov Money)
- 📦 Suivi des commandes en temps réel
- ⭐ Avis et notations des produits
- 💬 Chat avec les vendeurs
- ❤️ Liste de favoris

### 🏪 Pour les Vendeurs
- 📊 Dashboard avec statistiques complètes
- 📝 Gestion des produits (CRUD simple)
- 🖼️ Upload d'images avec optimisation Cloudinary
- 📈 Suivi des ventes et revenus
- 📦 Gestion des commandes et statuts
- ⚡ Notifications en temps réel via Pusher
- 📧 Emails automatiques aux clients

### 👨‍💼 Pour l'Admin
- 🎯 Modération des vendeurs
- 📋 Validation des produits
- 💰 Gestion des paiements
- 📊 Rapports de ventes
- 👥 Gestion des utilisateurs

---

## 🛠️ Stack Technique

| Composant | Technologie |
|-----------|-------------|
| **Backend** | Laravel 11 (PHP 8.3) |
| **Frontend** | Blade + Tailwind CSS + Alpine.js |
| **Base de Données** | MySQL 8.0+ |
| **Temps Réel** | Pusher (websockets) |
| **File d'Attente** | Database/Redis (configurable) |
| **Email** | Mailtrap (sandbox) / SMTP |
| **Images** | Cloudinary (optionnel) + LocalStorage |
| **Paiements** | IntactBank API, Moov Money API |

---

## 📋 Prérequis

- **PHP** ≥ 8.3
- **Composer** ≥ 2.0
- **MySQL** ≥ 8.0 (ou équivalent)
- **Node.js** ≥ 18 (pour Vite/Tailwind)
- **Redis** (optionnel, pour cache/queue)

---

## 🚀 Installation & Configuration

### 1️⃣ Cloner le Projet

```bash
git clone https://github.com/yourusername/supply.git
cd supply
```

### 2️⃣ Installer les Dépendances

```bash
# PHP
composer install

# JavaScript
npm install

# Build Tailwind CSS
npm run build
```

### 3️⃣ Configuration d'Environnement

```bash
cp .env.example .env
php artisan key:generate
```

**Configurer dans `.env`:**

```env
# Base de Données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=supply
DB_USERNAME=root
DB_PASSWORD=

# Email (Mailtrap)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_password
MAIL_FROM_ADDRESS=noreply@supply.local

# Queue (utilise sync pour développement)
QUEUE_CONNECTION=sync

# Pusher (Real-time notifications)
PUSHER_APP_ID=votre_app_id
PUSHER_APP_KEY=votre_app_key
PUSHER_APP_SECRET=votre_app_secret
PUSHER_APP_CLUSTER=mt1

# Cloudinary (images optimisées - optionnel)
CLOUDINARY_URL=cloudinary://key:secret@cloud

# Paiements
INTACTBANK_API_KEY=votre_clé
MOOV_MONEY_API_KEY=votre_clé
```

### 4️⃣ Base de Données

```bash
# Créer la base de données
php artisan migrate

# Remplir avec des données de test
php artisan db:seed
```

### 5️⃣ Créer le Lien Stockage

```bash
php artisan storage:link
```

### 6️⃣ Lancer le Serveur

```bash
# Terminal 1 - Serveur Laravel
php artisan serve

# Terminal 2 - Vite (développement)
npm run dev

# Terminal 3 - Queue (si non-sync)
php artisan queue:listen
```

L'application sera disponible à: **http://localhost:8000**

---

## 📁 Structure du Projet

```
supply/
├── app/
│   ├── Http/Controllers/
│   │   ├── ClientController.php
│   │   ├── VendeurProduitController.php
│   │   ├── CommandeController.php
│   │   └── Admin/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Produit.php
│   │   ├── Commande.php
│   │   └── LigneCommande.php
│   ├── Mail/
│   │   ├── ClientOrderStatusUpdatedMail.php
│   │   └── AdminNewVendorRegistrationMail.php
│   └── Events/
│       └── OrderStatusChanged.php
├── resources/
│   ├── views/
│   │   ├── vendeur/           # Dashboard vendeur
│   │   ├── commandes/         # Gestion commandes
│   │   ├── produits/          # Gestion produits
│   │   ├── emails/            # Templates mail
│   │   └── layouts/           # Layouts généraux
│   ├── css/
│   │   └── app.css            # Styles Tailwind
│   └── js/
│       └── app.js             # Initialisation Vue/Alpine
├── routes/
│   ├── web.php                # Routes principales
│   ├── api.php                # Routes API
│   ├── admin.php              # Routes admin
│   └── auth.php               # Routes authentification
├── database/
│   ├── migrations/            # Schéma base de données
│   └── seeders/               # Données initiales
├── config/
│   ├── app.php                # Configuration Laravel
│   ├── mail.php               # Configuration email
│   └── view.php               # Configuration Blade
└── storage/
    ├── app/public/            # Images uploadées
    └── logs/                  # Logs d'application
```

---

## 🔐 Authentification

### Rôles & Permissions

| Rôle | Permissions |
|------|-------------|
| **Client** | Acheter, écrire des avis, discuter avec vendeurs |
| **Vendeur** | Créer produits, gérer commandes, statistiques |
| **Admin** | Modérer contenu, gérer vendeurs, rapports |

### Créer des Utilisateurs Test

```bash
# Générer 5 utlisateurs clients + vendeurs
php artisan db:seed --class=UserSeeder
```

**Comptes de Test:**
- Admin: `admin@supply.local` / `password`
- Vendeur: `vendeur@supply.local` / `password`
- Client: `client@supply.local` / `password`

---

## 📧 Système d'Email

### Configuration Mailtrap (Sandbox)

1. Créer compte gratuit: https://mailtrap.io
2. Créer "Inbox" test
3. Copier les credentials dans `.env`
4. Les emails vont en sandbox au lieu d'être envoyés réellement

### Templates Email

| Email | Déclencheur | Destinataire |
|-------|------------|-------------|
| `client-order-status-updated.blade.php` | Changement statut commande | Client |
| `admin-new-vendor-registration.blade.php` | Nouvelle inscription vendeur | Admin |
| `vendor-approved.blade.php` | Vendeur approuvé | Vendeur |
| `vendor-rejected.blade.php` | Vendeur rejeté | Vendeur |

### Envoyer un Email Test

```bash
php artisan tinker
```

```php
use App\Mail\ClientOrderStatusUpdatedMail;
use App\Models\Commande;
use Illuminate\Support\Facades\Mail;

$commande = Commande::first();
Mail::to($commande->user->email)->send(new ClientOrderStatusUpdatedMail($commande));
```

---

## 🖼️ Gestion des Images

### Upload Local

- **Dossier**: `storage/app/public/produits/`
- **URL**: `asset('storage/produits/nom.jpg')`
- **Max**: 5 images par produit
- **Format**: JPG, PNG (max 5MB)

### Galerie Cloudinary (Optionnel)

Lorsque vous éditez un produit:
1. Cliquez sur **"✨ Galerie d'images"**
2. Uploader/réorganiser via Cloudinary
3. Optimisation automatique + CDN

---

## 💳 Paiements

### Configuration IntactBank

```env
INTACTBANK_API_KEY=votre_clé_api
INTACTBANK_SANDBOX=true  # true pour test, false pour production
```

### Configuration Moov Money

```env
MOOV_MONEY_API_KEY=votre_clé_api
MOOV_MERCHANT_ID=votre_merchant_id
```

### Flux de Paiement

1. Client sélectionne méthode de paiement
2. Redirection vers page de paiement sécurisée
3. Confirmation du paiement
4. Email de confirmation envoyé
5. Commande marquée comme "payée"

---

## ⏱️ Notifications Real-Time

Powered by **Pusher** - Les événements en direct:
- Nouvelle commande
- Changement de statut
- Nouveau message
- Avis publié

### Configuration Pusher

1. Créer compte: https://pusher.com
2. Ajouter les credentials dans `.env`
3. Les notifications sont automatiques

---

## 🧪 Testing

### Lancer les Tests

```bash
# Tests unitaires
php artisan test

# Avec couverture de code
php artisan test --coverage

# Test spécifique
php artisan test tests/Feature/CommandeTest.php
```

### Test Email

```bash
php artisan tinker --execute="@include('test-mail.php')"
```

---

## 📊 Commandes Utiles

```bash
# Cache & Config
php artisan config:cache          # Cache la configuration
php artisan config:clear         # Vide le cache config
php artisan cache:clear          # Vide tous les caches

# Database
php artisan migrate              # Exécuter migrations
php artisan migrate:fresh        # Reset + reseed
php artisan migrate:rollback     # Annuler dernière migration
php artisan db:seed              # Remplir données test

# Queue
php artisan queue:listen         # Écouter jobs (production)
php artisan queue:work           # Worker unique

# Assets
npm run dev                       # Mode développement (watch)
npm run build                     # Minifier pour production

# Général
php artisan route:list           # Lister toutes les routes
php artisan tinker               # REPL interactif
php artisan make:controller      # Créer un contrôleur
php artisan storage:link         # Créer symlink stockage
```

---

## 🐛 Troubleshooting

### "No hint path defined for [mail]"
**Solution**: La configuration de namespace mail pointe vers le mauvais répertoire.
```bash
# Vérifier config/view.php
# Ligne 48: 'mail' => resource_path('views/emails')
php artisan config:cache
```

### Images ne s'affichent pas
**Solution**: Vérifier le lien symlink stockage
```bash
php artisan storage:link
# Vérifier: public/storage → storage/app/public
```

### Queue pas exécutée
**Solution**: Vérifier configuration queue
```env
QUEUE_CONNECTION=sync             # Synchrone (développement)
QUEUE_CONNECTION=database         # Via base de données
QUEUE_CONNECTION=redis            # Via Redis
```

### 500 Error
1. Lire: `storage/logs/laravel.log`
2. Vérifier `.env` et migrations
3. Lancer: `php artisan migrate:fresh --seed`

---

## 🔒 Sécurité

- ✅ CSRF Protection sur tous les formulaires
- ✅ Password hashing (Bcrypt)
- ✅ Authentification Session/Token
- ✅ Authorization policies (Gate/Policy)
- ✅ Rate limiting sur API
- ✅ Validation côté serveur (Request class)
- ⚠️ **TODO**: Web Application Firewall (WAF)
- ⚠️ **TODO**: 2FA pour comptes sensibles

---

## 📈 Performance

### Optimisations Appliquées
- ✅ Lazy loading images
- ✅ Code splitting Vite
- ✅ Compression Tailwind CSS
- ✅ Database indexing
- ✅ Query optimization (eager loading)
- ✅ Responsive design (mobile-first)

### Vérifier Performance

```bash
# Laravel Debugbar
composer require barryvdh/laravel-debugbar --dev

# Metrics
php artisan log:tail

# Profiler
Time php artisan serve
```

---

## 📝 Conventions de Code

### Nommage
- **Controllers**: `PluralNameController.php`
- **Modèles**: `SingularName.php`
- **Routes**: kebab-case (`/vendeur/commandes/123`)
- **Méthodes**: camelCase (`getOrderTotal()`)
- **Variables**: camelCase (`$userEmail`)

### Architecture
- **MVC Strict**: Model → Controller → View
- **No Business Logic in Controllers**: Utiliser Service classes
- **DRY**: Ne pas répéter le code
- **SOLID Principles**: Single Responsibility + Dependency Injection

### Style CSS
- **Utility-first**: Tailwind CSS (pas de classes custom sauf nécessaire)
- **Color Palette**: Variables CSS dans `--color-*`
- **Responsive**: Mobile-first (`sm:`, `md:`, `lg:`)
- **Design System**: Voir `minimal-design-system.md`

---

## 🤝 Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b feature/amazing-feature`)
3. Commit les changements (`git commit -m 'Add amazing feature'`)
4. Push la branche (`git push origin feature/amazing-feature`)
5. Ouvrir une Pull Request

---

## 📄 Fichiers de Documentation

| Fichier | Contenu |
|---------|---------|
| `README.md` | Vue générale du projet |
| `INSTALLATION.md` | Guide d'installation détaillé |
| `API_SETUP.md` | Configuration des APIs externes |
| `ADMIN_GUIDE.md` | Guide d'administration |
| `ADMIN_SETUP.md` | Configuration admin avancée |
| `ADMIN_ROLE_GUIDE.md` | Rôles et permissions admin |
| `FIX_EMAIL_VENDOR_ORDER_COMPLETE.md` | Fix des emails vendeur |
| `RESPONSIVE_DESIGN_COMPLETE.md` | Design responsive |
| `VALIDATION_REPORT.txt` | Rapport de validation |

---

## 📞 Support

- 📧 Email: support@supply.local  
- 💬 Discord: [Rejoindre](https://discord.gg/supply)
- 📚 Wiki: Voir la documentation complète
- 🐛 Issues: Ouvrir une issue GitHub

---

## 📜 License

Ce projet est licencié sous la **MIT License** - voir [LICENSE](LICENSE) pour les détails.

---

## 🙏 Remerciements

- Laravel framework et communauté
- Tailwind CSS pour le design system
- Pusher pour les websockets
- Cloudinary pour l'optimisation d'images
- Mailtrap pour le sandbox email

---

**Dernière mise à jour**: 2026-03-11  
**Version**: 1.0.0  
**Statut**: ✅ Production Ready
