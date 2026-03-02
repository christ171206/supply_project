<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User, App\Models\Commande, App\Models\LigneCommande, App\Models\Produit;

$vendor = User::where('email', 'testshop@supply.ci')->first();
if (!$vendor) {
    echo "❌ Test Shop non trouvé\n";
    exit(1);
}

// Récupérer quelques produits du vendeur - utiliser la requête directe
$produits = Produit::where('user_id', $vendor->id)->limit(3)->get();
if ($produits->isEmpty()) {
    echo "❌ Aucun produit trouvé pour le vendeur\n";
    exit(1);
}

// Créer un client
$client = User::factory()->create([
    'role' => 'client',
    'email' => 'client_test_' . uniqid() . '@supply.ci'
]);
echo "✓ Client créé : {$client->name} (ID: {$client->id})\n";

// Créer une commande
$commande = Commande::create([
    'user_id' => $client->id,
    'total' => 0,
    'statut' => 'confirmee',
    'paiement_confirme' => true
]);

// Ajouter les lignes de commande
$total = 0;
foreach ($produits as $produit) {
    $quantite = 2;
    $prix_unitaire = $produit->prix;
    $sous_total = $quantite * $prix_unitaire;

    LigneCommande::create([
        'commande_id' => $commande->id,
        'produit_id' => $produit->id,
        'quantite' => $quantite,
        'prix_unitaire' => $prix_unitaire,
        'sous_total' => $sous_total
    ]);

    $total += $sous_total;
}

// Mettre à jour le total
$commande->update(['total' => $total]);

echo "✓ Commande créée : #" . $commande->id . " pour 50000 FCFA\n";
echo "✓ " . $produits->count() . " produit(s) ajouté(s)\n";
echo "\n✅ Données de test prêtes !\n";
echo "   Vous pouvez maintenant accéder à : http://127.0.0.1:8000/vendeur/dashboard\n";
echo "   Email : testshop@supply.ci\n";
echo "   Mot de passe : testshop123\n";
