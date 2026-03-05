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
        Schema::table('users', function (Blueprint $table) {
            // Ajouter les colonnes si elles n'existent pas
            if (!Schema::hasColumn('users', 'shop_name')) {
                $table->string('shop_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'id_document')) {
                $table->string('id_document')->nullable();
            }
            if (!Schema::hasColumn('users', 'vendor_status')) {
                $table->enum('vendor_status', ['pending', 'verified', 'rejected'])->default('pending')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToRemove = [];
            if (Schema::hasColumn('users', 'shop_name')) {
                $columnsToRemove[] = 'shop_name';
            }
            if (Schema::hasColumn('users', 'phone')) {
                $columnsToRemove[] = 'phone';
            }
            if (Schema::hasColumn('users', 'address')) {
                $columnsToRemove[] = 'address';
            }
            if (Schema::hasColumn('users', 'id_document')) {
                $columnsToRemove[] = 'id_document';
            }
            if (Schema::hasColumn('users', 'vendor_status')) {
                $columnsToRemove[] = 'vendor_status';
            }
            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
    }
};
