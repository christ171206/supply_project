# 🏛️ Guide Administrateur - Système de Rôle Admin

## 📋 Table des Matières
1. [Aperçu du Rôle](#aperçu-du-rôle)
2. [Connexion et Redirection](#connexion-et-redirection)
3. [Tableau de Bord Admin](#tableau-de-bord-admin)
4. [Mode Visualisation Client](#mode-visualisation-client)
5. [Restrictions Administrateur](#restrictions-administrateur)
6. [Guides Opérationnels](#guides-opérationnels)

---

## Aperçu du Rôle

L'**Administrateur** est le superviseur global de la plateforme Supply.

### Mission Principale
- ✅ **Contrôler** la plateforme
- ✅ **Superviser** les utilisateurs et les vendeurs
- ✅ **Valider** les demandes (vendeurs, documents KYC)
- ✅ **Garantir** la cohérence et la sécurité

### Responsabilité Clé
**L'Admin supervise, il ne commerce pas.**

---

## 🔐 Connexion et Redirection

### Workflow de Connexion

```
1. Utilisateur se connecte avec account admin
   ↓
2. Authentification réussie
   ↓
3. Détection automatique du rôle admin
   ↓
4. Redirection automatique vers → /admin/dashboard
   ↓
5. Pas d'accès à la page d'accueil publique
```

### Routes Admin Accessibles
- `GET /admin/dashboard` - Tableau de bord principal
- `GET /admin/users` - Gestion des utilisateurs
- `GET /admin/users/{id}` - Détails utilisateur
- `GET /admin/users/{id}/documents` - Vérification KYC
- `GET /admin/products` - Supervision produits
- `GET /admin/orders` - Supervision commandes
- `GET /admin/reports` - Rapports et statistiques

---

## 📊 Tableau de Bord Admin

### Statistiques Principales Affichées
1. **Chiffre d'affaires** - Commissions totales perçues
2. **Taux Commission** - Pourcentage par transaction
3. **Vendeurs à Valider** - Demandes en attente
4. **Produits à Valider** - Nouveaux produits
5. **Commandes** - Tous les statuts
6. **Utilisateurs** - Total et actifs

### Sections du Dashboard
- Statistiques clés (KPI Cards)
- Graphiques d'évolution mensuelle
- Tableau des produits en attente
- Tableau des vendeurs à valider
- Tableau des disputes ouvertes
- Tableau des commandes récentes

### Bouton Mode Visualisation Client
Un bouton **"👁️ Mode Visualisation Client"** est disponible pour tester la plateforme comme un client régulier.

---

## 🧭 Mode Visualisation Client

### Qu'est-ce que c'est ?

Le **Mode Visualisation Client** permet à l'administrateur de :
- ✅ Parcourir le catalogue de produits
- ✅ Consulter les fiches produits
- ✅ Tester l'expérience utilisateur
- ✅ Vérifier l'affichage des contenus

### Restrictions en Mode Client
- ❌ **Impossible d'ajouter au panier**
- ❌ **Impossible de passer une commande**
- ❌ **Impossible d'effectuer un paiement**
- ❌ **Impossible d'envoyer des messages**
- ❌ **Impossible de laisser des avis**

### Comment Activer le Mode Client

#### Depuis le Dashboard Admin
1. Cliquez sur le bouton **"👁️ Mode Visualisation Client"**
2. Vous êtes redirigé vers la page d'accueil
3. Un **badge bleu** apparaît en haut de la page indiquant que vous êtes en mode client
4. Vous pouvez maintenant naviguer comme un client

### Comment Revenir au Mode Admin

#### Option 1 : Via le Badge
1. Le badge "👁️ MODE VISUALISATION CLIENT ACTIF" apparaît en haut
2. Cliquez sur **"← Revenir à l'Admin"**
3. Vous êtes redirigé vers `/admin/dashboard`

#### Option 2 : Via la Déconnexion
- Cliquez sur "Déconnexion"
- Reconnectez-vous avec votre compte admin
- Redirection automatique vers `/admin/dashboard`

---

## 🚫 Restrictions Administrateur

### Actions Interdites pour l'Admin

#### Gestion du Panier
- ❌ Ajouter des produits au panier
- ❌ Modifier les quantités
- ❌ Vider le panier
- ❌ Procéder à la livraison

#### Transactions Commerciales
- ❌ Passer une commande
- ❌ Effectuer un paiement
- ❌ Recevoir une livraison
- ❌ Solliciter un remboursement

#### Interaction Client
- ❌ Envoyer des messages directs aux utilisateurs
- ❌ Laisser des avis sur les produits
- ❌ Ajouter des produits aux favoris

#### Opérations Vendeur
- ❌ Ajouter des produits
- ❌ Modifier les prix des produits
- ❌ Gérer le stock
- ❌ Traiter les commandes en tant que vendeur

### Messages d'Erreur

Si un admin essaie une action interdite, il recevra un message comme :

```
❌ "Les administrateurs n'ont pas le droit d'ajouter des articles au panier. 
Activez le mode client pour tester la plateforme."
```

---

## 📋 Guides Opérationnels

### Guide 1 : Gestion des Utilisateurs

#### Consulter la Liste
1. Allez à `/admin/users`
2. Voir tous les utilisateurs avec leurs rôles
3. Cliquez sur un utilisateur pour voir les détails

#### Voir le Profil Détaillé
1. Cliquez sur un utilisateur
2. Consulter :
   - Informations personnelles
   - Rôle et statut
   - Documents KYC (si vendeur)
   - Produits (si vendeur)
   - Commandes (si client)
   - Litiges

#### Actions Disponibles
- 🚫 **Bloquer/Débloquer** - Barre l'accès à la plateforme
- ✓ **Approuver un Vendeur** - Valide le statut vendeur
- ✗ **Rejeter un Vendeur** - Refuse la demande vendeur

### Guide 2 : Validation des Vendeurs

#### Processus de Validation

```
1. Vendeur soumis candidate au statut
   ├─ Rempli le formulaire de vendeur
   └─ Soumis les documents KYC
   
2. Admin reçoit la demande
   ├─ Va à /admin/users
   ├─ Cherche le vendeur
   └─ Clique sur son profil
   
3. Admin vérifie les documents
   ├─ Va à /admin/users/{id}/documents
   ├─ Examine chaque document
   └─ Approuve ou rejette
   
4. Admin valide/rejette le vendeur
   ├─ Va à /admin/users/{id}
   ├─ Clique "Approuver" ou "Rejeter"
   └─ Confirmation
   
5. Vendeur reçoit notification
   └─ Peut maintenant vendre (si approuvé)
```

#### Vérification des Documents KYC
1. Allez à `/admin/users/{user_id}/documents`
2. Consultez les documents en attente
3. Téléchargez les fichiers pour examen
4. Approuvez ou rejetez avec motif

### Guide 3 : Supervision des Produits

#### Consulter les Produits
1. Allez à `/admin/products`
2. Voir tous les produits de tous les vendeurs

#### Actions PossiblesD
- 📋 Consulter les détails
- ⚠️ Désactiver un produit inapproprié
- 🗑️ Supprimer un produit non conforme
- 📊 Voir Historique du stock

### Guide 4 : Supervision des Commandes

#### Consulter les Commandes
1. Allez à `/admin/orders`
2. Voir toutes les commandes de la plateforme

#### Informations Affichées
- Numéro de commande
- Statut actuel
- Client
- Vendors
- Montant total
- Date de création

#### Actions
- 👁️ Consulter les détails
- 📊 Voir l'historique
- 📈 Analyser les tendances

### Guide 5 : Configuration de la Plateforme

#### Accès aux Paramètres
1. Allez à `/admin/configuration`
2. Modifiez les paramètres globaux :
   - Taux de commission
   - Paramètres de livraison
   - Politiques de retour
   - etc.

---

## 📞 Support et Aide

### En Cas de Problème

1. **Je ne peux pas accéder au dashboard**
   - Vérifiez que votre compte a le rôle `is_admin = 1`
   - Clearchez le cache du navigateur (Ctrl+Shift+Delete)
   - Reconnectez-vous

2. **Le bouton Mode Client ne fonctionne pas**
   - Vérifiez que JavaScript est activé
   - Vérifiez les logs du navigateur (F12)
   - Contactez le support technique

3. **Je vois une erreur "Action interdite"**
   - C'est normal! C'est une protection intentionnelle
   - Activez le mode client si vous voulez tester
   - Les admins ne doivent pas passer de commandes

### Documentation Complémentaire

- 📖 [API Admin](./admin-api.md)
- 🔍 [Rapports et Statistiques](./reports-guide.md)
- 🛡️ [Sécurité et Audit](./security-guide.md)

---

## ✅ Checklist d'Installation

Pour s'assurer que le système Admin est correctement configuré :

- [ ] Au moins un utilisateur a `is_admin = 1` dans la table `users`
- [ ] Routes admin sous middleware `['auth:web', 'admin']`
- [ ] Dashboard accessible depuis `/admin/dashboard`
- [ ] Mode client peut être activé et désactivé
- [ ] Admins reçoivent erreur quand tentent d'acheter
- [ ] Admins reçoivent erreur quand tentent d'envoyer messages

---

## 🎯 Résumé des Permissions

| Action | Admin | Admin en Mode Client | Client Régulier |
|--------|-------|----------------------|-----------------|
| Voir Dashboard | ✅ | ❌ | ❌ |
| Consulter Utilisateurs | ✅ | ❌ | ❌ |
| Approuver Vendeurs | ✅ | ❌ | ❌ |
| Naviguer Catalogue | ✅ | ✅ | ✅ |
| Ajouter au Panier | ❌ | ❌ | ✅ |
| Passer Commande | ❌ | ❌ | ✅ |
| Envoyer Messages | ❌ | ❌ | ✅ |
| Laisser Avis | ❌ | ❌ | ✅ |

---

## 📞 Contact Support

Pour toute question ou problème technique :
- 📧 **Email** : admin-support@supply.ci
- 📞 **Téléphone** : +225 27 20 00 00 00
- 💬 **Chat** : support.supply.ci

---

**Dernière mise à jour** : 4 Mars 2026
**Version** : 1.0
