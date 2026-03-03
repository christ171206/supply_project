# Inventaire Complet des Actifs (Images & Icônes) - Supply Platform

## 📋 Vue d'ensemble

Ce document liste tous les éléments visuels (images, icônes, logos) utilisés dans la plateforme Supply. Les icônes SVG en ligne sont intégrées directement dans les templates Blade. Les images externes doivent être ajoutées aux chemins spécifiés.

---

## 🎨 ICÔNES SVG INLINE (Intégrés dans le code)

### Navigation & Menus
| Icône | Description | Localisation | Utilisation |
|-------|-------------|--------------|-------------|
| ⚡ Lightning/Zap | Logo Supply (accueil) | Navigation | Header des pages clients/vendeurs |
| ☰ Hamburger Menu | Menu mobile | navigation-client.blade.php | Toggle menu mobile |
| ▼ Chevron Down | Menu déroulant | navigation-client.blade.php | Profil utilisateur (open/close) |
| ✕ Close/X | Fermer | layout-client.blade.php | Fermer les modales |

### État & Statut
| Icône | Description | Localisation | Utilisation |
|-------|-------------|--------------|-------------|
| ✓ Checkmark | Confirmé/Succès | verify-email-code.blade.php | Code valide, action réussie |
| ✓✓ Double Check | Livré | commande-detail.blade.php | Statut "Livrée" |
| ⏱️ x | En attente | commande-detail.blade.php | Statut "En attente" |
| 🚚 Camion | Expédié | commande-detail.blade.php | Statut "Expédiée" |
| 📦  | Livré | commande-detail.blade.php | Statut "Livrée" |
| ⚠️ Alerte | Attention requise | Various | Messages d'erreur, avertissements |
| 🔓 Deverrouillé | Déverrouiller | Modales | Déverrouiller les sections |

### Formulaires & Entrées
| Icône | Description | Localisation | Utilisation |
|-------|-------------|--------------|-------------|
| 👁️ Oeil | Afficher mot de passe | login.blade.php, register.blade.php | Toggle visibilité mot de passe |
| 📧 Email | Email | verify-email-code.blade.php | Vérification email |
| 🔒 Cadenas | Sécurité | login.blade.php | Champs mot de passe sécurisés |
| 📷 Caméra | Photo | profil.blade.php, vendor profil | Upload photo de profil |
| 📁 Folder | Fichier | Documents | Upload documents |

### Actions & Commandes
| Icône | Description | Localisation | Utilisation |
|-------|-------------|--------------|-------------|
| ➕ Plus Circle | Ajouter | dashboard.blade.php, stock.blade.php | Ajouter produit, créer commande |
| ✏️ Edit/Pen | Modifier | stock.blade.php, produits/index.blade.php | Modifier produit |
| 💾 Save/Disque | Enregistrer | produits/form.blade.php | Sauvegarder formulaire |
| 🗑️ Corbeille | Supprimer | Various | Supprimer produit/commande |
| ↑ Upload | Télécharger | produits/form.blade.php | Upload images |
| ⚡ Zap | Actions rapides | dashboard.blade.php | Section actions rapides |
| 📊 Chart | Statistiques | profil.blade.php | Statistiques vendeur |

### Commerce & Magasinage
| Icône | Description | Localisation | Utilisation |
|-------|-------------|--------------|-------------|
| 🛍️ Sac achat | Shopping bag | accueil.blade.php | Catégorie produits |
| 💳 Panier | Shopping cart | dashboard.blade.php | Commandes |
| 💰 Dollar | Prix/Paiement | dashboard.blade.php, accueil.blade.php | Prix, revenus |
| 📦 Package | Stocks | dashboard.blade.php | Inventaire |
| ✅ Circle Check | Confirmé | dashboar.blade.php | Commandes confirmées |
| 💻 Hard drive | Électronique | accueil.blade.php | Catégorie électronique |
| 🏆 Award | Récompense | dashboard.blade.php | Commandes livrées |
| 📞 Téléphone | Contact | Contact pages | Support, appel |
| 💬 Message | Messagerie | Various | Messages, notifications |
| 🔔 Cloche | Notifications | Navigation | Notifications |

