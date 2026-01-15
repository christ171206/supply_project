# 🔐 Système d'Authentification Supply

## Vue d'Ensemble

Supply utilise un système d'authentification avec **deux rôles distincts** :

- **👤 Client** : Accès au catalogue, panier, commandes
- **🧑‍💼 Vendeur** : Accès au dashboard de gestion, gestion produits

---

## 🏗️ Architecture

### Pages d'Authentification

| Page | Route | Description |
|------|-------|-------------|
| **Login** | `/login` | Connexion (commun) |
| **Register** | `/register` | Inscription avec choix de rôle |
| **Forgot Password** | `/forgot-password` | Réinitialisation mot de passe |

### Redirection post-connexion

```
Login/Register
    ↓
Vérification du rôle
    ├─ Client  → /  (page produits)
    └─ Vendor  → /vendeur/dashboard  (dashboard)
```

---

## 📋 Flux d'Authentification Détaillé

### 1️⃣ INSCRIPTION (Register)

#### Étape 1: Choix du type de compte

```
☑ Je suis un client
☐ Je veux vendre des produits (vendeur)
```

**Par défaut**: Client (option recommandée pour commencer)

#### Étape 2: Informations obligatoires

Pour **TOUS** :

```
Nom complet          [requis]
Email                [requis, unique]
Mot de passe         [requis, min 8 caractères]
Confirmation         [requis]
```

#### Étape 3: Champs supplémentaires (si Vendeur)

Affichés **dynamiquement** avec JavaScript :

```
Nom de la boutique   [requis]
Téléphone            [requis]
Adresse              [requis]
Justificatif d'identité [optionnel, image JPG/PNG max 5MB]
```

#### Validation côté serveur

```php
// Validation commune
'name' => ['required', 'string', 'max:255']
'email' => ['required', 'email', 'unique:users']
'password' => ['required', 'confirmed', 'min:8']
'role' => ['required', 'in:client,vendor']

// Validation vendeur (si role = vendor)
'shop_name' => ['required', 'string']
'phone' => ['required', 'string']
'address' => ['required', 'string']
'id_document' => ['file', 'mimes:jpeg,png', 'max:5120']
```

#### Redirection après inscription

```
Client  → / (page produits)
Vendor  → / (page produits, statut "pending")
```

---

### 2️⃣ CONNEXION (Login)

**Pas de choix de rôle ici** ✓

#### Formulaire simple

```
Email               [requis]
Mot de passe        [requis]
☑ Se souvenir de moi [optionnel]
```

#### Logique de redirection

```php
// Dans AuthenticatedSessionController
$redirectRoute = Auth::user()->role === 'vendor'
    ? 'vendeur.dashboard'
    : 'accueil';

return redirect()->intended(route($redirectRoute));
```

#### Comportement selon le rôle

| Rôle | Redirection | Page |
|------|-----------|------|
| Client | `/` | Catalogue produits |
| Vendor | `/vendeur/dashboard` | Dashboard vendeur |

---

## 🗄️ Structure de la Base de Données

### Table `users`

```sql
id                   INTEGER PRIMARY KEY
name                 VARCHAR(255)
email                VARCHAR(255) UNIQUE
password             VARCHAR(255)
role                 ENUM('client', 'vendor') DEFAULT 'client'
shop_name            VARCHAR(255) NULL       -- Vendeurs uniquement
phone                VARCHAR(20) NULL        -- Vendeurs uniquement
address              TEXT NULL               -- Vendeurs uniquement
id_document          VARCHAR(255) NULL       -- Vendeurs uniquement
vendor_status        ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'
email_verified_at    TIMESTAMP NULL
created_at           TIMESTAMP
updated_at           TIMESTAMP
```

### Énumérations

```php
// Roles
'client'   → Acheteur normal
'vendor'   → Vendeur (peut publier produits)

// Vendor Status
'pending'  → En attente de vérification
'verified' → Vérifié, peut vendre
'rejected' → Demande rejetée
```

---

## 👤 Modèle User

```php
// app/Models/User.php

class User extends Authenticatable {
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'shop_name',
        'phone',
        'address',
        'id_document',
        'vendor_status',
    ];

    public function isClient(): bool {
        return $this->role === 'client';
    }

    public function isVendor(): bool {
        return $this->role === 'vendor';
    }

    public function isVerifiedVendor(): bool {
        return $this->role === 'vendor' && $this->vendor_status === 'verified';
    }
}
```

---

## 🔒 Sécurité

### Mots de passe

```php
// Hashage automatique
'password' => Hash::make($request->password)

// Validation
// - Minimum 8 caractères (règle de Laravel par défaut)
// - Doit être confirmé (password_confirmation)
// - Non storé en clair en base de données
```

### Protection CSRF

```blade
@csrf  <!-- Présent sur tous les formulaires -->
```

### Authentification

