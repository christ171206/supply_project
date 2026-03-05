# Système de Gestion Admin - Guide Complet

## Vue d'ensemble

Ce système d'administration complet vous permet de gérer tous les aspects de votre plateforme e-commerce. Il est divisé en plusieurs modules clés pour une gestion efficace.

## 1. Modules disponibles

### 1.1 **Gestion des Utilisateurs**
- **URL**: `/admin/users`
- **Fonctionnalités**:
  - Lister tous les utilisateurs (clients, vendeurs, admins)
  - Rechercher et filtrer par rôle et statut
  - Vérifier les documents KYC (pièce d'identité, registre de commerce)
  - Approuver ou rejeter les documents avec justification
  - Bannir les utilisateurs frauduleux ou non conformes
  - Débannir les utilisateurs
  - Assigner des rôles admin
  - Consulter l'historique d'activité

### 1.2 **Gestion des Produits et Stock**
- **URL**: `/admin/products`
- **Fonctionnalités**:
  - Lister tous les produits
  - Consulter les détails d'un produit
  - Ajuster manuellement le stock en cas d'erreur d'inventaire
  - Configurer les seuils d'alerte par produit
  - Voir les produits en stock critique
  - Consulter l'historique complet des mouvements de stock
  - Audit du stock sur une période donnée

### 1.3 **Supervision des Commandes**
- **URL**: `/admin/orders`
- **Fonctionnalités**:
  - Lister toutes les commandes avec filtres
  - Voir les détails complets d'une commande
  - Mettre à jour le statut de la commande
  - Suivre l'état de livraison en temps réel
  - Vue globale des livraisons en cours
  - Annuler les commandes bloquées

### 1.4 **Arbitrage des Litiges**
- **URL**: `/admin/disputes`
- **Fonctionnalités**:
  - Lister tous les litiges (non livré, mauvais produit, endommagé, etc.)
  - Consulter les détails complets
  - Mettre à jour le statut d'un litige
  - Résoudre les litiges avec remboursement, remplacement ou aucune action
  - Fermer les litiges résolus
  - Voir les litiges en attente d'action

### 1.5 **Configuration du Système**
- **URL**: `/admin/configuration`
- **Fonctionnalités**:
  - Définir les frais de livraison de base
  - Configurer le délai de livraison par défaut
  - Gérer le taux de change
  - Définir la commission de la plateforme
  - Créer et gérer les zones de livraison
  - Assigner les quartiers aux zones

### 1.6 **Rapports et Statistiques**
- **URL**: `/admin/reports`
- **Rapports disponibles**:
  - **Rapport financier**: Chiffre d'affaires par période, par jour, par vendeur
  - **Performance des vendeurs**: Nombre de commandes, délais de livraison
  - **Popularité des produits**: Top 5, Top 20, statistiques de vente
  - **Activité des utilisateurs**: Nouveaux utilisateurs, commandes récentes
  - **Audit du stock**: Historique des mouvements
  - **Export CSV**: Télécharger les rapports en CSV

## 2. Rôles Admin avec Permissions

### 2.1 **Super Admin (Administrateur Principal)**
Accès complet à toutes les fonctionnalités:
- Gestion des utilisateurs et documents
- Gestion du stock et catalogue
- Supervision des commandes
- Arbitrage des litiges
- Configuration du système
- Rapports et statistiques

### 2.2 **Stock Manager (Gestionnaire de Stock)**
Permissions limitées au stock:
- Voir les produits
- Ajuster le stock
- Configurer les seuils d'alerte
- Voir l'audit du stock
- Rapports sur le stock

### 2.3 **Order Manager (Gestionnaire de Commandes)**
Permissions pour les commandes:
- Voir toutes les commandes
- Mettre à jour les statuts
- Suivre les livraisons
- Arbitrer les litiges
- Rapports sur les commandes

### 2.4 **Financial Manager (Gestionnaire Financier)**
Permissions limitées aux rapports:
- Voir les rapports financiers
- Exporter les données

## 3. Procédures Clés

### 3.1 **Vérifier un vendeur (KYC)**
1. Aller à Utilisateurs
2. Cliquer sur "Documents" pour un vendeur
3. Consulter les documents fournis
4. Cliquer "Approuver" après vérification
5. Le vendeur sera marqué comme "vérifié"

### 3.2 **Bannir un utilisateur frauduleux**
1. Aller à Utilisateurs
2. Cliquer le bouton "Bannir"
3. Sélectionner la raison (fraude, livraison tardive, etc.)
4. Ajouter des détails
5. Optionnel: Définir une durée de bannissement

### 3.3 **Ajuster le stock**
1. Aller à Produits
2. Cliquer "Ajuster" sur le produit
3. Entrer la quantité (+/-)
4. Sélectionner la raison (erreur, casse, etc.)
5. Ajouter des notes si nécessaire

### 3.4 **Résoudre un litige**
1. Aller à Litiges
2. Cliquer sur le litige
3. Lire la description du client et vendeur
4. Choisir la résolution:
   - **Remboursement complet**: Rembourser toute la commande
   - **Remboursement partiel**: Rembourser une partie
   - **Remplacement**: Le vendeur doit renvoyer le produit
   - **Aucune action**: Rejeter la réclamation
5. Ajouter une justification détaillée
6. Valider

### 3.5 **Créer une zone de livraison**
1. Aller à Configuration > Zones de Livraison
2. Cliquer "Créer une zone"
3. Entrer le nom (ex: "Centre-Ville")
4. Définir les frais de livraison
5. Définir le délai de livraison (en jours)
6. Sélectionner les quartiers inclus
7. Valider

## 4. Dashboard Principal

Le dashboard principal affiche:
- **Statistiques clés**: Total utilisateurs, commandes, revenus
- **Alertes**: Litiges en attente, utilisateurs bannis
- **Commandes récentes**: Dernières 10 commandes
- **Litiges ouverts**: Litiges en attente de résolution
- **Top produits**: 5 produits les plus vendus
- **Top vendeurs**: 5 meilleurs vendeurs

## 5. Installation des Migrations

Pour mettre en place le système admin:

```bash
# Exécuter les migrations
php artisan migrate

# Seeder les données initiales (rôles, configurations)
php artisan db:seed --class=AdminSeeder

# Créer un administrateur (manuel)
php artisan tinker
```

## 6. Création d'un Administrateur

Via Laravel Tinker:
```php
$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@supply.ci',
    'password' => bcrypt('admin123'),
    'role' => 'admin',
    'is_admin' => true,
]);

$superAdminRole = App\Models\AdminRole::where('name', 'super_admin')->first();
$user->update(['admin_role_id' => $superAdminRole->id]);
```

## 7. Sécurité

Les routes admin sont protégées par:
- **Authentification**: L'utilisateur doit être connecté
- **Middleware Admin**: Only users with `is_admin = true` can access
- **Permissions basées sur les rôles**: Chaque rôle a des permissions spécifiques

## 8. Modèles de Données

### AdminRole
- `name`: Nom du rôle
- `description`: Description
- `permissions`: JSON array des permissions

### UserDocument
- `user_id`: Référence à l'utilisateur
- `document_type`: Type de document (ID, passeport, etc.)
- `document_path`: Chemin du fichier
- `status`: pending, approved, rejected
- `verified_by`: Admin qui a vérifié
- `verified_at`: Date de vérification

### UserBan
- `user_id`: Utilisateur banni
- `reason`: Raison du bannissement
- `is_active`: Ban actif?
- `banned_by`: Admin qui a banni
- `unbanned_at`: Date de débannissement

### Dispute
- `commande_id`: Commande concernée
- `user_id`: Client
- `vendor_id`: Vendeur
- `type`: non_delivery, wrong_product, damaged, quality_issue
- `status`: open, under_review, resolved, closed
- `resolution`: Résolution appliquée

### StockAlert
- `produit_id`: Produit
- `alert_threshold`: Seuil d'alerte
- `reorder_quantity`: Quantité à commander

### DeliveryZone
- `name`: Nom de la zone
- `delivery_fee`: Frais de livraison
- `delivery_days`: Délai en jours

### DeliveryTracking
- `commande_id`: Commande
- `latitude`, `longitude`: Position
- `status`: pending, picked_up, in_transit, delivered, failed

## 9. Fonctionnalités Futures

- [ ] Intégration avec un service de SMS pour les notifications
- [ ] Génération automatique de bons de livraison
- [ ] API pour les livreurs
- [ ] Système de rappels automatiques
- [ ] Dashboard temps réel avec WebSockets
- [ ] Emails de notification automatiques
- [ ] Export avancé (PDF, Excel)
- [ ] Audit trail complet des actions admin

## 10. Support et Dépannage

### Problème: Accès refusé aux routes admin
**Solution**: Vérifier que `is_admin = true` dans la table users et qu'un rôle admin est assigné.

### Problème: Les documents n'apparaissent pas
**Solution**: Vérifier que les fichiers sont uploadés dans le dossier public/documents

### Problème: Les emails de notification ne s'envoient pas
**Solution**: Vérifier la configuration MAIL dans .env

## Contact et Assistance

Pour toute question ou rapport de bug, veuillez contacter l'équipe de développement.
