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
        Schema::table('user_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('user_documents', 'document_number')) {
                $table->string('document_number')->nullable()->after('document_type');
            }
            if (!Schema::hasColumn('user_documents', 'document_side')) {
                $table->string('document_side')->nullable()->after('document_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_documents', function (Blueprint $table) {
            if (Schema::hasColumn('user_documents', 'document_number')) {
                $table->dropColumn('document_number');
            }
            if (Schema::hasColumn('user_documents', 'document_side')) {
                $table->dropColumn('document_side');
            }
        });
    }
};
