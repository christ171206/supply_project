<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->boolean('is_appropriate')->default(true)->after('commentaire');
            $table->string('report_reason')->nullable()->after('is_appropriate');
            $table->unsignedBigInteger('deleted_by_admin')->nullable()->after('report_reason');
            $table->timestamp('deleted_at')->nullable()->after('deleted_by_admin');
            $table->text('delete_reason')->nullable()->after('deleted_at');

            // Indexes
            $table->foreign('deleted_by_admin')->references('id')->on('users')->onDelete('set null');
            $table->index('is_appropriate');
        });
    }

    public function down(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->dropColumn(['is_appropriate', 'report_reason', 'deleted_by_admin', 'deleted_at', 'delete_reason']);
        });
    }
};
