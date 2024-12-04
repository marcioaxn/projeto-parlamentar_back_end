<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterVlrFinanceiroToDoublePrecision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE midr_pac.tab_evolucao_financeira ALTER COLUMN vlr_financeiro TYPE double precision');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE midr_pac.tab_evolucao_financeira ALTER COLUMN vlr_financeiro TYPE numeric(15,8)');
    }
}
