<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'vendor_status')) {
                $table->enum('vendor_status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending')->after('role');
            }
            if (!Schema::hasColumn('users', 'vendor_approved_at')) {
                $table->timestamp('vendor_approved_at')->nullable()->after('vendor_status');
            }
            if (!Schema::hasColumn('users', 'vendor_notes')) {
                $table->text('vendor_notes')->nullable()->after('vendor_approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['vendor_status', 'vendor_approved_at', 'vendor_notes']);
        });
    }
};
