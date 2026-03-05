# Mise en Place du Système Admin - Instructions d'Installation

## 📋 Résumé des Modifications

Vous avez maintenant un système admin complet avec les éléments suivants:

### ✅ Migrations créées (8 fichiers)
1. **2026_03_02_000001_create_admin_roles_table.php** - Rôles admin avec permissions
2. **2026_03_02_000002_create_user_documents_table.php** - Vérification KYC des vendeurs
3. **2026_03_02_000003_create_user_bans_table.php** - Gestion des bannissements
4. **2026_03_02_000004_create_stock_alerts_table.php** - Seuils d'alerte de stock
5. **2026_03_02_000005_create_disputes_table.php** - Arbitrage des litiges
6. **2026_03_02_000006_create_system_configurations_table.php** - Paramètres globaux
7. **2026_03_02_000007_create_delivery_zones_table.php** - Zones de livraison
8. **2026_03_02_000008_create_delivery_tracking_table.php** - Suivi des livraisons

### ✅ Modèles créés (8 fichiers)
- AdminRole
- UserDocument
- UserBan
- StockAlert
- Dispute
- SystemConfiguration
- DeliveryZone
- DeliveryTracking

### ✅ Contrôleurs créés (7 fichiers)
- AdminDashboardController
- AdminUserController
- AdminProductController
- AdminOrderController
- AdminDisputeController
- AdminConfigurationController
- AdminReportController

### ✅ Routes admin
- Toutes les routes de gestion admin dans `/routes/admin.php`
- Middleware de protection des routes admin

### ✅ Vues créées (5 vues principales)
- Dashboard principal
- Gestion des utilisateurs
- Gestion des produits
- Gestion des commandes
- Gestion des litiges
- Configuration du système

---

## 🚀 Étapes d'Installation Rapide

### Étape 1: Exécuter les Migrations

```bash
# Naviguer au répertoire du projet
cd d:\wamp\www\Supply

# Exécuter toutes les migrations
php artisan migrate

# Ou seulement les migrations admin
php artisan migrate --path=database/migrations/2026_03_02*
```

### Étape 2: Exécuter le Seeder Admin

```bash
# Créer les rôles et configurations par défaut
php artisan db:seed --class=AdminSeeder
```

### Étape 3: Créer un Compte Administrateur

Option A - Via Artisan Tinker (Interactif):
```bash
php artisan tinker
```

Puis exécuter:
```php
// Créer l'utilisateur admin
$admin = App\Models\User::create([
    'name' => 'Admin Principal',
    'email' => 'admin@supply.ci',
    'password' => bcrypt('admin123'),
    'role' => 'admin',
]);

// Obtenir le rôle super_admin
$superAdminRole = App\Models\AdminRole::where('name', 'super_admin')->first();

// Assigner le rôle
$admin->update([
    'is_admin' => true,
    'admin_role_id' => $superAdminRole->id,
]);

// Vérifier que c'est un admin
dd($admin->isAdmin()); // Devrait afficher true
```

Option B - Via Laravel Artisan Command (Si créée):
```bash
php artisan admin:create --name="Admin Principal" --email="admin@supply.local" --password="admin123"
```

### Étape 4: Accéder au Dashboard Admin

1. Se connecter avec le compte admin
2. Accéder à: `http://localhost/admin/` ou `http://supply.local/admin/`

---

## 📊 Fonctionnalités Clés par Module

### 1️⃣ **Gestion des Utilisateurs** (`/admin/users`)
```
- Lister tous les utilisateurs
- Valider les documents KYC
- Bannir/débannir les utilisateurs
- Assigner des rôles admin
- Consulter l'historique d'activité
```

### 2️⃣ **Gestion des Produits** (`/admin/products`)
```
- Vue complète du catalogue
- Ajustement manuel du stock
- Seuils d'alerte configurables
- Historique des mouvements de stock
- Audit du stock
```

### 3️⃣ **Supervision des Commandes** (`/admin/orders`)
```
- Lister toutes les commandes
- Mettre à jour les statuts
- Suivre les livraisons en temps réel
- Vue globale des livraisons
- Annuler les commandes bloquées
```

### 4️⃣ **Arbitrage des Litiges** (`/admin/disputes`)
```
- Lister tous les litiges
- Consulter les détails
- Résoudre avec remboursement/remplacement
- Suivre l'évolution
```

### 5️⃣ **Configuration** (`/admin/configuration`)
```
- Frais de livraison
- Délais de livraison
- Taux de change
- Commission de plateforme
- Zones de livraison
```

### 6️⃣ **Rapports** (`/admin/reports`)
```
- Rapport financier
- Performance des vendeurs
- Popularité des produits
- Activité des utilisateurs
- Audit du stock
- Export CSV
```

---

## 🔐 Sécurité et Authentification

### Routes Protégées
Toutes les routes admin requièrent:
- ✅ Authentification (`auth:sanctum`)
- ✅ Middleware admin (`admin`)
- ✅ Utilisateur avec `is_admin = true`
- ✅ Rôle admin assigné

### Vérifier que vous êtes Admin
```php
auth()->user()->isAdmin(); // true/false
auth()->user()->adminRole->hasPermission('manage_users'); // true/false
```