### Recherche & Filtres
| Icône | Description | Localisation | Utilisation |
|-------|-------------|--------------|-------------|
| 🔍 Loupe | Recherche | live-search.blade.php | Recherche produits |
| ⚙️ Engrenage | Paramètres | Profil | Paramètres utilisateur |
| 🎯 Target | Filtre | Commandes | Filtrer recherche |

### Chargement & Attente
| Icône | Description | Localisation | Utilisation |
|-------|-------------|--------------|-------------|
| ⏳ Spinner | Chargement | Various | Indication chargement |
| ⏸️ Pause | En cours | Various | Processus en cours |

---

## 💳 IMAGES DE PAIEMENT (À `/public/images/payments/`)

### Opérateurs Mobiles - Côte d'Ivoire
| Image | Dimensions | Format | Chemin | Utilisation |
|-------|-----------|--------|--------|-------------|
| `wave.png` | 150x150px | PNG | `/images/payments/wave.png` | Paiement Wave |
| `orange money.png` | 150x150px | PNG | `/images/payments/orange money.png` | Paiement Orange Money |
| `mtn money.png` | 150x150px | PNG | `/images/payments/mtn money.png` | Paiement MTN Money |
| `moov money.png` | 150x150px | PNG | `/images/payments/moov money.png` | Paiement Moov Money |

### Livraison
| Image | Dimensions | Format | Chemin | Utilisation |
|-------|-----------|--------|--------|-------------|
| `a la livraison.jfif` | 150x150px | JFIF | `/images/payments/a la livraison.jfif` | Paiement à la livraison |

### Code à référencer dans `commandes/create.blade.php`
```blade
<!-- Wave -->
<img src="{{ asset('images/payments/wave.png') }}" alt="Wave" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">

<!-- Orange Money -->
<img src="{{ asset('images/payments/orange money.png') }}" alt="Orange Money" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">

<!-- MTN Money -->
<img src="{{ asset('images/payments/mtn money.png') }}" alt="MTN Money" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">

<!-- Moov Money -->
<img src="{{ asset('images/payments/moov money.png') }}" alt="Moov Money" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">

<!-- À la Livraison -->
<img src="{{ asset('images/payments/a la livraison.jfif') }}" alt="À la Livraison" class="w-16 h-16 object-cover rounded-lg group-hover:scale-110 transition-transform">
```

---

## 📸 IMAGES DYNAMIQUES (Stockage Utilisateur)

### ✅ Profils Utilisateurs
| Type | Chemin | Dimensions | Utilisation |
|------|--------|-----------|-------------|
| Photo de profil client | `/storage/profils/{user_id}.{ext}` | 200x200px min | Navigation, profil client |
| Photo de profil vendeur | `/storage/vendors/{vendor_id}.{ext}` | 200x200px min | Dashboard vendeur, profil |
| Avatar généré (fallback) | `https://api.dicebear.com/7.x/avataaars/svg?seed={email}` | Variable | Si pas de photo |

### ✅ Produits (119 images disponibles)
| Type | Chemin | Dimensions | Utilisation | Exemple |
|------|--------|-----------|-------------|---------|
| Images produit (principal) | `/storage/produits/{image_name}` | 1200x1200px+ | Page détail produit | `Apple Magic Trackpad 2 1.jpg` |
| Images produit (galerie) | `/storage/produits/{image_name}` | 800x800px+ | Galerie thumbnails | `Dell XPS 13 Plus M4 2.jpg` |
| Ancienne image unique | `/storage/produits/{old_image_name}` | Variable | Fallback hérité | - |
| Images JSON (nouveau) | `images: ["path/to/img1", "path/to/img2"]` | Variable | Multi-image support | ✅ ACTIF |

