# Guide d'Affichage des Images - Supply Platform

## 📊 Résumé des Images Placées

```
✅ 119 images de produits     → /storage/app/public/produits/
✅ 19 images de catégories    → /storage/app/public/categories/
✅ 5 images de paiements      → /public/images/payments/
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Total: 143 images actives
```

---

## 🎯 OÙ VOIR LES IMAGES DANS L'APP

### 1️⃣ Page d'Accueil (accueil.blade.php)
**Catégories visibles:**
- Section "Parcourir les catégories" affiche 13 catégories avec images
- Chaque carte catégorie montre l'image vignette

**Code utilisé:**
```blade
@if($categorie->image)
    <img src="{{ asset('storage/categories/' . $categorie->image) }}" 
         alt="{{ $categorie->nom }}" 
         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
@else
    <!-- SVG placeholder -->
@endif
```

### 2️⃣ Liste des Produits (accueil.blade.php, panier/index.blade.php)
**Affichage:**
- Vignette d'image du produit (1ère image)
- Zoom au survol de la souris

**Code utilisé:**
```blade
@if($produit->images && is_array($produit->images) && count($produit->images) > 0)
    <img src="{{ asset('storage/' . $produit->images[0]) }}" 
         alt="{{ $produit->nom }}" 
         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
@elseif($produit->image)
    <img src="{{ asset('storage/produits/' . $produit->image) }}" 
         alt="{{ $produit->nom }}" class="...">
@else
    <!-- SVG placeholder gris -->
@endif
```

### 3️⃣ Page Détail Produit (produits/show.blade.php)
**Affichage:**
- ✅ Image principale (grande taille)
- ✅ Galerie de miniatures cliquables
- ✅ 2 images par produit (119 images), certains 3 images

**Code utilisé:**
```blade
<!-- Image principale -->
@if($produit->images && is_array($produit->images) && count($produit->images) > 0)
    <img src="{{ asset('storage/' . $produit->images[0]) }}" 
         id="main-image" 
         class="w-full h-auto object-cover">
@endif

<!-- Galerie thumbnails -->
@foreach($produit->images as $index => $imagePath)
    <button onclick="document.getElementById('main-image').src = '{{ asset('storage/' . $imagePath) }}';" 
            class="border-2 border-gray-200">
        <img src="{{ asset('storage/' . $imagePath) }}" alt="Image {{ $index + 1 }}">
    </button>
@endforeach
```

### 4️⃣ Dashboard Vendeur (vendeur/produits/index.blade.php)
**Affichage:**
- Vignette produit avec 1ère image
- Liens "Voir" et "Modifier"

### 5️⃣ Paiements (commandes/create.blade.php)
**Affichage:**
- Logo paiement Wave, Orange Money, MTN Money, Moov Money, À la Livraison
- Chaque logo cliquable pour sélectionner le mode de paiement

**Code utilisé:**
```blade
<img src="{{ asset('images/payments/wave.png') }}" 
     alt="Wave" 
     class="w-16 h-16 object-cover rounded-lg group-hover:scale-110">
```

---

## 🔍 Points de Contrôle - Vérifier que tout s'affiche

### Accueil (127.0.0.1:8000/)
- [ ] Logo Supply en haut à gauche
- [ ] Section "Parcourir les catégories" - 13 catégories avec images
- [ ] Section "Produits en vedette" - carrés produit avec images
- [ ] Produits s'affichent avec zoom au hover

### Page Produit (127.0.0.1:8000/produits/{id})
- [ ] Image principale affichée
- [ ] Galerie de miniatures dessous
- [ ] Clic sur miniature change l'image principale
- [ ] Fallback SVG gris si pas d'image

### Panier (127.0.0.1:8000/panier)
- [ ] Produits avec vignette image
- [ ] Prix et quantité visibles

### Commande (127.0.0.1:8000/commandes/create)
- [ ] 5 logos paiement visibles et cliquables
- [ ] Zoom au hover sur les logos

### Dashboard Vendeur (/vendeur)
- [ ] Section "Produits récents" - images produit visibles
- [ ] Page Stock - images produit visibles

---

## 🛠️ Troubleshooting

### Images ne s'affichent pas?

**1. Vérifier le lien symbolique (symlink) du storage:**
```bash
# La commande Laravel pour créer le lien
php artisan storage:link

# Cela crée: public/storage → storage/app/public
```

**2. Vérifier les permissions:**
```bash
# Donner les permissions au dossier storage
chmod -R 775 storage/app/public
```

**3. Vérifier l'URL générée:**
- Ouvrir DevTools (F12)
- Aller dans "Network" ou "Images"
- Chercher les URLs `/storage/produits/...` ou `/storage/categories/...`
- Si rouge (404), le lien symbolique n'existe pas

**4. Créer le lien symbolique manuellement (Windows):**
```powershell
# En tant qu'admin PowerShell
New-Item -ItemType SymbolicLink -Path "d:\wamp\www\Supply\public\storage" -Target "d:\wamp\www\Supply\storage\app\public"
```

---

## 📝 Note Importante

Les images s'affichent via le chemin **`/storage/`** qui est en fait un lien symbolique vers `storage/app/public/`.

**Mappage:**
- URL navigateur: `{{ asset('storage/produits/image.jpg') }}`
- Chemin réel: `/storage/app/public/produits/image.jpg`
- Lien symbolique: `/public/storage` → `/storage/app/public`

---

## ✅ Vérification Finale

Si tu vois:
- ✅ Images catégories à l'accueil
- ✅ Images produits sur les cartes
- ✅ Galerie d'images sur la page produit
- ✅ Logos paiement sur la commande

**Alors tout fonctionne correctement!** 🎉

---

**Status:** ✅ TOUTES LES IMAGES SONT EN PLACE ET PRÊTES À L'AFFICHAGE
**Date:** 2 Mars 2026
