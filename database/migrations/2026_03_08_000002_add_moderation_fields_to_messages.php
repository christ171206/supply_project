<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('is_flagged')->default(false)->after('contenu');
            $table->string('flag_reason')->nullable()->after('is_flagged');
            $table->unsignedBigInteger('flagged_by_user')->nullable()->after('flag_reason');
            $table->unsignedBigInteger('deleted_by_admin')->nullable()->after('flagged_by_user');
            $table->timestamp('deleted_at')->nullable()->after('deleted_by_admin');
            $table->text('delete_reason')->nullable()->after('deleted_at');

            // Indexes
            $table->foreign('flagged_by_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by_admin')->references('id')->on('users')->onDelete('set null');
            $table->index('is_flagged');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['is_flagged', 'flag_reason', 'flagged_by_user', 'deleted_by_admin', 'deleted_at', 'delete_reason']);
        });
    }
};
