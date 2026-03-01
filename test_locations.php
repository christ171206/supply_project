<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();

try {
    echo "🔍 Test des données de localisation Côte d'Ivoire\n";
    echo "================================================\n\n";

    $regions = \App\Models\CiRegion::count();
    echo "✅ Régions: $regions\n";

    $sample = \App\Models\CiRegion::with('districts.communes.quartiers')->first();
    if ($sample) {
        echo "\n📍 Exemple de région: {$sample->name}\n";
        echo "   Districts: {$sample->districts->count()}\n";
        if ($sample->districts->first()) {
            $d = $sample->districts->first();
            echo "   ├─ {$d->name}: {$d->communes->count()} communes\n";
            if ($d->communes->first()) {
                $c = $d->communes->first();
                echo "   │  ├─ {$c->name}: {$c->quartiers->count()} quartiers\n";
            }
        }
    }

    // Test de recherche
    echo "\n🔍 Test de recherche 'yopougon':\n";
    $results = \App\Models\CiQuartier::where('name', 'LIKE', '%yopougon%')->get();
    echo "   Résultats: {$results->count()}\n";
    foreach ($results as $r) {
        echo "   ✓ {$r->name}\n";
    }
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
}
