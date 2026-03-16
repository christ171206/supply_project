<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix_bundle', 10, 2);
            $table->decimal('prix_original', 10, 2)->nullable();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->integer('quantite_disponible')->nullable();
            $table->integer('quantite_vendues')->default(0);
            $table->enum('statut', ['actif', 'inactif', 'expire'])->default('actif');
            $table->boolean('archive')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'statut']);
            $table->index(['date_debut', 'date_fin']);
        });

        Schema::create('bundle_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('bundles')->cascadeOnDelete();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            $table->integer('quantite');
            $table->timestamps();

            $table->unique(['bundle_id', 'produit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundle_produits');
        Schema::dropIfExists('bundles');
    }
};
