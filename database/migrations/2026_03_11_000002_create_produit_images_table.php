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
        Schema::create('produit_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            
            // Stockage Cloudinary
            $table->string('cloudinary_public_id')->unique();
            $table->string('cloudinary_url');
            $table->string('cloudinary_secure_url');
            
            // Métadonnées
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('format')->nullable();
            
            // Gestion
            $table->integer('order')->default(0); // Ordre dans la galerie
            $table->boolean('is_primary')->default(false); // Image principale
            
            $table->timestamps();
            
            $table->index('produit_id');
            $table->index('is_primary');
        });

        // Ajouter colonne à la table produits pour l'image de couverture
        Schema::table('produits', function (Blueprint $table) {
            // Si la colonne n'existe pas déjà
            if (!Schema::hasColumn('produits', 'primary_image_cloudinary_id')) {
                $table->string('primary_image_cloudinary_id')->nullable()->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('primary_image_cloudinary_id');
        });

        Schema::dropIfExists('produit_images');
    }
};
