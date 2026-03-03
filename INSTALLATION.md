# 📦 Guide d'Installation - Supply Platform

Ce guide détaille comment installer et configurer le projet **Supply** sur une nouvelle machine.

---

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **PHP** 8.3 ou supérieur
  - Extensions requises : `php-mysql`, `php-pgsql`, `php-curl`, `php-json`, `php-xml`, `php-mbstring`, `php-zip`
- **Composer** (dernier version) - [Télécharger](https://getcomposer.org)
- **Node.js** 18+ et **npm** 9+ - [Télécharger](https://nodejs.org)
- **PostgreSQL** 14+ (ou MySQL 8+)
  - Base de données créée et accessible
  - Utilisateur avec permissions admin
- **Git** pour cloner le projet

### Vérifier les versions installées
```bash
php --version
composer --version
node --version
npm --version
psql --version  # ou mysql --version
```

---

## 🚀 Étapes d'Installation

### 1️⃣ Cloner le projet

```bash
git clone https://github.com/votre-repo/supply.git
cd supply
```

### 2️⃣ Installer les dépendances PHP

```bash
composer install
```

⏱️ Cette étape peut prendre 2-5 minutes selon votre connexion.

### 3️⃣ Installer les dépendances JavaScript

```bash
npm install
```

### 4️⃣ Copier le fichier d'environnement

```bash
cp .env.example .env
```

### 5️⃣ Configurer les variables d'environnement

Éditez le fichier `.env` et configurez :

```env
# Branding
APP_NAME="Supply"
APP_URL=http://127.0.0.1:8000

# Base de données (PostgreSQL recommandé)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=supply_db
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe

# Ou pour MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=supply_db
# DB_USERNAME=root
# DB_PASSWORD=

# Email (optionnel, pour tester)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_token

# Redis (pour cache/sessions - optionnel)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**⚠️ Important:** Ne jamais commiter le fichier `.env` avec les vrais identifiants !

### 6️⃣ Générer la clé d'application

```bash
php artisan key:generate
```

### 7️⃣ Créer la base de données

**Pour PostgreSQL :**
```bash
createdb supply_db
```

**Pour MySQL :**
```bash
mysql -u root -e "CREATE DATABASE supply_db;"
```

### 8️⃣ Exécuter les migrations

```bash
php artisan migrate
```

Cette commande crée toutes les tables nécessaires dans la base de données.

### 9️⃣ Remplir la base de données (Seeders optionnels)

Pour ajouter des données de test :

```bash
php artisan db:seed
```

### 🔟 Créer les répertoires de stockage

```bash
mkdir -p storage/app/public/produits
mkdir -p storage/app/public/categories
mkdir -p public/storage/produits
mkdir -p public/storage/categories
```

### 1️⃣1️⃣ Lier le répertoire de stockage public

```bash
php artisan storage:link
```

Cela crée un lien symbolique de `public/storage` vers `storage/app/public`.

### 1️⃣2️⃣ Compiler les assets (CSS/JS Tailwind)

```bash
npm run build
```

Pour le développement avec hot reload :
```bash
npm run dev
```

---

## ✅ Vérification de l'installation

### Vérifier que tout fonctionne

```bash
php artisan tinker
```

Dans le REPL que vous ouvrez, testez :
```php
User::count()  // Doit retourner un nombre
exit
```

### Vérifier les migrations

```bash
php artisan migrate:status
```

Tous les états doivent montrer "Ran" (✓).

---

## 🌐 Démarrer le serveur

### Terminal 1 - Laravel Server

```bash
php artisan serve
```

Accédez à : **http://127.0.0.1:8000**

### Terminal 2 - Compiler les assets (mode développement)

```bash
npm run dev
```

Ou juste une fois avec :
```bash
npm run build
```

### Terminal 3 - WebSocket Server (optionnel, pour temps réel)

```bash
node websocket-server.js
```

---

## 🔐 Authentification Initiale

### Créer un utilisateur administrateur

```bash
php artisan tinker
```

```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@supply.local',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'email_verified_at' => now(),
]);
exit
```

Ou utiliser le formulaire d'enregistrement à `http://127.0.0.1:8000/register`

