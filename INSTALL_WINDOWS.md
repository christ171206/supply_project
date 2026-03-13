🪟 Guide Installation Supply sur Windows
==========================================

## Installation Supply comme App Native sur Windows

### ✅ Prérequis

- **Navigateurs supportés:**
  - ✓ Google Chrome (v76+)
  - ✓ Microsoft Edge (v79+)
  - ✓ Brave (v1.0+)
  - ✓ Opera (v62+)

- **Versions Windows:**
  - ✓ Windows 10 (1903+)
  - ✓ Windows 11
  - ✓ Windows 7 SP1+ (Chrome seulement)

---

## 📖 Méthode 1: Chrome Desktop

### Étape par étape

**1. Ouvrir le site**
```
1. Lancer Google Chrome
2. Aller à: http://localhost:8000/pwa-install
   (ou directement http://localhost:8000)
3. Attendre le chargement complet (quelques secondes)
```

**2. Installer l'application**

Vous devriez voir **UNE** de ces indications:

**Option A: Icône dans la barre d'adresse** (Recommandé)
```
Barre d'adresse
┌─────────────────────────────────────┐
│ http://localhost:8000    [↓ INSTALL]│  ← Cherchez cette icône
└─────────────────────────────────────┘
            ↓ Cliquer ici
```

**Option B: Popup automatique**
```
"Installer Supply?" 
╔═════════════════════════════════════╗
║ Vous pouvez installer cette app     ║
║                                     ║
║  [INSTALLER]  [Non merci]           ║
╚═════════════════════════════════════╝
      ↓ Cliquer INSTALLER
```

**Option C: Menu Chrome**
```
Menu Chrome (⋯) en haut à droite
    ↓
[Plus d'outils]
    ↓
[Créer des raccourcis]
    ↓
✓ Ouvrir comme fenêtre
    ↓
[CRÉER]
```

**3. Confirmer l'installation**

Une popup s'affiche:
```
╔═════════════════════════════════════╗
║ Installer Supply?                   ║
║ Cet app sera installée sur Windows  ║
║ et accessible depuis le menu et le  ║
║ Bureau.                             ║
║                                     ║
║  [INSTALLER]  [ANNULER]             ║
╚═════════════════════════════════════╝
        ↓ Cliquer INSTALLER
```

**4. Succès! ✅**

L'app se lance automatiquement dans une **fenêtre autonome** sans la barre Chrome.

```
┌────────────────────────────────────────┐
│ Supply — Boutique Minimaliste    ■ □ ✕│  ← Pas de barre de navigation Chrome
├────────────────────────────────────────┤
│                                        │
│   Supply                    Accueil... │
│   [Logo]                               │
│                                        │
│   Boutique Minimaliste                 │
│                                        │
└────────────────────────────────────────┘
```

**5. Accès rapide**

Supply est maintenant disponible via:

✅ **Menu Démarrer**
```
Clic Windows → Taper "Supply" → Apparaît dans résultats
```

✅ **Raccourci Bureau** (optionnel)
```
Un raccourci "Supply" peut être créé (selon vos paramètres)
```

✅ **Épingler à la barre d'outils**
```
Clic droit sur l'icône Supply → Épingler à la barre d'outils
```

✅ **Liste des apps**
```
Menu Démarrer → Toutes les apps → Supply
```

---

## 📖 Méthode 2: Microsoft Edge

### Processus identique

**1. Lancer Edge**
```
Clic sur le logo Edge
```

**2. Aller au site**
```
http://localhost:8000
```

**3. Installer l'app**

**Option A: Icône dans la barre d'adresse**
```
Barre d'adresse
┌──────────────────────────────────────┐
│ http://localhost:8000  [+ INSTALLER] │  ← Cherchez cette icône
└──────────────────────────────────────┘
        ↓ Cliquer ici
```

**Option B: Menu Edge**
```
Menu Edge (⋯) en haut à droite
    ↓
[Applications]
    ↓
[Installer cette application web]
    ↓
[Installer]
```

**4. Confirmer**
```
╔═══════════════════════════════════════╗
║ Installer Supply?                     ║
║                                       ║
║ [INSTALLER]  [ANNULER]                ║
╚═══════════════════════════════════════╝
```

**5. Succès!** ✅

Même résultat que Chrome.

---

## 🔍 Dépannage - L'icône d'installation n'apparaît pas?

### Vérifier la compatibilité

1. **Ouvrir DevTools** (F12)
2. **Aller à l'onglet "Application"**
3. **Vérifier "Manifest"**

```
Application Tab
├─ Manifest
│  └─ [Voir "Status: OK"]
└─ Service Workers
   └─ [Voir "Active"]
```

### Solutions

**❌ "Manifest missing"**
```
Solution: 
1. Vérifier que http://localhost:8000/manifest.json existe
2. Ouvrir dans l'onglet: http://localhost:8000/manifest.json
3. Doit afficher du JSON, pas une erreur
```

**❌ "Service Worker not registered"**
```
Solution:
1. F12 → Console
2. Vérifier qu'il n'y a pas d'erreurs rouges
3. Recharger la page (Ctrl+R)
4. Essayer en mode incognito (Ctrl+Maj+N)
```

**❌ "HTTPS required"**
```
Status: Normal sur localhost ✓
Vous êtes sur http://localhost:8000 → C'est bon!
```

**❌ "Cache pas à jour"**
```
Solution:
1. Vider le cache: Ctrl+Maj+Suppr
2. Cocher "Images et fichiers en cache"
3. Effacer
4. Recharger
```

---

## 🖥️ Utiliser l'application installée

### Lancer Supply

**Méthode 1: Menu Démarrer** (Recommandé)
```
1. Appuyer sur la touche Windows
2. Taper "Supply"
3. Cliquer "Supply" dans les résultats
```

