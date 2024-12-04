<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UsersController;

Route::group(['middleware' => ['auth:sanctum', 'auth', 'check-permissao', 'trocarSenha', 'usuarioInativo']], function () {

    Route::get('/', [App\Http\Controllers\TabModulosController::class, 'index'])->name('principal');

    Route::post('parlamentar/{cod_parlamentar?}/{tema_selecionado?}', [App\Http\Controllers\ParlamentarController::class, 'index'])->name('parlamentar');
    Route::get('parlamentar/{cod_parlamentar?}/{tema_selecionado?}', [App\Http\Controllers\ParlamentarController::class, 'index'])->name('parlamentar');

    Route::get('gravar-celular-parlamentar/{num_celular}/{cod_parlamentar}/{cod_celular?}/{bln_excluir?}', [App\Http\Controllers\TabParlamentarCelularController::class, 'store'])->name('parlametar.celular.store');

    Route::get('gravar-observacao-parlamentar/{cod_assunto}/{txt_observacao_parlamentar}/{cod_parlamentar}/{cod_observacao_parlamentar?}/{bln_excluir?}', [App\Http\Controllers\TabParlamentarObservacoesController::class, 'store'])->name('parlametar.observacao.store');

    // Início das rotas em relação ao atedimento

    Route::post('atendimento/store', [App\Http\Controllers\TabAtendimentosController::class, 'store'])->name('atendimento.store');

    Route::post('atendimentos/{cod_atendimento?}', [App\Http\Controllers\TabAtendimentosController::class, 'index'])->name('atendimentos');
    Route::get('atendimentos/{cod_atendimento?}', [App\Http\Controllers\TabAtendimentosController::class, 'index'])->name('atendimentos');

    Route::get('atendimento/editar/{cod_atendimento}/{cod_parlamentar?}', [App\Http\Controllers\TabAtendimentosController::class, 'edit'])->name('atendimento.editar');

    Route::post('atendimento/update', [App\Http\Controllers\TabAtendimentosController::class, 'update'])->name('atendimento.detalhes.update');

    Route::get('atendimento-ajax-gravar-alteracao-select/{column_name}/{value}/{cod_atendimento}', [App\Http\Controllers\TabAtendimentosController::class, 'ajaxGravarAlteracaoSelect'])->name('atendimento.ajax-gravar-alteracao-select');

    Route::get('atendimento/convidados/{cod_atendimento}', [App\Http\Controllers\TabAtendimentoConvidadosController::class, 'montarDivConvidados'])->name('atendimentos.convidados');

    Route::post('atendimento/convidado/update', [App\Http\Controllers\TabAtendimentoConvidadosController::class, 'update'])->name('atendimento.convidado.update');

    Route::post('atendimento/demanda/update', [App\Http\Controllers\TabAtendimentoDemandasController::class, 'update'])->name('atendimento.demanda.update');

    Route::get('atendimento/incluir/convidado/{cod_interlocutor}/{nom_convidado}/{cod_atendimento}', [App\Http\Controllers\TabAtendimentoConvidadosController::class, 'incluirConvidado'])->name('atendimentos.convidado.incluir');

    Route::get('atendimento/excluir/convidado/{cod_convidado}/{cod_atendimento}', [App\Http\Controllers\TabAtendimentoConvidadosController::class, 'excluirConvidado'])->name('atendimentos.convidado.excluir');

    Route::get('atendimento/demandas/{cod_atendimento}', [App\Http\Controllers\TabAtendimentoDemandasController::class, 'montarDivDemandas'])->name('atendimentos.demandas');

    Route::get('atendimento/incluir/demanda/{dsc_demanda}/{codigoUnidade}/{dte_prazo}/{cod_status_demanda}/{cod_atendimento}', [App\Http\Controllers\TabAtendimentoDemandasController::class, 'incluirDemanda'])->name('atendimentos.demanda.incluir');

    Route::get('atendimento/excluir/demanda/{cod_demanda_atendimento}/{cod_atendimento}', [App\Http\Controllers\TabAtendimentoDemandasController::class, 'excluirDemanda'])->name('atendimentos.demanda.excluir');

    Route::post('incluirArquivoAjax', [App\Http\Controllers\TabAtendimentoArquivosController::class, 'incluirArquivoAjax'])->name('atendimento.anexo.incluir');

    Route::get('atendimento/excluir/arquivo/{cod_arquivo}/{cod_atendimento}', [App\Http\Controllers\TabAtendimentoArquivosController::class, 'excluirArquivo'])->name('atendimentos.arquivo.excluir');

    Route::delete('atendimento/delete', [App\Http\Controllers\TabAtendimentosController::class, 'destroy'])->name('atendimento.delete');

    // Fim das rotas em relação ao atedimento

    Route::get('uf-municipio/{sgl_uf?}/{nom_municipio?}', [App\Http\Controllers\MunicipiosController::class, 'index'])->name('uf-municipio');

    Route::get('empreendimento/{cod_mdr}', [App\Http\Controllers\TabCarteiraInvestimentoMidrController::class, 'show'])->name('empreendimento');

    Route::get('dashboard-clientes', [\App\Http\Controllers\UsersController::class, 'dashboardClientes'])->name('dashboard-clientes');

    Route::match(['get'], 'clientes/create', [\App\Http\Controllers\UsersController::class, 'create'])->name('cliente.create');

    Route::match(['put'], 'clientes/store', [\App\Http\Controllers\UsersController::class, 'store'])->name('cliente.store');

    Route::match(['get'], 'cliente/{cod_user}/edit', [\App\Http\Controllers\UsersController::class, 'edit'])->name('cliente.editar');

    Route::match(['put'], 'cliente/{cod_user}', [\App\Http\Controllers\UsersController::class, 'update'])->name('cliente.update');

    Route::match(['get', 'post'], '/resetar-senha/{cod_user}', [\App\Http\Controllers\UsersController::class, 'resetarSenha'])->name('resetar-senha');

    Route::get('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    Route::match(['get', 'post'], 'relatorios', [\App\Http\Controllers\RelatoriosController::class, 'index'])
        ->where('sgl_partido', '.*')->name('relatorios');

    Route::match(['get', 'post'], 'relatorios/resultado-pagina', [\App\Http\Controllers\RelatoriosController::class, 'resultadoPagina'])
        ->where('sgl_partido', '.*')->name('relatorios.pagina');

    Route::match(['get', 'post'], 'relatorios/word', [\App\Http\Controllers\RelatoriosController::class, 'carometroWordPorUF'])
        ->where('sgl_partido', '.*')->name('relatorios.carometro.uf.word');

    // Início rotas dos relatórios de carômetros
    Route::match(['get', 'post'], 'relatorios/carometro/partido/{sgl_partido}/{dsc_casa?}/{sgl_uf_representante?}', [\App\Http\Controllers\RelatoriosController::class, 'carometroPorPartido'])
        ->where('sgl_partido', '.*')->name('relatorios.carometro.partido');

    Route::match(['get', 'post'], 'relatorios/carometro/uf/{sgl_partido?}/{dsc_casa?}/{sgl_uf_representante}', [\App\Http\Controllers\RelatoriosController::class, 'carometroPorUF'])
        ->where('sgl_partido', '.*')->name('relatorios.carometro.uf');

    Route::match(['get', 'post'], 'relatorios/carometro/partido-word/{sgl_partido}/{dsc_casa?}/{sgl_uf_representante?}', [\App\Http\Controllers\RelatoriosController::class, 'carometroWordPorPartido'])->name('relatorios.carometro.partido');

    Route::match(['get', 'post'], 'relatorios/carometro/uf-word/{sgl_partido?}/{dsc_casa?}/{sgl_uf_representante}', [\App\Http\Controllers\RelatoriosController::class, 'carometroWordPorUF'])->name('relatorios.carometro.uf.word');

    Route::get('relatorios/exportar/excel/dados-parlamentares', [\App\Http\Controllers\RelatoriosController::class, 'getExportDadosParlamentaresFederais'])->name('relatorios.get.export-dados-parlamentares');
    // Fim rotas dos relatórios de carômetros

    // Início rotas dos fundos
    Route::match(['get', 'post'], 'fundos', [\App\Http\Controllers\FundosController::class, 'index'])->name('fundos');
    Route::match(['get'], 'fundos/pdf-relatorios-resumo-fundos-por-tipo-investimento-linha-financiamento-e-finalidade-operacao', [\App\Http\Controllers\FundosController::class, 'resumoFundosPorTipoInvestimentoLinhaFinanciamentoEFinalidadeOperacao'])->name('fundos.pdf-relatorios-resumo-fundos-por-tipo-investimento-linha-financiamento-e-finalidade-operacao');
    Route::match(['get'], 'fundos/gerar-pdf/{filtros}', [\App\Http\Controllers\FundosController::class, 'gerarPdf'])->name('fundos.gerar-pdf');
    // Fim rotas dos fundos

    // Início rotas dos fundos de desenvolvimento regional
    Route::match(['get', 'post'], 'fdr', [\App\Http\Controllers\Snfi\TabFundosDesenvolvimentoRegionalController::class, 'index'])->name('fdr');
    // Fim rotas dos fundos de desenvolvimento regional

    // Início rotas do novo PAC
    Route::match(['get', 'post'], 'novo-pac/{codigoUnidade?}', [\App\Http\Controllers\TabNovoPacController::class, 'index'])->name('novo-pac');
    Route::get('novo-pac/{cod_pac}/edit/{aba?}', [\App\Http\Controllers\TabNovoPacController::class, 'edit'])->name('novo-pac.edit');
    Route::put('novo-pac/{cod_pac}', [\App\Http\Controllers\TabNovoPacController::class, 'update'])->name('novo-pac.update');
    // Route::post('novo-pac/orcamentario-financeiro/store', [\App\Http\Controllers\TabNovoPacController::class, 'storeOrcamentarioFinanceiro'])->name('novo-pac.//orcamentario-financeiro.store');

    Route::get('novo-pac/exportar/excel/orcamentario-financeiro/{ano}', [\App\Http\Controllers\TabNovoPacController::class, 'exportNovoPacOrcamentarioFinanceiro'])->name('novo-pac.export-orcamentario-financeiro');

    Route::get('novo-pac/auditoria/gravar-auditoria/{cod_pac?}', [\App\Http\Controllers\TabNovoPacController::class, 'empreendimentosNovoPacParaAuditoria'])->name('novo-pac.gravar-auditoria-pac');

    Route::post('novo-pac/auditoria/show/modal', [\App\Http\Controllers\TabNovoPacController::class, 'showModalContent'])->name('novo-pac.auditoria');
    // Fim rotas do novo PAC

    // Início rotas das Emendas Parlamentares
    Route::match(['get', 'post'], 'emendas-parlamentares', [\App\Http\Controllers\EmendasParlamentaresController::class, 'index'])->name('emendas-parlamentares');
    // Fim rotas das Emendas Parlamentares

    Route::get('sem-permissao', function () {
        return view('acesso-negado');
    })->name('acesso-negado');

});

Route::post('novo-pac/orcamentario-financeiro/store', [\App\Http\Controllers\TabNovoPacController::class, 'storeOrcamentarioFinanceiro'])->name('novo-pac.orcamentario-financeiro-store');

Route::delete('novo-pac/orcamentario-financeiro/destroy', [\App\Http\Controllers\TabNovoPacController::class, 'destroy'])->name('novo-pac.orcamentario-financeiro-destroy');

Route::get('cliente-inativo', function () {
    return view('clientes.inativo');
})->name('cliente-inativo');

Route::POST('update-senha', [App\Http\Controllers\UsersController::class, 'updateSenha'])->name('update-senha');

Route::get('trocar-senha', [App\Http\Controllers\UsersController::class, 'paginaTrocarSenha'])->name('trocar-senha');

Auth::routes();

Route::get('/home', [App\Http\Controllers\ParlamentarController::class, 'index'])->name('home');

Route::get('theme/{theme}', 'App\Http\Controllers\helperController@updateTheme')->name('theme.update');

Route::get('atualizarLegislaturaApiCamaraDeputados', 'App\Http\Controllers\helperController@atualizarLegislaturaApiCamaraDeputados')->name('atualizar.legislatura');

Route::get('atualizarDeputadosPorLegislaturaApiCamaraDeputados', 'App\Http\Controllers\helperController@atualizarDeputadosPorLegislaturaApiCamaraDeputados')->name('atualizar.deputadosporlegislatura');

Route::get('gravarLegislaturasDeputados', 'App\Http\Controllers\helperController@gravarLegislaturasDeputados')->name('atualizar.legislaturas.deputados');

Route::get('gravarLegislaturaSenadores', 'App\Http\Controllers\helperController@gravarLegislaturaSenadores')->name('atualizar.legislaturas.senadores');

Route::get('atualizarMesaDiretoraDeputados', 'App\Http\Controllers\helperController@atualizarMesaDiretoraDeputados')->name('atualizar.mesa-diretora.deputados');

Route::get('atualizarMesaSenado', 'App\Http\Controllers\helperController@atualizarMesaSenado')->name('atualizar.mesa-senado');

Route::get('atualizarDeputadosPorIdDeputado', 'App\Http\Controllers\helperController@atualizarDeputadosPorIdDeputado')->name('atualizar.deputadosporiddeputado');

Route::get('atualizarListaAtualSenadores', 'App\Http\Controllers\helperController@atualizarListaAtualSenadores')->name('atualizar.atuaissenadores');

Route::get('listaColegiados', 'App\Http\Controllers\helperController@listaColegiados')->name('atualizar.colegiados');

Route::get('atualizarDadosCondensadosDeputadosESenadores', 'App\Http\Controllers\helperController@atualizarDadosCondensadosDeputadosESenadores')->name('atualizar.tabela_condensada');

Route::get('cargosSenadores/{codigoParlamentar}', 'App\Http\Controllers\helperController@cargosSenadores')->name('atualizar.cargossenadores');

Route::get('montarTabelaMunicipios', [App\Http\Controllers\MunicipiosController::class, 'montarTabelaMunicipios'])->name('municipios.senadores');

Route::get('readContentApiSiorgPai', 'App\Http\Controllers\helperController@readContentApiSiorgPai')->name('atualizar.siorg');

Route::get('gravarIDHViaIbge', [App\Http\Controllers\TabMunicipioIndicadoresController::class, 'gravarIDHViaIbge'])->name('atualizar.idh');

Route::get('atualizacaoApiIbge', [App\Http\Controllers\helperController::class, 'atualizacaoApiIbge'])->name('atualizaar-api-ibge');

Route::get('fotos-tse', [App\Http\Controllers\helperController::class, 'fotosTse'])->name('atualizar.fotos.tse');

Route::get('alterar-email-para-crypt', [App\Http\Controllers\Auth\LoginController::class, 'alterarEmailParaCrypt']);

Route::get('downloadJsonSenado', 'App\Http\Controllers\helperController@downloadJsonSenado')->name('atualizar.download-json-senado');

Route::get('atualizar-parlamentares', 'App\Http\Controllers\helperController@atualizarDadosApiCamaraEApiSenado')->name('atualizar.atualizardadosapicamaraesenado');

Route::get('comissoesSenadores', 'App\Http\Controllers\helperController@comissoesSenadores')->name('atualizar.atualizacomissoessenadores');

Route::get('liderancasSenadores', 'App\Http\Controllers\helperController@liderancasSenadores')->name('atualizar.liderancassenadores');

Route::get('atualizarTabParlamentares', 'App\Http\Controllers\helperController@atualizarTabParlamentares')->name('atualizar.tab_parlamentares');

Route::get('relatorios/download-exportar/excel/dados-parlamentares', [\App\Http\Controllers\helperController::class, 'downloadExportDadosParlamentaresFederais'])->name('relatorios.export-dados-parlamentares');

Route::get('downloadCaixaZip', 'App\Http\Controllers\ImportController@downloadCaixaZip')->name('atualizar.caixa');

Route::post('/login', [LoginController::class, 'login']);

// Route::get('/register', function () {
//     return redirect('/login');
// });
