<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabContratacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tab_contratos', function (Blueprint $table) {
            $table->uuid('cod_contrato')->primary();

            $table->foreignUuid('cod_user')->references('cod_user')->on('users')->onDelete('cascade');

            $table->uuid('cod_plano')->nullable();
            $table->foreign('cod_plano')->references('cod_plano')->on('tab_planos')->onDelete('SET NULL');

            $table->decimal('val_total', 10, 2)->nullable();
            $table->decimal('val_desconto_aplicado', 10, 2)->nullable();
            $table->text('dsc_observacoes')->nullable();
            $table->string('sta_status', 50)->default('ativo');
            $table->timestamp('dat_inicio')->nullable();
            $table->timestamp('dat_fim')->nullable();

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
        Schema::dropIfExists('tab_contratacao');
    }
}