**Méthode 2: Raccourci Bureau**
```
Double-cliquer sur "Supply" sur le bureau
```

**Méthode 3: Barre d'outils**
```
Cliquer sur l'icône Supply épinglée
```

**Méthode 4: À partir de Chrome/Edge**
```
Menu (⋯) → Applications → Supply
```

### Aspect de l'application

```
┌──────────────────────────────────────────┐
│ Supply — Boutique Minimaliste    ■ □ ✕  │
├──────────────────────────────────────────┤
│                                          │
│  🏠 Supply        🔍 Chercher  🛒 Panier │
│                                          │
│  ┌──────────────────────────────────┐   │
│  │ Produits tendance                │   │
│  │                                  │   │
│  │ [Image] Produit 1 - 500 XOF     │   │
│  │ [Image] Produit 2 - 750 XOF     │   │
│  │ [Image] Produit 3 - 1200 XOF    │   │
│  └──────────────────────────────────┘   │
│                                          │
└──────────────────────────────────────────┘
```

**Particularités de l'app:**
- ✅ Pas de barre d'adresse
- ✅ Pas d'onglets
- ✅ Fonctionne comme une vraie app
- ✅ Accès offline si disponible en cache
- ✅ Icône dans la barre d'outils

---

## 📱 Fonctionnalités quand installée

### Offline
```
Même sans internet, Supply fonctionne:
✓ Consulter les produits en cache
✓ Voir le panier
✓ Lire les reviews
✗ Passer commande (nécessite connexion)
```

### Notifications
```
Supply peut vous notifier de:
✓ Nouvelles commandes
✓ Mises à jour statut
✓ Promotions spéciales
✓ Messages des vendeurs
```

### Raccourcis
```
Menu → Raccourcis:
✓ Parcourir les produits
✓ Aller au panier
✓ Accéder au compte
```

---

## ⚙️ Paramètres après installation

### Gérer l'application

**Aller aux paramètres:**
```
Menu Démarrer → Paramètres (⚙️)
    ↓
Applications
    ↓
Applications installées
    ↓
Chercher "Supply"
```

**Options disponibles:**
```
Supply
├─ Désinstaller
├─ Lancer
├─ Paramètres avancés
└─ Réinitialiser
```

### Mettre à jour

```
Supply se met à jour automatiquement en arrière-plan.

Actuellement? Vérifier:
1. Ouvrir Supply
2. F12 → Application → Service Workers
3. Voir la version: supply-v1, supply-v2, etc.
```

---

## 🔐 Permissions

Lors du premier lancement, Supply peut demander:

### Notifications
```
"Supply veut vous envoyer des notifications"
├─ [AUTORISER] ← Recommandé pour alertes commandes
└─ [BLOQUER]
```

Donner la permission:
- ✅ Rester informé des mises à jour
- ✅ Ne pas manquer les offres
- ✅ Alerte commande reçue

### Localisation (selon région)
```
"Supply souhaite connaître votre position"
├─ [AUTORISER] ← Pour livraison
└─ [BLOQUER]
```

---

## 🚀 Conseils avancés

### Épingler au Menu Démarrer

```
Après installation:
1. Clic droit sur "Supply" (Menu démarrer)
2. "Épingler au menu Démarrer"
3. Supply apparaît dans le menu principal
```

### Créer un raccourci Bureau supplémentaire

```
1. Menu Démarrer → Supply
2. Clic droit → "Plus" → "Lieu du fichier"
3. Clic droit → Créer un raccourci
4. Place sur le Bureau ✓
```

### Épingler à la barre dés tâches

```
1. Chercher "Supply" (Menu Démarrer)
2. Clic droit → "Épingler à la barre d'outils"
3. Accès rapide en bas de l'écran
```

### Mode plein écran

```
Une fois la app lancée:
- F11 pour le mode plein écran
- F11 pour quitter
- La barre d'outils disparaît
```

---

## ✅ Checklist Installation

- [ ] Chrome ou Edge installé
- [ ] Windows 10+ (vérifier: Touche Windows → "À propos")
- [ ] Connexion internet active
- [ ] Navigateur à jour (Menu → À propos)
- [ ] Aller à http://localhost:8000
- [ ] Voir l'icône d'installation
- [ ] Cliquer et confirmer
- [ ] Supply s'ouvre automatiquement
- [ ] Menu Démarrer → "Supply" apparaît ✓
- [ ] Test offline (F12 → Network → Offline)

---

## 🆘 Aide supplémentaire

**Problem: Icône installation n'apparaît pas?**
```
1. Vérifier le site est chargé complètement
2. Attendre 3-5 secondes
3. Vérifier: F12 → Application → Manifest "OK"
4. Essayer incognito (Ctrl+Maj+N)
5. Vider cache: Ctrl+Maj+Suppr
```

**Problem: Installation fails?**
```
1. Vérifier Windows 10+ (Touches Windows + Pause)
2. Vérifier Chrome/Edge à jour
3. Essayer avec un autre navigateur
4. Redémarrer le navigateur
```

**Problem: App ne lance pas?**
```
1. Menu Démarrer → Supply → Clic
2. Si rien: Menu Démarrer → Paramètres → Applications
3. Chercher "Supply" → Désinstaller
4. Réinstaller depuis le navigateur
```

---

## 📊 Prérequis vérifiés

✅ Windows 10+
✅ Chrome/Edge/Brave/Opera
✅ Connexion internet
✅ Manifest.json valide
✅ Service Worker enregistré
✅ Icons présentes

**Votre installation:** PRÊTE ✓

---

**Date:** 12 mars 2026
**Version:** PWA v1.0
**Locale:** FR - Windows

Pour plus d'infos: http://localhost:8000/pwa-install
