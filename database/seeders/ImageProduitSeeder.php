<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Seeder;

class ImageProduitSeeder extends Seeder
{
    public function run(): void
    {
        // Mettre à jour les produits avec leurs images
        $updates = [
            'Dell XPS 13' => 'Dell XPS 13.jpg',
            'MacBook Pro 14' => 'MacBook Pro 14.jpg',
            'ASUS TUF Gaming F15' => 'ASUS TUF Gaming F15.jpg',
            'HP Pavilion 15' => 'Dell XPS 13.jpg',
            'LG UltraWide 34"' => 'LG UltraWide 34.jpg',
            'ASUS ROG 27" 240Hz' => 'Dell S2721DGF.jpg',
            'Dell U2720Q 27"' => 'BenQ PD2500Q.jpg',
            'Corsair K95 Platinum' => 'Corsair K95 RGB Platinum.png',
            'SteelSeries Apex Pro' => 'SteelSeries Apex Pro.jpeg',
            'Logitech G Pro X' => 'Logitech MX Master 3S.jpg',
            'SteelSeries Rival 600' => 'SteelSeries Rival 5.jpg',
            'Sony WH-1000XM5' => 'Sony WH-1000XM5.jpg',
            'SteelSeries Arctis 9' => 'SteelSeries Arctis 9.jfif',
            'JBL Quantum 800' => 'JBL Quantum 800.jpg',
            'Logitech C920 HD' => 'Logitech C920 HD.jpg',
            'Elgato Facecam' => 'Elgato Facecam.jfif',
            'Razer Kiyo Pro' => 'Razer Kiyo Pro.jpg',
        ];

        foreach ($updates as $produitNom => $imageName) {
            Produit::where('nom', $produitNom)->update(['image' => $imageName]);
        }
    }
}
