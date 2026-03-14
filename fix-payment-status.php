<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $db = app('db');

    // Mettre à jour tous les paiements pour les commandes livrées
    // Utiliser 'confirmee' (valeur enum disponible dans payment_status)
    $updated = $db->table('payments')
        ->whereIn(
            'commande_id',
            $db->table('commandes')
                ->where('statut', 'livree')
                ->pluck('id')
        )
        ->update(['payment_status' => 'confirmee']);

    echo "✓ " . $updated . " paiement(s) marqué(s) comme 'confirmee'\n";
    echo "Done!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
