# SUPPLY - Marketplace B2B Matériel Informatique

## Vue d'Ensemble

**Supply** est une marketplace B2B spécialisée dans la vente de matériel informatique. La plateforme connecte des vendeurs vérifiés avec des acheteurs professionnels, offrant une sélection de produits tech sourcés directement auprès de fournisseurs de confiance.

### Tagline
> "Le matériel tech, sans compromis" - Des milliers de produits informatiques sourcés directement auprès de vendeurs vérifiés.

---

## Fonctionnalités Principales

### Pour les Clients
- **Catalogue produits** : Navigation par catégories, recherche et filtres
- **Profils vendeurs vérifiés** : Affichage de la confiance (99% vendeurs vérifiés)
- **Panier et checkout** : Processus d'achat complet
- **Commandes** : Historique et suivi des commandes

### Pour les Vendeurs
- **Dashboard personnalisé** : Vue d'ensemble de l'activité (produits, stock, commandes, revenu)
- **Gestion des produits** : Ajouter, modifier, supprimer des articles
- **Gestion du stock** : Surveillance de l'inventaire en temps réel
- **Gestion des commandes** : Traitement des commandes en attente
- **Tableau de bord analytique** : Statistiques de ventes et performance

### Pour les Administrateurs
- **Gestion des utilisateurs** : Création, modification, suppression d'utilisateurs
- **Approbation des vendeurs** : Vérification et validation des vendeurs
- **Gestion des catégories** : Configuration du catalogue
- **Signalements et modération** : Gestion du contenu
- **Notifications en temps réel** : Système de notifications Pusher intégré

---

## Architecture Technique

### Stack Technologique
- **Backend** : Laravel 11 (PHP)
- **Frontend** : Blade Templates + Tailwind CSS + Alpine.js
- **Real-time** : Pusher (notifications)
- **Base de données** : MySQL
- **Build Tools** : Vite + PostCSS
- **Testing** : PHPUnit

### Rôles et Authentification
1. **Admin** : Contrôle total du système
2. **Vendor** : Gestion de propre boutique
3. **Client** : Achat et navigation

### Modules Clés
- `app/Http/Controllers/` : Contrôleurs pour les routes
- `app/Models/` : Modèles de données (User, Product, Order, etc.)
- `routes/` : Définition des routes (web, api, admin)
- `resources/views/` : Templates Blade
- `app/Events/` : Événements (OrderCreated, OrderStatusChanged, VendorApprovalStatusChanged, NewMessage)
- `app/Mail/` : Envoi d'emails
- `app/Services/` : Logique métier

---

## Design System

### Philosophie
**Minimal et épuré** : Design neutre, professionnel, centré sur le contenu.

### Couleurs
- **Noir** (#0a0a0a) : Texte principal, boutons primaires
- **Blanc** (#ffffff) : Arrière-plans
- **Grays** : Hiérarchie textuelle et éléments secondaires
- **Aucune couleur supplémentaire** : Approche minimaliste rigide

### Typographie
- **Instrument Serif** : Titres (elegant, impactant)
- **Geist** : Corps du texte (moderne, lisible)
- **Geist Mono** : Nombres et données techniques (prix, statistiques)

### Composants
- **Cartes produits** : 220px, image 200px, prix en monospace
- **Boutons** : Padding 0.75rem 1.5rem, border-radius 4px
- **Navbar** : Sticky, hauteur 3.5rem, white bg
- **Forms** : Border gray-200, focus black

---

## Statistiques Clés (Façade)
- **2 400+** Produits listés
- **186** Vendeurs actifs
- **99%** Vendeurs vérifiés

---

## État du Projet

### Complétés
✅ Authentification (Login/Register/Two-Factor)
✅ Système de rôles (Admin, Vendor, Client)
✅ Gestion des produits (CRUD)
✅ Panier et commandes
✅ Notifications en temps réel (Pusher)
✅ Gestion du stock
✅ API endpoints
✅ Design system minimaliste
✅ Support multi-langue (FR/EN)
✅ System d'approbation des vendeurs

### Integration Points
- Mailer avec configuration SMTP
- Pusher pour real-time
- File storage (images produits, catégories)
- Queue system pour jobs async

---

## Points de Présentation Clés

### Pitch Court (30 secondes)
"Supply est une marketplace B2B dédiée au matériel informatique. On connecte des vendeurs vérifiés avec des acheteurs professionnels. Le concept : 2400+ produits tech, 186 vendeurs, 99% de confiance. Design minimaliste, fonctionnalités modernes."

### Pitch Moyen (2 minutes)
1. **Le problème** : Marché du B2B tech fragmenté, manque de plateforme de confiance
2. **Notre solution** : Marketplace centralisée avec vendeurs vérifiés
3. **Fonctionnalités** : 
   - Dashboard vendors pour gérer boutique
   - Gestion stock en temps réel
   - Notifications instantanées
   - Processus d'achat fluide
4. **Design** : Minimaliste, professionnel, focus sur l'utilisabilité
5. **Traction** : Système complet prêt pour production

### Démo Live
1. **Home Page** : Montrer le hero section, stats, catégories
2. **Vendor Dashboard** : Montrer stats, produits récents, actions rapides
3. **Catalog** : Filtrer par catégorie, voir les produits
4. **Admin Panel** : Gestion des vendeurs, approbations

---

## Commandes Utiles

```bash
# Installation
composer install
npm install

# Migration DB
php artisan migrate

# Seed test data
php artisan db:seed

# Lancer le dev server
php artisan serve

# Compiler assets
npm run dev

# Tests
php artisan test
```

---

## Fichiers Importants à Connaître

- `QUICK_START.md` : Guide de démarrage rapide
- `INSTALLATION.md` : Installation détaillée
- `README.md` : Documentation générale
- `ADMIN_SETUP.md` : Configuration admin
- `VENDOR_REGISTRATION_IMPLEMENTATION.md` : Flux inscription vendeurs
- `resources/views/accueil.blade.php` : Page d'accueil principale

---

## Notes de Clarification pour la Présentation

- Insister sur la vérification des vendeurs (99% = confiance)
- Montrer la simplicité du UX design
- Expliquer la séparation des rôles (client vs vendor vs admin)
- Souligner les fonctionnalités temps réel (Pusher)
- Mentionner que le système est prêt pour scaler
