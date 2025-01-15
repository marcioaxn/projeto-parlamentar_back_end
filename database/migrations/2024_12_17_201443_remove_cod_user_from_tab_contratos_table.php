<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tab_contratos', function (Blueprint $table) {
            $table->dropForeign(['cod_user']); // Remove a chave estrangeira (IMPORTANTE)
            $table->dropColumn('cod_user'); // Remove a coluna
        });
    }

    public function down(): void
    {
        Schema::table('tab_contratos', function (Blueprint $table) {
            $table->uuid('cod_user'); // Recria a coluna (para reverter a migration)
            $table->foreign('cod_user')->references('cod_user')->on('users')->onDelete('CASCADE');
        });
    }
};
