<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=gestion_stock_ecommerce', 'root', '');
$pdo->prepare('UPDATE produits SET image = ? WHERE id = ?')->execute(['LG UltraWide 34.jpg', 4]);
echo "✓ LG UltraWide 34 updated!\n";
?>
