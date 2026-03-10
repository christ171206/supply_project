# Guide de Conformité à la Charte Graphique - Supply

Tous les fichiers Blade doivent respecter strictement ces règles.

---

## 🔴 VIOLATIONS DÉTECTÉES ET À CORRIGER

### 1. **DÉGRADÉS** ❌
Tous les `bg-gradient-to-*` DOIVENT être supprimés et remplacés par des fonds solides.

**Fichiers affectés :**
- `resources/views/client/dashboard.blade.php` (lignes 136-164)
- `resources/views/admin/dashboard.blade.php`
- `resources/views/partials/hero-section.blade.php`
- Autres fichiers avec `@apply ... gradient`

**Remplacement :**
```blade
❌ class="bg-gradient-to-br from-blue-500 to-blue-600"
✅ class="bg-black"  (ou off-white, selon le contexte)
```

---

### 2. **BOX-SHADOW** ❌
Tous les `shadow-lg`, `shadow-xl`, `shadow-2xl`, `shadow-md` DOIVENT être supprimés.

**Remplacement :**
```blade
❌ class="shadow-lg rounded-lg"
✅ class="border border-gray-200 rounded-lg"
```

**Pattern regex pour find/replace dans VS Code :**
- Find: `\s+shadow-(lg|xl|md|2xl)|shadow-(lg|xl|md|2xl)\s+`
- Replace: `` (vide)

---

### 3. **COULEURS INVALIDES** ❌
Les couleurs suivantes ne doivent **JAMAIS** apparaître sauf dans les badges :
- `red-*`, `blue-*`, `green-*`, `yellow-*`, `purple-*`, `orange-*`, `pink-*`, `cyan-*`, `indigo-*`

