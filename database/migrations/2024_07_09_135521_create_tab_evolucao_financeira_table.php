<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabEvolucaoFinanceiraTable extends Migration
{

    public function up()
    {
        Schema::create('midr_pac.tab_evolucao_financeira', function (Blueprint $table) {
            $table->uuid('cod_evolucao_financeira')->primary();
            $table->foreignId('cod_pac')->references('cod_pac')->on('midr_pac.tab_novo_pac');
            $table->string('cod_acao_orcamentaria', 4)->nullable(false);
            $table->smallInteger('num_ano')->nullable(false);
            $table->smallInteger('num_mes')->nullable(false);
            $table->decimal('vlr_financeiro', $precision = 15, $scale = 8)->nullable(true);
            $table->text('txt_observacao_financeira')->nullable(true);
            $table->string('bln_atualizado')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('midr_pac.tab_evolucao_financeira');
    }
}