**Produits avec images actuellement disponibles :**
- Apple Magic Trackpad 2 (2 images)
- ASUS ProArt PA278CV 27" (2 images)
- ASUS TUF Gaming A17 (2 images)
- Audio-Technica AT2020 (2 images)
- BenQ PD2500Q 25" Pro (2 images)
- Blue Yeti USB Streaming (2 images)
- Bose QuietComfort 45 (2 images)
- Corsair K95 RGB Platinum (2 images)
- Crucial MX500 SSD 500GB (2 images)
- Dell S2721DGF 27" 165Hz (2 images)
- Dell XPS 13 Plus M4 (2 images)
- Ducky One 3 (2 images)
- Elgato Facecam (2 images)
- HP Pavilion 15 (2 images)
- JBL Quantum 800 (2 images)
- Kingston DataTraveler 512GB (2 images)
- Lenovo ThinkPad X1 (2 images)
- LG UltraWide 34" 3440x1440 (2 images)
- Logitech BRIO 4K (2 images)
- Logitech C920 HD Pro (2 images)
- Logitech MX Keys Advanced (2 images)
- Logitech MX Master 3S (2 images)
- MacBook Pro 14" M3 (2 images)
- Microsoft Pro Intellimouse (2 images)
- Mini PC Intel NUC i7 (2 images)
- MSI GF65 Thin Gaming (2 images)
- MSI MEG 321URF 32" Gaming (2 images)
- PC Bureau i5-13600 GTX 1650 (2 images)
- PC Gamer I9-13900K RTX (2 images)
- Razer DeathAdder V3 (2 images)
- Razer Kiyo Pro (2 images)
- Rode Procaster Broadcasting (2 images)
- Samsung 990 Pro NVMe 2TB (2 images)
- Samsung Pro Plus microSD (2 images)
- SanDisk Extreme Pro SD 256GB (2 images)
- SanDisk Extreme USB 3.1 (2 images)
- Seagate IronWolf HDD 4TB (2 images)
- Sennheiser Momentum 4 (2 images)
- Shure SM7B Studio (2 images)
- Sony WH-1000XM5 (3 images)
- SteelSeries Apex Pro (2 images)
- SteelSeries Arctis Nova Pro (2 images)
- SteelSeries Rival 5 (2 images)
- Western Digital Blue SSD 1TB (2 images)
- Workstation Ryzen 7 Pro RTX (2 images)

### ✅ Catégories (19 images disponibles)
| Type | Chemin | Dimensions | Utilisation | Catégories |
|------|--------|-----------|-------------|-----------|
| Image catégorie | `/storage/categories/{category_image}` | 500x500px+ | Section catégories accueil | ✅ Toutes les 13 catégories |

**Catégories avec images :**
- Adaptateurs Réseau.jpeg
- Alimentations & Refroidissement.jpg
- Cartes Graphiques.jpg
- Clés USB & Cartes Mémoire.jpg
- Câbles & Connecteurs.jpg
- Hubs & Docking.jpg
- Mémoire RAM.jpg
- Ordinateurs de Bureau.jpg
- Ordinateurs Portables.jpg
- Processeurs.jpg
- Routeurs & Modems.jpg
- SSD & HDD.jpg
- Tapis & Supports.jpeg

**Code à utiliser dans les templates :**
```blade
@foreach($categories as $categorie)
    @if($categorie->image)
        <img src="{{ asset('storage/categories/' . $categorie->image) }}" alt="{{ $categorie->nom }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
    @endif
@endforeach
```

### Commandes & Livraisons
| Type | Chemin | Dimensions | Utilisation |
|------|--------|-----------|-------------|
| Bon de livraison | `/storage/delivery_notes/{order_id}.pdf` | N/A | Export/impression |
| Photo livraison | `/storage/delivery_photos/{order_id}/{photo}` | Variable | Preuve livraison |

### Support Utilisateur (CNI)
| Type | Chemin | Dimensions | Utilisation |
|------|--------|-----------|-------------|
| Documents CNI vendeur | `/storage/cni/{vendor_id}/{document}` | Variable | Vérification vendeur |

---

## 🎭 SVG INLINE PERSONNALISÉ

### Logo Supply
```html
<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
  <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>
```
**Utilisation:** Header principal, navbar

### Placeholder Image (Produit sans image)
```html
<svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
</svg>
```
**Utilisation:** Cards produit, galerie sans image

### Spinner/Chargement
```html
<svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
```
**Utilisation:** Formulaires, recherche, attente

---

