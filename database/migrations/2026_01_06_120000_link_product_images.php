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
        // Mapping des produits avec leurs images
        $images = [
            'Dell XPS 13' => 'Dell XPS 13.jpg',
            'MacBook Pro 14' => 'MacBook Pro 14.jpg',
            'ASUS TUF Gaming F15' => 'ASUS TUF Gaming F15.jpg',
            'LG UltraWide 34' => 'LG UltraWide 34.jpg',
            'Dell S2721DGF' => 'Dell S2721DGF.jpg',
            'BenQ PD2500Q' => 'BenQ PD2500Q.jpg',
            'Logitech MX Keys' => 'Logitech MX Keys.jpg',
            'Corsair K95 RGB Platinum' => 'Corsair K95 RGB Platinum.png',
        ];

        foreach ($images as $produitNom => $imageName) {
            DB::table('produits')
                ->where('nom', $produitNom)
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
