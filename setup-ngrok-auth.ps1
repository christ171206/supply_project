# ==================================================================================
# Configuration initiale de ngrok pour Supply
# ==================================================================================
# Usage: .\setup-ngrok-auth.ps1

Write-Host "🔐 Configuration de l'authentification ngrok..." -ForegroundColor Cyan

# Vérifier que ngrok est installé
$ngrokPath = Get-Command ngrok -ErrorAction SilentlyContinue
if (-not $ngrokPath) {
    Write-Host "❌ ngrok n'est pas installé!" -ForegroundColor Red
    Write-Host "`nÉtapes pour installer ngrok:" -ForegroundColor Yellow
    Write-Host "1. Téléchargez ngrok depuis: https://ngrok.com/download" -ForegroundColor Gray
    Write-Host "2. Extrayez le fichier ngrok.exe" -ForegroundColor Gray
    Write-Host "3. Ajoutez le dossier ngrok à votre PATH Windows" -ForegroundColor Gray
    Write-Host "   OU placez ngrok.exe dans C:\Program Files\ngrok\" -ForegroundColor Gray
    Write-Host "4. Relancez ce script" -ForegroundColor Gray
    exit 1
}

Write-Host "✅ ngrok trouvé: $($ngrokPath.Source)" -ForegroundColor Green

# Récupérer le token ngrok depuis le .env
$envFile = 'd:\wamp\www\Supply\.env'
$envContent = Get-Content $envFile
$tokenLine = $envContent | Select-String "NGROK_AUTH_TOKEN="
$token = $tokenLine -replace "^NGROK_AUTH_TOKEN=", ""

if ($token) {
    Write-Host "`n✅ Token ngrok trouvé dans .env" -ForegroundColor Green
    Write-Host "Token: $($token.Substring(0, 10))..." -ForegroundColor Gray

    Write-Host "`nConfiguration du token ngrok..." -ForegroundColor Yellow
    ngrok config add-authtoken $token

    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Token configuré avec succès!" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Token déjà configuré ou erreur" -ForegroundColor Yellow
    }
} else {
    Write-Host "`n⚠️  Aucun token ngrok trouvé dans .env" -ForegroundColor Yellow
    Write-Host "`nÉtapes pour obtenir un token:" -ForegroundColor Yellow
    Write-Host "1. Accédez à https://dashboard.ngrok.com/" -ForegroundColor Gray
    Write-Host "2. Créez un compte gratuit" -ForegroundColor Gray
    Write-Host "3. Allez dans: Dashboard > Auth > Your Authtoken" -ForegroundColor Gray
    Write-Host "4. Copiez votre token" -ForegroundColor Gray
    Write-Host "5. Mettez à jour NGROK_AUTH_TOKEN dans .env" -ForegroundColor Gray
    Write-Host "6. Relancez ce script" -ForegroundColor Gray
    exit 1
}

Write-Host "`n✅ Configuration ngrok terminée!" -ForegroundColor Green
Write-Host "Vous pouvez maintenant exécuter: .\start-ngrok-dev.ps1" -ForegroundColor Cyan
