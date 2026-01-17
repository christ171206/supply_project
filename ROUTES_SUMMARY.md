# 📋 Inventaire des Routes - Supply

## Routes Publiques

### Produits
- `GET /` → **Accueil** (index produits)
- `GET /produits` → **Catalogue** (tous les produits)
- `GET /produits/{id}` → **Détail produit**

### API
- `GET /api/produits/{ids}` → Récupérer les détails produits (JSON)

### Panier
- `GET /panier` → Voir le panier
- `GET /panier/count` → Nombre d'articles (JSON)
- `POST /panier/ajouter/{produitId}` → Ajouter au panier
- `PATCH /panier/{itemId}` → Modifier la quantité
- `DELETE /panier/{itemId}` → Supprimer du panier
- `POST /panier/vider` → Vider le panier

---

## Routes Authentifiées

### Dashboard Client
- `GET /dashboard` → Tableau de bord client
- `GET /mes-commandes` → Liste des commandes
- `GET /commande/{id}` → Détail de la commande
- `GET /mon-profil` → Profil utilisateur
- `PUT /mon-profil` → Modifier le profil

### Commandes
- `GET /commandes` → Liste des commandes
- `GET /commandes/paiement` → Formulaire de paiement
- `GET /commandes/{id}` → Détail de la commande
- `GET /commandes/{id}/facture` → Facture (ancienne route)
- `GET /commandes/{id}/download-pdf` → Télécharger la facture PDF
- `POST /commandes` → Créer une commande

### Profil (Breeze)
- `GET /profile` → Éditer le profil
- `PATCH /profile` → Mettre à jour le profil
- `DELETE /profile` → Supprimer le compte

### Avis
- `POST /avis` → Ajouter un avis
- `DELETE /avis/{id}` → Supprimer un avis

### Favoris
- `GET /favoris` → Liste des favoris
- `POST /favoris/{productId}/toggle` → Ajouter/Retirer des favoris
- `GET /favoris/{productId}/check` → Vérifier si en favoris

### Messages
- `GET /messages` → Inbox (liste des conversations)
- `GET /messages/{userId}` → Conversation avec un utilisateur
- `POST /messages` → Envoyer un message
- `POST /messages/{userId}/reply` → Répondre dans une conversation
- `DELETE /messages/{messageId}` → Supprimer un message
- `GET /messages/unread/count` → Nombre de messages non lus

---

## Routes Vendeur (avec middleware `auth` + `vendeur`)

Préfixe: `/vendeur/`

### Dashboard
- `GET /vendeur/dashboard` → Tableau de bord vendeur
- `GET /vendeur/apercu` → Aperçu des ventes

### Gestion de Stock
- `GET /vendeur/stock` → Gérer le stock

### Analytics
- `GET /vendeur/statistiques` → Statistiques des ventes

### Communication
- `GET /vendeur/messages` → Messages des clients

### Avis
- `GET /vendeur/avis` → Avis clients

### Paramètres
- `GET /vendeur/parametres` → Paramètres de la boutique
- `PUT /vendeur/parametres` → Mettre à jour les paramètres
- `DELETE /vendeur/parametres` → Supprimer la boutique

### Historique
- `GET /vendeur/historique` → Historique des modifications

### Profil Vendeur
- `GET /vendeur/profil` → Profil vendeur
- `PUT /vendeur/profil` → Modifier le profil

### Gestion des Produits (Resource)
- `GET /vendeur/produits` → Liste des produits
- `GET /vendeur/produits/create` → Créer un produit
- `POST /vendeur/produits` → Enregistrer un produit
- `GET /vendeur/produits/{id}` → Détail du produit
- `GET /vendeur/produits/{id}/edit` → Éditer un produit
- `PUT /vendeur/produits/{id}` → Mettre à jour un produit
- `DELETE /vendeur/produits/{id}` → Supprimer un produit

### Commandes Vendeur
- `GET /vendeur/commandes` → Commandes reçues
- `GET /vendeur/commandes/{id}` → Détail de la commande

---

## Routes d'Authentification (Breeze)

Routes fournies par Laravel Breeze (voir `routes/auth.php`)

- `GET /register` → Formulaire d'inscription
- `POST /register` → Enregistrer un nouvel utilisateur
- `GET /login` → Formulaire de connexion
- `POST /login` → Se connecter
- `POST /logout` → Se déconnecter
- `GET /forgot-password` → Récupérer le mot de passe
- `POST /forgot-password` → Demander un lien de reset
- `GET /reset-password/{token}` → Formulaire de reset
- `POST /reset-password` → Mettre à jour le mot de passe
- `GET /verify-email` → Vérifier l'email
- `POST /verify-email/resend` → Renvoyer le lien de vérification

---

## 📊 Résumé

- **Routes Publiques:** 9
- **Routes Authentifiées Client:** 27
- **Routes Vendeur:** 21
- **Routes Breeze:** 9
- **TOTAL:** 66 routes

---

## ⚙️ Middleware

### Authentification
- `auth` → Utilisateur connecté

### Rôles
- `vendeur` → L'utilisateur doit avoir le rôle 'vendeur'
- `client` → L'utilisateur doit avoir le rôle 'client'

### Groupes
- `middleware('auth')` → Toutes les routes du client
- `middleware(['auth', 'vendeur'])` → Toutes les routes du vendeur

---

## 🔗 Utilisation dans les Vues

Exemple d'utilisation avec `route()`:

```blade
<!-- Panier -->
<a href="{{ route('panier.index') }}">Panier</a>

<!-- Profil -->
<a href="{{ route('client.profil') }}">Mon Profil</a>

<!-- Messages -->
<a href="{{ route('messages.index') }}">Messages</a>

<!-- Dashboard Vendeur -->
<a href="{{ route('vendeur.dashboard') }}">Dashboard</a>

<!-- Ajouter au panier -->
<form action="{{ route('panier.ajouter', $produit->id) }}" method="POST">
    @csrf
    <button type="submit">Ajouter au panier</button>
</form>
```

---

## 🧹 Nettoyage Effectué

✅ Routes de debug supprimées:
- `/test-debug`
- `/diagnostic`
- `/debug-images`
- `/test-images`
- `/info`

✅ Vues de debug supprimées:
- `test-debug.blade.php`
- `diagnostic.blade.php`
- `debug-images.blade.php`
- `test-images.blade.php`
- `info/index.blade.php`

✅ Fichiers PHP de debug supprimés:
- `assign_vendor.php`
- `check_categories.php`
- `check_images.php`
- `check_roles.php`
- `convert_fcfa.php`
- `diagnostic.php`
- `fix_categories.php`
- `fix_lg.php`
- `match_images.php`
- `test_data.php`
- `update_images.php`

✅ Fichiers de documentation temporaire supprimés:
- Tous les fichiers `.md` de développement
- PDF de diagramme UML

Le projet est maintenant **propre et optimisé** ! 🎉
