<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::
        namespace('Api')->name('api.')->group(function () {

            Route::get('tci/{uf}', [App\Http\Controllers\Api\ApiController::class, 'tci'])->name('tci');

            Route::get('consultar-descricao-chave-estrangeira/{column_name_chave_estrangeira}/{chave_estrangeira}', [App\Http\Controllers\Api\ApiController::class, 'getDescricaoChaveEstrangeira'])->name('consultar.descricao-chave-estrangeira');

            Route::get('fundos/resultado-ano/{ano?}', [App\Http\Controllers\Api\ApiController::class, 'getFundosAnoAtual'])->name('fundos.soma_ano');

            Route::get('novopac-evolucao-financeira/{cod_pac}/{cod_acao_orcamentaria}/{ano}/{mes}', [App\Http\Controllers\Api\ApiController::class, 'getEvolucaoFinanceira'])->name('api.novopac-evolucao-financeira');

        });
