# 📖 Manuel d'Installation - Supply E-commerce

## Table des Matières
1. [Prérequis](#prérequis)
2. [Installation Locale](#installation-locale)
3. [Installation sur Serveur](#installation-sur-serveur)
4. [Configuration](#configuration)
5. [Dépannage](#dépannage)
6. [Support](#support)

---

## Prérequis

### Système d'Exploitation
- **Windows** 10+ / **macOS** 10.14+ / **Linux** (Ubuntu 18.04+)
- Droits administrateur pour installation

### Logiciels Requis
| Composant | Version | Lien |
|-----------|---------|------|
| **PHP** | 8.1+ | [php.net](https://www.php.net/downloads) |
| **MySQL/MariaDB** | 5.7+ | [mysql.com](https://www.mysql.com/downloads/) |
| **Node.js** | 16+ | [nodejs.org](https://nodejs.org/) |
| **Composer** | Latest | [getcomposer.org](https://getcomposer.org/) |
| **Git** | Latest | [git-scm.com](https://git-scm.com/) |

### Extensions PHP Requises
```bash
✓ php-mbstring
✓ php-json
✓ php-xml
✓ php-curl
✓ php-mysql
✓ php-zip
✓ php-gd
✓ php-bcmath
✓ php-tokenizer
✓ php-fileinfo
```

### Espace Disque
- **Minimum**: 2 GB
- **Recommandé**: 10 GB (pour images/uploads)

### Navigateur Web
- Chrome 60+
- Firefox 55+
- Safari 11+
- Edge 79+

---

## Installation Locale

### 1. Cloner le Répertoire

```bash
# Ouvrir terminal/cmd dans le dossier racine
cd d:/wamp/www (Windows)
# ou
cd ~/Sites (macOS)
# ou
cd /var/www (Linux)

# Cloner le projet
git clone https://github.com/votre-username/Supply.git
cd Supply
```

### 2. Installer les Dépendances PHP

```bash
# Installer avec Composer
composer install

# En production (sans dépendances de dév)
composer install --no-dev
```

**Temps estimé**: 3-5 minutes

### 3. Installer les Dépendances Node.js

```bash
# Installer les packages
npm install

# Compiler les assets (CSS/JS)
npm run dev          # Développement (lent mais avec source maps)
npm run build        # Production (optimisé)
```

**Temps estimé**: 2-3 minutes

### 4. Préparer la Base de Données

```bash
# Copier le fichier .env
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 5. Éditer le Fichier .env

Ouvrir `.env` et configurer:

```env
# Application
APP_NAME=Supply
APP_ENV=local                    # local/production
APP_KEY=base64:xxxxx...          # Généré par key:generate
APP_DEBUG=true                   # false en prod
APP_URL=http://localhost:8000    # URL local

# Base de Données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=supply_db
DB_USERNAME=root
DB_PASSWORD=                     # Votre password MySQL

# Email
MAIL_MAILER=mailtrap             # ou votre provider
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxx
MAIL_PASSWORD=xxxxx
MAIL_FROM_ADDRESS=noreply@supply.local

# Services Externes (Optionnels)
PUSHER_APP_ID=xxxxx
PUSHER_APP_KEY=xxxxx
CLOUDINARY_URL=cloudinary://xxxxx

# Queue
QUEUE_CONNECTION=sync            # sync pour local, redis en prod
```

### 6. Créer la Base de Données

**Avec MySQL CLI:**
```bash
mysql -u root -p
> CREATE DATABASE supply_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
> EXIT;
```

**Avec PhpMyAdmin:**
1. Ouvrir http://localhost/phpmyadmin
2. Cliquer "Nouvelle base de données"
3. Nom: `supply_db`
4. Collation: `utf8mb4_unicode_ci`
5. Cliquer "Créer"

### 7. Migrer la Base de Données

```bash
# Exécuter les migrations (créer tables)
php artisan migrate

# Remplir avec données test (optionnel)
php artisan db:seed

# Ou seed spécifique
php artisan db:seed --class=UserSeeder
```

### 8. Créer les Liens de Stockage

```bash
# Pour uploads (images, documents)
php artisan storage:link

# Vérifier: http://localhost:8000/storage devrait afficher dossier
```

### 9. Mettre en Cache la Configuration

```bash
# Optimiser pour performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 10. Démarrer le Serveur Local

**Option 1: Laravel Artisan (Recommandé)**
```bash
php artisan serve
# Ouvre http://localhost:8000 automatiquement
```

**Option 2: WAMP/XAMPP**
1. Démarrer Apache et MySQL
2. Accéder http://localhost/Supply/public

**Option 3: Nginx (Avancé)**
```bash
# Configuration nginx à ajouter dans sites-available/
server {
    listen 80;
    server_name localhost;
    root /path/to/Supply/public;

    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 11. Vérifier l'Installation

Accéder à: **http://localhost:8000**

Vous devriez voir:
- ✅ Page d'accueil Supply
- ✅ Menu de navigation
- ✅ Catégories chargées

---

## Installation sur Serveur

### Hébergeurs Recommandés
- **Shared Hosting**: OVH, Hostinger, DreamHost
- **VPS**: Linode, DigitalOcean, Vultr, AWS
- **Cloud**: Heroku, Fly.io, Render

### Prérequis Serveur

```bash
# SSH dans votre serveur
ssh user@votre-serveur.com

# Vérifier les versions
php --version          # Doit être 8.1+
mysql --version        # Doit être 5.7+
composer --version     # Doit être 2.x+
```

### 1. Déployer le Code

**Avec Git (Recommandé):**
```bash
cd /home/user/public_html
git clone https://github.com/votre-username/Supply.git .
```

**Avec FTP:**
1. Ouvrir client FTP (FileZilla, WinSCP)
2. Se connecter au serveur
3. Télécharger le dossier Supply

**Avec SSH + Tar:**
```bash
# Depuis votre machine local
tar czf Supply.tar.gz Supply/
scp Supply.tar.gz user@serveur.com:/home/user/

# Depuis le serveur
tar xzf Supply.tar.gz
```

### 2. Installer les Dépendances

```bash
# Installation PHP
composer install --no-dev --optimize-autoloader

# Installation Node.js (si Space limité, passer puis build local)
npm install
npm run build
```

### 3. Configurer Permissions

```bash
# Donner permission d'écriture à Laravel
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/storage/

# Optionnel: Pour plus de sécurité
chown -R www-data:www-data /path/to/Supply
```

### 4. Configurer le Domaine

**Apache (VirtualHost):**
```apache
<VirtualHost *:80>
    ServerName supply.votre-domaine.com
    ServerAlias www.supply.votre-domaine.com
    DocumentRoot /home/user/public_html/Supply/public

    <Directory /home/user/public_html/Supply/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/supply_error.log
    CustomLog ${APACHE_LOG_DIR}/supply_access.log combined
</VirtualHost>
```

**Nginx (Server Block):**
```nginx
server {
    listen 80;
    server_name supply.votre-domaine.com www.supply.votre-domaine.com;
    root /home/user/public_html/Supply/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 5. Configurer SSL/HTTPS

**Avec Let's Encrypt (Gratuit):**
```bash
# Installer Certbot
sudo apt-get install certbot python3-certbot-apache

# Créer certificat
sudo certbot certonly --apache -d supply.votre-domaine.com

# Auto-renouvellement
sudo systemctl enable certbot.timer
```

### 6. Configurer Email

Mettre à jour `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com          # Gmail, SendGrid, etc.
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@supply.com
```

### 7. Backup Automatique

```bash
# Ajouter cron job
crontab -e

# Ajouter ligne:
0 2 * * * mysqldump -u root -p'password' supply_db > /path/to/backups/db_$(date +\%Y\%m\%d).sql
```

---

## Configuration

### Variables d'Environnement Importantes

```env
# Performance
APP_DEBUG=false                  # TOUJOURS false en prod
APP_ENV=production

# Cache
CACHE_DRIVER=redis               # En prod, utiliser Redis/Memcached
SESSION_DRIVER=cookie            # ou redis pour sessions distribuées
QUEUE_CONNECTION=redis           # Emails/jobs en arrière-plan

# Sécurité
SESSION_SECURE_COOKIES=true      # HTTPS only
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
CSRF_TRUSTED_HOSTS=supply.com,www.supply.com

# Storage
FILESYSTEM_DISK=public           # Où stocker les uploads
AWS_BUCKET=votre-bucket          # Si utilisant AWS S3

# Logs
LOG_CHANNEL=stack                # Ou sentry pour monitoring
LOG_LEVEL=warning                # En prod: warning ou error
```

### Créer Utilisateur Admin

```bash
php artisan tinker
```

```php
// Dans tinker
$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@supply.com',
    'password' => Hash::make('secure-password'),
    'is_admin' => 1,
    'email_verified_at' => now(),
]);

// Vérifier
User::where('is_admin', 1)->first();
exit
```

Ou via database:
```sql
UPDATE users SET is_admin = 1 WHERE email = 'admin@supply.com';
```

### Configurer les Paiements

**Stripe:**
```env
STRIPE_PUBLIC_KEY=pk_live_xxxxx
STRIPE_SECRET_KEY=sk_live_xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx
```

**PayPal:**
```env
PAYPAL_CLIENT_ID=xxxxx
PAYPAL_SECRET=xxxxx
PAYPAL_MODE=live
```

---

## Dépannage

### Erreur 1: "No Application Encryption Key Has Been Specified"

```bash
# Solution
php artisan key:generate
```

### Erreur 2: "SQLSTATE Connection Refused"

```bash
# Vérifier MySQL
sudo service mysql status
sudo service mysql start

# Vérifier .env
DB_HOST=127.0.0.1              # Pas localhost
DB_PORT=3306
DB_DATABASE=supply_db
DB_USERNAME=root
```

### Erreur 3: "Permission Denied" sur storage/

```bash
# Fixer permissions
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
```

### Erreur 4: "Class App\Models\User Not Found"

```bash
# Regénérer autoloader
composer dump-autoload
```

### Erreur 5: "No Path Defined" pour Mail

```bash
# Vérifier .env
MAIL_MAILER=log                  # Pour dev
MAIL_MAILER=smtp                 # Pour prod

# ou
php artisan config:cache
```

### Erreur 6: Images ne s'affichent pas

```bash
# Créer symbolic link
php artisan storage:link

# Vérifier path
ls -la public/storage
```

### Erreur 7: "npm run build" échoue

```bash
# Nettoyer et réinstaller
rm node_modules package-lock.json
npm install
npm run build
```

### Performance Lente

```bash
# Optimiser
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Vérifier DB queries
php artisan tinker
> \DB::listen(fn($q) => dump($q->sql));
```

---

## Support

### Documentation
- **Laravel**: https://laravel.com/docs
- **Tailwind CSS**: https://tailwindcss.com/docs
- **MySQL**: https://dev.mysql.com/doc/

### Communautés
- Stack Overflow: https://stackoverflow.com/questions/tagged/laravel
- Laravel Slack: https://laravel.slack.com
- GitHub Issues: https://github.com/votre-repo/issues

### Logs
```bash
# Voir les logs en temps réel
tail -f storage/logs/laravel.log

# Ou via artisan
php artisan tail
```

### Vérifier la Santé de l'App

```bash
php artisan health

# Résultat devrait être: All systems operational
```

---

**Version**: Supply v1.0
**Dernière mise à jour**: 11 Mars 2026
**Supporté**: PHP 8.1+, MySQL 5.7+, Node.js 16+
