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
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Client qui a levé le litige
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade'); // Vendeur
            $table->string('type'); // non_delivery, wrong_product, damaged, quality_issue
            $table->text('description');
            $table->string('status')->default('open'); // open, under_review, resolved, closed
            $table->text('admin_notes')->nullable();
            $table->string('resolution')->nullable(); // refund, replacement, partial_refund, no_action
            $table->decimal('resolution_amount', 10, 2)->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['commande_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['vendor_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
