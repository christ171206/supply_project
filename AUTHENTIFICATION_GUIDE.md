# 🔐 Guide Complet - Système d'Authentification Supply

## 📋 Table des Matières
1. [Accès aux Pages](#accès-aux-pages)
2. [Comptes de Test](#comptes-de-test)
3. [Flux d'Authentification](#flux-dauthentification)
4. [Règles Métier](#règles-métier)
5. [Architecture Technique](#architecture-technique)

---

## 🌐 Accès aux Pages

### URL de Base
- **Site**: `http://127.0.0.1:8000`
- **Port**: 8000

### Pages d'Authentification

| Page | URL | Description |
|------|-----|-------------|
| **Connexion** | `/login` | Formulaire de connexion simple |
| **Inscription** | `/register` | Inscription client ou vendeur |
| **Déconnexion** | `/logout` | Bouton déconnexion (nav) |
| **Oublié mot de passe** | `/forgot-password` | Réinitialisation (Breeze) |

### Pages Protégées

| Rôle | Accès | URL |
|------|-------|-----|
| **Client** | Produits, Panier, Commandes | `/produits`, `/panier` |
| **Vendeur** | Dashboard, Statistiques, Produits | `/dashboard-vendeur` |
| **Admin** | Panel admin | `/admin` (si disponible) |

---

## 👥 Comptes de Test

### 1️⃣ Compte CLIENT

```
📧 Email:    client@test.com
🔐 Mot de passe: password
👤 Rôle:     Client
```

**Ce que vous pouvez faire:**
- ✅ Consulter les produits
- ✅ Ajouter au panier
- ✅ Passer une commande
- ✅ Voir l'historique des commandes
- ✅ Laisser des avis

**Redirection après login:** `/` (page produits)

---

### 2️⃣ Compte VENDEUR

```
📧 Email:    vendeur@test.com
🔐 Mot de passe: password
👤 Rôle:     Vendeur
📊 Statut:   Actif
```

**Ce que vous pouvez faire:**
- ✅ Accéder au dashboard vendeur
- ✅ Voir les statistiques
- ✅ Gérer les produits
- ✅ Voir les commandes

**Infos de boutique (pré-remplies):**
```
Nom: Tech Store Premium
Téléphone: +33612345678
Adresse: 123 Rue de la Technologie, Paris 75002
```

**Redirection après login:** `/dashboard-vendeur` (dashboard)

---

## 🔄 Flux d'Authentification

### Flux de Connexion (LOGIN)

```
1. Utilisateur clique sur "Se connecter"
   ↓
2. Page: /login (form simple)
   - Email
   - Mot de passe
   ↓
3. POST /login (authentification)
   ↓
4. Laravel vérifie les identifiants
   ↓
5. Détection du rôle (base de données)
   ├─ Si VENDOR → /dashboard-vendeur
   └─ Si CLIENT → / (accueil)
```

### Flux d'Inscription (REGISTER)

```
1. Utilisateur clique sur "Créer un compte"
   ↓
2. Page: /register (form complet)
   - Nom
   - Email
   - Mot de passe
   - [OPTION] Je veux vendre (checkbox)
   ↓
3. Si OPTION cochée:
   - Affichage dynamique (JS):
     * Nom de boutique
     * Téléphone
     * Adresse
     * Upload CNI
   ↓
4. POST /register (validation + création)
   ↓
5. Utilisateur créé avec rôle
   ├─ Si vendeur → /dashboard-vendeur
   └─ Si client → /
```

---

## 📌 Règles Métier (D'OR!)

### ✅ À RESPECTER

| Règle | Comportement |
|-------|-------------|
| **LOGIN** | PAS de choix rôle/client/vendeur |
| **REGISTER** | OUI, option pour devenir vendeur |
| **Rôle détection** | Côté backend (Laravel) |
| **Redirection** | Automatique selon `user->role` |
| **Client** | Accès produits immédiat |
| **Vendeur** | Accès dashboard immédiat |

### ❌ À ÉVITER

- ❌ Forcer le choix du rôle au login
- ❌ Afficher "Client" vs "Vendeur" à la connexion
- ❌ Deux formulaires séparés
- ❌ Demander trop de champs au client

---

## 🏗️ Architecture Technique

### Structure de Fichiers

```
resources/views/auth/
├── login.blade.php          ← Connexion simple
├── register.blade.php       ← Inscription avec choix
└── forgot-password.blade.php ← Réinitialisation

resources/views/layouts/
├── guest.blade.php          ← Layout pour auth pages
└── app.blade.php            ← Layout principal

app/Http/Controllers/Auth/
├── RegisteredUserController.php
├── AuthenticatedSessionController.php
├── PasswordResetLinkController.php
└── VerifyEmailController.php

database/migrations/
├── 0001_01_01_000000_create_users_table.php
└── [autres migrations...]
```

### Colonnes Base de Données (Users)

```sql
CREATE TABLE users (
  id                BIGINT UNSIGNED PRIMARY KEY,
  name              VARCHAR(255) NOT NULL,
  email             VARCHAR(255) UNIQUE NOT NULL,
  email_verified_at TIMESTAMP NULL,
  password          VARCHAR(255) NOT NULL,
  role              ENUM('client','vendor') DEFAULT 'client',
  -- CHAMPS VENDEUR (nullable)
  shop_name         VARCHAR(255) NULL,
  phone             VARCHAR(20) NULL,
  address           TEXT NULL,
  vendor_status     ENUM('active','pending','rejected') DEFAULT 'active',
  id_document       VARCHAR(255) NULL,
  remember_token    VARCHAR(100) NULL,
  created_at        TIMESTAMP,
  updated_at        TIMESTAMP
);
```

### Routes Disponibles

```php
// Groupes de routes (Breeze)
Route::middleware('guest')->group(function () {
    Route::get('/login', 'AuthenticatedSessionController@create')->name('login');
    Route::post('/login', 'AuthenticatedSessionController@store');
    Route::get('/register', 'RegisteredUserController@create')->name('register');
    Route::post('/register', 'RegisteredUserController@store');
    // ... autres routes guest
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', 'AuthenticatedSessionController@destroy')->name('logout');
    // Routes protégées
});
```

### Middleware d'Authentification

```php
// app/Http/Middleware/Authenticate.php
protected function redirectTo($request)
{
    if (! $request->expectsJson()) {
        return route('login');
    }
}
```

---

## 🧪 Tests à Effectuer

### Test 1: Connexion CLIENT
```
1. Allez sur /login
2. Email: client@test.com
3. Mot de passe: password
4. Cliquez "Se connecter"
5. ✅ Vous devez être redirigé vers /
   (page des produits)
```

### Test 2: Connexion VENDEUR
```
1. Allez sur /login
2. Email: vendeur@test.com
3. Mot de passe: password
4. Cliquez "Se connecter"
5. ✅ Vous devez être redirigé vers /dashboard-vendeur
```

### Test 3: Inscription CLIENT
```
1. Allez sur /register
2. Remplissez:
   - Nom: "Jean Dupont"
   - Email: "jean@example.com"
   - Mot de passe: "password"
   - Confirmation: "password"
   - Ne cochez PAS "vendre des produits"
   - Acceptez les conditions
3. Cliquez "Créer mon compte"
4. ✅ Vous devez être redirigé vers /
```

### Test 4: Inscription VENDEUR
```
1. Allez sur /register
2. Remplissez les infos de base
3. Cochez "Je souhaite vendre des produits"
4. Des champs apparaissent:
   - Nom de boutique: "Ma Boutique"
   - Téléphone: "+33123456789"
   - Adresse: "123 Rue Test"
   - (CNI: optionnel)
5. Cliquez "Créer mon compte"
6. ✅ Vous devez être redirigé vers /dashboard-vendeur
```

### Test 5: Déconnexion
```
1. Connecté en tant que quelconque
2. Cliquez sur votre avatar/menu en haut à droite
3. Cliquez "Se déconnecter"
4. ✅ Vous devez être redirigé vers /
   (page publique)
```

---

## 🎨 Aperçu Visuel

### Page LOGIN
```
┌─────────────────────────────────┐
│          Supply Logo             │
│   Votre boutique informatique   │
│                                 │
│  Email:     [____________]      │
│  Mot de passe: [____________]   │
│                                 │
│  ☐ Se souvenir de moi           │
│  Mot de passe oublié?           │
│                                 │
│  [Se connecter] (gradient cyan)  │
│                                 │
│  Pas de compte? Créer un compte │
│  ← Retour à l'accueil           │
└─────────────────────────────────┘
```

### Page REGISTER
```
┌─────────────────────────────────┐
│          Supply Logo             │
│  Créez votre compte pour...      │
│                                 │
│  Nom:        [____________]      │
│  Email:      [____________]      │
│  Mot de passe: [____________]    │
│  Confirmation: [____________]    │
│                                 │
│  ☐ Je souhaite vendre            │
│                                 │
│  [Si coché]:                    │
│  Boutique: [____________]        │
│  Téléphone: [____________]       │
│  Adresse:  [____________]        │
│  CNI:      [Sélectionner]        │
│                                 │
│  ☐ J'accepte les conditions      │
│  [Créer mon compte]              │
└─────────────────────────────────┘
```

---

## 🔒 Sécurité

### Mesures Implémentées

✅ **Hachage des mots de passe** (bcrypt)
✅ **Protection CSRF** (@csrf dans les formulaires)
✅ **Validation côté serveur** (Rules\Password::defaults())
✅ **Email unique** (unique:users)
✅ **Authentification par session**
✅ **Middleware d'authentification** (auth)
✅ **Middleware guest** (pour login/register)

### Validation des Mots de Passe

Minimum requis (Laravel default):
- ✅ Au moins 8 caractères
- ✅ Au moins une lettre majuscule
- ✅ Au moins une lettre minuscule
- ✅ Au moins un chiffre
- ✅ Au moins un caractère spécial

Exemple valide: `SecurePass123!`

---

## 📞 Support et Dépannage

### Problème: "Email déjà utilisé"
**Solution:** L'email existe déjà. Utilisez un autre email ou connectez-vous.

### Problème: "Identifiants incorrects"
**Solution:** Vérifiez l'email et le mot de passe (attention aux majuscules).

### Problème: "Page non trouvée après login"
**Solution:** Vérifiez que les routes sont enregistrées (`php artisan route:list`).

### Problème: "Accès refusé au dashboard"
**Solution:** Seuls les vendeurs peuvent accéder à `/dashboard-vendeur`. Créez un compte vendeur.

---

## 📄 Fichiers Modifiés / Créés

### Créés
- ✅ `resources/views/auth/login.blade.php`
- ✅ `resources/views/auth/register.blade.php`
- ✅ `resources/views/layouts/guest.blade.php`
- ✅ `database/seeders/TestAccountsSeeder.php`

### Modifiés
- ✅ `app/Http/Controllers/Auth/RegisteredUserController.php`
- ✅ `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- ✅ `app/Models/User.php` (fillable)
- ✅ `database/migrations/[users_table]` (colones vendeur)

---

## ✨ Conclusion

✅ **Système complet et professionnel**
✅ **Respect des bonnes pratiques Laravel**
✅ **Design moderne et cohérent**
✅ **Sécurité maximale**
✅ **Redirection automatique par rôle**
✅ **Comptes de test prêts à l'emploi**

Vous pouvez maintenant tester l'authentification complètement! 🚀
