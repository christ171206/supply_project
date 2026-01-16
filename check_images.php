<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gestion_stock_ecommerce', 'root', '');
    $stmt = $pdo->query('SELECT id, nom, image FROM produits LIMIT 10');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "=== VÉRIFICATION DES IMAGES ===\n\n";

    foreach($rows as $row) {
        $image = $row['image'] ?? 'NULL';
        $exists = ($image !== 'NULL') ? '✓' : '✗';
        echo "ID: {$row['id']} | Nom: {$row['nom']} | Image: $image | $exists\n";
    }

    echo "\n=== RÉSUMÉ ===\n";
    $total = count($rows);
    $with_images = count(array_filter($rows, fn($r) => !empty($r['image'])));
    $without_images = $total - $with_images;

    echo "Total produits: $total\n";
    echo "Avec images: $with_images\n";
    echo "Sans images: $without_images\n";

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
