# Supply PWA - Configuration et Installation

## Vue d'ensemble

Supply est maintenant transformée en Progressive Web App (PWA), permettant aux utilisateurs d'installer l'application sur leurs appareils comme une application native.

## ✨ Fonctionnalités PWA Activées

- ✅ Installation sur écran d'accueil (Android, iOS, Desktop)
- ✅ Support hors ligne avec cache stratégique
- ✅ Notifications push
- ✅ Synchronisation en arrière-plan
- ✅ Mode fullscreen standalone
- ✅ Icônes adaptatives (Android)
- ✅ Screenshots pour la boutique d'apps

## 📋 Fichiers PWA

```
public/
├── manifest.json                 # Métadonnées PWA
├── service-worker.js             # Service Worker pour cache
├── offline.html                  # Page hors ligne
├── icons/                        # Dossier des icônes
│   ├── icon-192x192.png
│   ├── icon-192x192-maskable.png
│   ├── icon-256x256.png
│   ├── icon-512x512.png
│   ├── icon-512x512-maskable.png
│   ├── apple-touch-icon-180x180.png
│   └── badge-*.png
└── screenshots/                  # Screenshots pour app stores
    ├── screenshot-540x720.png    # Mobile narrow
    └── screenshot-1280x720.png   # Desktop wide

resources/js/
├── pwa.js                        # Module de gestion PWA
└── app.js                        # Registre du Service Worker

resources/views/layouts/
├── app.blade.php                 # Meta tags PWA
└── guest.blade.php               # Meta tags PWA
```

## 🚀 Mise en place

### 1. Générer les icônes PWA

#### Option A: Génération automatique (Recommandé)

```bash
# Générer les icônes par défaut
php artisan pwa:generate-icons

# Générer à partir d'une image source
php artisan pwa:generate-icons --source-image=storage/app/my-logo.png
```

**Prérequis:** PHP GD extension
- ✅ Déjà activée dans la plupart des installations WAMP
- Si manquante: décommenter `extension=gd` dans `php.ini`

#### Option B: Icônes personnalisées

Si vous avez des icônes existantes, placez-les dans `public/icons/`:

```
public/icons/
├── icon-192x192.png
├── icon-192x192-maskable.png
├── icon-256x256.png
├── icon-512x512.png
├── icon-512x512-maskable.png
└── apple-touch-icon-180x180.png
```

**Tailles requises:**
- 192x192 (standard)
- 192x192-maskable (Android adaptive)
- 256x256 (intermédiaire)
- 512x512 (grande résolution)
- 512x512-maskable (Android adaptive)

### 2. Screenshots pour app stores (Optionnel)

Créez des screenshots et placez-les dans `public/screenshots/`:

```
public/screenshots/
├── screenshot-540x720.png   # Format mobile (540x720px min)
└── screenshot-1280x720.png  # Format desktop (1280x720px min)
```

**Recommandations:**
- Montrez l'interface clée et la valeur ajoutée
- Texte lisible et UI claire
- Format 16:9 pour les deux formats

### 3. Vérifier l'installation

#### Sur Chrome/Edge (Desktop)
1. Visiter le site: `http://localhost:8000`
2. Cliquer l'icône d'installation (en haut à droite) ou...
3. Menu → "Installer Supply" / "Install app"
4. L'app s'ouvre dans une fenêtre autonome

#### Sur Android
1. Ouvrir Supply dans Chrome
2. Menu (⋮) → "Installer l'app" / "Install app"
3. Confirmer → Supply s'ajoute à l'écran d'accueil

#### Sur iOS Safari
1. Ouvrir Supply dans Safari
2. Partage → "Sur l'écran d'accueil"
3. Supply se comporte comme une app native

#### Sur Desktop (Linux, Mac, Windows)
1. Ouvrir le site dans Chrome/Edge
2. Installation automatique proposée ou via menu
3. Lancer depuis l'app launcher du système

## 🔧 Configuration

### Personnaliser le manifest

Modifier `public/manifest.json`:

```json
{
  "name": "Votre nom complet",
  "short_name": "Nom court",
  "description": "Description...",
  "theme_color": "#0a0a0a",        // Couleur barre d'état
  "background_color": "#ffffff"    // Couleur splash screen
}
```

### Stratégies de cache

Le Service Worker implémente 3 stratégies:

1. **Network First** (API endpoints)
   - Essaie le réseau d'abord
   - Fallback sur cache si offline
   - Parfait pour les données dynamiques

