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
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('delivery_fee', 10, 2);
            $table->integer('delivery_days')->default(2); // Délai de livraison en jours
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Lier les zones aux quartiers
        Schema::create('delivery_zone_quartiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_zone_id')->constrained('delivery_zones')->onDelete('cascade');
            $table->foreignId('quartier_id')->constrained('quartiers')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['delivery_zone_id', 'quartier_id']);
        });

        // Ajouter colonne delivery_zone_id à commandes si elle n'existe pas
        Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'delivery_zone_id')) {
                $table->foreignId('delivery_zone_id')->nullable()->constrained('delivery_zones')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'delivery_zone_id')) {
                $table->dropForeign(['delivery_zone_id']);
                $table->dropColumn('delivery_zone_id');
            }
        });

        Schema::dropIfExists('delivery_zone_quartiers');
        Schema::dropIfExists('delivery_zones');
    }
};
