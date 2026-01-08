# 🚀 Guide d'Accès - Espace Vendeur

## 📌 Accéder à l'Espace Vendeur

### 1️⃣ URL Directe
```
http://localhost:8000/vendeur/apercu
http://localhost:8000/vendeur/stock
http://localhost:8000/vendeur/messages
http://localhost:8000/vendeur/historique
http://localhost:8000/vendeur/produits
http://localhost:8000/vendeur/commandes
http://localhost:8000/vendeur/profil
```

### 2️⃣ Via Navigation (Si connecté comme Vendeur)
1. Se connecter avec un compte **vendeur**
2. Cliquer sur le dropdown **Compte** (en haut à droite)
3. Voir le rôle : 🏪 **Vendeur**
4. Cliquer sur **🏪 Espace Vendeur**
5. Accès à l'Espace Vendeur avec sidebar

### 3️⃣ Via Routes Laravel
```php
// Dans une vue ou contrôleur :
<a href="{{ route('vendeur.apercu') }}">Aller à Aperçu</a>
<a href="{{ route('vendeur.stock') }}">Aller à Stock</a>
<a href="{{ route('vendeur.messages') }}">Aller à Messages</a>
<a href="{{ route('vendeur.historique') }}">Aller à Historique</a>
```

---

## 🔐 Authentification Requise

L'Espace Vendeur est protégé par :
- **Middleware** : `auth` (utilisateur connecté)
- **Middleware** : `vendeur` (utilisateur avec role = 'vendeur')

### Sans Authentification
→ Redirection vers `/login`

### Avec Role ≠ Vendeur
→ Redirection vers `/dashboard` (client)

---

## 🎯 Flux de Navigation Idéal

```
Login (avec role = vendeur)
  ↓
Dashboard Client (peut voir liens vendeur dans dropdown)
  ↓
Cliquer "Espace Vendeur" dans dropdown
  ↓
📊 Aperçu (page d'accueil vendeur avec sidebar)
  ↓
Sidebar visible sur la gauche
  - Cliquer sur l'un des 8 menu items
  - Chaque page s'ouvre avec le même sidebar
```

---

## 📱 Responsive Design

### Desktop (≥1024px)
- Sidebar w-64 fixed left
- Main content flex-1 right
- Grilles 3 colonnes (produits)

### Tablet (768px - 1023px)
- Sidebar w-64 fixed left
- Grilles 2 colonnes (produits)

### Mobile (<768px)
- Sidebar w-64 fixed left (peut nécessiter scroll horizontal)
- Grilles 1 colonne
- Contenu full width

**Note** : À améliorer pour mobile avec hamburger menu toggle

---

## 🧪 Test Rapide

### Vérifier les routes
```bash
php artisan route:list | grep vendeur
```

Devrait afficher :
```
vendeur/apercu .................. vendeur.apercu
vendeur/stock ................... vendeur.stock
vendeur/messages ................ vendeur.messages
vendeur/historique .............. vendeur.historique
vendeur/produits ................ vendeur.produits.index
vendeur/commandes ............... vendeur.commandes
vendeur/profil .................. vendeur.profil
```

### Vérifier les fichiers
```bash
ls -la resources/views/vendeur/
```

Devrait afficher :
```
layout.blade.php          (master layout)
apercu.blade.php          (dashboard)
stock.blade.php           (stock management)
messages.blade.php        (messages)
historique.blade.php      (order history)
profil.blade.php          (profile - adapté)
commandes.blade.php       (orders - adapté)
produits/
  ├── index.blade.php     (adapté)
  ├── create.blade.php
  ├── edit.blade.php
  └── show.blade.php
```

---

## 🐛 Dépannage

### Problem: "404 Not Found" sur vendeur/apercu
**Solution** :
1. Vérifier que `routes/web.php` a les routes ajoutées
2. Exécuter `php artisan route:cache` puis `php artisan route:clear`
3. Vérifier que le middleware `vendeur` existe

### Problem: "Unauthorized" ou redirection vers login
**Solution** :
1. S'assurer d'être connecté (`php artisan tinker` → `Auth::user()`)
2. Vérifier que l'utilisateur a `role = 'vendeur'`

### Problem: Sidebar absent
**Solution** :
1. Vérifier que le fichier `vendeur/layout.blade.php` existe
2. Vérifier que les autres fichiers héritent de ce layout avec `@extends('vendeur.layout')`

### Problem: Menu items ne se mettent pas en surbrillance
**Solution** :
1. Vérifier que `Route::currentRouteName()` retourne le bon nom
2. Dans `layout.blade.php`, vérifier la condition `$activeRoute == 'vendeur.apercu'`

---

## 📊 Exemple de Test

### 1. Accéder à Aperçu
```
URL: http://localhost:8000/vendeur/apercu
Attendu: Dashboard avec 5 cartes stats + 2 charts
```

### 2. Accéder à Stock
```
URL: http://localhost:8000/vendeur/stock
Attendu: Table avec 5 produits + indicateurs d'état
```

### 3. Accéder à Messages
```
URL: http://localhost:8000/vendeur/messages
Attendu: Liste de 5 messages avec indicateurs
```

### 4. Cliquer sur menu items
```
Depuis Aperçu:
- Cliquer sur "📦 Produits"
- Page change vers products
- Menu item se met en surbrillance
```

---

## 🎓 Données de Test

Pour tester avec un vendeur, créer un utilisateur :

```php
// Terminal avec artisan tinker
php artisan tinker

$user = User::create([
    'name' => 'Jean Vendeur',
    'email' => 'vendeur@test.com',
    'password' => bcrypt('password'),
    'role' => 'vendeur'
]);

// Puis Login avec : vendeur@test.com / password
```

---

**Guide créé le** : `2025-12-03`  
**État** : ✅ Prêt pour usage
