<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fixer les chemins des images en supprimant le préfixe "categories/"
        \Illuminate\Support\Facades\DB::update(
            "UPDATE categories SET image = REPLACE(image, 'categories/', '') WHERE image LIKE 'categories/%'"
        );

        // Fixer les chemins des images des produits en supprimant le préfixe "produits/"
        \Illuminate\Support\Facades\DB::update(
            "UPDATE produits SET image = REPLACE(image, 'produits/', '') WHERE image LIKE 'produits/%'"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cette migration ne peut pas être inversée car on ne sait pas le préfixe original
    }
};
