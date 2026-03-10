<?php
/**
 * Script pour configurer les comptes de test avec des mots de passe standards
 * Exécution: php setup-test-accounts.php
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n=== Configuration des Comptes de Test ===\n\n";

// Client Test
$client = User::where('email', 'client@test.com')->first();
if ($client) {
    $client->update([
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    echo "✓ client@test.com / password\n";
} else {
    $client = User::create([
        'name' => 'Client Test',
        'email' => 'client@test.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'email_verified_at' => now(),
    ]);
    echo "✓ [CRÉÉ] client@test.com / password\n";
}

// Vendeur Test
$vendor = User::where('email', 'testshop@supply.ci')->first();
if ($vendor) {
    $vendor->update([
        'password' => Hash::make('testshop123'),
        'email_verified_at' => now(),
    ]);
    echo "✓ testshop@supply.ci / testshop123\n";
} else {
    $vendor = User::create([
        'name' => 'Test Shop',
        'email' => 'testshop@supply.ci',
        'password' => Hash::make('testshop123'),
        'role' => 'vendor',
        'shop_name' => 'Test Shop',
        'vendor_status' => 'verified',
        'email_verified_at' => now(),
    ]);
    echo "✓ [CRÉÉ] testshop@supply.ci / testshop123\n";
}

// Admin Test
$admin = User::where('email', 'admin@supply.ci')->first();
if ($admin) {
    $admin->update([
        'password' => Hash::make('admin123'),
        'email_verified_at' => now(),
    ]);
    echo "✓ admin@supply.ci / admin123\n";
} else {
    $admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin@supply.ci',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    echo "✓ [CRÉÉ] admin@supply.ci / admin123\n";
}

echo "\n=== ✅ Comptes de test configurés ===\n";
echo "\nAccédez à: http://127.0.0.1:8000/login\n";
echo "Les comptes apparaîtront dans la section 'Comptes de test'\n\n";
?>
