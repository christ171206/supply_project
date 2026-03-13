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
            // Ajouter les colonnes manquantes pour le document d'identité recto/verso
            $table->string('document_side')->nullable()->after('document_type'); // front, back
            $table->string('document_number')->nullable()->after('document_path'); // Numéro du document
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_documents', function (Blueprint $table) {
            if (Schema::hasColumn('user_documents', 'document_side')) {
                $table->dropColumn('document_side');
            }
            if (Schema::hasColumn('user_documents', 'document_number')) {
                $table->dropColumn('document_number');
            }
        });
    }
};
