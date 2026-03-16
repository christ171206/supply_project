<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$db = $app->make('db');

// Get payments table structure
$cols = $db->select("DESCRIBE payments");
echo "=== PAYMENTS TABLE ===\n";
foreach ($cols as $col) {
    echo "{$col->Field}: {$col->Type}\n";
}

echo "\n=== COMMANDES TABLE ===\n";
$cols = $db->select("DESCRIBE commandes");
foreach ($cols as $col) {
    echo "{$col->Field}: {$col->Type}\n";
}
