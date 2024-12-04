<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabObservacoesParlamentar;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\QueryException;
use App\Models\TabLogErros;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\TabParlamentaresController;
use App\Http\Controllers\TabObservacaoParlamentarAssuntosController;

class TabParlamentarObservacoesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getObservacao()
    {
        return TabObservacoesParlamentar::orderBy('created_at', 'desc')
            ->get();
    }

    public function getObservacaoPorCodParlamentar($codParlamentar = null)
    {

        if (isset($codParlamentar) && !is_null($codParlamentar) && $codParlamentar != '') {
            return TabObservacoesParlamentar::where('cod_parlamentar', $codParlamentar)
                ->with('assunto')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            return [];
        }

    }

    public function store($codAssunto = null, $txtObservacaoParlamentar = null, $codParlamentar = null, $codObservacaoParlamentar = null, $blnExcluir = null)
    {

        if (auth()->check()) {
            // Início da parte de consulta ao perfil de acesso do cliente
            $user = Auth::user();

            $perfil = $user->perfil;
            $bln_acesso_inrestrito = $perfil->bln_acesso_inrestrito;
            // Fim da parte de consulta ao perfil de acesso do cliente

            if ($bln_acesso_inrestrito == 1) {

                $id = [];
                $campos = [];

                if (isset($codAssunto) && !is_null($codAssunto) && $codAssunto != '' && isset($txtObservacaoParlamentar) && !is_null($txtObservacaoParlamentar) && $txtObservacaoParlamentar != '' && isset($codParlamentar) && !is_null($codParlamentar) && $codParlamentar != '') {

                    // dd("Aqui 8", $codAssunto, $txtObservacaoParlamentar, $codParlamentar, $codObservacaoParlamentar, $blnExcluir);

                    if (isset($codObservacaoParlamentar) && !is_null($codObservacaoParlamentar) && $codObservacaoParlamentar != '') {

                        if (isset($blnExcluir) && !is_null($blnExcluir) && $blnExcluir != '' && $blnExcluir === 'Sim') {

                            try {
                                $delete = TabObservacoesParlamentar::find($codObservacaoParlamentar);

                                $delete->delete();

                                return $this->montarConteudoDivObservacoes($codParlamentar);
                            } catch (Illuminate\Database\QueryException $e) {
                                TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
                            }

                        } else {

                            $id['cod_observacao_parlamentar'] = $codObservacaoParlamentar;

                            $campos['cod_assunto'] = $codAssunto;
                            $campos['txt_observacao_parlamentar'] = $txtObservacaoParlamentar;
                            $campos['cod_parlamentar'] = $codParlamentar;

                            if (!Uuid::isValid($codAssunto)) {

                                $nomeProcedimentoIterno = 'Gravar novo assunto';
                                $schemaIterno = 'midr_gestao';
                                $tableIterno = 'tab_observacao_parlamentar_assuntos';
                                $modelIterno = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableIterno);

                                $campos['cod_assunto'] = $this->atualizarOuCriarPorModeloDadosComRetornoPk($modelIterno, '', ['dsc_assunto' => primeiraLetraMaiuscula($codAssunto)]);

                            }

                            $table = 'tab_observacoes_parlamentar';
                            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                            $this->atualizarOuCriarPorModeloDados($model, $id, $campos);

                            return $this->montarConteudoDivObservacoes($codParlamentar);

                        }

                    } else {

                        $campos['cod_assunto'] = $codAssunto;
                        $campos['txt_observacao_parlamentar'] = $txtObservacaoParlamentar;
                        $campos['cod_parlamentar'] = $codParlamentar;

                        if (!Uuid::isValid($codAssunto)) {

                            $nomeProcedimentoIterno = 'Gravar novo assunto';
                            $schemaIterno = 'midr_gestao';
                            $tableIterno = 'tab_observacao_parlamentar_assuntos';
                            $modelIterno = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableIterno);

                            $campos['cod_assunto'] = $this->atualizarOuCriarPorModeloDadosComRetornoPk($modelIterno, '', ['dsc_assunto' => primeiraLetraMaiuscula($codAssunto)]);

                        }

                        $table = 'tab_observacoes_parlamentar';
                        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                        $this->atualizarOuCriarPorModeloDados($model, '', $campos);

                        return $this->montarConteudoDivObservacoes($codParlamentar);

                    }

                }

            } else {
                return 'Cliente sem permissão para executar esta função.';
            }

        } else {
            // O usuário não está autenticado
            // Lidar com o caso de usuário não autenticado

            return 'Cliente não está logado no sistema';
        }

    }

    protected function atualizarOuCriarPorModeloDados($model = null, $id = [], $campos = [])
    {
        try {
            $registro = null;

            if (isset($id) && !is_null($id) && $id != '' && count($id) > 0) {
                $model::updateOrCreate($id, $campos);
            } else {
                $model::updateOrCreate($campos);
            }
            return true;
        } catch (Illuminate\Database\QueryException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
            return false;
        }
    }



    protected function atualizarOuCriarPorModeloDadosComRetornoPk($model = null, $id = [], $campos = [])
    {
        try {
            $registro = null;

            if (isset($id) && !is_null($id) && $id != '' && count($id) > 0) {
                $registro = $model::updateOrCreate($id, $campos);
            } else {
                $registro = $model::updateOrCreate($campos);
            }

            // Aqui você pode acessar o valor da chave primária
            $chavePrimaria = $registro->getKey(); // Isso assume que a chave primária é 'id'

            return $chavePrimaria;
        } catch (Illuminate\Database\QueryException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }
    }

    public function instanciarTabParlamentaresController()
    {
        return new TabParlamentaresController;
    }

    public function instanciarTabObservacaoParlamentarAssuntosController()
    {
        return new TabObservacaoParlamentarAssuntosController;
    }

    public function montarConteudoDivObservacoes($codParlamentar = null)
    {

        $content = null;

        if (isset($codParlamentar) && !is_null($codParlamentar) && $codParlamentar != '') {

            $tabParlamentaresController = $this->instanciarTabParlamentaresController();

            try {

                $getParlamentar = $tabParlamentaresController->getParlamentar($codParlamentar);
                $tabObservacaoParlamentarAssuntos = $this->instanciarTabObservacaoParlamentarAssuntosController();

                $observacao_cod_assunto_pluck = $tabObservacaoParlamentarAssuntos->getPluckAssuntos();

                $observacoes = $this->getObservacaoPorCodParlamentar($codParlamentar);

                $assuntos = [];
                foreach ($observacoes as $key => $value) {
                    if (!in_array($value->assunto->dsc_assunto, $assuntos)) {
                        array_push($assuntos, $value->assunto->dsc_assunto);
                    }
                }

                sort($assuntos);

                $content .= '<div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2 mb-3 collapse" id="collapseFormNovaObservacao">

                                        <div class="card border-primary">

                                        <div class="card-body border-primary">

                                        <div class="row">

                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 mb-4 text-left">
                                            <label for="observacao_cod_assundo" class="form-label">Assunto da observação</label>';

                $content .= \Form::select('cod_assunto', $observacao_cod_assunto_pluck, null, [
                    'class' => 'form-control text-dark',
                    'style' => 'cursor: pointer; width: 100% !Important;',
                    'id' => 'observacao_cod_assundo',
                    'autocomplete' => 'off',
                    'placeholder' => 'Selecione ou digite um novo assunto',
                    'required' => 'required',
                ]);

                $content .= '<div class="form-text textoPequeno text-secondary">
                                Se o tópico desejado não estiver listado, você pode simplesmente
                                digitá-lo e
                                ao
                                finalizar confirme selecionando-o.
                            </div>

                            <script>
                                $(document).ready(function() {
                                    var select = $(\'#observacao_cod_assundo\').select2({
                                        tags: true,
                                        tokenSeparators: [\',\'],
                                        createTag: function(params) {
                                            return {
                                                id: params.term,
                                                text: params.term,
                                                newTag: true
                                            };
                                        },
                                        templateResult: function(data) {
                                            if (data.newTag) {
                                                return $(\'<span class="new-tag">\' + data.text + \'</span>\');
                                                alert(\'Ajax\');

                                            }
                                            return data.text;
                                        }
                                    });

                                    // Intercepta a abertura do dropdown do Select2 para permitir edição da tag
                                    select.on(\'select2:open\', function() {
                                        $(".new-tag").each(function() {
                                            var $this = $(this);
                                            $this.replaceWith($(\'<option>\', {
                                                value: $this.text(),
                                                text: $this.text(),
                                                selected: true
                                            }));
                                        });
                                    });
                                });
                            </script>

                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 mb-4 text-left">
                            <label for="observacao_cod_assundo" class="form-label">Texto da observação</label>';

                $content .= \Form::textarea('txt_observacao_parlamentar', null, [
                    'class' => 'form-control text-dark',
                    'id' => 'observacao_txt_observacao_parlamentar',
                    'placeholder' => 'Digite a observação',
                    'rows' => 2,
                    'cols' => 50,
                    'required' => 'required',
                ]);

                $content .= '</div>

                    </div>
                </div>
                <div class="card-footer border-primary bg-light text-right">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse"
                        data-bs-target="#collapseFormNovaObservacao" aria-expanded="false"
                        aria-controls="collapseFormNovaObservacao">
                        Cancelar</button>
                    <button class="btn btn-primary"
                        onclick="javascript: gravar_observacao($(\'#observacao_cod_assundo\').val(), $(\'#observacao_txt_observacao_parlamentar\').val(),\'' . $codParlamentar . '\',\'\',\'\');">
                        Salvar observação
                    </button>
                </div>
            </div>

        </div>
    </div>';

                if ($observacoes->count() > 0) {

                    $content .= '<div class="row">
                    ';

                    $contObservacao = 1;

                    foreach ($assuntos as $assunto) {

                        $content .= '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
                        <ul class="list-group">
                            <li class="list-group-item ';

                        isset($getParlamentar) && $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? $content .= 'bg-camara-sub-titulo-modal' : $content .= 'bg-senado-sub-titulo-modal';

                        $content .= ' p-1 pl-3" style="border: 0px solid #e5e5e5;">
                            <span class="font-numero">' . $contObservacao . '</span>. ' . $assunto . '</li>';

                        foreach ($observacoes as $key => $value) {

                            if ($assunto === $value->assunto->dsc_assunto) {

                                $content .= '<li class="list-group-item" style="border: 1px solid #e5e5e5;">';

                                $content .= $value->txt_observacao_parlamentar;

                                $content .= '<span data-bs-toggle="modal"
                                        data-bs-target="#modalEditarObservacao' . $value->cod_observacao_parlamentar . '"
                                        class="d-print-none">
                                        <i class="fas fa-edit text-primary d-print-none" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Editar observação"
                                            style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                    </span>';

                                $content .= '<!-- Modal -->
                                    <div class="modal fade" id="modalEditarObservacao' . $value->cod_observacao_parlamentar . '"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                        data-bs-backdrop="static" data-bs-keyboard="false"
                                        style="padding-top: 150px!Important;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header"
                                                    style="background: linear-gradient(135deg,#898989 0%,#acacac 100%);color: white;">
                                                    <p class="modal-title text-white"
                                                        style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                        Editar observação</p>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">

                                                        <div
                                                            class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-4 text-left">
                                                            <label for="observacao_cod_assundo"
                                                                class="form-label">Assunto da observação</label>';

                                $content .= \Form::select('cod_assunto', $observacao_cod_assunto_pluck, $value->cod_assunto, [
                                    'class' => 'form-control text-dark',
                                    'style' => 'cursor: pointer; width: 100% !Important;',
                                    'id' => 'observacao_cod_assundo' . $value->cod_observacao_parlamentar,
                                    'autocomplete' => 'off',
                                    'placeholder' => 'Selecione ou digite um novo assunto',
                                    'required' => 'required',
                                ]);
                                $content .= '<div class="form-text textoPequeno text-secondary">
                                                                Se o tópico desejado não estiver listado, você pode
                                                                simplesmente
                                                                digitá-lo e
                                                                ao
                                                                finalizar confirme selecionando-o.
                                                            </div>

                                                            <script>
                                                                $(document).ready(function() {
                                                                    // Inicialize o Select2
                                                                    var select = $(\'#observacao_cod_assundo' . $value->cod_observacao_parlamentar . '\').select2({
                                                                        dropdownParent: $("#modalEditarObservacao' . $value->cod_observacao_parlamentar . '"),
                                                                        tags: true,
                                                                        tokenSeparators: [\',\'],
                                                                        createTag: function(params) {
                                                                            return {
                                                                                id: params.term,
                                                                                text: params.term,
                                                                                newTag: true
                                                                            };
                                                                        },
                                                                        templateResult: function(data) {
                                                                            if (data.newTag) {
                                                                                return $(\'<span class="new-tag">\' + data.text + \'</span>\');
                                                                                alert(\'Ajax\');

                                                                            }
                                                                            return data.text;
                                                                        }
                                                                    });

                                                                    // Intercepta a abertura do dropdown do Select2 para permitir edição da tag
                                                                    select.on(\'select2:open\', function() {
                                                                        $(".new-tag").each(function() {
                                                                            var $this = $(this);
                                                                            $this.replaceWith($(\'<option>\', {
                                                                                value: $this.text(),
                                                                                text: $this.text(),
                                                                                selected: true
                                                                            }));
                                                                        });
                                                                    });
                                                                });
                                                            </script>

                                                        </div>

                                                        <div
                                                            class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-4 text-left">
                                                            <label for="observacao_cod_assundo" class="form-label">Texto
                                                                da observação</label>';

                                $content .= \Form::textarea('txt_observacao_parlamentar', $value->txt_observacao_parlamentar, [
                                    'class' => 'form-control text-dark',
                                    'id' => 'observacao_txt_observacao_parlamentar' . $value->cod_observacao_parlamentar,
                                    'placeholder' => 'Digite a observação',
                                    'rows' => 2,
                                    'cols' => 50,
                                    'required' => 'required',
                                ]);

                                $content .= '</div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="javascript: gravar_observacao($(\'#observacao_cod_assundo' . $value->cod_observacao_parlamentar . '\').val(), $(\'#observacao_txt_observacao_parlamentar' . $value->cod_observacao_parlamentar . '\').val(),\'' . $codParlamentar . '\', \'' . $value->cod_observacao_parlamentar . '\', \'\');"
                                                        data-bs-dismiss="modal">Alterar</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>';

                                // Início da parte de exclusão da observação

                                $content .= '<span data-bs-toggle="modal"
                                        data-bs-target="#modalExcluirObservacao' . $value->cod_observacao_parlamentar . '"
                                        class="d-print-none">
                                        <i class="fas fa-trash-alt text-danger" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Excluir observação"
                                            style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                    </span>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modalExcluirObservacao' . $value->cod_observacao_parlamentar . '"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                        data-bs-backdrop="static" data-bs-keyboard="false"
                                        style="padding-top: 150px!Important;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header"
                                                    style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                    <p class="modal-title text-white"
                                                        style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                        Excluir observação</p>
                                                </div>
                                                <div class="modal-body">

                                                    <p>
                                                        Observação: <span class="textoNormalTabela">' . $value->txt_observacao_parlamentar . '</span>
                                                    </p>

                                                    <p>
                                                        Assunto: <span class="textoNormalTabela">' . $value->assunto->dsc_assunto . '</span>
                                                    </p>

                                                    <p class="">
                                                        Deseja realmente excluir esta observação?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="javascript: gravar_observacao($(\'#observacao_cod_assundo' . $value->cod_observacao_parlamentar . '\').val(), $(\'#observacao_txt_observacao_parlamentar' . $value->cod_observacao_parlamentar . '\').val(),\'' . $codParlamentar . '\', \'' . $value->cod_observacao_parlamentar . '\', \'Sim\');"
                                                        data-bs-dismiss="modal">Sim,
                                                        excluir</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>';

                                // Fim da parte de exclusão da observação

                                $content .= '</li>';

                            }

                        }

                        $content .= '</ul>
                        </div>';

                        $contObservacao++;

                    }

                    $content .= '</div>';

                } else {

                    $content .= '<p>Não há observação cadastrada</p>';

                }

                return $content;

            } catch (Illuminate\Database\QueryException $e) {
                TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
            }

        }
    }

}
