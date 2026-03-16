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
        Schema::table('avis', function (Blueprint $table) {
            // Critères de notation détaillés
            $table->integer('qualite_note')->nullable()->default(null); // 1-5
            $table->integer('livraison_note')->nullable()->default(null); // 1-5
            $table->integer('communication_note')->nullable()->default(null); // 1-5 (pour vendeurs)
            $table->integer('rapport_qualite_prix')->nullable()->default(null); // 1-5

            // Détails complémentaires
            $table->text('points_positifs')->nullable();
            $table->text('points_negatifs')->nullable();
            $table->boolean('recommande')->default(true);
            $table->integer('utilite_votes')->default(0); // Nb de ppl qui trouvent ça utile

            // Métadonnées
            $table->enum('type_acheteur', ['verifie', 'non_verifie'])->default('non_verifie');
            $table->boolean('contient_images')->default(false);
            $table->json('images_urls')->nullable();

            // Indices
            $table->index('qualite_note');
            $table->index('livraison_note');
            $table->index('type_acheteur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->dropColumn([
                'qualite_note',
                'livraison_note',
                'communication_note',
                'rapport_qualite_prix',
                'points_positifs',
                'points_negatifs',
                'recommande',
                'utilite_votes',
                'type_acheteur',
                'contient_images',
                'images_urls',
            ]);
        });
    }
};
