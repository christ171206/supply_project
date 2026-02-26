# 🗄️ Guide de Configuration de la Base de Données - Supply

## ✅ Problème Résolu

**Erreur initiale:**
```
SQLSTATE[HY000] [1049] Base 'gestion_stock_ecommerce' inconnue
```

**Cause:** La base de données `gestion_stock_ecommerce` n'avait pas été créée sur le serveur MySQL.

**Solution:** Base de données créée et migrations exécutées avec succès.

---

## 📋 Configuration Actuelle

### Paramètres de Connexion
Fichier: `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_stock_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

### Structure de la Base de Données
La base de données contient les tables suivantes:

| Table | Description |
|-------|-------------|
| `users` | Utilisateurs (clients, vendeurs) |
| `categories` | Catégories de produits |
| `produits` | Produits disponibles |
| `panier_items` | Articles dans les paniers |
| `commandes` | Commandes clients |
| `ligne_commandes` | Détail des commandes |
| `stock` | Gestion du stock |
| `stock_mouvements` | Historique des mouvements |
| `payments` | Paiements et transactions |
| `avis` | Avis et évaluations |
| `favoris` | Produits favoris |
| `messages` | Messages entre utilisateurs |
| `migrations` | Historique des migrations |

---

## 🚀 Commandes Usuelles

### Créer les tables (si non existantes)
```bash
php artisan migrate
```

### Réinitialiser complètement la base de données
```bash
php artisan migrate:fresh
```
⚠️ **Attention:** Supprime TOUTES les données

### Réinitialiser et remplir avec des données de test
```bash
php artisan migrate:fresh --seed
```
Crée les tables ET remplit les tables avec des données de teste (utilisateurs, produits).

### Voir le statut des migrations
```bash
php artisan migrate:status
```

### Annuler la dernière migration
```bash
php artisan migrate:rollback
```

### Vider la cache de la base de données
```bash
php artisan cache:clear
```

---

## 📊 Données Initiales Chargées

Après `migrate:fresh --seed`, les données suivantes sont disponibles:

### Utilisateurs
- **Administrateur**: 1 utilisateur admin
- **Vendeurs**: Plusieurs vendeurs avec produits
- **Clients**: Plusieurs clients de test

### Produits
- **Catégories**: Catégories informatiques variées
- **Produits**: ~50+ produits avec prix, stock, images

### Avis & Favoris
- Données de test pour avis produits
- Données de test pour favoris

---

## 🔧 Troubleshooting

### Erreur: "Base inconnue"
```
SQLSTATE[HY000] [1049] Base 'gestion_stock_ecommerce' inconnue
```

**Solution:**
```bash
php artisan tinker
# Dans le REPL:
>>> DB::statement("CREATE DATABASE IF NOT EXISTS gestion_stock_ecommerce CHARACTER SET utf8mb4")
```

Ou manuellement dans MySQL:
```sql
CREATE DATABASE IF NOT EXISTS gestion_stock_ecommerce 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

### Erreur: "Table inconnue"
```
SQLSTATE[42S02]: Table 'gestion_stock_ecommerce.categories' doesn't exist
```

**Solution:**
```bash
php artisan migrate
```

### Erreur: Données corrompues/incohérentes
**Solution:**
```bash
php artisan migrate:fresh --seed
```

### Performance lente
**Vider le cache:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📝 Créer une Nouvelle Migration

Pour ajouter une table ou modifier une existante:

```bash
php artisan make:migration create_nouvelle_table
php artisan make:migration add_colonne_to_table
```

Éditer le fichier créé dans `database/migrations/`, puis:
```bash
php artisan migrate
```

---

## 🔐 Backups

### Exporter la base de données
```bash
mysqldump -h 127.0.0.1 -u root gestion_stock_ecommerce > backup.sql
```

### Importer une sauvegarde
```bash
mysql -h 127.0.0.1 -u root gestion_stock_ecommerce < backup.sql
```

---

## 📋 Checklist de Déploiement

Avant de déployer en production:

- [ ] Créer un compte MySQL de production (pas root)
- [ ] Définir des mots de passe forts
- [ ] Créer la base de données de production
- [ ] Exécuter les migrations
- [ ] Faire un backup de la base de données
- [ ] Tester l'accès depuis l'application
- [ ] Vérifier les logs d'erreur

---

## 📞 Support

Si vous rencontrez des problèmes:

1. Vérifiez que MySQL est en cours d'exécution (sur WAMP: cliquez sur l'icône WAMP)
2. Vérifiez la configuration `.env`
3. Exécutez `php artisan migrate --seed`
4. Consultez les logs: `storage/logs/laravel.log`

---

**Dernière mise à jour:** 10 février 2026  
**Status:** ✅ Base de données configurée et fonctionnelle
