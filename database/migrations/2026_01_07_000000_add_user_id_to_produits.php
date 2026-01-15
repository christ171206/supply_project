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
        Schema::table('produits', function (Blueprint $table) {
            // Ajouter la colonne user_id pour identifier le vendeur du produit
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('categorie_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            if (Schema::hasColumn('produits', 'user_id')) {
                // Utiliser la syntaxe correcte pour supprimer une clé étrangère
                $table->dropForeign('produits_user_id_foreign');
                $table->dropColumn('user_id');
            }
        });
    }
};
