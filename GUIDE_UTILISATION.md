# 📖 GUIDE D'UTILISATION - APPLICATION SUPPLY

## 🚀 DÉMARRER L'APPLICATION

### 1. Ouvrir un terminal PowerShell
```powershell
cd D:\wamp\www\Supply
php artisan serve --host=localhost --port=8000
```

Vous verrez :
```
INFO  Server running on [http://localhost:8000].
Press Ctrl+C to stop the server
```

### 2. Ouvrir un navigateur
Allez à : **http://localhost:8000**

---

## 🔐 SE CONNECTER

### Accueil public
Vous voyez :
- Hero section "Bienvenue à Supply"
- Catégories (Laptops, Écrans, Claviers, etc.)
- 8 produits vedettes

### Bouton Connexion (haut droit)
Cliquez sur "Log in"

### Comptes disponibles

#### Client
- **Email** : client@test.com
- **Mot de passe** : password

#### Vendeur
- **Email** : vendeur@test.com
- **Mot de passe** : password

#### Autres clients
- alice@test.com / password
- bob@test.com / password

---

## 👤 AS CLIENT

### Parcourir les produits
1. Cliquez sur **"Catalogue"** ou **"Produits"**
2. Vous voyez tous les produits disponibles

#### Filtrer les produits
- **Recherche** : Taper un nom (ex: "Dell")
- **Catégorie** : Choisir une catégorie
- **Tri** : Plus récent, Prix croissant, Prix décroissant, Nom
- **Bouton "Filtrer"** : Appliquer les filtres

### Voir un produit en détail
1. Cliquez sur **"Voir les détails"** d'un produit
2. Vous voyez :
   - Image 💻
   - Nom et catégorie
   - Prix
   - Stock disponible
   - Description complète
   - Formulaire pour ajouter au panier
   - Produits similaires

### Ajouter au panier
1. Choisissez la **quantité** (1-stock max)
2. Cliquez **"Ajouter au panier"**
3. Message de confirmation

### Voir votre panier
Cliquez sur **"Mon panier"** (haut de page)

#### Dans le panier
Vous voyez :
- Liste des articles
- Prix unitaire
- Quantité modifiable
- Total par article
- TOTAL final
- Boutons :
  - **"Procéder au paiement"** → Valider la commande
  - **"Continuer les achats"** → Retour catalogue
  - **"Vider le panier"** → Supprimer tous les articles

### Passer commande
1. Panier non vide → **"Procéder au paiement"**
2. Remplir :
   - **Adresse de livraison** (obligatoire)
   - **Mode de paiement** :
     - ☑ Paiement à la livraison (par défaut)
     - ○ Mobile Money
     - ○ Carte bancaire
3. **"Confirmer la commande"**
4. Redirection vers les détails de votre commande

### Voir vos commandes
Cliquez sur **"Mes commandes"**

