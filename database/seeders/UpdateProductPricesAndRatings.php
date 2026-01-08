<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;

class UpdateProductPricesAndRatings extends Seeder
{
    public function run(): void
    {
        $produits = [
            'Dell XPS 13' => ['prix' => 1899000, 'note' => 4.8, 'avis' => 128],
            'MacBook Pro 14' => ['prix' => 2899000, 'note' => 4.9, 'avis' => 156],
            'Sony WH-1000XM5' => ['prix' => 349900, 'note' => 4.7, 'avis' => 89],
            'SteelSeries Arctis 9' => ['prix' => 149900, 'note' => 4.6, 'avis' => 72],
            'JBL Quantum 800' => ['prix' => 119900, 'note' => 4.5, 'avis' => 54],
            'Logitech C920 HD' => ['prix' => 79900, 'note' => 4.4, 'avis' => 103],
            'Razer Kiyo Pro' => ['prix' => 149900, 'note' => 4.6, 'avis' => 68],
            'Elgato Facecam' => ['prix' => 199900, 'note' => 4.5, 'avis' => 81],
            'HP Pavilion 15' => ['prix' => 749900, 'note' => 4.3, 'avis' => 45],
            'ASUS VivoBook 15' => ['prix' => 699900, 'note' => 4.4, 'avis' => 52],
            'BenQ EW2480' => ['prix' => 189900, 'note' => 4.2, 'avis' => 38],
            'LG UltraWide 34' => ['prix' => 999900, 'note' => 4.8, 'avis' => 91],
            'Corsair K95 Platinum' => ['prix' => 279900, 'note' => 4.7, 'avis' => 67],
            'Logitech MX Master 3S' => ['prix' => 149900, 'note' => 4.9, 'avis' => 134],
            'SteelSeries Rival 5' => ['prix' => 99900, 'note' => 4.5, 'avis' => 59],
            'Razer DeathAdder V3' => ['prix' => 119900, 'note' => 4.6, 'avis' => 78],
            'HyperX Fury S' => ['prix' => 79900, 'note' => 4.4, 'avis' => 42],
            'AOC 24G2' => ['prix' => 249900, 'note' => 4.3, 'avis' => 55],
            'ASUS PA248QV' => ['prix' => 399900, 'note' => 4.6, 'avis' => 48],
        ];

        foreach ($produits as $nom => $data) {
            Produit::where('nom', $nom)->update([
                'prix' => $data['prix'],
                'note_moyenne' => $data['note'],
                'nombre_avis' => $data['avis'],
            ]);
        }
    }
}
