# 🧪 GUIDE DE TEST RAPIDE - Supply

## 🚀 Démarrage

```bash
# S'assurer que le serveur Laravel fonctionne
php artisan serve

# Accédez à http://127.0.0.1:8000
```

---

## 👤 Comptes de Test

| Rôle | Email | Mot de passe | Accès |
|------|-------|-------------|-------|
| Client | `client@test.com` | `password` | Accueil + Produits |
| Vendeur | `vendeur@test.com` | `password` | Dashboard Vendeur |

---

## 🧪 Scénario 1: Avis Produit (Client)

### Étapes:
1. **Allez sur** → http://127.0.0.1:8000/
2. **Cliquez** → "Catalogue" ou "Produits"
3. **Cliquez** → Un produit (ex: "Dell XPS 13") → "Voir Détails"
4. **Scrollez** → Section "Avis Clients"
5. **Observez:**
   - ⭐ Note moyenne (ex: 4.5/5)
   - 📊 Répartition graphique des notes
   - 💬 Liste des avis existants

### Ajouter un Avis:
1. **Connectez-vous** → client@test.com / password
2. **Rafraîchissez** la fiche produit
3. **Scrollez** → Nouveau formulaire "Votre Avis"
4. **Remplissez:**
   - Cliquez ⭐⭐⭐⭐⭐ (ou autre note)
   - Tapez commentaire (min 10 caractères)
5. **Cliquez** → "📤 Publier mon Avis"
6. **Vérifiez:** L'avis apparaît dans la liste

### Supprimer son Avis:
1. **Retrouvez votre avis** dans la liste
2. **Cliquez** → "🗑️ Supprimer"
3. **Confirmez**
4. **Vérifiez:** Avis disparu

---

## 🏪 Scénario 2: Section Vendeur (Fiche Produit)

### Étapes:
1. **Sur** une fiche produit quelconque
2. **En haut à droite** → Section "VENDU PAR"
3. **Observez:**
   ```
   🏪 VENDU PAR
   [Avatar] Vendeur Test (ou shop_name)
   Adresse boutique
   ⭐ 4.7/5 (145 avis)
   [💬 Contacter]
   ```

### Contacter le Vendeur:
1. **Cliquez** → "💬 Contacter"
2. **Redirigé** vers messagerie (si implémentée)

---

## 📊 Scénario 3: Dashboard Avis Vendeur

### Étapes:
1. **Connectez-vous** → vendeur@test.com / password
2. **Attendu:** Redirigé vers dashboard
3. **Sidebar gauche** → "⭐ Avis Clients"
4. **Observez la page:**

#### Stats en Haut (4 Cartes):
```
┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐
│ 4.5/5    │ │ 45 avis  │ │ 18x ⭐5  │ │ 3x ⚠️    │
└──────────┘ └──────────┘ └──────────┘ └──────────┘
```

#### Graphique Répartition (5 lignes):
```
⭐⭐⭐⭐⭐ ████████████████████ 20
⭐⭐⭐⭐   ████████████████     16
⭐⭐⭐     ██████████           10
⭐⭐       ███                  3
⭐        ██                    2
```

#### Filtres:
- 🔍 Recherche (nom, produit, contenu)
- Dropdown note (toutes, 5★, 4★, etc.)

#### Liste d'Avis:
Pour chaque avis:
```
[Avatar] Jean Dupont         ⭐⭐⭐⭐⭐ 5/5
il y a 3 jours
───────────────────────────────────────
📦 Dell XPS 13
Excellent produit! Livraison rapide.

[💬 Répondre] [👁️ Voir Produit]
```

### Répondre à un Avis Critique:
1. **Trouvez un avis** avec note ≤ 2 ⭐
2. **Cliquez** → "💬 Répondre"
3. **Écrivez** votre réponse
4. **Cliquez** → "✓ Envoyer"
5. **Observez:** Réponse publiée

### Filtrer les Avis:
1. **Recherche:** Tapez un nom ou un mot
2. **Dropdown:** Sélectionnez une note
3. **Résultat:** La liste se filtre en temps réel

---

## 🔑 Scénario 4: Mot de Passe Oublié

### Étapes:
1. **Allez sur** → http://127.0.0.1:8000/login
2. **Cliquez** → "Mot de passe oublié?" (en bas)
3. **Entrez** email: `client@test.com`
4. **Cliquez** → "🔗 Envoyer le Lien de Réinitialisation"
5. **Observez:** Message "✅ Un lien de réinitialisation a été envoyé (simulation)"

