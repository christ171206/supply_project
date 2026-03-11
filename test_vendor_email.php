<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Test the vendor email sending
use App\Models\Commande;
use App\Events\OrderCreated;

$commandes = Commande::with('ligneCommandes.produit', 'user')->get();

if ($commandes->isEmpty()) {
    echo "❌ Aucune commande trouvée\n";
    exit(1);
}

echo "✓ Commandes trouvées: " . $commandes->count() . "\n\n";

foreach ($commandes as $commande) {
    echo "Testing Commande ID: " . $commande->id . " (Numéro: " . $commande->numero . ")\n";

    // Get vendor groups
    $lignesCommandes = $commande->ligneCommandes()->with('produit')->get();
    $vendorGroups = [];
    foreach ($lignesCommandes as $ligne) {
        $vendorId = $ligne->produit->user_id;
        if (!isset($vendorGroups[$vendorId])) {
            $vendorGroups[$vendorId] = [];
        }
        $vendorGroups[$vendorId][] = [
            'nom_produit' => $ligne->produit->nom,
            'quantite' => $ligne->quantite,
            'prix_unitaire' => $ligne->prix_unitaire,
            'sous_total' => $ligne->sous_total,
        ];
    }

    echo "  Vendor Groups: " . count($vendorGroups) . "\n";

    foreach ($vendorGroups as $vendorId => $items) {
        $vendor = \App\Models\User::find($vendorId);
        echo "    Vendeur #" . $vendorId . ": " . ($vendor ? $vendor->name . " (" . $vendor->email . ")" : "NOT FOUND") . "\n";
        echo "      Items: " . count($items) . "\n";
    }

    echo "\n";
}

// Try to dispatch an event
if ($commandes->count() > 0) {
    echo "Dispatching OrderCreated event for Commande #1...\n";
    try {
        OrderCreated::dispatch($commandes->first());
        echo "✓ Event dispatched successfully and listener executed\n";
        echo "Check Mailtrap for emails sent to vendors\n";
    } catch (\Throwable $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
        echo "Stack trace:\n";
        echo $e->getTraceAsString() . "\n";
    }
}
