<?php
// Script pour créer le compte Test Shop et assigner tous les produits

require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\Produit;
use Illuminate\Support\Facades\Hash;

// Vérifier si un compte Test Shop existe déjà
$testShop = User::where('email', 'testshop@supply.ci')->first();

if ($testShop) {
    echo "✓ Compte 'Test Shop' existe déjà (ID: {$testShop->id})\n";
    $vendor = $testShop;
} else {
    // Créer le compte Test Shop
    $vendor = User::create([
        'name' => 'Test Shop',
        'email' => 'testshop@supply.ci',
        'phone' => '+225 01 23 45 67 89',
        'password' => Hash::make('testshop123'),
        'role' => 'vendeur',
        'shop_name' => 'Test Shop',
        'is_verified' => true,
        'email_verified_at' => now(),
    ]);
    echo "✓ Compte 'Test Shop' créé avec succès (ID: {$vendor->id})\n";
}

// Assigner tous les produits à Test Shop
$products = Produit::all();
$count = 0;

foreach ($products as $product) {
    if ($product->vendeur_id !== $vendor->id) {
        $product->update(['vendeur_id' => $vendor->id]);
        $count++;
    }
}

echo "✓ {$count} produit(s) assigné(s) à Test Shop\n";
echo "✓ Total produits de Test Shop: " . Produit::where('vendeur_id', $vendor->id)->count() . "\n";

echo "\n✓✓✓ Configuration terminée! ✓✓✓\n";
echo "Email: testshop@supply.ci\n";
echo "Motdepasse: testshop123\n";
