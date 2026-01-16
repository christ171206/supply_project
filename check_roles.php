<?php
// Teste les rôles des utilisateurs
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

$client = \App\Models\User::where('email', 'client@test.com')->first();
$vendor = \App\Models\User::where('email', 'vendeur@test.com')->first();

echo "=== VÉRIFICATION DES RÔLES ===\n\n";
echo "CLIENT (client@test.com):\n";
echo "  Email: " . ($client?->email ?? 'NOT FOUND') . "\n";
echo "  Rôle: " . ($client?->role ?? 'NOT FOUND') . "\n";
echo "  Attendu: client\n\n";

echo "VENDOR (vendeur@test.com):\n";
echo "  Email: " . ($vendor?->email ?? 'NOT FOUND') . "\n";
echo "  Rôle: " . ($vendor?->role ?? 'NOT FOUND') . "\n";
echo "  Attendu: vendor\n\n";

if ($client?->role === 'client' && $vendor?->role === 'vendor') {
    echo "✅ TOUT EST BON!\n";
} else {
    echo "❌ PROBLÈME DÉTECTÉ!\n";
    if ($client?->role !== 'client') {
        echo "   - Client a le rôle: '{$client?->role}' au lieu de 'client'\n";
    }
    if ($vendor?->role !== 'vendor') {
        echo "   - Vendor a le rôle: '{$vendor?->role}' au lieu de 'vendor'\n";
    }
}
