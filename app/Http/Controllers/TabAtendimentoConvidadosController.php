<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\TabAtendimentoInterlocutoresController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\AtualizarOuCriarPorModeloDadosController;

use App\Models\TabAtendimentoConvidados;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TabAtendimentoConvidadosController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function instanciarTabAtendimentoInterlocutoresController()
    {
        return new TabAtendimentoInterlocutoresController;
    }

    public function instanciarAuditController()
    {
        return new AuditController;
    }

    public function instanciarAtualizarOuCriarPorModeloDadosController()
    {
        return new AtualizarOuCriarPorModeloDadosController;
    }

    public function getAuditoriaPorChavePrimaria($chavePrimaria = null)
    {

        $model = 'App\Models\TabAtendimentoConvidados';

        $chaves = $this->getPKsConvidadosPorCodAtendimento($chavePrimaria);

        $audit = $this->instanciarAuditController();

        if (isset($chavePrimaria) && !is_null($chavePrimaria) && $chavePrimaria != '') {

            return $audit->getAuditoriaPorTableNameEChavePrimaria($model, $chaves);

        } else {
            return [];
        }

    }

    public function getPKsConvidadosPorCodAtendimento($codAtendimento = null)
    {
        $chaves = TabAtendimentoConvidados::select('cod_convidado')
            ->where('cod_atendimento', $codAtendimento)
            ->get();

        return $chaves->toArray();
    }

    public function getConvidadosPorAtendimento($codAtendimento = null)
    {

        if (Auth::check()) {

            if (isset($codAtendimento) && !is_null($codAtendimento) && $codAtendimento != '') {

                return TabAtendimentoConvidados::where('cod_atendimento', $codAtendimento)
                    ->get();

            } else {
                return [];
            }

        } else {
            return [];
        }

    }

    public function incluirConvidado($codInterlocutor = null, $nomConvidado = null, $codAtendimento = null)
    {

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        // Início da parte de inclusão de um novo do convidado

        if (isset($codInterlocutor) && !is_null($codInterlocutor) && $codInterlocutor != '' && isset($nomConvidado) && !is_null($nomConvidado) && $nomConvidado != '' && isset($codAtendimento) && !is_null($codAtendimento) && $codAtendimento != '') {

            $id = [];
            $campos = [];

            $nomeProcedimento = 'Gravar dados de novo convidado de um determinado atendimento';
            $schema = 'midr_gestao';
            $table = 'tab_atendimento_convidados';
            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

            $campos['cod_interlocutor'] = $codInterlocutor;
            $campos['nom_convidado'] = $nomConvidado;
            $campos['cod_atendimento'] = $codAtendimento;

            $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

            return $this->montarDivConvidados($codAtendimento);

        } else {
            return false;
        }

        // Fim da parte de inclusão de um novo do convidado
    }
    public function update(Request $request)
    {
        $input = $request->all();

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        $id = [];
        $campos = [];

        $nomeProcedimento = 'Gravar dados do convidado do atendimento';
        $schema = 'midr_gestao';
        $table = 'tab_atendimento_convidados';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $id['cod_convidado'] = $input['cod_convidado'];

        foreach ($input as $key => $value) {

            if ($key != '_token' && $key != 'cod_atendimento') {

                $campos[$key] = $value;

            }

        }

        $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

    }

    public function getPKConvidadosPorCodAtendimento($codAtendimento = null)
    {
        return TabAtendimentoConvidados::select('cod_convidado')
            ->where('cod_atendimento', $codAtendimento)
            ->get();
    }

    public function auditoria($codAtendimento = null)
    {

    }

    public function destroyConvidadosPorPK($codConvidado = null)
    {
        TabAtendimentoConvidados::destroy($codConvidado);
    }

    public function excluirConvidado($codConvidado = null, $codAtendimento = null)
    {

        // Início da parte de exclusão do convidado

        if (isset($codConvidado) && !is_null($codConvidado) && $codConvidado != '') {

            $excluir = TabAtendimentoConvidados::find($codConvidado);

            $excluir->delete();

        }

        // Fim da parte de exclusão do convidado

        return $this->montarDivConvidados($codAtendimento);
    }

    public function montarDivConvidados($codAtendimento = null)
    {

        $cod_interlocutor_pluck = [];

        $tabAtendimentoInterlocutores = $this->instanciarTabAtendimentoInterlocutoresController();

        $cod_interlocutor_pluck = $tabAtendimentoInterlocutores->getPluckInterlocutoresElegiveis();

        $retorno = null;

        $convidados = TabAtendimentoConvidados::where('cod_atendimento', $codAtendimento)
            ->get();

        $contConvidado = 1;

        foreach ($convidados as $convidado) {

            $retorno .= '<form id="formEditarConvidado' . $convidado->cod_convidado . '" class="" method="post">';

            $retorno .= csrf_field();

            $retorno .= "<div class='row'>";

            $retorno .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left text-bold"><span class="font-numero">' . $contConvidado . '</span>º
                                        Convidado(a)</div>';

            $retorno .= "<div class='col-xs-12 col-sm-5 col-md-5 col-lg-5 mb-4 text-left'>
                <label for='' class='form-label'>Cargo do(a) convidado(a)</label>";

            $retorno .= \Form::select('cod_interlocutor', ${'cod_interlocutor_pluck'}, $convidado->cod_interlocutor, [
                'class' => 'form-control text-dark',
                'style' => 'cursor: pointer; width: 100% !Important;',
                'id' => 'cod_interlocutor_convidado_' . $convidado->cod_convidado,
                'autocomplete' => 'off',
                'placeholder' => 'Selecione',
            ]);

            $retorno .= '<script type="text/javascript">
                        $(document).ready(function() {
                            $(\'#cod_interlocutor_convidado_' . $convidado->cod_convidado . '\').select2();
                            $(document).on("select2:open", () => {
                                document.querySelector(".select2-container--open .select2-search__field").focus()
                            });
                        });
                    </script>';

            $retorno .= "</div>";

            $retorno .= "<div class='col-11 col-sm-5 col-md-3 col-lg-3 text-left'>
                <label for='' class='form-label'>Nome convidado(a)</label>";

            $retorno .= \Form::text('nom_convidado', $convidado->nom_convidado, [
                'class' => 'form-control text-dark',
                'id' => 'nom_convidado',
                'placeholder' => 'Digite o nome do(a) convidado(a)',
                'autocomplete' => 'off',
            ]);

            $retorno .= "</div>";

            $retorno .= '<div id="buttonsFileAddRemove" class="col-1 col-sm-1 col-md-4 col-lg-4 m-0 mb-4 p-0 pb-4 text-left"
                    style="padding-top: 2.1rem !Important; padding-left: 0rem !Important;">

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-save text-white"></i> Confirmar alteração
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modalConfirmarExclusaoConvidado_' . $convidado->cod_convidado . '">
                        <i class="fas fa-trash-alt"></i> Excluir convidado
                    </button>

                </div>';

            $retorno .= '<div class="modal"
                                        id="modalConfirmarExclusaoConvidado_' . $convidado->cod_convidado . '"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                        data-bs-backdrop="static" data-bs-keyboard="false"
                                        style="padding-top: 150px!Important;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header"
                                                    style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                    <p class="modal-title text-white"
                                                        style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                        Excluir Convidado</p>
                                                </div>
                                                <div class="modal-body">

                                                    <p>
                                                        Deseja realmente excluir este convidado?
                                                    </p>
midr_gestao.tab_api_camara_deputados_redes_sociais
                                                    <p>
                                                        <span class="text-bold">' . $convidado->nom_convidado . '</span> /
                                                        <span
                                                            class="text-bold">' . $convidado->interlocutor->dsc_interlocutor . '</span>
                                                    </p>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        data-bs-dismiss="modal">Fechar</button>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="javascript: excluir_convidado(\'' . $convidado->cod_convidado . '\', \'' . $codAtendimento . '\');"
                                                        data-bs-dismiss="modal">Sim, quero excluir!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

            $retorno .= "</div>";

            $retorno .= \Form::hidden('cod_convidado', $convidado->cod_convidado);
            $retorno .= \Form::hidden('cod_atendimento', $codAtendimento);

            $retorno .= "</form>";

            $retorno .= '<script>
                            $(document).ready(function() {
                                $("#formEditarConvidado' . $convidado->cod_convidado . '").submit(function(event) {

                                    event.preventDefault();
                                    var formData = new FormData(this);

                                    $.ajax({
                                        headers: {
                                            "X-CSRF-TOKEN": $(\'meta[name="_token"]\').attr("content")
                                        },
                                        url: "' . url("atendimento/convidado/update") . '",
                                        data: formData,
                                        type: "post",
                                        async: false,
                                        processData: false,
                                        contentType: false,
                                        success: function(response) {

                                            setTimeout(function() {
                                                $("#divColRetorno_convidado").fadeIn("slow");
                                            }, 100);

                                            setTimeout(function() {
                                                $("#divColRetorno_convidado").fadeOut("slow");
                                            }, 3900);

                                        }
                                    });

                                });
                            });
                        </script>';

            $contConvidado++;

        }

        return $retorno;
    }
}
