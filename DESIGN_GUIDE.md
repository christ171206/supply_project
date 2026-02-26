# 📋 Guide de Style - Supply

## 🎨 Système de Design Modern Corporate + Clean UI

Ce guide documente le système de design de Supply, basé sur les principes de **Modern Corporate** et **Clean UI Épurée**.

---

## 📦 Palette de Couleurs

### Couleurs Primaires
- **Primary (Bleu professionnel)**: `#3b82f6` 
  - Usage: CTA, boutons principaux, navigation active, accentuation
  - Toutes les teintes disponibles: `primary-50` à `primary-900`

### Couleurs d'Accent
- **Accent (Vert tendre)**: `#22c55e`
  - Usage: Succès, validation, éléments positifs
  - Toutes les teintes disponibles: `accent-50` à `accent-900`

### Couleurs Neutres
- **Secondary (Gris professionnel)**: `#6b7280`
  - Usage: Texte secondaire, bordures discrètes
  - Toutes les teintes disponibles: `secondary-50` à `secondary-900`

### Couleurs Fonctionnelles
- **Success**: `#22c55e` (même que accent)
- **Warning**: `#eab308` (jaune)
- **Danger**: `#ef4444` (rouge)

### Variables CSS
```css
:root {
    --color-primary: #3b82f6;
    --color-accent: #22c55e;
    --color-secondary: #6b7280;
    --color-success: #22c55e;
    --color-warning: #eab308;
    --color-danger: #ef4444;
}
```

---

## 🎯 Composants Principaux

### Boutons

#### Bouton Primaire
```html
<button class="btn-primary">Action Principale</button>
```
- Couleur: `bg-primary-600`
- Hover: `hover:bg-primary-700`
- Texte: Blanc
- Ombre: `shadow-sm`
- Border-radius: `rounded-lg`

#### Bouton Secondaire
```html
<button class="btn-secondary">Action Secondaire</button>
```
- Couleur: Blanc avec bordure grise
- Hover: `hover:bg-gray-50`
- Texte: Gris foncé

#### Bouton Outline
```html
<button class="btn-outline">Aperçu</button>
```
- Bordure: `border border-primary-600`
- Hover: `hover:bg-primary-50`

### Cartes
```html
<div class="card">Contenu</div>
```
- Background: Blanc
- Border: Gris 200
- Shadow: `shadow-sm`
- Hover: `hover:shadow-md`
- Border-radius: `rounded-lg`

### Badges
```html
<span class="badge-primary">Label</span>
<span class="badge-accent">Success</span>
<span class="badge-secondary">Neutral</span>
```
- Format: `inline-flex`
- Padding: `px-3 py-1`
- Border-radius: `rounded-full`
- Taille: `text-xs`

### Champs de Formulaire
```html
<input class="input-field" type="text">
<textarea class="textarea-field"></textarea>
```
- Border: `border border-gray-300`
- Focus: `focus:ring-2 focus:ring-primary-500`
- Border-radius: `rounded-lg`
- Padding: `px-4 py-2.5`

---

## 🎨 Typographie

### Hiérarchie
```html
<h1>Titre Niveau 1 - 4xl font-bold</h1>
<h2>Titre Niveau 2 - 3xl font-bold</h2>
<h3>Titre Niveau 3 - xl font-semibold</h3>
<h4>Titre Niveau 4 - lg font-semibold</h4>
<p>Texte régulier - text-gray-700</p>
```

### Styles Disponibles
- `.section-header`: Titre de section (2xl font-bold)
- `.section-subheader`: Sous-titre (lg font-semibold)

---

## 🔲 Espacements

### Principes
- **Whitespace généreux**: L'espace blanc est utilisé pour créer de la clarté
- **Cohérence**: Utiliser les espacements Tailwind standard

### Espacement Vertical
```html
<div class="space-y-4">Élément 1</div>
<div class="space-y-4">Élément 2</div>
<div class="space-y-4">Élément 3</div>
```

### Container avec Espacement Relaxé
```html
<div class="container-relaxed">Contenu principal</div>
```
- Padding: `px-4 py-8 lg:px-8 lg:py-12`

---

## ✨ Animations

