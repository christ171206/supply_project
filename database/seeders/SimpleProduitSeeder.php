<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Seeder;

class SimpleProduitSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les catégories
        $categories = [
            ['nom' => 'Ordinateurs Portables', 'slug' => 'ordinateurs-portables', 'description' => 'Laptops performants'],
            ['nom' => 'Écrans', 'slug' => 'ecrans', 'description' => 'Moniteurs haute résolution'],
            ['nom' => 'Claviers', 'slug' => 'claviers', 'description' => 'Claviers mécaniques'],
            ['nom' => 'Souris', 'slug' => 'souris', 'description' => 'Souris gamer et ergonomiques'],
            ['nom' => 'Casques Audio', 'slug' => 'casques-audio', 'description' => 'Casques pour gaming et musique'],
            ['nom' => 'Webcams', 'slug' => 'webcams', 'description' => 'Caméras pour visioconférence'],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        // Produits - Prix en FCFA (x500 environ des dollars)
        $produits = [
            // Ordinateurs Portables
            ['categorie_id' => 1, 'nom' => 'Dell XPS 13', 'slug' => 'dell-xps-13', 'description' => 'Laptop ultraléger', 'prix' => 649500, 'stock' => 15, 'image' => null],
            ['categorie_id' => 1, 'nom' => 'MacBook Pro 14', 'slug' => 'macbook-pro-14', 'description' => 'Laptop Apple puissant', 'prix' => 999950, 'stock' => 8, 'image' => null],
            ['categorie_id' => 1, 'nom' => 'ASUS TUF Gaming F15', 'slug' => 'asus-tuf-gaming-f15', 'description' => 'Gaming haute performance', 'prix' => 899000, 'stock' => 12, 'image' => null],
            ['categorie_id' => 1, 'nom' => 'HP Pavilion 15', 'slug' => 'hp-pavilion-15', 'description' => 'Laptop polyvalent', 'prix' => 549500, 'stock' => 20, 'image' => null],

            // Écrans
            ['categorie_id' => 2, 'nom' => 'LG UltraWide 34"', 'slug' => 'lg-ultrawide-34', 'description' => 'Écran ultra-large', 'prix' => 749500, 'stock' => 6, 'image' => null],
            ['categorie_id' => 2, 'nom' => 'ASUS ROG 27" 240Hz', 'slug' => 'asus-rog-27', 'description' => 'Gaming haute fréquence', 'prix' => 399950, 'stock' => 10, 'image' => null],
            ['categorie_id' => 2, 'nom' => 'Dell U2720Q 27"', 'slug' => 'dell-u2720q', 'description' => '4K professionnel', 'prix' => 549500, 'stock' => 5, 'image' => null],

            // Claviers
            ['categorie_id' => 3, 'nom' => 'Corsair K95 Platinum', 'slug' => 'corsair-k95', 'description' => 'Clavier mécanique gaming', 'prix' => 249950, 'stock' => 18, 'image' => null],
            ['categorie_id' => 3, 'nom' => 'SteelSeries Apex Pro', 'slug' => 'steelseries-apex', 'description' => 'Clavier mécanique professionnel', 'prix' => 199950, 'stock' => 14, 'image' => null],

            // Souris
            ['categorie_id' => 4, 'nom' => 'Logitech G Pro X', 'slug' => 'logitech-g-pro', 'description' => 'Souris gaming professionnelle', 'prix' => 99500, 'stock' => 30, 'image' => null],
            ['categorie_id' => 4, 'nom' => 'SteelSeries Rival 600', 'slug' => 'steelseries-rival', 'description' => 'Souris gamer haute précision', 'prix' => 149950, 'stock' => 22, 'image' => null],

            // Casques Audio
            ['categorie_id' => 5, 'nom' => 'Sony WH-1000XM5', 'slug' => 'sony-wh-1000xm5', 'description' => 'Casque sans fil premium', 'prix' => 349950, 'stock' => 10, 'image' => null],
            ['categorie_id' => 5, 'nom' => 'SteelSeries Arctis 9', 'slug' => 'steelseries-arctis-9', 'description' => 'Casque gaming sans fil', 'prix' => 249950, 'stock' => 12, 'image' => null],
            ['categorie_id' => 5, 'nom' => 'JBL Quantum 800', 'slug' => 'jbl-quantum-800', 'description' => 'Casque gaming 7.1', 'prix' => 199950, 'stock' => 15, 'image' => null],

            // Webcams
            ['categorie_id' => 6, 'nom' => 'Logitech C920 HD', 'slug' => 'logitech-c920', 'description' => 'Webcam 1080p', 'prix' => 79950, 'stock' => 25, 'image' => null],
            ['categorie_id' => 6, 'nom' => 'Elgato Facecam', 'slug' => 'elgato-facecam', 'description' => 'Webcam gaming 1080p', 'prix' => 149950, 'stock' => 16, 'image' => null],
            ['categorie_id' => 6, 'nom' => 'Razer Kiyo Pro', 'slug' => 'razer-kiyo-pro', 'description' => 'Webcam gaming professionnelle', 'prix' => 179950, 'stock' => 11, 'image' => null],
        ];

        foreach ($produits as $prod) {
            Produit::create($prod);
        }
    }
}