---

## 📁 Structure des Points d'Entrée

Une fois installé, voici les URLs principales :

| URL | Description |
|-----|-------------|
| `http://127.0.0.1:8000` | Page d'accueil publique |
| `http://127.0.0.1:8000/login` | Connexion client |
| `http://127.0.0.1:8000/register` | Inscription |
| `http://127.0.0.1:8000/vendeur/dashboard` | Tableau de bord vendeur |
| `http://127.0.0.1:8000/vendeur/produits` | Gestion des produits |
| `http://127.0.0.1:8000/vendeur/commandes` | Gestion des commandes |
| `http://127.0.0.1:8000/api/...` | API REST |

---

## 🔧 Commandes Utiles

### Management de Cache

```bash
# Vider tous les caches
php artisan cache:clear

# Vider le cache des vues
php artisan view:clear

# Vider le cache de configuration
php artisan config:clear
```

### Gestion Base de Données

```bash
# Rouler toutes les migrations
php artisan migrate

# Annuler les migrations (attention!)
php artisan migrate:rollback

# Réinitialiser complètement la BD
php artisan migrate:fresh

# Réinitialiser + remplir données test
php artisan migrate:fresh --seed
```

### Assets et Compilation

```bash
# Compiler production (minifiée)
npm run build

# Compiler développement avec hot reload
npm run dev

# Nettoyer la cache des assets
npm run clean
```

---

## 🐛 Dépannage Courant

### Erreur : "SQLSTATE[HY000]: General error: 1030"
**Solution :** Augmentez `max_allowed_packet` dans MySQL
```sql
SET GLOBAL max_allowed_packet = 268435456;
```

### Erreur : "Target class does not exist" en API
**Solution :** Régénérez le cache des routes
```bash
php artisan route:cache
php artisan route:clear
```

### Images ne s'affichent pas
**Solution :** Vérifiez le lien de stockage
```bash
php artisan storage:link
```

### Permission denied sur `storage/`
**Solution (Linux/Mac) :**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Port 8000 déjà utilisé
**Solution :** Utiliser un autre port
```bash
php artisan serve --port=8001
```

---

## 🐳 Installation avec Docker (Optionnel)

Si vous avez Docker installé :

### 1. Créer un Dockerfile

```dockerfile
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    postgresql-client \
    libpq-dev \
    && docker-php-ext-configure pgsql \
    && docker-php-ext-install pdo_pgsql pdo_mysql

WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader
```

### 2. Créer docker-compose.yml

```yaml
version: '3'
services:
  web:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/app
    environment:
      DB_HOST: postgres
      DB_PASSWORD: postgres
    command: php artisan serve --host=0.0.0.0

  postgres:
    image: postgres:15
    environment:
      POSTGRES_DB: supply_db
      POSTGRES_PASSWORD: postgres
    volumes:
      - postgres_data:/var/lib/postgresql/data

volumes:
  postgres_data:
```

### 3. Démarrer avec Docker

```bash
docker-compose up
```

---

## 📝 Notes Importantes

✅ **À faire après installation :**
- [ ] Configurer le `.env` avec vos paramètres réels
- [ ] Configurer SMTP pour les emails
- [ ] Activer HTTPS en production
- [ ] Configurer un backup automatique de la BD
- [ ] Configurer les logs de monitoring

⚠️ **Sécurité :**
- Ne jamais pusher le `.env` sur Git
- Changer la clé `APP_KEY` à chaque nouvelle installation
- Utiliser des mots de passe forts
- Activer le CORS seulement pour les domaines autorisés

---

## 📞 Support

Pour toute question ou problème :
1. Vérifiez que PHP 8.3+ est installé
2. Vérifiez la connexion à la base de données
3. Consultez les logs : `tail -f storage/logs/laravel.log`
4. Lancez une issue sur GitHub

---

**Bon développement! 🎉**