### Réinitialiser Mot de Passe:
*Note: Dans une version production avec emails réels, vous cliqueriez un lien reçu.*

Pour tester localement:
1. **Allez sur** → http://127.0.0.1:8000/password/reset/{TOKEN}
   *(Le token est automatique en local)*
2. **Voyez le formulaire:**
   ```
   📧 Email: client@test.com (lecture seule)
   🔐 Nouveau Mot de Passe: [Entrer nouveau]
   ✓ Confirmation: [Confirmation]
   ```
3. **Remplissez:**
   - Nouveau mot de passe: `NewPassword123!`
   - Confirmation: `NewPassword123!`
4. **Cliquez** → "🔄 Réinitialiser le Mot de Passe"
5. **Attendu:** Redirigé vers login
6. **Connectez-vous** avec nouveau mot de passe ✅

---

## 🎨 Scénario 5: Layout Vendeur

### Header (Nouveau):
```
[📦 Supply] [🏪 Nom Boutique] [🔔(3)] [Avatar ▼]
```

### Vérifier:
1. **Sur dashboard vendeur**
2. **En haut:** Header avec sticky
3. **Scroll:** Header reste en haut ✅
4. **Avatar (droite):** Cliquez → Menu:
   - 👤 Mon Profil
   - 🛍️ Mode Client
   - 🚪 Déconnexion

### Sidebar (Amélioré):
```
PRINCIPAL
┌ 📊 Tableau de Bord
└ 👁️ Aperçu Boutique

GESTION
┌ 📦 Mes Produits
├ 📋 Gestion Stock
└ 🛒 Commandes

CLIENT
┌ ⭐ Avis Clients
└ 💬 Messages

COMPTE
┌ 👤 Mon Profil
├ 📈 Statistiques
└ ⚙️ Paramètres

← Retour Boutique
```

### Vérifier:
1. **Cliquez chaque lien** → Active item surligné
2. **Observe:** Couleur + bordure gauche ✅
3. **Sticky:** Sidebar reste visible en scrollant ✅

---

## ✅ Checklist de Validation

### Authentification
- [ ] Login fonctionne (client et vendeur)
- [ ] Logout fonctionne
- [ ] Mot de passe oublié affiche formulaire
- [ ] Reset password fonctionne
- [ ] Redirection correcte (client → /, vendor → dashboard)

### Avis Produit
- [ ] Formulaire "Votre Avis" visible si connecté
- [ ] Avis publiés s'affichent dans la liste
- [ ] Note moyenne calculée correctement
- [ ] Graphique répartition s'affiche
- [ ] Pagination fonctionne (5 par page)
- [ ] Suppression d'avis fonctionne

### Section Vendeur Produit
- [ ] Carte vendeur s'affiche bien formatée
- [ ] Avatar avec initiale
- [ ] Nom boutique et adresse
- [ ] Note et nombre d'avis du vendeur
- [ ] Bouton contacter présent

### Dashboard Avis Vendeur
- [ ] Stats 4 cartes en haut
- [ ] Graphique répartition visible
- [ ] Filtre recherche fonctionne
- [ ] Filtre note fonctionne
- [ ] Liste avis bien formatée
- [ ] Bouton "Répondre" sur avis critiques
- [ ] Pagination fonctionne

### Layout Vendeur
- [ ] Header sticky en haut
- [ ] Logo Supply cliquable
- [ ] Affiche nom boutique
- [ ] Notifications badge visible
- [ ] Avatar menu déroulant fonctionne
- [ ] Sidebar sections bien organisées
- [ ] Active item surligné
- [ ] Sidebar sticky
- [ ] Footer visible en bas

---

## 🐛 Dépannage

### Les avis ne s'affichent pas?
```bash
# Vérifier DB
php artisan tinker
> App\Models\Avis::all();
```

### Layout vendeur cassé?
```bash
npm run build
php artisan view:clear
```

### Erreur 404?
```bash
# Vérifier routes
php artisan route:list | grep avis
```

---

## 📝 Notes

- Tous les comptes utilisent mot de passe: `password`
- Les avis sont sans limite (un seul par client par produit cependant)
- Les notes moyennes se recalculent automatiquement
- Pas d'envoi email réel (simulation locale)

---

**Bon testing! 🚀**
