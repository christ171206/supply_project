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
        // Vérifier si l'ancienne table existe et la renommer/supprimer
        if (Schema::hasTable('delivery_tracking')) {
            // Récupérer les données si nécessaire avant suppression
            $data = DB::table('delivery_tracking')->get();

            // Créer la nouvelle table avec le bon nom pluriel
            Schema::create('delivery_trackings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->string('status');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['commande_id', 'status']);
            });

            // Copier les données si la nouvelle table est créée
            foreach ($data as $row) {
                DB::table('delivery_trackings')->insert((array) $row);
            }

            // Supprimer l'ancienne table
            Schema::dropIfExists('delivery_tracking');
        } elseif (!Schema::hasTable('delivery_trackings')) {
            // Si aucune des deux tables n'existe, créer la nouvelle
            Schema::create('delivery_trackings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->string('status');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['commande_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_trackings');
    }
};