2. **Cache First** (Assets statiques)
   - Utilise le cache en priorité
   - Chargement ultra-rapide
   - Parfait pour CSS, JS, images

3. **Stale While Revalidate** (Rapports)
   - Retourne le cache immédiatement
   - Met à jour en arrière-plan
   - Meilleure UX avec données à jour

### Modifier les stratégies

Dans `public/service-worker.js`:

```javascript
const CACHE_STRATEGIES = {
  networkFirst: [
    /\/api\//,              // Vos patterns API
    /\.json$/
  ],
  cacheFirst: [
    /\.css$/,               // Vos assets statiques
    /\.js$/,
    /\/images\//
  ]
};
```

## 📱 Installation - Guide Utilisateur

### Pour l'utilisateur final

**Desktop (Chrome/Edge):**
1. Visiter supply.app
2. Cliquer l'icône d'installation (adresse)
3. Cliquer "Installer"
4. Supply apparaît dans les apps

**Mobile Android:**
1. Ouvrir dans Chrome
2. Menu (⋮) → Installer l'app
3. Confirmer
4. Accès depuis l'écran d'accueil

**iOS:**
1. Ouvrir dans Safari
2. Partage → Sur l'écran d'accueil
3. Ajouter → Accès depuis l'écran d'accueil

## 🔌 Fonctionnalités avancées

### Notifications Push

```javascript
// Demander la permission
import { requestNotificationPermission } from './pwa.js';
requestNotificationPermission();

// Envoyer depuis le backend
Notification::send($user, new StockAlertNotification($product));
```

### Synchronisation en arrière-plan

```javascript
import { syncData } from './pwa.js';
syncData(); // Synchronise les ordres offlines
```

### Communication avec le Service Worker

```javascript
import { sendMessageToServiceWorker } from './pwa.js';
sendMessageToServiceWorker({
  type: 'CLEAR_CACHE',
  cacheName: 'supply-api-v1'
});
```

## 📊 Vérifier le statut

### Vérifications en DevTools (F12)

1. **Application Tab:**
   - Manifest status ✓
   - Service Worker registration ✓
   - Cache storage

2. **Console:**
   - `[PWA]` messages de débogage
   - Erreurs de Service Worker

3. **Network Tab:**
   - Service Worker interception
   - Stratégies de cache appliquées

### Tests offline

1. DevTools → Network → Offline
2. Vérifier que les pages restent accessibles
3. Vérifier les APIs retournent du cache

## 🐛 Dépannage

### Service Worker ne s'enregistre pas

```javascript
// Vérifier dans console (F12)
navigator.serviceWorker.getRegistrations()
  .then(regs => console.log(regs));

// Pour Chrome: DevTools → Application → Service Workers
```

**Solutions:**
- ✓ Site doit être HTTPS (sauf localhost)
- ✓ Public/service-worker.js accessible
- ✓ Pas d'erreurs JS dans la console
- ✓ Try incognito mode (pas de cache navigateur)

### Installation n'apparaît pas

**Critères pour PWA installable:**
- ✓ HTTPS (exception: localhost)
- ✓ Manifest valide avec icons
- ✓ Service Worker enregistré
- ✓ Viewport meta tag présent
- ✓ Display mode standalone

**Vérifier:**
```javascript
// Console
fetch('/manifest.json').then(r => r.json()).then(console.log)
```

### Cache obsolète

```bash
# Forcer actualisation du Service Worker
# Dans DevTools Application → Service Workers
# Cliquer "Unregister"
# Recharger la page
```

Ou ajouter un timestamp dans le manifest:
```bash
php artisan pwa:generate-icons  # Régénère avec nouveau timestamp
```

## 📈 Optimisations futures

- [ ] Service Worker v2 avec Delta Sync
- [ ] Background Synchronization pour commandes offline
- [ ] Share Target pour partager produits
- [ ] Periodic Background Sync pour notifications
- [ ] File Handling pour imports

## 🔗 Ressources

- [MDN - Progressive Web Apps](https://developer.mozilla.org/en-US/docs/Web/Progressive_web_apps)
- [Web.dev - PWA Checklist](https://web.dev/pwa-checklist/)
- [PWA Builder](https://www.pwabuilder.com)
- [Manifest Validator](https://manifest-validator.appspot.com)

## 📝 Notes techniques

- **Cache versioning:** `supply-v1` dans service-worker.js
- **Install timeout:** 60 secondes par défaut
- **Update check:** Toutes les minutes
- **Notification scope:** `/` (site entier)
- **Background sync scope:** `sync-orders`

---

**Dernière mise à jour:** 12 mars 2026