### Animations Discrètes
- **fade-in-up**: Apparition fluide depuis le bas
- **fade-in**: Fondu simple
- **scale-in**: Apparition avec micro-zoom

```html
<div class="animate-fade-in-up">Contenu</div>
<div class="animate-fade-in">Contenu</div>
<div class="animate-scale-in">Contenu</div>
```

### Transitions
- Durée standard: `duration-150` (transitions rapides et discrètes)
- Classe utilitaire: `.transition-subtle`

---

## 🎯 Layouts & Grilles

### Grid Responsive
```html
<div class="grid-clean">
    <!-- Auto 1 col mobile, 2 col tablet, 3 col desktop -->
</div>
```

### Utilities
```html
<div class="flex-center">Centré</div>
<div class="flex-between">Espaced</div>
```

---

## 🌐 Navigation

### Barre de Navigation
**Styles appliqués**:
- Background: Blanc
- Border: `border-b border-gray-200`
- Shadow: `shadow-sm`
- Links actifs: Couleur primary
- Hover: Transition douce

### Sidebar
**Styles appliqués**:
- Background: Blanc
- Border-right: `border-gray-200`
- Items actifs: Fond primary avec texte blanc
- Items inactifs: Texte gris avec hover gris clair

---

## 📱 Responsive Design

### Breakpoints Tailwind
- `sm`: 640px
- `md`: 768px
- `lg`: 1024px
- `xl`: 1280px
- `2xl`: 1536px

### Pattern Commun
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
    <!-- Cards -->
</div>
```

---

## 🎬 Page Loader

Style moderne et minimaliste:
```html
<div id="page-loader" class="loader"></div>
```
- Couleur: Primary (`#3b82f6`)
- Animation fluide et continue

---

## ❌ Ce Qu'il Faut ÉVITER

### ❌ À ne pas faire
- ❌ Dégradés excessifs (utiliser des couleurs unies)
- ❌ Ombres trop prononcées (`shadow-lg` ou plus)
- ❌ Animations compliquées (garder discrètes)
- ❌ Couleurs vibrantes ou flashy
- ❌ Espacements trop serrés
- ❌ Bordures épaisses (utiliser `border` = 1px)
- ❌ Hover effects agressifs (pas de `scale-105`, etc.)

### ✅ À faire à la place
- ✅ Couleurs unies et professionnelles
- ✅ Ombres subtiles (`shadow-sm` ou `shadow-md`)
- ✅ Animations douces et rapides
- ✅ Spacings généreux
- ✅ Bordures fines et discrètes
- ✅ Transitions lisses (`duration-150`)
- ✅ Hover effects subtils (changements de couleur, petite ombre)

---

## 📋 Checklist de Conformité

Avant de commiter du code, vérifier:

- [ ] Utilisation des couleurs de la palette définie
- [ ] Pas de dégradés inutiles
- [ ] Ombres discrètes (`shadow-sm` ou `shadow-md`)
- [ ] Espacements cohérents
- [ ] Border-radius: `rounded-lg` (ou `rounded-xl` pour modals)
- [ ] Transitions: `duration-150`
- [ ] Boutons utilisent `.btn-*` classes
- [ ] Cartes utilisent `.card` class
- [ ] Input fields utilisent `.input-field` class
- [ ] Mobile-first responsive design
- [ ] Pas de hover effects agressifs
- [ ] Texte lisible (contrast approprié)

---

## 🔄 Mise à Jour et Maintenance

### Fichiers Clés à Maintenir
1. `tailwind.config.js` - Configuration des couleurs
2. `resources/css/app.css` - Styles globaux et composants
3. `resources/views/layouts/*.blade.php` - Templates de layout

### Modifications Futures
Si vous devez ajuster le design:
1. Mettre à jour d'abord `tailwind.config.js`
2. Ajouter les styles nécessaires dans `app.css`
3. Appliquer les classes aux layouts et composants
4. Tester la cohérence sur tous les breakpoints

---

## 📚 Ressources

- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Tailwind Color Palette](https://tailwindcss.com/docs/customizing-colors)
- Design Tokens utilisés dans ce projet

---

**Dernière mise à jour**: 10 février 2026  
**Auteur**: System Design  
**Version**: 1.0
