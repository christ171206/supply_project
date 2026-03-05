# Déploiement sur Railway.app

Ce guide vous explique comment déployer l'application Supply sur Railway.app.

## Prérequis

1. Compte Railway créé : https://railway.app
2. Git installé et configuré
3. CLI Railway installés (optionnel mais recommandé)

## Étapes de Déploiement

### 1. Préparer le Projet (Déjà fait)

Les fichiers de configuration ont été créés :
- **Procfile** : Configure le serveur Apache avec PHP
- **railway.json** : Configuration pour Railway
- **composer.json** : Mis à jour avec les dépendances (spatie/laravel-activitylog)
- **.env.production** : Template de configuration

### 2. Commit et Push le Code

```bash
# Vérifier le statut
git status

# Ajouter tous les fichiers
git add .

# Commit avec message descriptif
git commit -m "chore: prepare for Railway deployment"

# Push vers la branche main
git push origin main
```

### 3. Créer un Projet sur Railway

Option A : Via Interface Web
1. Allez sur https://railway.app/dashboard
2. Cliquez sur "New Project"
3. Sélectionnez "Deploy from GitHub"
4. Connectez votre compte GitHub
5. Sélectionnez le repository `Supply`
6. Railway va détecter qu'il y a un Procfile et configurer automatiquement

Option B : Via CLI Railway
```bash
railway login
railway init
```

### 4. Configurer les Variables d'Environnement

Dans le dashboard Railway, allez à votre projet et ajoutez ces variables :

#### Variables Obligatoires

```
APP_NAME=Supply
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:VOTRE_CLÉ_GÉNÉRÉE_ICI
APP_URL=https://supply.railway.app
DB_CONNECTION=postgres
```

#### Paramètres de Base de Données

Railway créera automatiquement une BD PostgreSQL. Les variables suivantes seront pré-remplies :
```
PGHOST=*
PGPORT=*
PGDATABASE=*
PGUSER=*
PGPASSWORD=*
```

Ces valeurs d'environnement PostgreSQL seront fournies automatiquement quand vous attachez une BD PostgreSQL au projet.

#### Variables de Mail (optionnel)

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_FROM_ADDRESS=noreply@supply.com
MAIL_FROM_NAME=Supply
```

#### Autres Variables

```
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=public
LOG_CHANNEL=stack
LOG_LEVEL=warning
```

### 5. Ajouter une Base de Données PostgreSQL

1. Dans votre dashboard Railway
2. Cliquez sur "Add Service"
3. Sélectionnez "PostgreSQL"
4. Railway liera automatiquement les variables d'environnement

### 6. Déployer

Une fois que vous avez pushé le code sur GitHub, Railway déploiera automatiquement en detectant :
- Le `Procfile` pour la configuration
- Le `composer.json` pour les dépendances PHP
- Le `package.json` pour les dépendances npm

### 7. Vérifier la Génération de la Clé APP_KEY

Après le déploiement initial, vous devez générer une clé APP_KEY sécurisée :

Option A : Via CLI Local
```bash
php artisan key:generate --show
```
Copiez la clé générée (format `base64:...`) et mettez-la dans Railway.

Option B : Via SSH dans Railway (si disponible)
```bash
railway run php artisan key:generate
```

### 8. Migrer la Base de Données

Une fois les variables d'environnement configurées et la BD attachée :

Option A : Via CLI Railway
```bash
railway run php artisan migrate --force
```

Option B : Automatiquement (Procfile)
Le fichier Procfile contient :
```
release: php artisan migrate --force && php artisan db:seed --force
```

Cette commande s'exécute automatiquement avant chaque déploiement.

### 9. Vérifier le Déploiement

1. Allez dans l'onglet "Deployments" sur Railway
2. Vérifiez que le déploiement est "Success"
3. Cliquez sur l'URL pour accéder à votre application
4. Testez les routes :
   - `/login` - Page de connexion
   - `/admin/dashboard` - Dashboard admin (test: admin@supply.ci / admin123)
   - `/api/locations/regions` - API test

## Structure des Fichiers de Déploiement

```
├── Procfile                 # Configuration Railway (créé)
├── railway.json             # Config avancée Railway (créé)
├── .env.production          # Template variables prod (créé, ne pas commiter)
├── composer.json            # Dépendances PHP (mis à jour)
└── package.json             # Dépendances npm (inchangé)
```

## Optimisations für la Production

Après chaque déploiement, les commandes suivantes s'exécutent automatiquement (via `post-install-cmd` dans composer.json) :

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Cela optimise les performances en mettant en cache la configuration, les routes et les vues.

## Dépannage

### Erreur : "Class not found: Spatie\ActivityLog"

**Solution** : La dépendance Spatie Activity Log a été ajoutée au composer.json.
- Forcez un redéploiement en pushant un nouveau commit ou en utilisant `railway deploy` via CLI

### Erreur de Migration : "LARAVEL_MIGRATION_TABLE does not exist"

**Solution** : Les migrations s'exécutent pendant le déploiement (phase release). Attendez que le déploiement soit terminé et vérifiez les logs.

### Variable PGHOST non trouvée

**Solution** :
1. Assurez-vous qu'une BD PostgreSQL est attachée au service
2. Dans Railway UI : allez à votre app → "Variables"
3. Vous devriez voir `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD`
4. Si absent, supprimez la BD et réajoutez-la

### Erreur : "Storage directory is not writable"

**Solution** : 
- Le stockage sur Railway est éphémère
- Configurez `FILESYSTEM_DISK=public` dans les variables
- Ou utilisez S3/Spaces pour persistent storage

## Mise à Jour du Code

Pour déployer une mise à jour :

```bash
# Faire des modifications
git add .
git commit -m "description de la mise à jour"
git push origin main
```

Railway détectera le nouveau push et redéploiera automatiquement.

## Statistiques du Déploiement

- **Framework** : Laravel 12.44.0
- **PHP** : 8.2+
- **Frontend** : Tailwind CSS 3.4.19 + DaisyUI 5.5.19
- **Assets** : 33 composants Heroicons custom
- **Base de Données** : PostgreSQL
- **Sessions** : Database-based
- **Cache** : Database-based
- **Queue** : Database-based

## Contacts et Support

En cas de problème lors du déploiement :
1. Consultez les logs Railway : Déploiements → Détails
2. Vérifiez que toutes les variables d'environnement sont configurées
3. Assurez-vous que la BD PostgreSQL est attachée
4. Vérifiez la documentation : https://docs.railway.app

---

**Dernière mise à jour** : 5 mars 2026
**Statut** : Prêt pour le déploiement
