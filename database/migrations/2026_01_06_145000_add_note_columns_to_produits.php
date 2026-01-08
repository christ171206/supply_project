<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            if (!Schema::hasColumn('produits', 'note_moyenne')) {
                $table->decimal('note_moyenne', 3, 1)->default(4.5)->after('prix');
            }
            if (!Schema::hasColumn('produits', 'nombre_avis')) {
                $table->integer('nombre_avis')->default(0)->after('note_moyenne');
            }
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['note_moyenne', 'nombre_avis']);
        });
    }
};
