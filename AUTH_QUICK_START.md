# 🚀 QUICK START - AUTHENTIFICATION SUPPLY

## 📍 URLs Importantes

```
Login:          http://localhost/login
Register:       http://localhost/register
Logout:         POST /logout
Forgot Pwd:     http://localhost/forgot-password
```

---

## 🧪 Comptes de Test Prêts à l'Emploi

### Client

```
Email:      client@test.com
Mot de passe: password
```

→ **Redirection**: Page d'accueil (`/`)

### Vendeur

```
Email:      vendeur@test.com
Mot de passe: password
```

→ **Redirection**: Dashboard vendeur (`/vendeur/dashboard`)

---

## ✨ Fonctionnalités Principales

| Fonctionnalité | Détails |
|---|---|
| **Inscription** | Client par défaut, optionnel vendeur |
| **Champs vendeur** | Affichés dynamiquement (nom boutique, tél, adresse, CNI) |
| **Validation** | Côté serveur + client |
| **Sécurité** | Hashage bcrypt, CSRF, middleware auth |
| **Redirection** | Automatique selon rôle |

---

## 📚 Documentation

- **[AUTHENTICATION_GUIDE.md](./AUTHENTICATION_GUIDE.md)** - Guide complet
- **[TEST_ACCOUNTS.md](./TEST_ACCOUNTS.md)** - Comptes de test
- **[DEMO_AUTHENTICATION.md](./DEMO_AUTHENTICATION.md)** - Démo pour jury

---

## ⚡ Quick Commands

```bash
# Créer comptes test
php artisan db:seed --class=TestAccountsSeeder

# Réinitialiser BDD
php artisan migrate:fresh

# Build assets
npm run build

# Clear caches
php artisan view:clear && php artisan cache:clear
```

---

## 🎨 Design

✓ Thème Supply (cyan/rose/violet)
✓ Layout minimaliste centré
✓ Champs larges et clairs
✓ Boutons gradient modernes
✓ Responsive mobile

---

## 📅 Dernière mise à jour: 15 janvier 2026

**Prêt pour la soutenance! 🎉**
