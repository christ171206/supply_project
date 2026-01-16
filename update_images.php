<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gestion_stock_ecommerce', 'root', '');
    $stmt = $pdo->query('SELECT id, nom FROM produits WHERE image IS NOT NULL');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $srcDir = __DIR__ . '/storage/app/public/produits/';
    $mapping = [];

    // Créer un mapping des fichiers
    $files = scandir($srcDir);
    foreach($files as $file) {
        if($file === '.' || $file === '..') continue;
        $mapping[strtolower(pathinfo($file, PATHINFO_FILENAME))] = $file;
    }

    echo "=== MISE À JOUR DES IMAGES ===\n\n";

    $updated = 0;
    foreach($rows as $row) {
        $nomProduit = $row['nom'];

        // Créer slug du produit
        $slug = strtolower($nomProduit);
        $slug = str_replace(' ', '-', $slug);
        $slug = str_replace('"', '', $slug);
        $slug = str_replace("'", '', $slug);
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Chercher le fichier
        $found = false;
        foreach($mapping as $fileSlug => $filename) {
            if(stripos($fileSlug, $slug) !== false || stripos($filename, $nomProduit) !== false) {
                $pdo->prepare('UPDATE produits SET image = ? WHERE id = ?')
                    ->execute([$filename, $row['id']]);
                echo "✓ ID {$row['id']}: $nomProduit → $filename\n";
                $found = true;
                $updated++;
                break;
            }
        }

        if(!$found) {
            echo "✗ ID {$row['id']}: $nomProduit → Fichier non trouvé\n";
        }
    }

    echo "\n=== RÉSUMÉ ===\n";
    echo "Produits mis à jour: $updated\n";

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