**Seules couleurs autorisées :**
- `black`, `white`, `off-white`
- `gray-50`, `gray-100`, `gray-200`, `gray-400`, `gray-600`, `gray-800`
- Stock colors: `stock-ok` (#22c55e), `stock-out` (#f87171)
- Badge colors: UNIQUEMENT dans `.badge-warn`, `.badge-ok`, `.badge-err`

**Fichiers critiques à corriger :**
- `resources/views/admin/orders/index.blade.php` (lignes 97-101)
- `resources/views/admin/orders/tracking.blade.php` (lignes 40-68)
- `resources/views/admin/disputes/index.blade.php` (lignes 52-56)
- `resources/views/client/dashboard.blade.php`

**Pattern pour badges :**
```blade
❌ <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full">🔴 Ouvert</span>
✅ <span class="badge badge-err">Ouvert</span>
```

---

### 4. **EMOJIS DANS L'INTERFACE** ❌
Les emojis ne doivent **PAS** apparaître dans l'interface utilisateur.

**Seul endroit autorisé :** Emails (`.mail` files)

**À corriger :**
- Tous les badges d'état avec emojis `🔴`, `🟡`, `✅`, `🚚`, `❌`, etc.
- Les icônes decoratives en texte

**Remplacement :**
```blade
❌ <span class="badge">🔴 Ouvert</span>
✅ <span class="badge badge-err">Ouvert</span>
```

---

### 5. **SCALE ANIMATIONS** 
`hover:scale-105`, `group-hover:scale-110` doivent être remplacés par des transitions d'opacité.

```blade
❌ class="hover:scale-105 transition transform"
✅ class="hover:opacity-85 transition"
```

---

## 📋 PALETTE DE COULEURS VALIDES

### Variables CSS (à utiliser dans les Blade)
```blade
<!-- Neutres -->
bg-black          text-black
bg-white          text-white
bg-off-white      text-gray-800
bg-gray-50        text-gray-600
bg-gray-100       text-gray-400
bg-gray-200
bg-gray-400
bg-gray-600
bg-gray-800

<!-- Stock UNIQUEMENT -->
text-stock-ok     (vert, badges de stock seulement)
text-stock-out    (rouge, badges de stock seulement)

<!-- Badges UNIQUEMENT -->
badge-warn, badge-ok, badge-err
```

---

## 🔧 CORRECTIONS AUTOMATISÉES

### Regex Find/Replace dans VS Code

**1. Supprimer tous les shadow :**
- Find: ` shadow-(lg|xl|md|2xl|sm)`
- Replace: `` (empty)

**2. Remplacer gradients par noir :**
- Find: `bg-gradient-to-[a-z]+\sfrom-[a-z-]+\sto-[a-z-]+`
- Replace: `bg-black`

**3. Remplacer hover:scale par hover:opacity :**
- Find: `hover:scale-\d+\s+transition\s+transform`
- Replace: `hover:opacity-85 transition`

**4. Remplacer couleurs de boutons :**
- Find: `bg-(blue|red|green|yellow|purple|pink|orange|cyan|indigo)-(100|500|600|700|800)`
- Replace: `bg-gray-200` ou `bg-black` (selon contexte)

---

## ✅ CHECKLIST D'AUDIT

Pour chaque fichier `.blade.php`, vérifier :

- [ ] Pas de `bg-gradient`
- [ ] Pas de `shadow-*`
- [ ] Pas de couleurs `red-*`, `blue-*`, `green-*`, etc.
- [ ] Emojis uniquement en `.mail` files
- [ ] `hover:scale` remplacé par `hover:opacity`
- [ ] Badges utilisent `.badge-warn`, `.badge-ok`, `.badge-err`
- [ ] Status colors uniquement dans badges
- [ ] Stock indicators utilisent `.stock-ok`, `.stock-out`
- [ ] Tous les `border-radius` → 4px (badges), 8px (inputs), 7px (boutons), 12px (cards)
- [ ] Fond page = `bg-off-white` (jamais blanc pur)

---

## 📝 TEMPLATES À CORRIGER EN PRIORITÉ

**Critiques (beaucoup de violations) :**
1. `resources/views/client/dashboard.blade.php` ⚠️⚠️⚠️
2. `resources/views/admin/orders/tracking.blade.php` ⚠️⚠️
3. `resources/views/admin/orders/index.blade.php` ⚠️⚠️
4. `resources/views/admin/disputes/index.blade.php` ⚠️
5. `resources/views/components/carte-produit.blade.php` ⚠️

**Autres à vérifier :**
- `resources/views/auth/register.blade.php`
- `resources/views/layouts/navigation-client.blade.php`
- `resources/views/vendeur/profil.blade.php`
- `resources/views/produits/show.blade.php`
- `resources/views/favoris/index.blade.php`
- `resources/views/admin/dashboard.blade.php`

---

## 🎨 EXEMPLES DE CORRECTIONS

### Avant et Après

**Exemple 1 : Dashboard Card**
```blade
❌ AVANT
<a class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 
   hover:to-blue-700 text-white rounded-xl shadow-lg p-8 hover:scale-105 transform">
  Continuer les Achats
</a>

✅ APRÈS  
<a class="group bg-black text-white rounded-xl p-8 hover:opacity-85 transition">
  Continuer les Achats
</a>
```

**Exemple 2 : Status Badge**
```blade
❌ AVANT
<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full">🔴 Ouvert</span>

✅ APRÈS
<span class="badge badge-err">Ouvert</span>
```

**Exemple 3 : Product Card**
```blade
❌ AVANT
<div class="border shadow-lg bg-white rounded-lg hover:shadow-xl hover:scale-105">

✅ APRÈS
<div class="border border-gray-200 bg-white rounded-xl hover:bg-off-white">
```

---

## 📚 RÉFÉRENCES

- **Charte complète** : Structure figurée dans `tailwind.config.js` et `resources/css/app.css`
- **Variables disponibles** : Toutes basées sur `--color-*` CSS
- **Composants** : Utiliser les classes `.badge-*`, `.btn-*`, `.card`, etc. définies en CSS

---

## ⚙️ COMMANDES UTILES

**Trouver tous les fichiers avec "shadow-" :**
```bash
grep -r "shadow-" resources/views/
```

**Trouver tous les gradients :**
```bash
grep -r "gradient" resources/views/
```

**Trouver toutes les couleurs invalides :**
```bash
grep -r "red-\|blue-\|green-\|yellow-\|purple-\|pink-\|orange-\|cyan-\|indigo-" resources/views/ | grep -v "red-600\|blue-600" | head -20
```

**Trouver todos les emojis :**
```bash
grep -r "🔴\|🟡\|✅\|🚚\|❌\|🟢" resources/views/
```

---

## 🚀 PROCHAINES ÉTAPES

1. ✅ CSS global configuré (`resources/css/app.css`)
2. ✅ Tailwind config updated (`tailwind.config.js`)
3. 🔄 **Corriger les vues Blade** (vous êtes ici)
4. 🔄 Vérifier les mails
5. 🔄 Test final complet
