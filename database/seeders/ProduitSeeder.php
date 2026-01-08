<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les catégories
        $categories = [
            [
                'nom' => 'Ordinateurs Portables',
                'slug' => 'ordinateurs-portables',
                'description' => 'Laptops et notebooks performants pour le travail et les loisirs',
                'image' => 'laptop.jpg',
            ],
            [
                'nom' => 'Écrans',
                'slug' => 'ecrans',
                'description' => 'Moniteurs haute résolution pour bureaux et gaming',
                'image' => 'monitor.jpg',
            ],
            [
                'nom' => 'Claviers',
                'slug' => 'claviers',
                'description' => 'Claviers mécaniques et sans fil',
                'image' => 'keyboard.jpg',
            ],
            [
                'nom' => 'Souris',
                'slug' => 'souris',
                'description' => 'Souris gamer et ergonomiques',
                'image' => 'mouse.jpg',
            ],
            [
                'nom' => 'Casques Audio',
                'slug' => 'casques-audio',
                'description' => 'Casques pour gaming, musique et appels',
                'image' => 'headset.jpg',
            ],
            [
                'nom' => 'Webcams',
                'slug' => 'webcams',
                'description' => 'Caméras pour visioconférence',
                'image' => 'webcam.jpg',
            ],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        // Créer les produits
        $produits = [
            // Ordinateurs Portables
            [
                'categorie_id' => 1,
                'nom' => 'Dell XPS 13',
                'slug' => 'dell-xps-13',
                'description' => 'Laptop ultraléger avec écran FHD, processeur Intel Core i7',
                'prix' => 1299.99,
                'stock' => 15,
                'stock_minimum' => 5,
                'image' => 'dell-xps-13.jpg',
            ],
            [
                'categorie_id' => 1,
                'nom' => 'MacBook Pro 14',
                'slug' => 'macbook-pro-14',
                'description' => 'Puissant ordinateur portable Apple avec M3 Pro',
                'prix' => 1999.99,
                'stock' => 8,
                'stock_minimum' => 3,
                'image' => 'macbook-pro.jpg',
            ],
            [
                'categorie_id' => 1,
                'nom' => 'ASUS TUF Gaming F15',
                'slug' => 'asus-tuf-gaming-f15',
                'description' => 'Laptop gaming haute performance pour joueurs exigeants',
                'prix' => 1599.99,
                'stock' => 12,
                'stock_minimum' => 5,
                'image' => 'asus-tuf.jpg',
            ],

            // Écrans
            [
                'categorie_id' => 2,
                'nom' => 'LG UltraWide 34"',
                'slug' => 'lg-ultrawide-34',
                'description' => 'Écran ultra-large 34 pouces avec résolution 3440x1440',
                'prix' => 799.99,
                'stock' => 10,
                'stock_minimum' => 3,
                'image' => 'lg-ultrawide.jpg',
            ],
            [
                'categorie_id' => 2,
                'nom' => 'Dell S2721DGF',
                'slug' => 'dell-s2721dgf',
                'description' => 'Moniteur 27" gaming 165Hz avec panneau QHD',
                'prix' => 599.99,
                'stock' => 18,
                'stock_minimum' => 8,
                'image' => 'dell-s2721.jpg',
            ],
            [
                'categorie_id' => 2,
                'nom' => 'BenQ PD2500Q',
                'slug' => 'benq-pd2500q',
                'description' => 'Écran professionnel 25" pour designers et photographes',
                'prix' => 699.99,
                'stock' => 7,
                'stock_minimum' => 2,
                'image' => 'benq-pd2500.jpg',
            ],

            // Claviers
            [
                'categorie_id' => 3,
                'nom' => 'Corsair K95 RGB Platinum',
                'slug' => 'corsair-k95-rgb',
                'description' => 'Clavier mécanique gaming avec interrupteurs Cherry MX',
                'prix' => 249.99,
                'stock' => 25,
                'stock_minimum' => 10,
                'image' => 'corsair-k95.jpg',
            ],
            [
                'categorie_id' => 3,
                'nom' => 'Logitech MX Keys',
                'slug' => 'logitech-mx-keys',
                'description' => 'Clavier sans fil premium pour productivité',
                'prix' => 129.99,
                'stock' => 30,
                'stock_minimum' => 12,
                'image' => 'logitech-mx.jpg',
            ],
            [
                'categorie_id' => 3,
                'nom' => 'SteelSeries Apex Pro',
                'slug' => 'steelseries-apex-pro',
                'description' => 'Clavier gaming avec touches personnalisables',
                'prix' => 199.99,
                'stock' => 20,
                'stock_minimum' => 8,
                'image' => 'steelseries-apex.jpg',
            ],

            // Souris
            [
                'categorie_id' => 4,
                'nom' => 'Logitech MX Master 3S',
                'slug' => 'logitech-mx-master-3s',
                'description' => 'Souris sans fil ergonomique pour productivité professionnelle',
                'prix' => 99.99,
                'stock' => 35,
                'stock_minimum' => 15,
                'image' => 'logitech-mx-master.jpg',
            ],
            [
                'categorie_id' => 4,
                'nom' => 'Razer DeathAdder V3',
                'slug' => 'razer-deathadder-v3',
                'description' => 'Souris gamer légère et précise',
                'prix' => 69.99,
                'stock' => 40,
                'stock_minimum' => 18,
                'image' => 'razer-deathadder.jpg',
            ],
            [
                'categorie_id' => 4,
                'nom' => 'SteelSeries Rival 5',
                'slug' => 'steelseries-rival-5',
                'description' => 'Souris gaming avec capteur TrueMove Air',
                'prix' => 49.99,
                'stock' => 45,
                'stock_minimum' => 20,
                'image' => 'steelseries-rival.jpg',
            ],

            // Casques Audio
            [
                'categorie_id' => 5,
                'nom' => 'Sony WH-1000XM5',
                'slug' => 'sony-wh-1000xm5',
                'description' => 'Casque avec réduction de bruit active premium',
                'prix' => 399.99,
                'stock' => 12,
                'stock_minimum' => 5,
                'image' => 'sony-wh1000.jpg',
            ],
            [
                'categorie_id' => 5,
                'nom' => 'SteelSeries Arctis 9',
                'slug' => 'steelseries-arctis-9',
                'description' => 'Casque gaming sans fil avec microphone bidirectionnel',
                'prix' => 329.99,
                'stock' => 16,
                'stock_minimum' => 6,
                'image' => 'steelseries-arctis.jpg',
            ],
            [
                'categorie_id' => 5,
                'nom' => 'JBL Quantum 800',
                'slug' => 'jbl-quantum-800',
                'description' => 'Casque gaming sans fil avec surround 7.1',
                'prix' => 249.99,
                'stock' => 20,
                'stock_minimum' => 8,
                'image' => 'jbl-quantum.jpg',
            ],

            // Webcams
            [
                'categorie_id' => 6,
                'nom' => 'Logitech C920 HD',
                'slug' => 'logitech-c920-hd',
                'description' => 'Webcam USB Full HD avec autofocus',
                'prix' => 79.99,
                'stock' => 50,
                'stock_minimum' => 20,
                'image' => 'logitech-c920.jpg',
            ],
            [
                'categorie_id' => 6,
                'nom' => 'Razer Kiyo Pro',
                'slug' => 'razer-kiyo-pro',
                'description' => 'Webcam gaming 1080p 60fps avec capteur Sony',
                'prix' => 199.99,
                'stock' => 18,
                'stock_minimum' => 7,
                'image' => 'razer-kiyo.jpg',
            ],
            [
                'categorie_id' => 6,
                'nom' => 'Elgato Facecam',
                'slug' => 'elgato-facecam',
                'description' => 'Webcam 1080p conçue pour streamers et creators',
                'prix' => 169.99,
                'stock' => 22,
                'stock_minimum' => 9,
                'image' => 'elgato-facecam.jpg',
            ],
        ];

        foreach ($produits as $produit) {
            Produit::create($produit);
        }
    }
}
