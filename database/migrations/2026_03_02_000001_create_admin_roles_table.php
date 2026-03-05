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
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, stock_manager, order_manager, financial_manager
            $table->string('description')->nullable();
            $table->json('permissions')->nullable(); // Stocker les permissions JSON
            $table->timestamps();
        });

        // Ajouter la colonne admin_role_id à la table users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('admin_role_id')->nullable()->constrained('admin_roles')->onDelete('set null');
            $table->boolean('is_admin')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['admin_role_id']);
            $table->dropColumn(['admin_role_id', 'is_admin']);
        });

        Schema::dropIfExists('admin_roles');
    }
};
