# ✅ CHECKLIST AUTHENTIFICATION - POUR LE JURY

## 🎯 Démo Guidée (5-10 minutes)

### A. Test de Connexion - Client

1. **Accéder à la page login**
   ```
   http://localhost/login
   ```

2. **Remplir les identifiants**
   ```
   Email: client@test.com
   Mot de passe: password
   Cocher: Se souvenir de moi
   ```

3. **Cliquer "Se connecter"**
   ✓ Redirection vers **page produits** (`/`)
   ✓ Voir le catalogue
   ✓ Voir profil utilisateur avec "Client"

4. **Cliquer sur le profil** → Voir "client@test.com"

5. **Déconnexion** → Retour à login

---

### B. Test de Connexion - Vendeur

1. **Login page** → Same credentials
   ```
   Email: vendeur@test.com
   Mot de passe: password
   ```

2. **Cliquer "Se connecter"**
   ✓ Redirection vers **`/vendeur/dashboard`** ⭐
   ✓ Accès limité aux fonctionnalités vendeur

3. **Points clés à montrer:**
   - Navigation différente (Dashboard vs Produits)
   - Accès aux outils de gestion
   - Statut "verified" (prêt à vendre)

---

### C. Test d'Inscription - Nouveau Client

1. **Accéder à register**
   ```
   http://localhost/register
   ```

2. **Vérifier l'interface**
   ✓ Titre: "Créer un compte"
   ✓ Description claire
   ✓ Choix: ☑ Client / ☐ Vendeur

3. **Remplir formulaire client**
   ```
   Nom: Test User
   Email: testuser@example.com
   Mot de passe: TestPass123
   Confirmer: TestPass123
   ☑ J'accepte les conditions
   ```

4. **Cliquer "Créer mon compte"**
   ✓ Redirection vers `/`
   ✓ Utilisateur connecté
   ✓ Nouveau compte visible en BDD

---

### D. Test d'Inscription - Nouveau Vendeur

1. **Register page** → Même URL

2. **Sélectionner "Vendeur"**
   ✓ Champs supplémentaires apparaissent ⭐
   ```
   - Nom de la boutique
   - Téléphone
   - Adresse
   - Justificatif d'identité (upload)
   ```

3. **Remplir le formulaire**
   ```
   Nom: Nouveau Vendeur
   Email: newvendor@example.com
   Mot de passe: VendorPass123
   ----
   Nom boutique: Ma Boutique Tech
   Téléphone: +33 6 00 00 00 00
   Adresse: 456 Rue Tech, 75000
   Upload: [image JPG optionnelle]
   ☑ J'accepte
   ```

4. **Cliquer "Créer mon compte"**
   ✓ Redirection `/`
   ✓ Compte créé avec statut "pending"
   ✓ Données visibles en BDD

---

## 📊 Vérifications Techniques

### Architecture - À montrer

```
1. Pages d'authentification
   ✓ resources/views/auth/login.blade.php
   ✓ resources/views/auth/register.blade.php
   ✓ resources/views/layouts/guest.blade.php

2. Contrôleurs
   ✓ app/Http/Controllers/Auth/AuthenticatedSessionController.php
   ✓ app/Http/Controllers/Auth/RegisteredUserController.php

3. Base de données
   ✓ Colonne 'role' enum('client', 'vendor')
   ✓ Colonnes vendeur: shop_name, phone, address, etc.

4. Routes
   ✓ routes/auth.php (login, register, logout)
   ✓ routes/web.php (redirections)
```

### Fonctionnalités Clés ✓

- [ ] **Séparation des rôles**
  - Client vs Vendeur clairement distincts
  - Redirection automatique selon rôle

- [ ] **Champs dynamiques**
  - Register: champs vendeur cachés au départ
  - Affichés au clic sur "Vendeur" (JavaScript)

- [ ] **Validation côté serveur**
  - Tous les champs vérifiés
  - Email unique
  - Mot de passe confirmé
  - Infos vendeur requises si vendeur

- [ ] **Sécurité**
  - Mots de passe hashés (bcrypt)
  - Protection CSRF (@csrf)
  - Middleware 'guest' (guest) et 'auth' (authenticated)

- [ ] **Comptes test**
  - Client: client@test.com / password
  - Vendeur: vendeur@test.com / password

---

## 🗄️ Vérification BDD

### Requêtes SQL à montrer

```sql
-- Voir les utilisateurs
SELECT id, name, email, role, vendor_status FROM users;

-- Détails vendeur
SELECT id, name, email, role, shop_name, phone, vendor_status 
FROM users 
WHERE role = 'vendor';

-- Résultat attendu:
-- | 1 | Client Test | client@test.com | client | NULL |
-- | 2 | Vendeur Test | vendeur@test.com | vendor | verified |
```

---

## 🎨 Design & UX

À montrer au jury:

