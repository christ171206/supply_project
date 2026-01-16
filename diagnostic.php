<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gestion_stock_ecommerce', 'root', '');

    echo "=== DIAGNOSTIC SUPPLY ===\n\n";

    // Vérifier les produits
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM produits WHERE est_actif = 1');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✓ Produits actifs: " . $result['total'] . "\n";

    // Vérifier les catégories
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM categories');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✓ Catégories: " . $result['total'] . "\n";

    // Vérifier les utilisateurs
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM users');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✓ Utilisateurs: " . $result['total'] . "\n";

    // Vérifier les avis
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM avis');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✓ Avis: " . $result['total'] . "\n";

    // Vérifier les fichiers de vue
    echo "\n=== FICHIERS IMPORTANTS ===\n\n";

    $files = [
        'resources/views/accueil.blade.php',
        'resources/views/partials/hero-section.blade.php',
        'resources/views/partials/categories-section.blade.php',
        'resources/views/partials/produits-vedettes.blade.php',
        'resources/views/components/carte-produit.blade.php',
    ];

    foreach($files as $f) {
        $status = file_exists($f) ? '✓' : '✗';
        echo "$status $f\n";
    }

    // Vérifier les dossiers d'images
    echo "\n=== DOSSIERS D'IMAGES ===\n\n";

    $dirs = [
        'storage/app/public/produits' => 'Produits',
        'storage/app/public/categories' => 'Catégories',
        'public/storage/produits' => 'Symlink Produits',
        'public/storage/categories' => 'Symlink Catégories',
    ];

    foreach($dirs as $dir => $label) {
        $status = is_dir($dir) ? '✓' : '✗';
        echo "$status $label ($dir)\n";
    }

    echo "\n=== PREMIER PRODUIT ===\n\n";
    $stmt = $pdo->query('SELECT id, nom, image, est_actif FROM produits LIMIT 1');
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if($product) {
        echo "ID: " . $product['id'] . "\n";
        echo "Nom: " . $product['nom'] . "\n";
        echo "Image: " . $product['image'] . "\n";
        echo "Actif: " . ($product['est_actif'] ? 'OUI' : 'NON') . "\n";
    }

    echo "\n✅ Connexion OK\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>
