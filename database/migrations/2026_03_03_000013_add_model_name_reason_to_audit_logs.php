<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add model_name and reason columns to audit_logs if they don't exist
        if (Schema::hasTable('audit_logs')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('audit_logs', 'model_name')) {
                    $table->string('model_name')->nullable()->after('model_id');
                }
                if (!Schema::hasColumn('audit_logs', 'reason')) {
                    $table->text('reason')->nullable()->after('description');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('audit_logs')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                if (Schema::hasColumn('audit_logs', 'model_name')) {
                    $table->dropColumn('model_name');
                }
                if (Schema::hasColumn('audit_logs', 'reason')) {
                    $table->dropColumn('reason');
                }
            });
        }
    }
};
