<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            // Ajouter colonne pour tracer qui a assigné le coupon
            $table->enum('type_distribution', ['public', 'private'])->default('public')->after('code');
            $table->foreignId('assigned_by')->nullable()->after('type_distribution')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropForeignKey(['assigned_by']);
            $table->dropColumn(['type_distribution', 'assigned_by']);
        });
    }
};
