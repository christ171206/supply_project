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
        Schema::create('user_bans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('reason'); // fraud, late_delivery, policy_violation, etc.
            $table->text('details')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('banned_at')->useCurrent();
            $table->timestamp('unbanned_at')->nullable();
            $table->foreignId('banned_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('unbanned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });

        // Ajouter colonnes à users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_banned')->default(false);
            $table->timestamp('banned_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_banned', 'banned_until']);
        });

        Schema::dropIfExists('user_bans');
    }
};
