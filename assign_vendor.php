<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Produit;
use App\Models\User;

// Chercher ou créer un vendeur
$vendeur = User::first();

if (!$vendeur) {
    echo "❌ Aucun utilisateur trouvé. Créez d'abord un utilisateur.\n";
    exit(1);
}

echo "📦 Assigning vendor (ID: {$vendeur->id}, Name: {$vendeur->name}) to all products...\n";

// Assigner le vendeur à tous les produits
$updated = Produit::whereNull('user_id')->update(['user_id' => $vendeur->id]);

echo "✅ {$updated} products updated!\n";

// Vérifier
$productsWithVendor = Produit::whereNotNull('user_id')->count();
$totalProducts = Produit::count();

echo "📊 Status: {$productsWithVendor}/{$totalProducts} products have a vendor.\n";
