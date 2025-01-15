<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabContratosTable extends Migration
{
    public function up()
    {
        Schema::create('tab_contratos', function (Blueprint $table) {
            $table->uuid('cod_contrato')->primary();

            $table->uuid('cod_gabinete')->nullable();
            $table->foreign('cod_gabinete')->references('cod_gabinete')->on('tab_gabinete')->onDelete('SET NULL');


            $table->uuid('cod_plano')->nullable();
            $table->foreign('cod_plano')->references('cod_plano')->on('tab_planos')->onDelete('SET NULL');

            $table->double('val_total')->nullable();
            $table->double('val_desconto_aplicado')->nullable();
            $table->double('val_sub_total')->nullable();
            $table->text('dsc_observacoes')->nullable();
            $table->string('sta_ativo', 50)->default('ativo');
            $table->date('dat_inicio')->nullable();
            $table->date('dat_fim')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tab_contratos');
    }
}
