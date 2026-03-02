<?php

/**
 * Script pour créer des messages de test
 * Exécution: php artisan tinker < create_test_messages_script.php
 */

use App\Models\User;
use App\Models\Message;

// Récupérer tous les users
$users = User::all();
echo "Total d'utilisateurs: " . $users->count() . "\n";

// Chercher le vendeur Test Shop
$testShop = $users->where('email', 'testshop@supply.ci')->first();
if (!$testShop) {
    $testShop = $users->where('name', 'Test Shop')->first();
}

// Chercher les clients
$clients = $users->where('role', 'client')->take(3);

echo "Test Shop: " . ($testShop ? $testShop->name . " (ID: {$testShop->id})" : "Not found") . "\n";
echo "Clients trouvés: " . $clients->count() . "\n\n";

if ($testShop && $clients->count() > 0) {
    foreach ($clients as $client) {
        // Message du client
        Message::create([
            'from_user_id' => $client->id,
            'to_user_id' => $testShop->id,
            'contenu' => "Bonjour, j'aimerais me renseigner sur vos produits. Pouvez-vous m'aider?",
            'lu' => false,
        ]);

        // Réponse du vendeur
        Message::create([
            'from_user_id' => $testShop->id,
            'to_user_id' => $client->id,
            'contenu' => "Bonjour! Je serais ravi de vous aider. Quel produit vous intéresse?",
            'lu' => false,
        ]);

        echo "✓ Messages créés avec {$client->name}\n";
    }
    echo "\n✓ Tous les messages de test ont été créés!\n";
} else {
    echo "❌ Impossible de créer les messages - données manquantes\n";
}

// Afficher le décompte
$totalMessages = Message::count();
echo "\nTotal de messages dans la base: $totalMessages\n";
