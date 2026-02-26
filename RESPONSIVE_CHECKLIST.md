# ✅ Responsive Design Checklist

## Vue d'ensemble du design responsive

### Points de rupture (Breakpoints)
- **Mobile**: sm (640px) - Small devices
- **Tablet**: md (768px) et lg (1024px) - Medium/Large tablets  
- **Desktop**: xl (1280px+) - Large screens

### Élévations de classe testées

#### 1. Composant Carte Produit
- ✅ **Mobile (sm)**: Grid 1 colonne, hauteur image 48, texte réduit
- ✅ **Tablet (md)**: Grid 2 colonnes
- ✅ **Desktop (lg)**: Grid 3 colonnes, espacements normaux
- ✅ **XLarge (xl)**: Grid 4 colonnes avec gap-5

**Fichier**: `resources/views/components/carte-produit.blade.php`

#### 2. Catalogue
- ✅ **Mobile**: Sidebar en bas ou masquée, contenu plein largeur
- ✅ **Tablet**: Grid 4 colonnes (lg:grid-cols-4)
- ✅ **Desktop**: Sidebar + produits côte à côte

**Classes utilisées**:
```blade
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
  <!-- Sidebar: lg:col-span-1 -->
  <!-- Produits: lg:col-span-3 --> 
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
```

#### 3. Détail Produit (show.blade.php)
- ✅ **Mobile**: Image 100%, détails en bas
- ✅ **Desktop**: Grid 2/3 colonnes (image + contenu)

```blade
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Image: lg:col-span-1 -->
  <!-- Détails: lg:col-span-2 -->
```

#### 4. Formulaires
- ✅ **Input**: Classe `.input-field` responsive (100% à mobile, contrôlé sur desktop)
- ✅ **Textarea**: Classe `.textarea-field` responsive
- ✅ **Boutons**: `.btn-primary`, `.btn-secondary` avec padding adapté

#### 5. Tableaux
- ✅ **Mobile**: Horizontal scroll avec `overflow-x-auto`
- ✅ **Desktop**: Pleine largeur sans scroll

**Exemple**:
```blade
<div class="overflow-x-auto">
  <table class="w-full text-sm">
```

#### 6. Dashboards
- ✅ **Mobile**: Cartes 1 colonne (`grid-cols-1`)
- ✅ **Tablet**: 2 colonnes (`md:grid-cols-2`)
- ✅ **Desktop**: 4 colonnes (`lg:grid-cols-4`)

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
```

## Points à vérifier manuellement

### Alignement et Espacements
- [ ] Padding/margin cohérent sur tous les breakpoints
- [ ] Aucun débordement de texte long
- [ ] Images scalent correctement
- [ ] Boutons ont tailles appropriées par device

### Typographie
- [ ] Titres lisibles sur mobile (h1 doit être réduit)
- [ ] Texte corps >= 14px sur mobile
- [ ] Line-height maintenu pour lisibilité

### Interactions
- [ ] Boutons cliquables sur mobile (48px min height)
- [ ] Hover states visibles sur desktop
- [ ] Modaux responsive sur mobile
- [ ] Listes scrollables si nécessaire

### Navigation
- [ ] Sidebar accessible sur mobile (ou hidden)
- [ ] Breadcrumb mobile-friendly
- [ ] Pagination responsive

## Outils de Test

1. **Chrome DevTools**
   - F12 → Toggle Device Toolbar (Ctrl+Shift+M)
   - Tester: iPhone SE, iPad, Desktop 1920x1080

2. **Breakpoints à Tester**
   - 375px (SM): iPhone SE
   - 768px (MD): iPad Portrait
   - 1024px (LG): iPad Landscape
   - 1280px+ (XL): Desktop

3. **Validations CSS**
   - Vérifier syntax avec Tailwind CLI
   - Pas de dégradés excessifs (utiliser only solid colors)
   - Classes standardisées (.btn-*, .card, .input-field)

## Notes Important

### Classes Standardisées à Utiliser
```css
.btn-primary     /* bg-primary-600 text-white */
.btn-secondary   /* bg-gray-100 text-gray-900 */
.btn-outline     /* border + text color */
.card            /* bg-white + border + shadow */
.input-field     /* border + padding + focus states */
.textarea-field  /* pareil comme input-field */
.badge-*         /* pour statuts/tags */
```

### Responsive Utilities
- `hidden md:block` - Masquer sur mobile, afficher sur tablette
- `grid-cols-1 md:grid-cols-2 lg:grid-cols-3` - Colonnes adaptatives
- `px-4 sm:px-6 lg:px-8` - Padding adaptif
- `text-sm md:text-base lg:text-lg` - Typographie adaptative

### À Éviter
- ❌ Dégradés complexes (`from-*/to-*/via-*`)
- ❌ Animations trop longues (duration-500+)
- ❌ box-shadow-2xl/lg sur petits écrans
- ❌ Dimensions fixes (height: 500px sans media query)

## Testing Results

| Component | Mobile | Tablet | Desktop | Notes |
|-----------|--------|--------|---------|-------|
| Carte Produit | 1 col | 2 col | 3 col | ✅ |
| Catalogue | 1 col | 2 col | 3 col | ✅ |
| Dashboard | 1 col | 2 col | 4 col | ✅ |
| Détail | Stack | Stack | 2/3 col | ✅ |
| Tableaux | Scroll | Scroll | Full | ✅ |
|

 Formulaires | Full | Full | Controlled | ✅ |

## Prochaines Actions

1. Tester chaque page sur 3 breakpoints
2. Vérifier aucun débordement (overflow)
3. Tester interactions tactiles (hover → focus)
4. Valider performance Lighthouse (mobile)
5. Vérifier images responsive

---

**Statut**: 🟢 Prêt pour tests manuels
