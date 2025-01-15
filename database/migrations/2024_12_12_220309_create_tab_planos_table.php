<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tab_planos', function (Blueprint $table) {
            $table->uuid('cod_plano')->primary(); // Identificador único
            $table->string('nom_plano', 255); // Nome do plano
            $table->text('dsc_plano')->nullable(); // Descrição detalhada
            $table->decimal('val_plano', 10, 2); // Valor do plano
            $table->integer('lim_usuarios')->nullable(); // Limite de usuários (aplicável ao Básico)
            $table->string('frequencia_cobranca', 50)->default('mensal'); // Frequência de cobrança
            $table->uuid('cod_plano_dependente')->nullable(); // Relacionamento para upgrade/downgrade
            $table->boolean('sta_ativo')->default(true); // Status ativo ou descontinuado

            // Controle de timestamps
            $table->timestamps();
        });

        // Foreign key para planos dependentes (executada após a tabela ser criada)
        Schema::table('tab_planos', function (Blueprint $table) {
            $table->foreign('cod_plano_dependente')
                ->references('cod_plano')
                ->on('tab_planos')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tab_planos', function (Blueprint $table) {
            $table->dropForeign(['cod_plano_dependente']);
        });
        Schema::dropIfExists('tab_planos');
    }
};
