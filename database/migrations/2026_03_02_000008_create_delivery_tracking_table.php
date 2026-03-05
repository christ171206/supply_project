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
        Schema::create('delivery_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('status'); // pending, picked_up, in_transit, delivered, failed
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['commande_id', 'status']);
        });

        // Ajouter des colonnes à commandes pour le tracking
        Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'delivery_status')) {
                $table->string('delivery_status')->default('pending');
            }
            if (!Schema::hasColumn('commandes', 'expected_delivery_date')) {
                $table->timestamp('expected_delivery_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_trackings');
        
        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'delivery_status')) {
                $table->dropColumn('delivery_status');
            }
            if (Schema::hasColumn('commandes', 'expected_delivery_date')) {
                $table->dropColumn('expected_delivery_date');
            }
        });
    }
};
