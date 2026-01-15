# Icônes du Projet Supply

## Structure des Icônes

Ce dossier contient toutes les icônes SVG pour le projet Supply. Les icônes sont organisées par catégorie.

### Catégories Disponibles

#### 1. **Électronique & Informatique** (electronics/)
- desktop-computer.svg - Ordinateur de bureau
- laptop.svg - Ordinateur portable
- monitor.svg - Écran/Moniteur
- keyboard.svg - Clavier
- mouse.svg - Souris
- headphones.svg - Casque audio
- microphone.svg - Microphone
- speaker.svg - Haut-parleur
- usb-drive.svg - Clé USB
- hard-drive.svg - Disque dur externe
- motherboard.svg - Carte mère
- graphics-card.svg - Carte graphique
- power-supply.svg - Alimentation
- cooling-fan.svg - Ventilation/Refroidissement
- router.svg - Routeur Wi-Fi
- webcam.svg - Webcam
- printer.svg - Imprimante

#### 2. **Périphériques Mobile** (mobile/)
- smartphone.svg - Téléphone intelligent
- tablet.svg - Tablette
- smartwatch.svg - Montre intelligente
- airpods.svg - Écouteurs sans fil
- phone-case.svg - Étui de téléphone
- screen-protector.svg - Protecteur d'écran
- charger.svg - Chargeur

#### 3. **Accessoires Réseau** (network/)
- ethernet-cable.svg - Câble Ethernet
- hdmi-cable.svg - Câble HDMI
- usb-cable.svg - Câble USB
- power-cable.svg - Câble d'alimentation
- usb-hub.svg - Hub USB
- docking-station.svg - Station d'accueil

#### 4. **Stockage & Sauvegarde** (storage/)
- ssd.svg - Disque SSD
- m2-drive.svg - Disque M.2
- nvme.svg - Disque NVMe
- sd-card.svg - Carte SD
- microsd-card.svg - Micro SD
- memory-ram.svg - Barrette RAM
- storage-cloud.svg - Stockage cloud

#### 5. **Logiciels & Services** (software/)
- windows.svg - Windows
- macos.svg - macOS
- linux.svg - Linux
- antivirus.svg - Antivirus
- vpn.svg - VPN
- password-manager.svg - Gestionnaire de mots de passe
- backup.svg - Sauvegarde

#### 6. **Gaming** (gaming/)
- gaming-mouse.svg - Souris gaming
- gaming-keyboard.svg - Clavier gaming
- gaming-headset.svg - Casque gaming
- gaming-chair.svg - Chaise gaming
- gaming-monitor.svg - Moniteur gaming
- graphics-intensive.svg - Performance graphique

#### 7. **Outils & Support** (tools/)
- wrench.svg - Outil/Maintenance
- settings.svg - Paramètres
- upgrade.svg - Amélioration
- warranty.svg - Garantie
- support.svg - Support client
- repair.svg - Réparation
- installation.svg - Installation

#### 8. **Statuts & Indicateurs** (status/)
- in-stock.svg - En stock
- low-stock.svg - Stock limité
- out-of-stock.svg - Rupture de stock
- new-product.svg - Nouveau produit
- best-seller.svg - Meilleure vente
- on-sale.svg - En promotion
- hot-deal.svg - Offre du jour
- verified.svg - Vérifié/Certifié
- warning.svg - Attention

#### 9. **Commerce & Panier** (commerce/)
- shopping-cart.svg - Panier
- shopping-bag.svg - Sac d'achat
- checkout.svg - Paiement
- order.svg - Commande
- delivery.svg - Livraison
- packaging.svg - Emballage
- gift.svg - Cadeau/Offre

#### 10. **Utilisateur & Compte** (user/)
- user-profile.svg - Profil utilisateur
- seller.svg - Vendeur
- buyer.svg - Acheteur
- verified-seller.svg - Vendeur vérifié
- login.svg - Connexion
- logout.svg - Déconnexion
- register.svg - Inscription
- wishlist.svg - Liste de souhaits
- reviews.svg - Avis

#### 11. **Informations** (info/)
- info.svg - Information
- question.svg - Question
- help.svg - Aide
- notification.svg - Notification
- alert.svg - Alerte
- success.svg - Succès
- error.svg - Erreur
- loading.svg - Chargement

#### 12. **Navigation** (navigation/)
- home.svg - Accueil
- search.svg - Recherche
- menu.svg - Menu
- close.svg - Fermer
- back.svg - Retour
- forward.svg - Suivant
- up.svg - Haut
- down.svg - Bas
- filter.svg - Filtre
- sort.svg - Tri

## Utilisation dans les Templates Blade

### Utilisation Simple
```blade
<x-icon name="electronics/desktop-computer" class="w-6 h-6" />
```

### Avec Couleurs
```blade
<x-icon name="status/in-stock" class="w-6 h-6 text-green-500" />
<x-icon name="status/out-of-stock" class="w-6 h-6 text-red-500" />
```

### Pour les Catégories
```blade
@switch($categorie->nom)
    @case('Ordinateurs')
        <x-icon name="electronics/desktop-computer" class="w-8 h-8 text-primary-500" />
        @break
    @case('Périphériques')
        <x-icon name="electronics/keyboard" class="w-8 h-8 text-primary-500" />
        @break
@endswitch
```

## Recommandations d'Utilisation

### Par Catégorie de Produit
- **Ordinateurs**: `electronics/desktop-computer` ou `electronics/laptop`
- **Écrans**: `electronics/monitor`
- **Claviers**: `electronics/keyboard`
- **Souris**: `electronics/mouse`
- **Audio**: `electronics/headphones` ou `electronics/speaker`
- **Stockage**: `storage/ssd` ou `storage/memory-ram`
- **Câbles**: `network/ethernet-cable` ou `network/usb-cable`
- **Gaming**: `gaming/gaming-mouse` ou `gaming/gaming-keyboard`
- **Logiciels**: `software/antivirus` ou `software/vpn`

### Par Statut de Stock
- ✅ En stock → `status/in-stock` (vert)
- ⚠️ Stock limité → `status/low-stock` (orange)
- ❌ Rupture → `status/out-of-stock` (rouge)

### Par Action
- Voir → `navigation/forward` ou emoji 👁️
- Ajouter au panier → `commerce/shopping-cart`
- Ajouter aux favoris → `user/wishlist`
- Support → `tools/support`

## Couleurs Harmonisées

Utilisez les couleurs du projet Supply:
- **Primary (Cyan)**: `text-primary-500` ou `text-cyan-500`
- **Accent (Rose)**: `text-accent-500` ou `text-rose-500`
- **Secondary (Violet)**: `text-secondary-500` ou `text-violet-500`
- **Success (Vert)**: `text-green-500`
- **Warning (Orange)**: `text-amber-500`
- **Danger (Rouge)**: `text-red-500`

## Composant Blade Disponible

Un composant `x-icon` est disponible pour faciliter l'utilisation:

```blade
<!-- Simple -->
<x-icon name="electronics/laptop" />

<!-- Avec classe personnalisée -->
<x-icon name="electronics/laptop" class="w-8 h-8 text-primary-500" />

<!-- Avec plusieurs classes -->
<x-icon name="electronics/laptop" class="w-6 h-6 text-primary-500 hover:text-accent-500 transition-colors" />
```
