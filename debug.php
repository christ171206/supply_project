<?php
// Quick debug script
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;

$vendeur = User::where('email', 'testshop@supply.ci')->first();
$clientTest = User::where('email', 'client@test.com')->first();

echo "=== VENDEUR TESTSHOP ===\n";
echo "ID: " . $vendeur->id . "\n";
echo "Produits: " . Produit::where('user_id', $vendeur->id)->count() . "\n";

echo "\n=== CLIENT TEST ===\n";
echo "ID: " . $clientTest->id . "\n";
echo "Commandes: " . $clientTest->commandes()->count() . "\n";

echo "\n=== COMMANDES GENERALES ===\n";
Commande::with('user')->get()->each(function ($c) {
    echo "Commande #{$c->id} - Client: {$c->user->name}\n";
});
