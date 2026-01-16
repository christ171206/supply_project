<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gestion_stock_ecommerce', 'root', '');

    // Vérifier les catégories
    $stmt = $pdo->query('SELECT id, nom, image FROM categories');
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "=== VÉRIFICATION DES CATÉGORIES ===\n\n";
    echo "Total catégories: " . count($categories) . "\n\n";

    foreach($categories as $cat) {
        $image = $cat['image'] ?? 'NULL';
        $status = ($image !== 'NULL') ? '✓' : '✗';
        echo "ID: {$cat['id']} | Nom: {$cat['nom']} | Image: $image | $status\n";
    }

    // Vérifier fichiers
    echo "\n=== FICHIERS DISPONIBLES ===\n\n";
    $srcDir = __DIR__ . '/storage/app/public/categories/';
    if(is_dir($srcDir)) {
        $files = scandir($srcDir);
        $files = array_filter($files, fn($f) => $f !== '.' && $f !== '..');
        foreach($files as $file) {
            echo "- $file\n";
        }
    } else {
        echo "Dossier n'existe pas: $srcDir\n";
    }

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
