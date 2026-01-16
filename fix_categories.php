<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gestion_stock_ecommerce', 'root', '');

    // Mapping: BD → Fichier réel
    $mapping = [
        'laptop.jpg' => 'Ordinateur portable.jpg',
        'monitor.jpg' => 'Ecran.jpg',
        'keyboard.jpg' => 'Clavier.jpg',
        'mouse.jpg' => 'Souris.jpg',
        'headset.jpg' => 'Casque audio.jpg',
        'webcam.jpg' => 'Webcams.jpg',
    ];

    echo "=== MISE À JOUR DES CATÉGORIES ===\n\n";

    foreach($mapping as $old => $new) {
        $pdo->prepare('UPDATE categories SET image = ? WHERE image = ?')->execute([$new, $old]);
        echo "✓ $old → $new\n";
    }

    echo "\n=== VÉRIFICATION ===\n\n";
    $stmt = $pdo->query('SELECT id, nom, image FROM categories');
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($categories as $cat) {
        echo "ID: {$cat['id']} | {$cat['nom']} | {$cat['image']}\n";
    }

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