```
✓ Thème cohérent avec Supply (cyan/rose/violet)
✓ Layout centré et minimaliste
✓ Fond dégradé bleu clair
✓ Ombre légère sur la carte
✓ Boutons gradient Supply
✓ Icônes claires (logo, champs)
✓ Messages de validation
✓ Responsive (mobile-friendly)
```

---

## 🔒 Démonstration Sécurité

### Point 1: Mot de passe hashé

```
Login page → F12 (DevTools) → Network
- Pas de mot de passe en clair dans la requête
- Hash bcrypt en BDD
```

### Point 2: CSRF Protection

```
Code source → Voir @csrf token dans formulaires
- Token unique par session
- Impossible de faire POST sans token
```

### Point 3: Middleware

```
Essayer d'accéder à /vendeur/dashboard sans auth
- Redirection forcée vers /login
- Pas d'accès direct possible
```

---

## 📋 Réponses aux Questions Probables du Jury

### Q: "Pourquoi pas de choix de rôle au login?"

**R**: Comme Amazon, Jumia, etc. Le rôle est connu en BDD.
- Moins de confusion
- Plus rapide
- Meilleure UX

### Q: "Comment les vendeurs sont vérifiés?"

**R**: Statut `vendor_status`:
- `pending` → En attente (après inscription)
- `verified` → Approuvé (peut vendre)
- `rejected` → Rejeté (admin revire)

Admin peut modifier via dashboard/SQL.

### Q: "Les infos vendeur sont optionnelles?"

**R**: Non, requises si `role = 'vendor'`.
- Validation côté serveur stricte
- Affichage dynamique au client
- Upload document (optionnel, max 5MB)

### Q: "Où aller après login?"

**R**: Automatique selon rôle:
- Client → `/` (catalogue)
- Vendor → `/vendeur/dashboard` (gestion)

Logique dans `AuthenticatedSessionController::store()`

### Q: "Les comptes test?"

**R**: Oui, créés avec seeder:
```bash
php artisan db:seed --class=TestAccountsSeeder
```

Fichier: `TEST_ACCOUNTS.md`

---

## 🚀 Fichiers à Montrer au Jury

### Présentation (ordre logique)

1. **Layout & Design**
   ```
   resources/views/layouts/guest.blade.php
   ```

2. **Login**
   ```
   resources/views/auth/login.blade.php
   - Formulaire simple
   - Liens utiles
   - Comptes test (env local)
   ```

3. **Register**
   ```
   resources/views/auth/register.blade.php
   - Choix rôle
   - Champs dynamiques (JS)
   - Validation
   ```

4. **Contrôleurs**
   ```
   app/Http/Controllers/Auth/AuthenticatedSessionController.php
   - Redirection selon rôle
   
   app/Http/Controllers/Auth/RegisteredUserController.php
   - Validation
   - Création utilisateur
   - Upload fichier
   ```

5. **Modèle**
   ```
   app/Models/User.php
   - Propriétés fillable
   - Méthodes helper
   ```

6. **Migrations**
   ```
   database/migrations/2026_01_06_082335_add_role_to_users_table.php
   database/migrations/2026_01_15_000000_add_vendor_fields_to_users_table.php
   ```

7. **Documentation**
   ```
   AUTHENTICATION_GUIDE.md
   TEST_ACCOUNTS.md
   ```

---

## ⏱️ Timeline de Démo

| Temps | Action | Résultat |
|------|--------|----------|
| 0:00-1:00 | Intro architecture | Expliquer flux |
| 1:00-2:00 | Show login page | Client + Vendeur |
| 2:00-3:00 | Show register page | Choix rôle |
| 3:00-4:00 | Create client account | New client in DB |
| 4:00-5:00 | Create vendor account | New vendor in DB |
| 5:00-6:00 | Check DB & code | Montrer tables |
| 6:00-7:00 | Sécurité & détails | Hashage, CSRF |
| 7:00-10:00 | Q&A | Répondre jury |

---

## ✨ Points Forts à Souligner

1. **Séparation claire client/vendeur**
   - Pas de confusion possible
   - Redirection automatique

2. **Champs dynamiques**
   - JavaScript professionnel
   - Expérience utilisateur fluide

3. **Validation robuste**
   - Serveur + client
   - Gestion erreurs claire

4. **Design moderne**
   - Cohérent avec Supply
   - Responsive & accessible

5. **Documentation complète**
   - Guides + comptes test
   - Code commenté

6. **Sécurité**
   - Hashage bcrypt
   - CSRF protection
   - Middleware auth

---

## 🎯 Conclusion pour le Jury

**Ce système d'authentification démontre:**

✅ Compréhension de Laravel & Breeze
✅ Gestion des rôles utilisateur
✅ Formulaires dynamiques (JS + PHP)
✅ Validation sécurisée
✅ UX/Design moderne
✅ Séparation des responsabilités (MVC)
✅ Documentation professionnelle

---

📅 **Dernière mise à jour: 15 janvier 2026**

---
