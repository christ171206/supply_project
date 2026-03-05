# 🚀 Guide Rapide : Déployer sur Railway en 5 minutes

## ✅ Ce qui a été fait

Le projet a été préparé pour Railway avec :
- ✅ `Procfile` configuré pour Laravel
- ✅ `railway.json` avec configuration optimisée
- ✅ `composer.json` mis à jour (spatie/laravel-activitylog ajouté)
- ✅ Scripts de migration et seeding en place
- ✅ Tout pushé vers GitHub

## 📋 Étapes suivantes (à faire manuellement)

### Étape 1 : Créer un compte Railway (5 min)
```
1. Allez sur https://railway.app
2. Cliquez "Sign Up"
3. Connectez-vous avec GitHub
```

### Étape 2 : Connecter le Repo GitHub (3 min)
```
1. Allez sur https://railway.app/dashboard
2. Cliquez "Create New Project"
3. Sélectionnez "Deploy from GitHub"
4. Autorisez Railway d'accéder à vos repos
5. Sélectionnez "supply_project"
```

### Étape 3 : Configurer les Variables d'Environnement (5 min)

Dans le dashboard Railway pour votre projet, allez à "Variables" et ajoutez :

```
APP_NAME=Supply
APP_ENV=production
APP_DEBUG=false
APP_URL=https://supply-production-xxx.railway.app
APP_KEY=base64:VOTRE_CLÉ_ICI_VOIR_CI-DESSOUS
DB_CONNECTION=postgres
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=public
LOG_CHANNEL=stack
LOG_LEVEL=warning

# Mail (optionnel)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_FROM_ADDRESS=noreply@supply.com
```

#### Générer APP_KEY (une seule fois)

En local, exécutez :
```bash
php artisan key:generate --show
```

Vous obtiendrez quelque chose comme : `base64:xxxxxxxxxxxxx`
Mettez cette valeur dans `APP_KEY` dans Railway.

### Étape 4 : Ajouter PostgreSQL (2 min)

```
1. Dans votre dashboard Railway
2. Cliquez "Add Service"
3. Sélectionnez "PostgreSQL"
4. Railway attachera automatiquement les variables
```

Vous verrez ensuite :
- `PGHOST`
- `PGPORT`
- `PGDATABASE`
- `PGUSER`
- `PGPASSWORD`

### Étape 5 : Déployer ! (Automatique)

Une fois que vous avez ajouté PostgreSQL et les variables :
- Railway détecte le `Procfile`
- Construit vos dépendances PHP et npm
- Exécute les migrations (phase "release")
- Lance votre application

**C'est tout ! Votre app est en ligne** ✨

## 🔗 URL de Base de Données

Une fois déployé, une URL de base de données sera disponible :
```
postgresql://user:password@host:port/database
```

## ✨ Après le Déploiement

1. **Vérifier** : Cliquez l'URL du projet pour voir votre app
2. **Tester la connexion admin** : 
   - Email : `admin@supply.ci`
   - Mot de passe : `admin123`
3. **Monitorer** : Onglet "Deployments" pour les logs

## 📊 Capacités du Déploiement

- **Framework** : Laravel 12.44.0
- **PHP** : 8.2+
- **Base de Données** : PostgreSQL
- **Assets** : Tailwind CSS + DaisyUI + 33 Heroicons
- **Sessions** : Database-backed
- **Cache** : Database-backed
- **Logs** : Railway Logs

## 🆘 En Cas de Problème

### Problème : "PGHOST not found"
→ Assurez-vous d'avoir ajouté le service PostgreSQL

### Problème : "Migration failed"
→ Vérifiez les logs : Dashboard → Déploiement → Cliquez le build

### Problème : "Class not found: activity()"
→ Forcez un redéploiement en pushant un commit ou en upant une variable

## 📚 Documentation Complète

Pour plus de détails, consultez : `DEPLOYMENT_RAILWAY.md`

---

**Railway est gratuit avec 500 heures/mois incluses** 💰

Plus d'infos : https://docs.railway.app
