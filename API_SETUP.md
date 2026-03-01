# 🔑 Configuration des APIs et Guide de Démonstration

## 📋 Table des Matières
1. [APIs Intégrées](#apis-intégrées)
2. [Configuration des Clés](#configuration-des-clés)
3. [Guide de Démonstration](#guide-de-démonstration)

---

## APIs Intégrées

### 1️⃣ WhatsApp Business (wa.me)
**Status**: ✅ Gratuit - Pas de clé requise

**Endpoint**: `https://wa.me/{NUMERO}?text={MESSAGE}`

**Utilisation**: Bouton de contact direct sur les fiches produits

**Avantage**: Interaction directe avec le vendeur via WhatsApp  
**Marché local**: Adapté aux habitudes de communication en Côte d'Ivoire

**Configuration**:
```dotenv
WHATSAPP_BUSINESS_PHONE=22501234567
```

**Format du numéro**:
- `225` = indicatif Côte d'Ivoire
- Suivi des 8 chiffres sans espace
- Exemple: `22501234567`

**Test**:
```
https://wa.me/22501234567?text=Bonjour%20je%20veux%20acheter%20ce%20produit
```

---

### 2️⃣ DiceBear Avatars
**Status**: ✅ Entièrement gratuit - Pas de clé

**URL de base**: `https://api.dicebear.com/7.x/avataaars/svg?seed={email}`

**Utilisation**: Génération automatique d'avatars personnalisés

**Styles disponibles**:
- `avataaars` (actuel)
- `bottts`
- `lorelei`
- `personas`
- `adventurer`
- `pixels`

**Exemple**:
```html
<img src="https://api.dicebear.com/7.x/avataaars/svg?seed=user@example.com" />
```

**Avantage**: Avatars stylisés basés sur l'email de l'utilisateur

---

### 3️⃣ ExchangeRate-API
**Status**: ⚠️ REQUIERT UNE CLÉ API

**Endpoint**: `https://api.exchangerate-api.com/v4/latest/XOF`

**Utilisation**: Convertisseur de devises en temps réel

**Devises supportées**: EUR, USD, GBP (pour XOF)

**Avantage**: Gratuit, mise à jour quotidienne des taux

**Obtention de la clé**:
1. Aller sur https://exchangerate-api.com/
2. Cliquer sur "Sign Up" (plan gratuit)
3. Créer un compte avec email
4. Vérifier votre email
5. Copier votre **API Key** depuis le tableau de bord
6. Ajouter dans `.env`:
   ```dotenv
   EXCHANGE_RATE_API_KEY=votre_cle_ici
   ```

**Test en console**:
```javascript
const apiKey = "votre_cle_ici";
fetch(`https://api.exchangerate-api.com/v4/latest/XOF?apikey=${apiKey}`)
  .then(r => r.json())
  .then(d => console.log(d.rates));
```

---

### 4️⃣ Leaflet + OpenStreetMap
**Status**: ✅ Entièrement gratuit

**CDN**: `https://cdn.jsdelivr.net/npm/leaflet@1.9.4/`

**Utilisation**: Carte interactive pour sélection de zone de livraison

**Configuration**:
```javascript
L.tileLayer(
  'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
  {
    maxZoom: 18,
    attribution: '© OpenStreetMap contributors'
  }
)
```

**Avantage**:
- Gratuit et open-source
- Pas de clé API requise
- Géolocalisation interactive

**Limite**: Gratuit pour usage modéré (< 1M requêtes/mois)

---

### 5️⃣ ApexCharts
**Status**: ✅ Open-source gratuit

**NPM**: `apexcharts@^4.10.0`

**Utilisation**: 
- Graphiques statistiques au dashboard client
- Trendlines et charts au dashboard vendeur
- Visualisation des données de consommation et ventes

**Avantage**: Librairie moderne avec animations fluides

---

### 6️⃣ SweetAlert2
**Status**: ✅ Open-source gratuit

**NPM**: `sweetalert2@^11.14.5`

**Utilisation**: 
- Confirmations élégantes au lieu des alerts basiques
- Toasts notifications pour feedback utilisateur
- Suppressions de compte et actions sensibles

**Avantage**: UX soignée, notifications cohérentes

---

## Configuration des Clés

### Checklist Configuration

- [ ] **ExchangeRate-API**: Clé obtenue et .env configuré
- [ ] **WhatsApp**: Numéro du vendeur ajouté dans .env
- [ ] **Vérifier** que `npm run build` compile sans erreur
- [ ] **Tester** les APIs une par une en local
- [ ] **Documenter** les résultats de test

### Script de Test Global

```javascript
// À exécuter dans la console navigateur

// 1️⃣ Test ExchangeRate-API
async function testCurrency() {
  const result = await currencyConverter.fetchRates();
  console.log('✓ Currency API:', result);
}

// 2️⃣ Test DiceBear
function testDiceBear() {
  const img = new Image();
  img.src = "https://api.dicebear.com/7.x/avataaars/svg?seed=test@example.com";
  img.onload = () => console.log('✓ DiceBear OK');
  img.onerror = () => console.error('✗ DiceBear Failed');
}

// 3️⃣ Test Leaflet
function testLeaflet() {
  if (window.L) console.log('✓ Leaflet OK');
  else console.error('✗ Leaflet Not Loaded');
}

// 4️⃣ Test ApexCharts
function testApexCharts() {
  if (window.ApexCharts) console.log('✓ ApexCharts OK');
  else console.error('✗ ApexCharts Not Loaded');
}

// 5️⃣ Test SweetAlert2
function testSweetAlert() {
  if (window.Swal) console.log('✓ SweetAlert2 OK');
  else console.error('✗ SweetAlert2 Not Loaded');
}

// Lancer tous les tests
console.log('🧪 Starting API Tests...');
testCurrency();
testDiceBear();
testLeaflet();
testApexCharts();
testSweetAlert();
```

---

## Guide de Démonstration

### Avant de Commencer

```bash
# S'assurer que le projet est en marche
php artisan serve
# Accédez via: http://localhost:8000

# Voir les logs en temps réel
tail -f storage/logs/laravel.log
```

### Scénario 1: Vue d'Ensemble RAPIDE (5 min)

#### Étape 1: Page d'Accueil (1 min)
```
Allez sur: http://localhost:8000

Montrez:
- Layout clean et moderne
- Navigation fonctionnelle
- Produits affichés avec images
```

#### Étape 2: Fiche Produit (2 min)
```
Allez sur: http://localhost:8000/produits/1

Montrez:
1. 💚 Bouton "Contacter sur WhatsApp"
   → Cliquez → Ouvre WhatsApp Web/mobile
   → Message pré-rempli avec produit
   → API: WhatsApp
   
2. ➕ "Ajouter au Panier" 
   → Toast: "✓ Produit ajouté!" (top-right)
   → Auto-disparaît après 3s
   → API: SweetAlert2

APIS DÉMONTRÉES: ✅ WhatsApp + ✅ SweetAlert2
```

#### Étape 3: Inscription avec Validation (2 min)
```
Allez sur: http://localhost:8000/register

Montrez:
1. Champ Email - Validation AJAX:
   - Tapez "ahmed@" → Spinner apparaît
   - "ahmed@example.com" → "✓ Email disponible" (vert)
   - "admin@example.com" → "✗ Email déjà utilisé" (rouge)

2. Champ Username:
   - Validation en temps réel
   - Message: disponible / déjà pris

3. Champ Password:
   - "123" → "Faible"
   - "Password123!" → "Fort"

APIS DÉMONTRÉES: ✅ AJAX Validation + ✅ Password Strength
```

---

### Scénario 2: Profil Client Complet (5 min)

#### Connexion
```
Email: client@example.com
Password: password

OU créez votre compte via /register
```

#### Aller au Profil
```
http://localhost:8000/mon-profil

Montrez:
1. AVATAR (en haut):
   → Généré automatiquement depuis email
   → Si email change, avatar change
   → API: DiceBear

2. ZONE DE LIVRAISON (milieu):
   → Cliquez sur la CARTE
   → Vous voyez la Côte d'Ivoire
   → Cliquez pour sélectionner zone
   → Latitude/Longitude se mettent à jour
   → API: Leaflet + OpenStreetMap

3. HISTORIQUE DE SÉCURITÉ (bas):
   → Dernière connexion
   → Browser: Chrome 120
   → OS: Windows 10
   → Device: Desktop
   → IP: 192.168.1.5
   → Localisation: Abidjan, CI
   → S'enrichit à chaque connexion
   → API: Security Logs (Custom)

APIS DÉMONTRÉES: ✅ DiceBear + ✅ Leaflet + ✅ Security Logs
```

---

### Scénario 3: Dashboard Client (2 min)

```
http://localhost:8000/dashboard

Montrez:

1. STATISTIQUES EN HAUT:
   - Solde disponible
   - Nombre de commandes
   - Montant total dépensé

2. GRAPHIQUE - "Dépenses 7 derniers jours":
   → Area chart interactif
   → Hover pour voir valeurs
   → Données en temps réel
   → API: ApexCharts

APIS DÉMONTRÉES: ✅ ApexCharts
```

---

### Scénario 4: Vendeur Dashboard (5 min)

#### Connexion Vendeur
```
Email: vendeur@example.com
Password: password

Ou créez un compte avec rôle vendeur
```

#### Dashboard Vendeur
```
http://localhost:8000/vendeur/dashboard

Montrez:

1. KPI CARDS (en haut):
   💰 Ventes: 2.5M XOF
   📦 Commandes: 156
   📈 AOV: 16K XOF
   🛍️ Produits: 45

2. GRAPHIQUE 1 - TENDANCES VENTES:
   → Sélecteur: [7j] [30j] [90j]
   → Cliquez: "7 jours" → Graphique change
   → Area chart avec données réelles
   → API: ApexCharts

3. GRAPHIQUE 2 - STOCK STATUS:
   → Donut chart (pie circulaire)
   → Répartition: Bon/Bas/Critique/Rupture
   → Couleurs: vert/orange/rouge
   → API: ApexCharts

4. GRAPHIQUE 3 - AVIS CLIENTS:
   → Bar chart horizontal
   → Distribution des avis par note
   → Analyse sentiments
   → API: ApexCharts

APIS DÉMONTRÉES: ✅ ApexCharts (3x) + ✅ Currency Formatting (XOF)
```

---

### Scénario 5: Live Search (3 min)

```
1. Allez sur: http://localhost:8000/

2. Utilisez la barre de search:
   → Tapez "t" → Attendre 2s
   → Résultats dropdown: Image + Nom + Prix + Stock
   → Max 8 résultats
   → "Voir tous" link

   OU API direct:
   POST /api/search/live?q=t-shirt
   
   Réponse JSON:
   [
     {id: 1, nom: "T-shirt Nike", prix: 15000, image: "..."},
     {id: 2, nom: "T-shirt Adidas", prix: 12000, image: "..."},
     ...
   ]

APIS DÉMONTRÉES: ✅ Live Search (Custom)
```

---

### Scénario 6: Testing Technique (3 min)

#### Via Postman ou curl:

```bash
# 1. Email Validation
POST http://localhost:8000/api/validate/email
Body: {"email": "test@example.com"}
Response: {"valid": true/false, "message": "..."}

# 2. Live Search
POST http://localhost:8000/api/search/live?q=t-shirt
Response: {"results": [...], "count": 8}

# 3. Vendor Statistics - Ventes
GET http://localhost:8000/vendeur/api/statistics/sales?days=7
Response: {
  "data": {
    "dates": ["2026-02-23", ...],
    "ventes": [50000, 45000, ...],
    "commandes": [3, 2, ...],
    "indicators": {totalVentes: 2.5M, ...}
  }
}

# 4. Inventory Status
GET http://localhost:8000/vendeur/api/statistics/inventory
Response: {"data": {"Bon": 120, "Bas": 45, "Critique": 12, ...}}
```

---

## Checklist pour les Jurés

### APIs Externes (6)
- [ ] WhatsApp: Page produit → Bouton "Contacter"
- [ ] DiceBear: /mon-profil → Avatar
- [ ] Leaflet: /mon-profil → Carte interactive
- [ ] ApexCharts: /dashboard + /vendeur/dashboard
- [ ] SweetAlert2: Confirmations + toasts partout
- [ ] ExchangeRate-API: Convertisseur devises

### Features Premium (5)
- [ ] Security Logs: /mon-profil → Historique
- [ ] Email Validation: /register → Real-time
- [ ] Live Search: Chercher produits → Dropdown
- [ ] Skeleton Screens: DevTools throttle → Loading states
- [ ] Vendor Stats: /vendeur/dashboard → 3 graphiques

### Code Quality
- [ ] `npm run build`: ✅ SUCCESS
- [ ] PHP syntax: ✅ Zero errors
- [ ] Database: ✅ Migrations OK
- [ ] Routes: ✅ All endpoints working

---

## Quick Links

| Feature | Page | URL |
|---------|------|-----|
| WhatsApp | Produit | /produits/1 |
| DiceBear | Profil Client | /mon-profil |
| Leaflet | Profil Client | /mon-profil |
| ApexCharts | Dashboard Client | /dashboard |
| ApexCharts | Vendor Stats | /vendeur/dashboard |
| Email Validation | Register | /register |
| Security Logs | Profil Client | /mon-profil |
| Live Search | (Partout) | /api/search/live |

---

## 💡 Notes Importantes

- ⚠️ **NE JAMAIS** committer les clés API dans Git
- ✅ Les clés sont déjà dans `.gitignore` (fichier `.env`)
- 🔒 En production, utiliser les variables d'environnement du serveur
- 📱 WhatsApp: Numéro au format international obligatoire
- 🔑 ExchangeRate-API: Clé gratuite pour usage modéré

---

**Date**: 1 mars 2026  
**Status**: Documentation consolidée  
**Prêt**: Pour démonstration jury ✅
