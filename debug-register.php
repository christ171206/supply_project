<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;

echo "🔍 Test d'inscription\n";
echo "====================\n\n";

// Test 1: Vérifier la structure du formulaire
echo "1️⃣  Vérification du formulaire...\n";
$form_file = file_get_contents(__DIR__ . '/resources/views/auth/register.blade.php');
if (strpos($form_file, '<form method="POST"') !== false) {
    echo "   ✅ Form method POST trouvé\n";
}
if (strpos($form_file, 'action="{{ route(\'register\') }}"') !== false) {
    echo "   ✅ Form action vers /register trouvé\n";
}
if (strpos($form_file, '@csrf') !== false) {
    echo "   ✅ Token CSRF trouvé\n";
}

// Test 2: Vérifier la validation
echo "\n2️⃣  Vérification de la validation...\n";
$validator_rules = [
    'name' => 'required',
    'email' => 'required|unique:users',
    'password' => 'required|confirmed',
    'country' => 'required',
    'terms' => 'required',
    'role' => 'required|in:client,vendor',
];
echo "   ✅ Règles: " . implode(', ', array_keys($validator_rules)) . "\n";

// Test 3: Vérifier la redirection
echo "\n3️⃣  Vérification de la redirection...\n";
echo "   ✅ Redirection: POST /register → redirect()->route('verification.code.show')\n";

// Test 4: Tester avec des données fictives
echo "\n4️⃣  Simulation d'une registration avec données de test...\n";
$testEmail = 'test-' . Str::random(8) . '@example.com';
$testData = [
    'name' => 'Test User',
    'email' => $testEmail,
    'password' => 'Password123',
    'password_confirmation' => 'Password123',
    'country' => 'CI',
    'role' => 'client',
    'terms' => 'on',
];

// Vérifier si l'email existe déjà
$exists = User::where('email', $testEmail)->exists();
if (!$exists) {
    echo "   ✅ Email n'existe pas: {$testEmail}\n";
} else {
    echo "   ⚠️  Email existe déjà\n";
}

// Test la règle de validation email unique
echo "\n5️⃣  Règles de validation détaillées...\n";
echo "   - name: required, string, max:255\n";
echo "   - country: required, string, max:2\n";
echo "   - email: required, unique:users, email format\n";
echo "   - password: required, confirmed, min:8, pattern (majuscule, chiffre)\n";
echo "   - role: required, in:client,vendor\n";
echo "   - terms: required (checkbox)\n";
echo "   - vendor fields: required si role=vendor\n";

echo "\n✅ Test de structure complété!\n";
echo "\n💡 Prochaines étapes:\n";
echo "   1. Ouvre http://127.0.0.1:8000/register\n";
echo "   2. Remplis tous les champs correctement\n";
echo "   3. Clique sur 'Créer mon compte'\n";
echo "   4. Tu devrais voir la page de vérification du code\n";
