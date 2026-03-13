<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('stock_alerts')) {
            Schema::create('stock_alerts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
                $table->integer('alert_threshold')->default(5);
                $table->integer('reorder_quantity')->default(20);
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_alert_sent')->nullable();
                $table->timestamps();

                $table->index(['produit_id', 'is_active']);
                $table->unique('produit_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};
