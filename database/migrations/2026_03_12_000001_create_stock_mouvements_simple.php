<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('stock_mouvements')) {
            Schema::create('stock_mouvements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
                $table->enum('type', ['addition', 'withdrawal']);
                $table->integer('quantity');
                $table->string('reason')->default('manual'); // inventory_error, loss, damage, correction, manual, etc.
                $table->integer('previous_stock')->nullable();
                $table->integer('new_stock')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index('produit_id');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mouvements');
    }
};
