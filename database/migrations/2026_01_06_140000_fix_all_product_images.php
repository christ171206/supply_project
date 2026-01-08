<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mapping complet des produits avec leurs images
        $productImages = [
            // Ordinateurs Portables
            'Dell XPS 13' => 'Dell XPS 13.jpg',
            'MacBook Pro 14' => 'MacBook Pro 14.jpg',
            'ASUS TUF Gaming F15' => 'ASUS TUF Gaming F15.jpg',

            // Écrans
            'LG UltraWide 34"' => 'LG UltraWide 34.jpg',
            'Dell S2721DGF' => 'Dell S2721DGF.jpg',
            'BenQ PD2500Q' => 'BenQ PD2500Q.jpg',

            // Claviers
            'Corsair K95 RGB Platinum' => 'Corsair K95 RGB Platinum.png',
            'Logitech MX Keys' => 'Logitech MX Keys.jpg',
            'SteelSeries Apex Pro' => 'SteelSeries Apex Pro.jpeg',

            // Souris
            'Razer DeathAdder V3' => 'Razer DeathAdder V3.png',
            'Logitech MX Master 3S' => 'Logitech MX Master 3S.jpg',
            'SteelSeries Rival 5' => 'SteelSeries Rival 5.jpg',

            // Casques Audio
            'Sony WH-1000XM5' => 'Sony WH-1000XM5.jpg',
            'SteelSeries Arctis 9' => 'SteelSeries Arctis 9.jfif',
            'JBL Quantum 800' => 'JBL Quantum 800.jpg',

            // Webcams
            'Logitech C920 HD' => 'Logitech C920 HD.jpg',
            'Razer Kiyo Pro' => 'Razer Kiyo Pro.jpg',
            'Elgato Facecam' => 'Elgato Facecam.jfif',
        ];

        foreach ($productImages as $productName => $imageName) {
            DB::table('produits')
                ->where('nom', $productName)
                ->update(['image' => $imageName]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('produits')->update(['image' => null]);
    }
};
