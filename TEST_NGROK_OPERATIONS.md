# Test des opérations Supply via ngrok

## 🧪 Tests rapides à faire

### URL à tester
```
https://unintimately-postallantoic-felicity.ngrok-free.dev
```

---

## ✅ Checklist de test

### 1. Frontend chargé?
- [ ] L'URL ngrok s'ouvre sans erreur
- [ ] Les images se chargent (CSS/JS visible)
- [ ] Le design est correct

**Commande terminal:**
```bash
curl -s https://unintimately-postallantoic-felicity.ngrok-free.dev | grep -o "<title>.*</title>"
# Doit afficher: <title>Supply - Boutique Informatique</title>
```

---

### 2. API fonctionnelle?
- [ ] Accéder à `/api/produits`
- [ ] Accéder à `/api/categories`
- [ ] Vérifier les réponses JSON

**Test via curl:**
```bash
curl -s https://unintimately-postallantoic-felicity.ngrok-free.dev/api/produits | jq . | head -20
```

---

### 3. Forms avec CSRF?
- [ ] Accéder à `/register` 
- [ ] Voir le champ CSRF token `<input name="_token"`
- [ ] Soumettre le formulaire

**Vérifier le token:**
```bash
curl -s https://unintimately-postallantoic-felicity.ngrok-free.dev/register | grep -o 'name="_token" value="[^"]*"'
```

---

### 4. Upload images?
- [ ] Aller sur le profil
- [ ] Uploader une image
- [ ] Vérifier qu'elle s'affiche
- [ ] URL doit être: `https://unintimately-postallantoic-felicity.ngrok-free.dev/storage/...`

---

### 5. Sessions persistantes?
- [ ] Se connecter (login)
- [ ] Naviguer vers d'autres pages
- [ ] Être encore connecté
- [ ] Se déconnecter

---

### 6. Hot reload Vite?
**SEULEMENT en développement local:**
- [ ] Ouvrir l'inspect (F12) → Console
- [ ] Modifier un fichier CSS
- [ ] Vérifier: "HMR Reloading" ou changement automatique

---

### 7. Panier?
- [ ] Ajouter un produit au panier
- [ ] Voir le badge compteur se mettre à jour
- [ ] Augmenter/diminuer la quantité
- [ ] Supprimer l'article

---

### 8. Commande complète?
- [ ] Login / Register
- [ ] Ajouter 2-3 produits au panier
- [ ] Aller au checkout
- [ ] Confirmer la commande
- [ ] Vérifier email reçu (Mailtrap)

---

## 🎯 Résultats attendus

| Test | Résultat |
|------|----------|
| Frontend charge | ✅ CSS/JS visibles |
| API répond | ✅ JSON valide |
| CSRF tokens | ✅ Présent et valide |
| Uploads | ✅ Fichier stocké + affiché |
| Sessions | ✅ Persistantes |
| Hot reload | ✅ Changements instantanés (dev seulement) |
| Panier | ✅ Fonctionne 100% |
| Commande | ✅ Email reçu |

---

## 🔧 Dépannage rapide

### Erreur: "Connection refused"
```
→ Le serveur Laravel n'est pas démarré
→ Commande: php artisan serve --port=8000
```

### Erreur: "File not found"
```
→ Fichier de stockage manquant
→ Commande: php artisan storage:link
```

### Erreur: "CSRF token mismatch"
```
→ Token expiré ou SESSION_LIFETIME trop court
→ Vérifier .env: SESSION_LIFETIME=120 (2 heures)
```

### Images ne s'affichent pas
```
→ Storage link manquant
→ Commande: php artisan storage:link && php artisan config:cache
```

### Hot reload ne fonctionne pas
```
→ Vite mal configuré
→ Vérifier .env:
   VITE_HMR_HOST=unintimately-postallantoic-felicity.ngrok-free.dev
   VITE_HMR_PROTOCOL=https
```

---

## 📱 Test depuis téléphone/autre PC

**Étapes:**
1. Obtenir l'URL ngrok: `https://unintimately-postallantoic-felicity.ngrok-free.dev`
2. Taper sur navigateur mobile: l'URL complète
3. Site doit se charger normalement
4. Tous les tests ci-dessus doivent appliquer

---

## 📊 Rapport de test

Quand vous testez, remplissez ceci:

```
Date: [date]
Machine testeur: [Windows/Mac/Linux]
Navigateur: [Chrome/Firefox/Safari/Edge]

Résultats:
- Frontend: PASS / FAIL
- API: PASS / FAIL
- CSRF: PASS / FAIL
- Upload: PASS / FAIL
- Session: PASS / FAIL
- Panier: PASS / FAIL
- Commande: PASS / FAIL

Notes:
- [vos observations]
```

---

## ✅ TOUT FONCTIONNE?

Si tous les tests passent = **ngrok est 100% operationnel!**

Les autres machines peuvent:
- Consulter le site ✅
- Uploader des fichiers ✅
- Passer des commandes ✅
- Tester les formulaires ✅

**Prêt à partager!** 🚀
