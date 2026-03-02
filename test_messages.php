<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Message;

// Récupérer le compte Test Shop (vendor_id = 14)
$testShop = User::where('email', 'testshop@supply.ci')->first();

if (!$testShop) {
    echo "Test Shop not found!\n";
    exit(1);
}

echo "Test Shop found: {$testShop->name} (ID: {$testShop->id})\n";
echo "Email: {$testShop->email}\n\n";

// Récupérer les clients
$clients = User::where('role', 'client')->limit(3)->get();
echo "Clients trouvés: " . $clients->count() . "\n\n";

// Créer des messages de test
foreach ($clients as $client) {
    // Message du client au vendeur
    Message::create([
        'from_user_id' => $client->id,
        'to_user_id' => $testShop->id,
        'contenu' => "Bonjour, je suis intéressé par vos produits et j'aimerais poser quelques questions.",
        'lu' => false,
    ]);

    // Réponse du vendeur
    Message::create([
        'from_user_id' => $testShop->id,
        'to_user_id' => $client->id,
        'contenu' => "Bonjour! Merci pour votre intérêt. Je serais heureux de répondre à vos questions. Comment puis-je vous aider?",
        'lu' => false,
    ]);

    echo "✓ Messages created with {$client->name} ({$client->email})\n";
}

echo "\n✓ Tous les messages de test ont été créés!\n";
