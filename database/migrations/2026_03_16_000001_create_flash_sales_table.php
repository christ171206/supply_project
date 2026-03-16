<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('categorie_id')->constrained('categories')->cascadeOnDelete();
            $table->decimal('pourcentage_reduction', 5, 2);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->enum('statut', ['actif', 'inactif', 'expire'])->default('actif');
            $table->boolean('archive')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'statut']);
            $table->index(['date_debut', 'date_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