## 🏪 IMAGES RECOMMANDÉES À AJOUTER

### Logos & Branding
| Élément | Recommandation | Dimensions | Format |
|--------|----------------|-----------|--------|
| Logo Supply principal | Logo avec texte "Supply" | 200x200px | PNG/SVG |
| Favicon Supply | Petite version du logo | 32x32px, 16x16px | ICO/PNG |
| Logo noir (dark mode) | Version sombre du logo | 200x200px | PNG/SVG |
| Logo blanc (light mode) | Version claire du logo | 200x200px | PNG/SVG |

### Icônes Complémentaires recommandées
| Icône | Utilisation | Format |
|-------|------------|--------|
| Home/Accueil | Bouton accueil | SVG |
| Back/Retour | Navigation précédente | SVG |
| Settings/Paramètres | Page paramètres | SVG |
| Logout/Déconnexion | Menu profil | SVG |
| Help/Aide | Support utilisateur | SVG |
| Star/Étoile | Favoris, notation | SVG |
| Heart/Cœur | Favoris alternatif | SVG |
| Share/Partager | Partager produit | SVG |
| Print/Imprimer | Imprimer commande | SVG |
| Download/Télécharger | Télécharger doc | SVG |

### Images d'Erreur & Vide
| Image | Utilisation | Dimensions |
|-------|------------|-----------|
| 404 Not Found | Page erreur | 400x300px |
| Empty State | Aucun produit | 300x300px |
| Network Error | Erreur connexion | 400x300px |
| No Results | Recherche vide | 300x300px |

### Bannières & Promotions
| Type | Utilisation | Dimensions | Nombre |
|------|------------|-----------|--------|
| Banner promotion | Hero section accueil | 1920x600px | 3-5 |
| Catégorie image | Section catégories | 600x400px | 1 par catégorie |
| Offre spéciale | Popup promotions | 800x600px | Variable |

---

## 📁 STRUCTURE DES DOSSIERS

```
public/
├── images/
│   ├── payments/              ✅ COMPLÉTÉ (5 images)
│   │   ├── wave.png
│   │   ├── orange money.png
│   │   ├── mtn money.png
│   │   ├── moov money.png
│   │   └── a la livraison.jfif
│   ├── banners/               ← Bannières (À créer)
│   ├── errors/                ← Erreurs (À créer)
│   └── logos/                 ← Logos branding (À créer)
│
storage/
└── app/
    └── public/
        ├── produits/          ✅ COMPLÉTÉ (119 images)
        ├── categories/        ✅ COMPLÉTÉ (19 images)
        ├── profils/           ← Photos profil clients (vide, dynamique)
        ├── vendors/           ← Photos profil vendeurs (vide, dynamique)
        ├── cni/               ✅ ACTIF (Documents CNI)
        ├── delivery_photos/   ← Photos livraisons (vide, dynamique)
        └── .gitignore         ← Permet le tracking
```

### 📊 Statistiques des Images Stockées
- **Images Produits:** 119 fichiers ✅
- **Images Catégories:** 19 fichiers ✅
- **Total Actifs:** 138 images + 5 paiements = 143 images

---

## 🔧 UTILISATION DES ICÔNES SVG

### Icônes x-icon (Composant Blade)
Le projet utilise probablement un composant `x-icon` pour afficher les icônes SVG. Exemples d'utilisation trouvés :

```blade
<!-- Format: name = "icon-name" -->
<x-icon name="dollar-sign" class="w-12 h-12 text-green-600" />
<x-icon name="package" class="w-12 h-12 text-blue-600" />
<x-icon name="shopping-cart" class="w-12 h-12 text-purple-600" />
<x-icon name="check-circle" class="w-12 h-12 text-cyan-600" />
<x-icon name="clock" class="w-8 h-8 text-yellow-600" />
<x-icon name="award" class="w-8 h-8 text-green-600" />
<x-icon name="alert-circle" class="w-8 h-8 text-red-600" />
<x-icon name="zap" class="w-6 h-6 text-yellow-500" />
<x-icon name="plus-circle" class="w-8 h-8 text-blue-600" />
<x-icon name="edit-2" class="w-8 h-8 text-green-600" />
<x-icon name="save" class="w-4 h-4 inline mr-1" />
<x-icon name="smartphone" class="w-4 h-4 inline mr-1" />
<x-icon name="check-circle" class="w-4 h-4 inline mr-1" />
<x-icon name="bar-chart-2" class="w-8 h-8 text-blue-600" />
<x-icon name="commerce/shopping-bag" class="w-12 h-12 text-primary-500" />
<x-icon name="commerce/checkout" class="w-12 h-12 text-secondary-500" />
<x-icon name="electronics/hard-drive" class="w-12 h-12 text-accent-500" />
```

