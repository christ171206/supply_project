# 🧪 Comptes de Test Supply

## Environnement de Développement

Pour tester l'application Supply, utilisez les comptes suivants :

---

## 👤 Compte Client

**Rôle :** Client (acheteur)

**Email :** `client@test.com`

**Mot de passe :** `password`

**Redirection après connexion :** Page d'accueil / Catalogue produits

---

## 🧑‍💼 Compte Vendeur

**Rôle :** Vendeur (vendeur vérifié)

**Email :** `vendeur@test.com`

**Mot de passe :** `password`

**Statut :** Vérifié

**Redirection après connexion :** Dashboard vendeur

---

## 📋 Instructions de Création

### Créer les comptes manuellement (SQL)

```sql
-- Client test
INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) 
VALUES (
    'Client Test',
    'client@test.com',
    '$2y$12$...[hash de password]...',
    'client',
    NOW(),
    NOW(),
    NOW()
);

-- Vendeur test
INSERT INTO users (name, email, password, role, shop_name, phone, address, vendor_status, email_verified_at, created_at, updated_at) 
VALUES (
    'Vendeur Test',
    'vendeur@test.com',
    '$2y$12$...[hash de password]...',
    'vendor',
    'Tech Store Test',
    '+33 6 00 00 00 00',
    '123 Rue de la Tech, 75000 Paris',
    'verified',
    NOW(),
    NOW(),
    NOW()
);
```

### Ou utiliser un DatabaseSeeder (recommandé)

Voir `database/seeders/TestAccountsSeeder.php`

---

## 🔐 Sécurité

⚠️ **IMPORTANT :**

- Ces comptes ne doivent EXISTER QUE en développement/test
- À **SUPPRIMER** avant la mise en production
- Ne pas utiliser dans un environnement public
- Les mots de passe doivent être complexes en production

---

## ✅ Flux de Test

### Test Client

1. Aller sur `http://localhost/auth/login`
2. Entrer : `client@test.com` / `password`
3. Vérifier redirection vers page produits
4. Vérifier accès au catalogue
5. Vérifier ajout produit au panier
6. Vérifier accès au profil utilisateur

### Test Vendeur

1. Aller sur `http://localhost/auth/login`
2. Entrer : `vendeur@test.com` / `password`
3. Vérifier redirection vers dashboard vendeur
4. Vérifier accès aux paramètres boutique
5. Vérifier gestion des produits
6. Vérifier commandes reçues

### Test Inscription

1. Aller sur `http://localhost/auth/register`
2. Remplir formulaire client
3. Créer compte → Vérifier redirection vers accueil
4. Répéter avec option vendeur → Vérifier champs supplémentaires

---

## 📝 Notes pour le Jury

Ces comptes permettent de démontrer :

✅ Authentification correcte
✅ Séparation des rôles (client/vendeur)
✅ Redirection dynamique selon le rôle
✅ Accès différencié aux pages/fonctionnalités
✅ Sécurité des mots de passe (hash)
✅ Validation des formulaires

---

## 🔄 Réinitialiser les Comptes

Pour remettre les comptes test à zéro :

```bash
php artisan migrate:refresh --seed
```

Puis recréer les comptes avec les seeders.

---

## 📅 Dernière mise à jour

15 janvier 2026

---

