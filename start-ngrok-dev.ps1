# ==================================================================================
# Script de démarrage complet pour Supply avec ngrok
# ==================================================================================
# Utilisation: .\start-ngrok-dev.ps1

param(
    [string]$NgrokPort = "8000",
    [string]$VitePort = "5173"
)

Write-Host "[+] Demarrage de Supply avec ngrok..." -ForegroundColor Cyan

# ==================================================================================
# 1. PREPARATION - Nettoyer les configurations precedentes
# ==================================================================================
Write-Host "[*] Nettoyage des caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# ==================================================================================
# 2. DEMARRER LE SERVEUR LARAVEL
# ==================================================================================
Write-Host "[>>] Demarrage du serveur Laravel (port $NgrokPort)..." -ForegroundColor Green
Start-Process powershell -ArgumentList @"
cd 'd:\wamp\www\Supply'
php artisan serve --port=$NgrokPort --host=127.0.0.1
"@

Start-Sleep -Seconds 3
Write-Host "[OK] Serveur Laravel started on http://127.0.0.1:$NgrokPort" -ForegroundColor Green

# ==================================================================================
# 3. DEMARRER VITE DEVELOPMENT SERVER
# ==================================================================================
Write-Host "[>>] Demarrage du serveur Vite (port $VitePort)..." -ForegroundColor Blue
Start-Process powershell -ArgumentList @"
cd 'd:\wamp\www\Supply'
npm run dev
"@

Start-Sleep -Seconds 3
Write-Host "[OK] Vite dev server started on http://localhost:$VitePort" -ForegroundColor Blue

# ==================================================================================
# 4. DEMARRER NGROK
# ==================================================================================
Write-Host "[*] Configuration de ngrok..." -ForegroundColor Magenta

# Verifier que ngrok est installe
$ngrokPath = Get-Command ngrok -ErrorAction SilentlyContinue
if (-not $ngrokPath) {
    Write-Host "[!] ngrok n'est pas installe ou pas dans le PATH" -ForegroundColor Red
    Write-Host "[!] Telechargez ngrok depuis https://ngrok.com/download" -ForegroundColor Yellow
    exit 1
}

Write-Host "[OK] ngrok trouve: $($ngrokPath.Source)" -ForegroundColor Green

Write-Host "[>>] Demarrage de ngrok (port $NgrokPort)..." -ForegroundColor Magenta
Write-Host "[*] Attendez quelques secondes pour que ngrok genere l'URL..." -ForegroundColor Yellow

# Demarrer ngrok et capturer l'URL
$ngrokProcess = Start-Process ngrok -ArgumentList "http $NgrokPort" -PassThru -NoNewWindow

# Attendre que ngrok soit pret et recuperer l'URL
Start-Sleep -Seconds 5

