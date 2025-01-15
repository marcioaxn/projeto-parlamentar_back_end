<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabGabineteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tab_gabinete', function (Blueprint $table) {
            $table->uuid('cod_gabinete')->primary();
            $table->integer('cod_parlamentar');
            $table->string('nom_gabinete', 255);
            $table->boolean('sta_ativo')->default(true);

            // Foreign Key Constraint
            $table->foreign('cod_parlamentar')
                ->references('cod_parlamentar')
                ->on('tab_parlamentares')
                ->onDelete('cascade');

            // Timestamps and Soft Deletes
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
        Schema::dropIfExists('tab_gabinete');
    }
}
