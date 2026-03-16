<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('promo_codes', 'limit_per_user')) {
                $table->integer('limit_per_user')->nullable()->after('max_utilisations');
            }
        });
    }

    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            if (Schema::hasColumn('promo_codes', 'limit_per_user')) {
                $table->dropColumn('limit_per_user');
            }
        });
    }
};
