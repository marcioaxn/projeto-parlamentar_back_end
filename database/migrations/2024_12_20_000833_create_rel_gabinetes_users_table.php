<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelGabinetesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_gabinetes_users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->uuid('cod_gabinete')->nullable();
            $table->foreign('cod_gabinete')
                ->references('cod_gabinete')
                ->on('tab_gabinete')
                ->onDelete('SET NULL');

            // Corrigindo a referência para usar a coluna correta
            $table->uuid('cod_user');
            $table->foreign('cod_user')
                ->references('cod_user')  // Referenciando a coluna correta na tabela users
                ->on('users')
                ->onDelete('cascade');

            $table->boolean('acesso_total')->default(false);
            $table->timestamps();

            $table->unique(['cod_gabinete', 'cod_user']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_gabinetes_users');
    }
}
