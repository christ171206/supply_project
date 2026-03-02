<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;

// Trouver le vendeur testshop
$vendeur = User::where('email', 'testshop@supply.ci')->first();

if ($vendeur) {
    echo "=== VENDEUR ===\n";
    echo "Nom: " . $vendeur->name . "\n";
    echo "ID: " . $vendeur->id . "\n";
    echo "Role: " . $vendeur->role . "\n\n";

    // Ses produits
    $produits = Produit::where('user_id', $vendeur->id)->get();
    echo "=== PRODUITS DU VENDEUR ===\n";
    echo "Nombre: " . $produits->count() . "\n";
    $produits->each(fn($p) => echo "- [ID: {$p->id}] {$p->nom}\n");

    // Ses commandes (lignes commandes)
    echo "\n=== COMMANDES (via LigneCommandes) ===\n";
    $commandes = Commande::whereHas('ligneCommandes', function($q) use ($vendeur) {
        $q->whereHas('produit', function($q2) use ($vendeur) {
            $q2->where('user_id', $vendeur->id);
        });
    })->with('user')->get();

    echo "Nombre de commandes: " . $commandes->count() . "\n";
    $commandes->each(function($c) {
        echo "- Commande #{$c->id} - Client: {$c->user->name}\n";
    });
} else {
    echo "Vendeur testshop@supply.ci non trouvé\n";
}

// Voir aussi le Client Test
echo "\n=== CLIENT TEST ===\n";
$clientTest = User::where('email', 'client@test.com')->first();
if ($clientTest) {
    echo "ID: " . $clientTest->id . "\n";
    echo "Commandes: " . $clientTest->commandes()->count() . "\n";
    $clientTest->commandes->each(fn($c) => echo "- Commande #{$c->id}\n");
}