---

## ✅ CHECKLIST D'IMPLÉMENTATION

### Étape 1: ✅ COMPLÉTÉE - Vérifier les Images Existantes
- [x] Images de paiement (5 fichiers trouvés)
- [x] Images de produits (119 fichiers placés)
- [x] Images de catégories (19 fichiers placés)
- [ ] Logos branding

### Étape 2: ⏳ EN COURS - Ajouter les Images Manquantes
- [ ] Logo Supply (3 versions: couleur, noir, blanc)
- [ ] Favicon (16x16, 32x32)
- [ ] 3-5 bannières accueil
- [ ] Images erreur (404, empty state, etc.)

### Étape 3: ✅ OPTIONNEL - Optimiser les Images
- [x] Images produits et catégories présentes
- [ ] Compresser PNG pour optimisation (optionnel)
- [ ] Créer versions WebP (optionnel)
- [ ] Vérifier dimensions recommandées (optionnel)

### Étape 4: ✅ PRÊT - Configurer le Cache
- [x] Images statiques configurées
- [x] Lazy loading images dynamiques (Blade)
- [ ] CDN configuration (optionnel)

---

## 📝 NOTES

1. **SVG Inline** : Les icônes SVG sont codées directement dans les templates (économe en requêtes)
2. **Images Dynamiques** : Stockées dans `/storage/` via Laravel Storage (user_id basé)
3. **Fallbacks** : DiceBear API pour avatars si pas de photo profil
4. **Format Recommandé** : PNG pour logos/icônes, JPEG pour photos, SVG pour logos vecteur
5. **Responsive** : Utiliser `srcset` pour images responsives

---

## 🚀 STATUS FINAL

### ✅ IMAGES PLACÉES ET ACTIVES

#### Produits (119 images)
Toutes les images de produits ont été copiées vers `/storage/app/public/produits/` et sont maintenant accessibles via :
```blade
<img src="{{ asset('storage/produits/' . $image_name) }}" alt="Product">
```

#### Catégories (19 images)
Toutes les images de catégories ont été copiées vers `/storage/app/public/categories/` et sont maintenant accessibles via :
```blade
<img src="{{ asset('storage/categories/' . $categorie->image) }}" alt="{{ $categorie->nom }}">
```

#### Paiements (5 images)
Déjà présentes dans `/public/images/payments/` et en fonctionnement

### 📋 IMAGES MAINTENANT VISIBLES
- ✅ Page liste produits : affiche la 1ère image du produit
- ✅ Page détail produit : galerie avec toutes les images
- ✅ Section catégories : affiche l'image vignette
- ✅ Panier : affiche la 1ère image du produit
- ✅ Favoris : affiche l'image du produit
- ✅ Vendeur - produits : affiche la 1ère image
- ✅ Visiteur en attente : placeholder SVG si pas d'image

### 🎯 PROCHAINES ÉTAPES (OPTIONNEL)

1. **Branding Supply:**
   - Créer logo officiel Supply
   - Placer dans `/public/images/logos/`
   - Placer favicon dans `/public/`

2. **Amélioration UX:**
   - Créer images erreur (404, empty state)
   - Créer bannières pour accueil
   - Placer dans `/public/images/`

3. **Optimisation (si nécessaire):**
   - Compresser images (tinypng.com)
   - Convertir en WebP pour performance
   - Setup lazy loading avancé

---

**Dernière mise à jour:** 2 Mars 2026
**Projet:** Supply - Plateforme E-Commerce Multi-Vendeur (Côte d'Ivoire)