#### Liste de vos commandes
Vous voyez :
- Numéro (#1, #2...)
- Date
- Montant total
- Statut (En attente, Confirmée, Expédiée, Livrée, Annulée)
- Statut paiement (Confirmé, En attente)
- Bouton **"Voir détails"**

### Détails d'une commande
- Infos : Date, Mode paiement, Statut paiement, Adresse
- Client : Votre nom et email
- Articles : Liste avec prix, quantité, total
- Montant total
- Bouton "Contacter le vendeur"

### Votre profil
Cliquez sur votre **nom** (haut droit) → **"Profile"**
- Modifier nom, email
- Changer mot de passe
- Supprimer compte

---

## 🏪 AS VENDEUR (vendeur@test.com)

### Dashboard
Après connexion, vous voyez :
- Lien **"Dashboard vendeur"** dans le menu
- OU Allez directement à : **http://localhost:8000/vendeur/dashboard**

#### Statistiques
- Total des ventes
- Nombre de commandes
- Total de produits
- Produits en stock faible

### Gérer les produits
Cliquez sur **"Produits"** dans le menu vendeur

#### Liste des produits
Vous voyez tous vos produits avec :
- Actions : Modifier, Supprimer

### Ajouter un produit
Bouton **"Ajouter un produit"**

Remplir le formulaire :
- **Nom*** (obligatoire)
- **Description*** (obligatoire)
- **Prix*** (obligatoire, numérique)
- **Stock*** (obligatoire, nombre)
- **Stock minimum*** (obligatoire, pour alerte)
- **Catégorie*** (obligatoire, liste déroulante)

Cliquez **"Créer le produit"**

### Modifier un produit
1. Cliquez **"Modifier"** sur un produit
2. Remplir le formulaire (pré-rempli)
3. **"Sauvegarder"**

### Supprimer un produit
Cliquez **"Supprimer"** → Confirmation → Suppression

### Voir les commandes reçues
Cliquez sur **"Commandes"** dans le menu vendeur

Vous voyez :
- Toutes les commandes reçues
- Numéro, Date, Client, Montant, Statut

---

## 🛒 SCÉNARIO COMPLET (Client)

### 1. Se connecter
```
Email: client@test.com
Password: password
```

### 2. Explorer
- Accueil → Cliquer sur catégories
- Catalogue → Rechercher "laptop"

### 3. Ajouter au panier
- Voir détails d'un produit
- Quantité : 2
- "Ajouter au panier" ✓

### 4. Modifier le panier
- Mon panier
- Voir les articles
- Modifier quantité à 3
- Clic "Maj"

### 5. Passer commande
- "Procéder au paiement"
- Adresse : "123 rue Paris, 75000 Paris"
- Mode : Paiement à la livraison
- "Confirmer la commande"

### 6. Voir la commande
- Redirection automatique
- Voir détails (#1)
- Infos, articles, total

### 7. Voir l'historique
- Menu → Mes commandes
- Voir la commande #1

---

## 🎯 CAS D'UTILISATION VENDEUR

### 1. Se connecter
```
Email: vendeur@test.com
Password: password
```

### 2. Dashboard
- Voir les stats
- 19 produits déjà en stock
- Historique des commandes

### 3. Ajouter un produit
- Produits → Ajouter
- Nom: "Nouveau Monitor"
- Prix: 299.99
- Stock: 50
- Catégorie: Écrans
- "Créer"

### 4. Modifier le produit
- Produits → Modifier
- Changer prix à 279.99
- "Sauvegarder"

### 5. Voir les commandes
- Commandes (vendeur)
- Voir toutes les commandes reçues

---

## ⚠️ POINTS IMPORTANTS

### Authentification
- Vous devez être connecté pour :
  - Ajouter au panier ✓
  - Voir le panier ✓
  - Passer commande ✓

### Stock
- Si stock = 0 → **"Rupture de stock"** (pas d'achat possible)
- Le stock est décrémenté automatiquement à la commande
- Stock minimum génère une alerte (pour vendeur)

### Paiement
- **Actuellement simulé** (pas de vraie transaction)
- Les 3 modes (livraison, mobile, carte) sont acceptés
- Statut = "Confirmé" ou "En attente"

### Commandes
- **Statuts possibles** :
  - En attente (créée)
  - Confirmée (paiement confirmé)
  - Expédiée (en route)
  - Livrée (arrivée)
  - Annulée

### Vendeur
- Routes protégées → Doit avoir role = "vendeur"
- Autres utilisateurs voient : **"Accès non autorisé"**

---

## 🆘 SI CA NE MARCHE PAS

### Page blanche
1. Vérifier : http://localhost:8000 (pas 8001)
2. Vérifier que `php artisan serve` tourne
3. Ctrl+C pour arrêter, relancer

### Erreur de base de données
1. Vérifier MySQL tourne (WAMP)
2. Supply_db existe dans PhpMyAdmin
3. Relancer migrations :
   ```bash
   php artisan migrate:fresh --seed
   ```

### Erreur 500
1. Vérifier les logs :
   ```bash
   tail -f storage/logs/laravel.log
   ```
2. Vérifier .env (DB_HOST, DB_DATABASE, etc.)

### Panier vide après rafraîchir
- Normal, panier en BD (recharger la page)

### Ne peux pas ajouter au panier
- Êtes-vous connecté? Oui → Essayer reconnexion

---

## 📞 SUPPORT

### Commandes utiles
```bash
# Voir les routes
php artisan route:list

# Voir les modèles
php artisan tinker
> App\Models\Produit::count()

# Réinitialiser la BD
php artisan migrate:fresh --seed

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

**Profitez de Supply ! 🚀**
