# Variables Railway Optimisées pour Supply

## CRITIQUE - Mettre à jour dans Railway

APP_NAME="SUPPLY"
APP_ENV="production"
APP_KEY="base64:Wl/gOPffHlfZY0EApAxpx40C70BeWHiajU7UPKEuIY0="
APP_DEBUG="false"
APP_URL="https://supplyproject-production.up.railway.app"

## Database - MySQL
DB_CONNECTION="mysql"
DB_HOST="${{MySQL.MYSQL_HOST}}"
DB_PORT="${{MySQL.MYSQL_PORT}}"
DB_DATABASE="${{MySQL.MYSQL_DATABASE}}"
DB_USERNAME="${{MySQL.MYSQL_USER}}"
DB_PASSWORD="${{MySQL.MYSQL_PASSWORD}}"

## Cache & Queue
CACHE_STORE="database"
QUEUE_CONNECTION="database"
SESSION_DRIVER="database"
SESSION_LIFETIME="120"

## Filesystem & Broadcasting
FILESYSTEM_DISK="public"
BROADCAST_CONNECTION="log"

## Logging
LOG_CHANNEL="stack"
LOG_STACK="single"
LOG_LEVEL="warning"

## Locale & Timezone
APP_LOCALE="fr"
APP_FALLBACK_LOCALE="fr"
APP_FAKER_LOCALE="fr_FR"
APP_TIMEZONE="Africa/Abidjan"

## Server
PORT="8080"

## Optional - Email
MAIL_MAILER="log"
MAIL_FROM_ADDRESS="noreply@supply.com"
MAIL_FROM_NAME="SUPPLY"
