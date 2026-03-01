<?php

$urls = [
    'http://127.0.0.1:8000/api/delivery-locations/regions',
    'http://127.0.0.1:8000/api/delivery-locations/search?q=yop'
];

foreach ($urls as $url) {
    echo "Test: $url\n";
    $response = file_get_contents($url);
    if ($response === false) {
        echo "❌ Erreur (404 ou timeout)\n\n";
    } else {
        $data = json_decode($response, true);
        echo "✅ Réponse reçue (" . strlen($response) . " bytes)\n";
        if (is_array($data)) {
            echo "   Status: " . ($data['status'] ?? 'unknown') . "\n";
            if (isset($data['data'])) {
                echo "   Données: " . count($data['data']) . " items\n";
            }
        }
        echo "\n";
    }
}
