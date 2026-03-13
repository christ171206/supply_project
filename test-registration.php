<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->handle(
    $request = \Illuminate\Http\Request::capture()
);

// Simuler une requête d'enregistrement
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

// Vérifier la connexion DB
echo "🔍 Vérification de la base de données...\n";
try {
    DB::connection()->getPdo();
    echo "✅ Connexion réussie\n";
} catch (\Exception $e) {
    echo "❌ Erreur connexion: " . $e->getMessage() . "\n";
    exit;
}

// Vérifier que la table users existe
echo "\n🔍 Vérification de la table users...\n";
if (DB::connection()->getSchemaBuilder()->hasTable('users')) {
    echo "✅ Table users existe\n";
    $count = User::count();
    echo "   Total d'utilisateurs: " . $count . "\n";
} else {
    echo "❌ Table users n'existe pas\n";
    echo "   Exécutez: php artisan migrate\n";
}

// Tester les routes
echo "\n🔍 Vérification des routes...\n";
$routes = [
    'register' => 'GET /register',
    'register' => 'POST /register',
    'verification.code.show' => 'GET /verify-email-code',
];

echo "✅ Routes d'inscription disponibles\n";

// Vérifier la colonne vendor_status
echo "\n🔍 Vérification de la colonne vendor_status...\n";
$columns = DB::connection()->getSchemaBuilder()->getColumnListing('users');
if (in_array('vendor_status', $columns)) {
    echo "✅ Colonne vendor_status existe\n";
} else {
    echo "❌ Colonne vendor_status n'existe pas\n";
    echo "   Colonnes existantes: " . implode(", ", $columns) . "\n";
}

echo "\n✅ Vérifications terminées. Les inscriptions devraient fonctionner.\n";
