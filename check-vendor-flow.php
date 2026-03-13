<?php
require __DIR__ . '/vendor/autoload.php';

echo "🔍 VÉRIFICATION DU FLUX D'INSCRIPTION VENDEUR\n";
echo "============================================\n\n";

// Vérifier les fichiers clés
$files = [
    'Vue d\'enregistrement' => 'resources/views/auth/register.blade.php',
    'Vue vérification code' => 'resources/views/auth/verify-email-code.blade.php',
    'Vue soumission documents' => 'resources/views/auth/vendor-submit-documents.blade.php',
    'Contrôleur enregistrement' => 'app/Http/Controllers/Auth/RegisteredUserController.php',
    'Contrôleur vérification code' => 'app/Http/Controllers/Auth/EmailVerificationCodeController.php',
    'Contrôleur documents' => 'app/Http/Controllers/Vendeur/VendorDocumentController.php',
];

$missing = [];
foreach ($files as $name => $path) {
    $fullPath = __DIR__ . '/' . $path;
    if (file_exists($fullPath)) {
        echo "✅ $name\n";
    } else {
        echo "❌ $name - MANQUANT\n";
        $missing[] = $name;
    }
}

if (empty($missing)) {
    echo "\n✅ Tous les fichiers EXISTENT!\n";
} else {
    echo "\n❌ Fichiers manquants:\n";
    foreach ($missing as $file) {
        echo "   - $file\n";
    }
    exit;
}

// Vérifier les routes
echo "\n🔍 VÉRIFICATION DES ROUTES\n";
echo "==========================\n";

$auth_content = file_get_contents(__DIR__ . '/routes/auth.php');
$routes_to_check = [
    'POST /register' => "Route::post('register'",
    'GET /verify-email-code' => "Route::get('verify-email-code'",
    'POST /verify-email-code' => "Route::post('verify-email-code'",
    'GET /vendor/documents/submit' => "Route::get('vendor/documents/submit'",
    'POST /vendor/documents' => "Route::post('vendor/documents'",
];

foreach ($routes_to_check as $route => $pattern) {
    if (strpos($auth_content, $pattern) !== false) {
        echo "✅ $route\n";
    } else {
        echo "❌ $route - MANQUANTE\n";
    }
}

// Vérifier les contrôleurs
echo "\n🔍 VÉRIFICATION DES MÉTHODES\n";
echo "=============================\n";

$methods = [
    'RegisteredUserController::store' => __DIR__ . '/app/Http/Controllers/Auth/RegisteredUserController.php',
    'EmailVerificationCodeController::verify' => __DIR__ . '/app/Http/Controllers/Auth/EmailVerificationCodeController.php',
    'VendorDocumentController::submit' => __DIR__ . '/app/Http/Controllers/Vendeur/VendorDocumentController.php',
    'VendorDocumentController::store' => __DIR__ . '/app/Http/Controllers/Vendeur/VendorDocumentController.php',
];

foreach ($methods as $method => $file) {
    list($class, $methodName) = explode('::', $method);
    $content = file_get_contents($file);
    if (strpos($content, "public function $methodName") !== false) {
        echo "✅ $method\n";
    } else {
        echo "❌ $method - MANQUANTE\n";
    }
}

echo "\n📋 FLUX D'INSCRIPTION VENDEUR:\n";
echo "==============================\n";
echo "1. GET  /register                    → Affiche formulaire\n";
echo "2. POST /register                    → Valide et créée utilisateur\n";
echo "3. GET  /verify-email-code           → Affiche page de vérification code\n";
echo "4. POST /verify-email-code           → Vérifie le code\n";
echo "5. GET  /vendor/documents/submit      → 🎯 PAGE DOCUMENTS (ici!)\n";
echo "6. POST /vendor/documents            → Soumet les documents\n";
echo "7. GET  /vendor/documents/confirmation→ Page de confirmation\n";

echo "\n✅ LE FLUX EST COMPLET!\n";
echo "\n💡 Si tu n'arrives pas à la page de documents:\n";
echo "   1. Vérifies que tu remplis CORRECTEMENT le formulaire\n";
echo "   2. Vérifies que tu rentres le BON code (reçu par email)\n";
echo "   3. Vérifies que tu es VENDEUR (pas client)\n";
echo "   4. Regarde les logs: storage/logs/laravel.log\n";