---

## 🛠️ Configuration Additionnelle

### Créer des rôles personnalisés
```php
App\Models\AdminRole::create([
    'name' => 'custom_role',
    'description' => 'Description du rôle',
    'permissions' => ['permission1', 'permission2'],
]);
```

### Assigner un rôle à un utilisateur
```php
$user = App\Models\User::find(1);
$role = App\Models\AdminRole::where('name', 'stock_manager')->first();
$user->update(['admin_role_id' => $role->id, 'is_admin' => true]);
```

### Modifier une configuration
```php
// Via le modèle
App\Models\SystemConfiguration::set('delivery_base_fee', 3000, 'number');

// Via la forme GET
$fee = App\Models\SystemConfiguration::get('delivery_base_fee');
```

---

## 🧪 Tester le Système

### Test rapide via Artisan
```bash
php artisan tinker

# Créer un utilisateur test
$user = App\Models\User::factory()->create(['role' => 'vendor']);

# Créer un document test
$user->documents()->create([
    'document_type' => 'id_card',
    'document_path' => 'path/to/file.pdf',
    'status' => 'pending',
]);

# Bannir l'utilisateur
$admin = App\Models\User::where('is_admin', true)->first();
$user->ban($admin, 'fraud', 'Comportement suspect');

# Vérifier le bannissement
$user->refresh();
$user->isBanned(); // true

# Débannir
$user->activeBan->unban($admin);
```

---

## 📝 Fichiers et Répertoires Créés

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── AdminDashboardController.php
│   │       ├── AdminUserController.php
│   │       ├── AdminProductController.php
│   │       ├── AdminOrderController.php
│   │       ├── AdminDisputeController.php
│   │       ├── AdminConfigurationController.php
│   │       └── AdminReportController.php
│   └── Middleware/
│       └── AdminMiddleware.php
│
├── Models/
│   ├── AdminRole.php
│   ├── UserDocument.php
│   ├── UserBan.php
│   ├── StockAlert.php
│   ├── Dispute.php
│   ├── SystemConfiguration.php
│   ├── DeliveryZone.php
│   └── DeliveryTracking.php
│
database/
├── migrations/
│   ├── 2026_03_02_000001_create_admin_roles_table.php
│   ├── 2026_03_02_000002_create_user_documents_table.php
│   ├── 2026_03_02_000003_create_user_bans_table.php
│   ├── 2026_03_02_000004_create_stock_alerts_table.php
│   ├── 2026_03_02_000005_create_disputes_table.php
│   ├── 2026_03_02_000006_create_system_configurations_table.php
│   ├── 2026_03_02_000007_create_delivery_zones_table.php
│   └── 2026_03_02_000008_create_delivery_tracking_table.php
│
├── seeders/
│   └── AdminSeeder.php
│
resources/
└── views/
    └── admin/
        ├── dashboard.blade.php
        ├── users/
        │   └── index.blade.php
        ├── products/
        │   └── index.blade.php
        ├── orders/
        │   └── index.blade.php
        ├── disputes/
        │   └── index.blade.php
        ├── configuration/
        │   └── index.blade.php
        └── reports/
            └── (à compléter)

routes/
├── admin.php (nouveau)
└── web.php (modifié pour inclure admin.php)
```

---

## ⚠️ Points à Vérifier

- [ ] Les migrations s'exécutent sans erreur
- [ ] Le seeder crée les rôles correctement
- [ ] Un administrateur peut se connecter
- [ ] Les routes `/admin/*` sont accessibles
- [ ] Le middleware admin bloque l'accès aux non-admins
- [ ] Les documents peuvent être uploadés
- [ ] Les stocks peuvent être ajustés
- [ ] Les litiges peuvent être résolus

---

## 🚨 Dépannage Courant

### Erreur: "Access denied"
```
Cause: L'utilisateur n'a pas is_admin = true
Solution: UPDATE users SET is_admin = true WHERE id = 1;
```

### Erreur: "Table 'admin_roles' doesn't exist"
```
Cause: Les migrations n'ont pas été exécutées
Solution: php artisan migrate
```

### Les vues n'affichent pas les données
```
Cause: Les modèles ne sont pas complètement chargés
Solution: Vérifier les relations dans les contrôleurs avec ->with('relations')
```

---

## 📚 Documentation Complète

Consultez le fichier `ADMIN_GUIDE.md` pour:
- Guide détaillé de chaque module
- Procédures pas à pas
- Explications des rôles
- FAQ

---

## ✨ Prochaines Étapes Recommandées

1. **Personnaliser les vues** - Adapter le style à votre brand
2. **Ajouter des logs d'audit** - Tracer toutes les actions admin
3. **Mettre en place des notifications** - Alertes par email/SMS
4. **Sauvegardes régulières** - Données critiques
5. **Tests automatisés** - Tester les contrôleurs
6. **Documentation API** - Si besoin d'intégration

---

## 📞 Support

Pour plus d'informations ou en cas de problème, consultez:
- ADMIN_GUIDE.md
- La documentation Laravel: https://laravel.com/docs
- Les commentaires dans le code

Bon administrage! 🎉
