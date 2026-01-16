<?php
// Conversion €/FCFA: 1€ = 655 FCFA (approximatif)

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gestion_stock_ecommerce', 'root', '');

    echo "=== CONVERSION € → FCFA ===\n\n";

    // Récupérer tous les produits
    $stmt = $pdo->query('SELECT id, nom, prix FROM produits');
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $taux = 655; // 1€ = 655 FCFA
    $updated = 0;

    foreach($produits as $produit) {
        // Convertir le prix (arrondir aux 500 FCFA supérieurs pour cohérence)
        $nouveau_prix = round($produit['prix'] * $taux / 500) * 500;

        // Mettre à jour
        $pdo->prepare('UPDATE produits SET prix = ? WHERE id = ?')
            ->execute([$nouveau_prix, $produit['id']]);

        echo "✓ {$produit['nom']}: {$produit['prix']}€ → $nouveau_prix FCFA\n";
        $updated++;
    }

    echo "\n=== RÉSUMÉ ===\n";
    echo "Produits mis à jour: $updated\n";

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
