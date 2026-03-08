# 🧪 GUIDE DE TEST - FLUX INSCRIPTION VENDEUR

## 📍 URLS DE TEST

### Environnement Local
- **Base URL** : `http://127.0.0.1:8000`
- **Port WAMP** : 8000 (adapter si autre)

### Routes Principales
| Étape | URL | Méthode | Accès |
|-------|-----|---------|--------|
| Inscription | `/register` | GET/POST | Sans auth |
| Vérif Email | `/verify-email-code` | GET/POST | Sans auth |
| Docs Recto/Verso | `/vendor/documents/submit` | GET/POST | Auth |
| Confirmation | `/vendor/documents/confirmation` | GET | Auth |

---

## 🚀 SCÉNARIO DE TEST COMPLET

### ✅ ÉTAPE 1 : Inscription sans Documents

1. **Ouvrir** : `http://127.0.0.1:8000/register`

2. **Formulaire - Section Cliente** (voir tête noire "Créer un compte")
   ```
   Nom complet : Test Vendeur
   Pays : 🇨🇮 Côte d'Ivoire
   Email : vendor.test@test.local
   Password : SecurePass123!
   Password Confirm : SecurePass123!
   Je suis un : [x] Vendeur
   ```

3. **Formulaire - Section Vendeur** (apparaît après cocher "Vendeur")
   ```
   Nom de la boutique : Ma Super Boutique
   Téléphone : +225 07 12 34 56 78
   Adresse : Abidjan, Cocody, Rue de la Paix
   🪪 Justificatif d'identité : [ABSENT - C'EST CORRECT] ✅
   ```

4. **Accepter les conditions** ✓

5. **Cliquer** : `✨ Créer un compte`

**Résultat Attendu** ✅
- Redirection vers `/verify-email-code`
- Message : "Vérifiez votre email"
- Email reçu avec code 6 chiffres
- Message : "Entrez le code reçu dans votre boîte mail"

---

### ✅ ÉTAPE 2 : Vérification Email

1. **Page** : `/verify-email-code` (automatique)

2. **Information affichée**
   ```
   Email de vérification envoyé à :
   vendor.test@test.local
   ```

3. **Récupérer le code**
   - 📧 Vérifier mailbox (réelle ou test)
   - Code format : `000000` (6 chiffres)
   - Exemple : `123456`

4. **Entrer le code**
   ```
   Code de vérification (6 chiffres) : 123456
   ```

5. **Cliquer** : `✓ Vérifier le code`

**Résultat Attendu** ✅
- ✅ Page de succès
- Authentification automatique
- Redirection vers `/vendor/documents/submit`
- `vendor_status` = `pending_validation` (dans DB)

---

### ✅ ÉTAPE 3 : Soumission Documents

1. **Page** : `/vendor/documents/submit` (automatique)

2. **En-tête de page**
   ```
   🪪 Vérification d'identité
   Étape requise pour activer votre boutique
   Étape 2 sur 3
   ```

3. **Type de document** - Sélectionner UNE option
   ```
   [ ] 🇨🇮 Carte d'identité nationale
   [ ] 📱 Carte Multiservice
   [x] 🛂 Passeport  ← Exemple
   ```

4. **Numéro du document**
   ```
   Numéro du document : ABC123456789
   ```

5. **Recto (avant)** - Upload photo
   - Cliquer sur zone upload
   - Sélectionner image JPG/PNG
   - Max 5 Mo
   - Aperçu doit s'afficher

6. **Verso (arrière)** - Upload photo
   - Même processus que recto
   - Image différente du verso

7. **Vérifier les conseils** ✓
   ```
   💡 Conseil :
   ✓ Texte bien lisible
   ✓ Fond clair
   ✓ Pas de reflets/ombres
   ✓ Tous les coins visibles
   ```

8. **Cliquer** : `✓ Soumettre les documents`

**Résultat Attendu** ✅
- Redirection vers `/vendor/documents/confirmation`
- `vendor_status` = `pending_validation`
- Fichiers stockés dans `/public/vendors/documents/`
- DB : 2x UserDocument créés (front + back)

---

### ✅ ÉTAPE 4 : Confirmation Soumission

1. **Page** : `/vendor/documents/confirmation`

