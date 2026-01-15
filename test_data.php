<?php

// Test rapide des données
$produits = \App\Models\Produit::count();
$categories = \App\Models\Categorie::count();
$users = \App\Models\User::count();

echo "Produits: $produits\n";
echo "Catégories: $categories\n";
echo "Utilisateurs: $users\n";

if ($produits == 0) {
    echo "\n⚠️ AUCUN PRODUIT EN BASE DE DONNÉES!\n";
    echo "Vous devez créer des produits et des catégories.\n";
}

if ($categories == 0) {
    echo "\n⚠️ AUCUNE CATÉGORIE EN BASE DE DONNÉES!\n";
    echo "Vous devez créer des catégories d'abord.\n";
}
