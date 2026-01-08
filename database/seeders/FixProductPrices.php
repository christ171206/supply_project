<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Seeder;

class FixProductPrices extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tarifs réalistes en FCFA pour l'Afrique de l'Ouest
        $prices = [
            1 => 1_299_000,  // Dell XPS 13
            2 => 2_599_000,  // MacBook Pro 14
            3 => 899_000,    // ASUS TUF Gaming F15
            4 => 599_000,    // LG UltraWide 34"
            5 => 449_000,    // Dell S2721DGF
            6 => 499_000,    // BenQ PD2500Q
            7 => 149_900,    // Corsair K95 RGB Platinum
            8 => 129_900,    // Logitech MX Keys
            9 => 149_900,    // SteelSeries Apex Pro
            10 => 149_900,   // Logitech MX Master 3S (already correct)
            11 => 119_900,   // Razer DeathAdder V3 (already correct)
            12 => 99_900,    // SteelSeries Rival 5 (already correct)
            13 => 349_900,   // Sony WH-1000XM5 (already correct)
            14 => 149_900,   // SteelSeries Arctis 9 (already correct)
            15 => 119_900,   // JBL Quantum 800 (already correct)
            16 => 79_900,    // Logitech C920 HD (already correct)
            17 => 149_900,   // Razer Kiyo Pro (already correct)
            18 => 199_900,   // Elgato Facecam (already correct)
        ];

        foreach ($prices as $id => $price) {
            Produit::where('id', $id)->update(['prix' => $price]);
        }

        $this->command->info('✓ Prix des produits corrigés avec succès!');
    }
}