```php
// Routes protégées par middleware 'auth'
Route::middleware('auth')->group(function () {
    // ...
});
```

### Email vérifié (optionnel)

```php
// Peut être activé pour forcer la vérification email
Route::middleware(['auth', 'verified'])->group(function () {
    // ...
});
```

---

## 🧪 Comptes de Test

### Client Test

```
Email: client@test.com
Password: password
Rôle: client
```

### Vendor Test

```
Email: vendeur@test.com
Password: password
Rôle: vendor
Statut: verified
Shop: Tech Store Test
```

### Création

```bash
php artisan db:seed --class=TestAccountsSeeder
```

---

## 📁 Fichiers Clés

```
resources/views/
├── auth/
│   ├── login.blade.php           # Formulaire connexion
│   ├── register.blade.php        # Formulaire inscription
│   ├── forgot-password.blade.php # Réinitialisation
│   └── reset-password.blade.php  # Nouveau mot de passe
└── layouts/
    └── guest.blade.php           # Layout authentification

app/Http/Controllers/Auth/
├── AuthenticatedSessionController.php    # Login/Logout
├── RegisteredUserController.php          # Register
├── PasswordResetLinkController.php       # Forgot password
└── ...

app/Models/
└── User.php                      # Modèle utilisateur

database/migrations/
├── 2026_01_06_082335_add_role_to_users_table.php
└── 2026_01_15_000000_add_vendor_fields_to_users_table.php

database/seeders/
└── TestAccountsSeeder.php        # Comptes test

routes/
└── auth.php                       # Routes d'authentification
```

---

## 🎯 Utilisation en Templates

### Vérifier l'authentification

```blade
@auth
    <!-- Utilisateur connecté -->
    <p>Bienvenue, {{ Auth::user()->name }}</p>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button>Déconnexion</button>
    </form>
@endauth

@guest
    <!-- Pas connecté -->
    <a href="{{ route('login') }}">Se connecter</a>
    <a href="{{ route('register') }}">S'inscrire</a>
@endguest
```

### Vérifier le rôle

```blade
@if (Auth::user()?->role === 'vendor')
    <!-- Afficher pour vendeurs -->
    <a href="{{ route('vendeur.dashboard') }}">Dashboard</a>
@endif

@if (Auth::user()?->isVerifiedVendor())
    <!-- Afficher pour vendeurs vérifiés -->
@endif
```

### Utiliser les méthodes helper

```php
// Dans un contrôleur
if (auth()->user()->isVendor()) {
    // ...
}

// Ou via Auth facade
if (Auth::user()->isClient()) {
    // ...
}
```

---

## 🔄 Flux Complet d'Exemple

### Scénario: Nouveau vendeur

1. **Accès** → `https://example.com/register`
2. **Sélectionne** → "Je veux vendre des produits"
3. **Remplit**:
   - Nom: "Jean Tech"
   - Email: "jean@techstore.com"
   - Mot de passe: "SecurePass123!"
   - Nom boutique: "Jean's Tech"
   - Téléphone: "+33 6 00 00 00 00"
   - Adresse: "123 Rue de la Tech"
   - Upload CNI
4. **Crée le compte**
5. **Redirigé vers** → `/` (page produits)
6. **Statut**: `pending` (en attente de vérification)
7. **Admin vérifie** → Statut passe à `verified`
8. **Login** → Email + mot de passe
9. **Redirigé vers** → `/vendeur/dashboard`
10. **Accès** → Gestion produits, commandes, etc.

---

## ⚙️ Configuration

### Fichier `.env`

```env
APP_NAME=Supply
APP_ENV=local  # ou 'production'
MAIL_DRIVER=smtp  # Pour email vérification

# Base de données
DB_HOST=localhost
DB_DATABASE=supply
DB_USERNAME=root
DB_PASSWORD=
```

### Routes (routes/web.php)

```php
Route::middleware('guest')->group(function () {
    // Login, Register, Forgot Password
    Route::include('auth.php');
});

Route::middleware('auth')->group(function () {
    // Pages protégées
});
```

---

## 🐛 Dépannage

### "Email déjà utilisé"

```
➜ Email existe en base de données
✓ Se connecter avec cet email
✓ Réinitialiser le mot de passe
```

### "Mot de passe incorrect"

```
✓ Vérifier la casse (majuscules)
✓ Utiliser "Mot de passe oublié"
✓ Contacter support
```

### "Vendeur ne peut pas accéder au dashboard"

```
➜ Statut n'est pas 'verified'
✓ En attente de validation admin
✓ Contact admin pour accélération
```

---

## 📚 Ressources

- [Docs Laravel Authentication](https://laravel.com/docs/11.x/authentication)
- [Breeze Documentation](https://laravel.com/docs/11.x/starter-kits#laravel-breeze)
- [Password Hashing](https://laravel.com/docs/11.x/hashing)

---

## 📅 Dernière mise à jour

15 janvier 2026

---
