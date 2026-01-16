<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gestion_stock_ecommerce', 'root', '');
    $stmt = $pdo->query('SELECT id, nom, image FROM produits WHERE image IS NOT NULL');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $srcDir = __DIR__ . '/storage/app/public/produits/';

    echo "=== CORRESPONDANCE NOMS ===\n\n";

    foreach($rows as $row) {
        $bdImage = $row['image'];  // ex: dell-xps-13.jpg
        $nomProduit = $row['nom']; // ex: Dell XPS 13

        // Convertir nom produit en nom de fichier (slug)
        $slug = strtolower($nomProduit);
        $slug = str_replace(' ', '-', $slug);
        $slug = str_replace('"', '', $slug);
        $slug = str_replace("'", '', $slug);
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Chercher le fichier dans le dossier
        $files = scandir($srcDir);
        $found = null;

        foreach($files as $file) {
            if($file === '.' || $file === '..') continue;

            $fileSlug = strtolower($file);
            // Retirer l'extension et comparer
            $fileNameOnly = pathinfo($fileSlug, PATHINFO_FILENAME);

            // Chercher une correspondance proche
            if(stripos($file, $nomProduit) !== false || stripos($file, $slug) !== false) {
                $found = $file;
                break;
            }
        }

        if($found) {
            echo "✓ ID: $row[id] | BD: $bdImage | Fichier: $found\n";
        } else {
            echo "✗ ID: $row[id] | BD: $bdImage | Fichier introuvable pour: $nomProduit\n";
        }
    }

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