2. **Contenu affiché** ✅
   ```
   ✓ Excellent !
   Vos documents d'identité ont été reçus avec succès
   
   Timeline :
   1️⃣ Vérification rapide (24h en jours ouvrés)
   2️⃣ Confirmation par email (vendor.test@test.local)
   3️⃣ Accès à votre tableau de bord
   
   ⏱️ Délai estimé : 24-48 heures
   
   👤 Vérification des données
   Nom : Test Vendeur
   Boutique : Ma Super Boutique
   Téléphone : +225 07 12 34 56 78
   Lieu : Abidjan, Cocody, Rue de la Paix
   
   🔒 Sécurité et confidentialité
   Documents cryptés et sécurisés
   ```

3. **Boutons disponibles**
   - 🏠 Retour à l'accueil
   - 📧 Contacter le support

4. **Numéro de suivi** (pour support)
   ```
   Numéro de suivi : XXXXX-20260307
   ```

**Résultat Attendu** ✅
- Vendeur vu page complète
- Peut revenir à accueil ou contact support
- Demande soumise avec succès
- En attente approbation admin

---

## 🔍 VÉRIFICATIONS TECHNIQUES

### Base de Données

**Vérifier User créé**
```sql
SELECT id, name, role, vendor_status, email_verified_at 
FROM users 
WHERE email = 'vendor.test@test.local';
```
```
id=123, role='vendor', vendor_status='pending_validation', email_verified_at=NOT NULL
```

**Vérifier Documents créés**
```sql
SELECT id, user_id, document_type, document_side, status
FROM user_documents
WHERE user_id = 123;
```
```
id=1, document_type='passport', document_side='front', status='pending'
id=2, document_type='passport', document_side='back', status='pending'
```

### Fichiers Stockés

Vérifier l'arborescence :
```
public/
├── vendors/
│   └── documents/
│       ├── front/
│       │   └── [GUID].jpg
│       └── back/
│           └── [GUID].jpg
```

### Logs

Vérifier les logs :
```bash
tail -f storage/logs/laravel.log
```

Rechercher :
```
"Documents d'identité soumis"
"vendor_id": 123
```

---

## ❌ PROBLÈMES & SOLUTIONS

### Problème : Page blanche après clique sur "Créer un compte"

**Cause** : PHP error
**Solution** :
```bash
# Vérifier logs
tail -f storage/logs/laravel.log

# Vérifier permissions
chmod -R 755 storage/
chmod -R 755 public/
```

---

### Problème : "Route not found" sur `/vendor/documents/submit`

**Cause** : Routes pas enregistrées
**Solution** :
```bash
# Vérifier routes
php artisan route:list | grep documents

# Vider cache routes
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

---

### Problème : Email ne arrive pas

**Cause** : Configuration mail incorrecte
**Solution** :
```bash
# Vérifier config
php artisan tinker
> config('mail.driver')  # doit être 'log' ou 'smtp'

# Vérifier logs générés
tail -f storage/logs/laravel.log | grep -i mail
```

---

### Problème : Upload échoue avec "max 5MB"

**Cause** : Permissions ou restrictions PHP
**Solution** :
```bash
# Vérifier permissions
ls -la public/vendors/
chmod -R 777 public/vendors/

# Vérifier php.ini
php -i | grep upload_max_filesize
# Doit être >= 5M
```

---

## 📊 CHECKLIST DE VALIDATION

- [ ] Étape 1 : Inscription réussie (vendor_status=pending)
- [ ] Étape 2 : Email envoyé + code valide (email_verified_at=NOW)
- [ ] Étape 3 : Formulaire documents s'affiche
- [ ] Étape 3 : Upload recto/verso fonctionne
- [ ] Étape 3 : Aperçu images affichés
- [ ] Étape 4 : Page confirmation affichée
- [ ] Database : User créé avec bon role/status
- [ ] Database : 2x UserDocument créés (front+back)
- [ ] Fichiers : Images stockées dans `/public/vendors/documents/`
- [ ] Logs : Messages "Documents d'identité soumis" visibles

---

## 🎯 PROCHAIN TESTS

### Après Admin Dashboard (à implémenter)
1. Approuver document → Email envoyé
2. Rejeter document → Email rejet + status updated
3. Re-soumettre après rejet → Workflow correct

### Après intégration Admin
- [ ] Admin voit les documents
- [ ] Admin peut approver/rejeter
- [ ] Email reçu par vendeur
- [ ] Vendor access dashboard après approval

---

**Status : Prêt au Test** ✅
