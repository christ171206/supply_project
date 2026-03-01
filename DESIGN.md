# 🎨 Guide de Style et Design Responsive

## 📋 Table des Matières
1. [Palette de Couleurs](#palette-de-couleurs)
2. [Composants](#composants)
3. [Typographie](#typographie)
4. [Responsive Design](#responsive-design)

---

## Palette de Couleurs

### Couleurs Primaires
- **Bleu Principal**: `#3B82F6` (Confiance, Professionnalisme)
- **Pourpre Accent**: `#A855F7` (Premium, Innovation)
- **Vert Action**: `#10B981` (Succès, Validation)

### Couleurs Neutres
- **Noir**: `#1F2937` (Texte, Headlines)
- **Gris Clair**: `#F3F4F6` (Backgrounds)
- **Gris Moyen**: `#6B7280` (Subtexte)
- **Blanc**: `#FFFFFF` (Cards, Espaces)

### Couleurs Fonctionnelles
- **Danger**: `#EF4444` (Erreurs, Suppression)
- **Warning**: `#F59E0B` (Avertissements)
- **Success**: `#10B981` (Succès, Validation)
- **Info**: `#3B82F6` (Information)

### Variables CSS
```css
:root {
  /* Primaires */
  --primary: #3B82F6;
  --primary-dark: #1E40AF;
  --primary-light: #DBEAFE;
  
  /* Accent */
  --accent: #A855F7;
  --accent-light: #E9D5FF;
  
  /* État */
  --success: #10B981;
  --warning: #F59E0B;
  --danger: #EF4444;
  --info: #3B82F6;
  
  /* Neutres */
  --text-primary: #1F2937;
  --text-secondary: #6B7280;
  --bg-light: #F3F4F6;
  --bg-white: #FFFFFF;
}
```

---

## Composants

### Boutons

#### Bouton Primaire
```html
<button class="bg-blue-600 text-white px-6 py-3 rounded-lg 
           hover:bg-blue-700 transition-colors duration-200
           font-semibold shadow-md hover:shadow-lg">
  Action Primaire
</button>
```
**Utilisation**: Actions principales (Acheter, Envoyer, Valider)

#### Bouton Secondaire
```html
<button class="bg-gray-200 text-gray-900 px-6 py-3 rounded-lg 
           hover:bg-gray-300 transition-colors duration-200
           font-semibold">
  Action Secondaire
</button>
```
**Utilisation**: Actions moins importantes (Annuler, Plus tard)

#### Bouton Danger
```html
<button class="bg-red-600 text-white px-6 py-3 rounded-lg 
           hover:bg-red-700 transition-colors duration-200
           font-semibold">
  Supprimer
</button>
```
**Utilisation**: Actions irréversibles (Supprimer, Annuler commande)

### Cartes
```html
<div class="bg-white rounded-lg shadow-sm p-6 
           hover:shadow-lg transition-shadow duration-200">
  <h3 class="text-lg font-semibold text-gray-900 mb-2">Titre</h3>
  <p class="text-gray-600 mb-4">Description</p>
  <button class="text-blue-600 hover:text-blue-700 font-medium">
    En savoir plus →
  </button>
</div>
```

### Badges
```html
<!-- Succès -->
<span class="inline-flex items-center px-3 py-1 rounded-full 
            bg-green-100 text-green-800 text-sm font-medium">
  ✓ Actif
</span>

<!-- Danger -->
<span class="inline-flex items-center px-3 py-1 rounded-full 
            bg-red-100 text-red-800 text-sm font-medium">
  ✗ Inactif
</span>

<!-- Info -->
<span class="inline-flex items-center px-3 py-1 rounded-full 
            bg-blue-100 text-blue-800 text-sm font-medium">
  ℹ Information
</span>
```

### Champs de Formulaire
```html
<div class="mb-6">
  <label class="block text-sm font-semibold text-gray-900 mb-2">
    Email
  </label>
  <input type="email" 
         class="w-full px-4 py-2 border border-gray-300 rounded-lg
                focus:ring-2 focus:ring-blue-500 focus:border-transparent
                placeholder-gray-400"
         placeholder="votre@email.com" />
</div>
```

---

## Typographie

### Hiérarchie

#### H1 - Page Title
```html
<h1 class="text-4xl font-bold text-gray-900">
  Titre Principal
</h1>
```

#### H2 - Section Title
```html
<h2 class="text-2xl font-bold text-gray-900">
  Titre Section
</h2>
```

#### H3 - Subsection
```html
<h3 class="text-xl font-semibold text-gray-900">
  Sous-titre
</h3>
```

#### Paragraphe
```html
<p class="text-base text-gray-700 leading-relaxed">
  Texte normal avec bonne lisibilité
</p>
```

### Styles Spéciaux
- **Gras**: `font-bold` ou `font-semibold`
- **Italique**: `italic`
- **Majuscules**: `uppercase` avec `tracking-wide`
- **Ligne-through**: `line-through` (textes supprimés)

---

## Responsive Design

### Breakpoints Tailwind
```css
/* Mobile-first approach */
Default: 0px (Mobile)
sm: 640px   (Small tablets)
md: 768px   (Tablets)
lg: 1024px  (Desktops)
xl: 1280px  (Large screens)
2xl: 1536px (Extra large)
```

### Pattern Commun

#### Grille responsive
```html
<!-- 1 col mobile, 2 col tablet, 3 col desktop -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <div class="bg-white rounded-lg">Carte 1</div>
  <div class="bg-white rounded-lg">Carte 2</div>
  <div class="bg-white rounded-lg">Carte 3</div>
</div>
```

#### Navigation responsive
```html
<nav class="flex flex-col md:flex-row items-center justify-between">
  <div class="text-xl font-bold">Logo</div>
  <ul class="flex flex-col md:flex-row gap-4 md:gap-8">
    <li><a href="/">Accueil</a></li>
    <li><a href="/produits">Produits</a></li>
  </ul>
</nav>
```

### Vérification Responsive

#### Mobile (375px wide)
- [ ] Texte lisible
- [ ] Boutons poussables (min 48x48px)
- [ ] Images redimensionnées
- [ ] Navigation en colonnes
- [ ] Pas de scrolling horizontal

#### Tablet (768px wide)
- [ ] Grille 2 colonnes
- [ ] Navigation horizontale
- [ ] Espacement adéquat
- [ ] Images optimisées

#### Desktop (1024px+)
- [ ] Grille 3+ colonnes
- [ ] Layout maxwidth (1200px)
- [ ] Animations fluides
- [ ] Hover states fonctionnels

---

## Animations et Transitions

### Transitions Subtiles
```html
<!-- Couleur hover -->
<button class="bg-blue-600 hover:bg-blue-700 
              transition-colors duration-200">
  Click me
</button>

<!-- Shadow hover -->
<div class="shadow-sm hover:shadow-lg 
           transition-shadow duration-300">
  Hover me
</div>

<!-- Scale hover -->
<button class="hover:scale-105 transition-transform duration-200">
  Enlarge
</button>
```

### Loading States
```html
<!-- Skeleton loader -->
<div class="animate-pulse">
  <div class="bg-gray-300 h-12 w-12 rounded"></div>
  <div class="bg-gray-200 h-4 w-3/4 mt-4 rounded"></div>
  <div class="bg-gray-200 h-4 w-1/2 mt-2 rounded"></div>
</div>
```

---

## Questions de Conformité

### Colors
- [ ] Contraste texte/fond ≥ 4.5:1
- [ ] Pas relying couleur uniquement (+ icone)
- [ ] Modes light/dark testés

### Typography
- [ ] Font sizes ≥ 16px (mobile)
- [ ] Line height ≥ 1.5
- [ ] Letterspacing optimal
- [ ] Max width texte ≤ 80 chars

### Spacing
- [ ] Padding/margin cohérent (utiliser grid 4px ou 8px)
- [ ] Gutter responsive (16px mobile, 24px desktop)
- [ ] Whitespace suffisant

### Interactive Elements
- [ ] Boutons ≥ 48x48px
- [ ] Links souligné ou distinction claire
- [ ] Focus states visibles (outline)
- [ ] Hover/active states différents

### Images
- [ ] Alt text descriptif
- [ ] Responsive (srcset ou CSS)
- [ ] Format optimisé (WebP + fallback)
- [ ] Lazy loading si off-screen

---

## Performance

### CSS
- [ ] Utiliser classes Tailwind (PurgeCSS enlève inutilisées)
- [ ] Éviter inline styles
- [ ] Regroup media queries
- [ ] CSS min < 50KB

### JavaScript
- [ ] Defer scripts non-critical
- [ ] Lazy load components
- [ ] Minify JS buildtime
- [ ] JS bundle < 150KB

### Images
- [ ] Compression lossy (80-85 quality)
- [ ] WebP format avec fallback
- [ ] Responsive images (srcset)
- [ ] Lazy loading avec loading="lazy"

---

## À ÉVITER

### ❌ Mauvaises Pratiques
- Texte très petit (< 14px)
- Couleurs trop contrastées/flash
- Animations excessives/nauséabondes
- Layouts figés (pas responsive)
- Fonts trop nombreuses (max 2)
- Espacements inconsistents

### ✅ Bonnes Pratiques
- Accessibilité d'abord
- Mobile-first design
- Performance optimized
- Animations subtiles
- Whitespace approprié
- Typographie hiérarchique

---

## Tests

### DevTools
```
1. F12 → Device Mode (Ctrl+Shift+M)
2. Tester: 375px, 768px, 1024px widths
3. Vérifier: Scroll, Touch, Readability
4. Console: Pas d'erreurs CSS
```

### Lighthouse
```
1. F12 → Lighthouse tab
2. Run audit (Mobile + Desktop)
3. Target: > 90 (Performance, Accessibility, Best Practices)
```

### Cross-browser
```
- Chrome/Chromium ✅
- Firefox ✅
- Safari ✅
- Mobile browsers ✅
```

---

## Checklist Finale

- [ ] Palette couleurs utilisée
- [ ] Composants cohérents
- [ ] Typographie lisible
- [ ] Responsive (3 breakpoints)
- [ ] Animations fluides
- [ ] Accessibilité OK
- [ ] Performance > 90
- [ ] Pas d'erreurs console
- [ ] Cross-browser OK
- [ ] Prêt pour production

---

**Date**: 1 mars 2026  
**Version**: 1.0  
**Status**: Design System Finalisé ✅