try {
    $ngrokApi = Invoke-WebRequest -Uri "http://127.0.0.1:4040/api/tunnels" -UseBasicParsing
    $tunnels = $ngrokApi.Content | ConvertFrom-Json

    if ($tunnels.tunnels.Count -gt 0) {
        $publicUrl = $tunnels.tunnels[0].public_url
        Write-Host "[OK] ngrok tunnel created: $publicUrl" -ForegroundColor Green

        # ==================================================================================
        # 5. METTRE A JOUR L'APP_URL DANS .ENV
        # ==================================================================================
        Write-Host "[*] Mise a jour de APP_URL dans .env..." -ForegroundColor Yellow

        $envFile = 'd:\wamp\www\Supply\.env'
        $envContent = Get-Content $envFile -Raw

        # Remplacer l'URL ngrok existante
        $envContent = $envContent -replace `
            'APP_URL=https?://[^\s]+\.ngrok[^\s]*\.dev', `
            "APP_URL=$publicUrl"

        Set-Content -Path $envFile -Value $envContent
        Write-Host "[OK] APP_URL mise a jour: $publicUrl" -ForegroundColor Green

        # ==================================================================================
        # 6. CONFIGURER VITE HMR POUR NGROK
        # ==================================================================================
        Write-Host "[*] Configuration de Vite HMR pour ngrok..." -ForegroundColor Yellow

        # Extraire le hostname de l'URL ngrok
        $ngrokHost = ([Uri]$publicUrl).Host

        $envContent = Get-Content $envFile -Raw

        # Mettre a jour les variables HMR Vite
        $envContent = $envContent -replace `
            'VITE_HMR_HOST=.*', `
            "VITE_HMR_HOST=$ngrokHost"

        $envContent = $envContent -replace `
            'VITE_HMR_PROTOCOL=.*', `
            'VITE_HMR_PROTOCOL=https'

        Set-Content -Path $envFile -Value $envContent
        Write-Host "[OK] Vite HMR configure pour $ngrokHost" -ForegroundColor Green

        # ==================================================================================
        # 7. REDEMARRER LARAVEL AVEC NOUVELLE CONFIGURATION
        # ==================================================================================
        Write-Host "[*] Rechargement de la configuration Laravel..." -ForegroundColor Yellow

        cd 'd:\wamp\www\Supply'
        php artisan config:cache

        Write-Host "[OK] Configuration rechargee" -ForegroundColor Green

        # ==================================================================================
        # 8. AFFICHER LES INFORMATIONS DE CONNEXION
        # ==================================================================================
        Write-Host ""  -ForegroundColor Cyan
        Write-Host "========================================" -ForegroundColor Cyan
        Write-Host "[OK] SUPPLY EST PRET!" -ForegroundColor Cyan
        Write-Host "========================================" -ForegroundColor Cyan

        Write-Host "" -ForegroundColor Green
        Write-Host "[URLS D'ACCES]" -ForegroundColor Green
        Write-Host "  Public (ngrok) : $publicUrl" -ForegroundColor White
        Write-Host "  Local          : http://127.0.0.1:$NgrokPort" -ForegroundColor Green
        Write-Host "  Vite Dev       : http://localhost:$VitePort" -ForegroundColor Blue
        Write-Host "  ngrok Admin    : http://localhost:4040" -ForegroundColor Magenta

        Write-Host "" -ForegroundColor Yellow
        Write-Host "[CONSEILS]" -ForegroundColor Yellow
        Write-Host "  - L'URL ngrok ($publicUrl) est accessible depuis n'importe ou" -ForegroundColor Gray
        Write-Host "  - Les autres machines peuvent acceder au site via l'URL ngrok" -ForegroundColor Gray
        Write-Host "  - Les uploads de fichiers fonctionnent (stockes localement)" -ForegroundColor Gray
        Write-Host "  - Les formulaires avec CSRF fonctionnent normalement" -ForegroundColor Gray
        Write-Host "  - Vite hot reload fonctionne via ngrok" -ForegroundColor Gray

        Write-Host "" -ForegroundColor Yellow
        Write-Host "[IMPORTANT]" -ForegroundColor Yellow
        Write-Host "  - Cette URL ngrok change chaque fois que vous redemarrez ngrok" -ForegroundColor Gray
        Write-Host "  - Ce script met a jour automatiquement APP_URL et Vite HMR" -ForegroundColor Gray
        Write-Host "  - Gardez ce terminal ouvert pour que les services continuent" -ForegroundColor Gray

        Write-Host "" -ForegroundColor Yellow
        Write-Host "[POUR ARRETER]" -ForegroundColor Yellow
        Write-Host "  - Appuyez sur Ctrl+C dans ce terminal" -ForegroundColor Gray

        Write-Host "" -ForegroundColor Cyan

        # Garder le script en cours d'execution
        Write-Host "Les services sont maintenant en cours d'execution..." -ForegroundColor Cyan
        Wait-Process -Id $ngrokProcess.Id

    } else {
        Write-Host "[!] Impossible de recuperer l'URL ngrok" -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "[!] Erreur lors de la connexion a ngrok API: $_" -ForegroundColor Red
    Write-Host "[*] Assurez-vous que ngrok a demarrer correctement" -ForegroundColor Yellow
    exit 1
}
