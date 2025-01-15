<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabAgendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tab_agenda', function (Blueprint $table) {
            $table->uuid('cod_agenda')->primary();
            $table->string('dsc_titulo');
            $table->text('dsc_descricao')->nullable();
            $table->timestamp('dat_inicio');
            $table->timestamp('dat_fim');
            $table->string('nom_cor')->nullable();
            $table->boolean('ind_recorrente')->default(false);
            $table->string('dsc_url')->nullable();
            $table->integer('cod_parlamentar');
            $table->foreign('cod_parlamentar')
                ->references('id')
                ->on('tab_parlamentares')
                ->onDelete('cascade');
            $table->string('dsc_rrule')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('midr_gestao.tab_agenda');
    }
}
