<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\TabOrganizacaoController;
use App\Http\Controllers\TabAtendimentoDemandaStatusController;
use App\Http\Controllers\TabAuditController;
use App\Http\Controllers\AtualizarOuCriarPorModeloDadosController;
use App\Http\Controllers\AuditController;

use App\Models\TabAtendimentoDemandas;
use App\Models\TabLogErros;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TabAtendimentoDemandasController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getDemandasPorAtendimento($codAtendimento = null)
    {

        if (Auth::check()) {

            if (isset($codAtendimento) && !is_null($codAtendimento) && $codAtendimento != '') {

                return TabAtendimentoDemandas::where('cod_atendimento', $codAtendimento)
                    ->get();

            } else {
                return [];
            }

        } else {
            return [];
        }

    }

    public function instanciarTabOrganizacaoController()
    {
        return new TabOrganizacaoController;
    }

    public function instanciarTabAtendimentoDemandaStatusController()
    {
        return new TabAtendimentoDemandaStatusController;
    }

    public function instanciarTabAuditController()
    {
        return new TabAuditController;
    }

    public function instanciarAtualizarOuCriarPorModeloDadosController()
    {
        return new AtualizarOuCriarPorModeloDadosController;
    }

    public function instanciarAuditController()
    {
        return new AuditController;
    }

    public function incluirDemanda($dscDemanda = null, $codigoUnidade = null, $dtePrazo = null, $codStatusDemanda = null, $codAtendimento = null)
    {

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        // Início da parte de inclusão de uma nova demanda recebida

        if (isset($dscDemanda) && !is_null($dscDemanda) && $dscDemanda != '' && isset($codigoUnidade) && !is_null($codigoUnidade) && $codigoUnidade != '' && isset($dtePrazo) && !is_null($dtePrazo) && $dtePrazo != '' && isset($codStatusDemanda) && !is_null($codStatusDemanda) && $codStatusDemanda != '' && isset($codAtendimento) && !is_null($codAtendimento) && $codAtendimento != '') {

            $id = [];
            $campos = [];

            $nomeProcedimento = 'Gravar dados de nova demanda recebida de um determinado atendimento';
            $schema = 'midr_gestao';
            $table = 'tab_atendimento_demandas';
            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

            $campos['dsc_demanda'] = $dscDemanda;
            $campos['codigoUnidade'] = $codigoUnidade;
            $campos['dte_prazo'] = $dtePrazo;
            $campos['cod_status_demanda'] = $codStatusDemanda;
            $campos['cod_atendimento'] = $codAtendimento;

            $chavePrimaria = $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

            return $this->montarDivDemandas($codAtendimento);

        } else {
            return false;
        }

        // Fim da parte de inclusão de uma nova demanda recebida
    }

    public function update(Request $request)
    {
        $input = $request->all();

        $fazerJsonFotos = json_encode($input);

        Storage::put('a.json', $fazerJsonFotos);

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        $id = [];
        $campos = [];

        $nomeProcedimento = 'Gravar dados da demanda do atendimento';
        $schema = 'midr_gestao';
        $table = 'tab_atendimento_demandas';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $id['cod_demanda_atendimento'] = $input['cod_demanda_atendimento'];

        foreach ($input as $key => $value) {

            if ($key != '_token' && $key != 'cod_atendimento') {

                $campos[$key] = $value;

            }

        }

        $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

    }

    public function getAuditoriaPorChavePrimaria($chavePrimaria = null)
    {

        $model = 'App\Models\TabAtendimentoDemandas';

        $chaves = $this->getPKsDemandasPorCodAtendimento($chavePrimaria);

        $audit = $this->instanciarAuditController();

        if (isset($chavePrimaria) && !is_null($chavePrimaria) && $chavePrimaria != '') {

            return $audit->getAuditoriaPorTableNameEChavePrimaria($model, $chaves);

        } else {
            return [];
        }

    }

    public function getPKsDemandasPorCodAtendimento($codAtendimento = null)
    {
        $chaves = TabAtendimentoDemandas::select('cod_demanda_atendimento')
            ->where('cod_atendimento', $codAtendimento)
            ->get();

        return $chaves->toArray();
    }

    public function getPKDemandasPorCodAtendimento($codAtendimento = null)
    {
        return TabAtendimentoDemandas::select('cod_demanda_atendimento')
            ->where('cod_atendimento', $codAtendimento)
            ->get();
    }

    public function destroyDemandasPorPK($codDemandaAtendimento = null)
    {
        TabAtendimentoDemandas::destroy($codDemandaAtendimento);
    }

    public function excluirDemanda($codDemandaAtendimento = null, $codAtendimento = null)
    {

        // Início da parte de excluir uma demanda

        if (isset($codDemandaAtendimento) && !is_null($codDemandaAtendimento) && $codDemandaAtendimento != '') {

            $excluir = TabAtendimentoDemandas::find($codDemandaAtendimento);

            $excluir->delete();

        }

        // Fim da parte de excluir uma demanda

        return $this->montarDivDemandas($codAtendimento);
    }

    public function montarDivDemandas($codAtendimento = null)
    {

        $tabOrganizacao = $this->instanciarTabOrganizacaoController();
        $tabAtendimentoDemandaStatus = $this->instanciarTabAtendimentoDemandaStatusController();

        $responsaveisDemanda = $tabOrganizacao->getPluckOrganizacaoResponsavelDemanda();
        $statusDemanda = $tabAtendimentoDemandaStatus->getPluckStatus();
        $getStatus = $tabAtendimentoDemandaStatus->getStatus();

        $retorno = null;

        $demandas = TabAtendimentoDemandas::where('cod_atendimento', $codAtendimento)
            ->get();

        $contDemanda = 1;

        foreach ($demandas as $demanda) {

            $retorno .= '<form id="formEditarDemanda' . $demanda->cod_demanda_atendimento . '" class="" method="post">
                            <div class="row">';

            $retorno .= csrf_field();

            $retorno .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left text-bold">
                            <span class="font-numero">' . $contDemanda . '</span>ª Demanda recebida
                        </div>';

            $retorno .= '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">
                            <label for="" class="form-label">Descrição da
                                demanda</label>';

            $retorno .= \Form::textarea('dsc_demanda', $demanda->dsc_demanda, [
                'class' => 'form-control',
                'rows' => 1,
                'id' => 'dsc_demanda',
                'placeholder' => 'Digite a descrição da demanda',
                'rows' => 2,
                'cols' => 50,
            ]);
            $retorno .= '</div>';

            $retorno .= '<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 mb-4 text-left">
                            <label for="" class="form-label">Área Responsável pela demanda</label>';

            $retorno .= \Form::select('codigoUnidade', $responsaveisDemanda, $demanda->codigoUnidade, [
                'id' => 'codigoUnidade',
                'class' => 'form-control',
                'style' => 'cursor: pointer;',
                'placeholder' => 'Selecione',
            ]);
            $retorno .= '</div>';

            $retorno .= '<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 mb-4 text-left">
                            <label for="" class="form-label">Prazo estimado de concluir a demanda</label>';

            $retorno .= \Form::date('dte_prazo', $demanda->dte_prazo, [
                'class' => 'form-control text-dark text-right font-numero date',
                'id' => 'dte_prazo',
                'style' => 'cursor: pointer',
                'autocomplete' => 'off',
            ]);

            $retorno .= '</div>';

            $retorno .= '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 mb-4 text-left">
                            <label for="" class="form-label">Status da demanda</label>';

            $retorno .= \Form::select('cod_status_demanda', $statusDemanda, $demanda->cod_status_demanda, [
                'id' => 'cod_status_demanda',
                'class' => 'form-control',
                'style' => 'cursor: pointer;',
            ]);

            $retorno .= '</div>';

            $retorno .= '<div id="buttonsFileAddRemove" class="col-1 col-sm-1 col-md-2 col-lg-3 mb-4 pb-4 text-left"
                    style="padding-top: 2.1rem !Important; padding-left: 0rem !Important;">

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-save text-white"></i> Confirmar alteração
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#modalConfirmarExclusaoDemanda_' . $demanda->cod_demanda_atendimento . '">
                        <i class="fas fa-trash-alt"></i> Excluir demanda
                    </button>

                </div>';

            // Início da modal de confirmação de exclusão do convidado

            $retorno .= '<div class="modal"
                            id="modalConfirmarExclusaoDemanda_' . $demanda->cod_demanda_atendimento . '"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                            data-bs-backdrop="static" data-bs-keyboard="false" style="padding-top: 150px!Important;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                        <p class="modal-title text-white"
                                            style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                            Excluir Demanda
                                        </p>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            Deseja realmente excluir esta demanda recebida?
                                        </p>
                                        <p>
                                            <span class="text-bold">' . $demanda->dsc_demanda . '</span>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                            Fechar
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="javascript: excluir_demanda(\'' . $demanda->cod_demanda_atendimento . '\', \'' . $codAtendimento . '\');"
                                                        data-bs-dismiss="modal">
                                            Sim, quero excluir!
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>';

            $retorno .= "</div>";

            $retorno .= \Form::hidden('cod_demanda_atendimento', $demanda->cod_demanda_atendimento);
            $retorno .= \Form::hidden('cod_atendimento', $codAtendimento);

            $retorno .= '</form>';

            $retorno .= '<script>
                            $(document).ready(function() {
                                $("#formEditarDemanda' . $demanda->cod_demanda_atendimento . '").submit(function(event) {

                                    event.preventDefault();
                                    var formData = new FormData(this);

                                    $.ajax({
                                        headers: {
                                            "X-CSRF-TOKEN": $(\'meta[name="_token"]\').attr("content")
                                        },
                                        url: "' . url("atendimento/demanda/update") . '",
                                        data: formData,
                                        type: "post",
                                        async: false,
                                        processData: false,
                                        contentType: false,
                                        success: function(response) {

                                            setTimeout(function() {
                                                $("#divColRetorno_demadas").fadeIn("slow");
                                            }, 100);

                                            setTimeout(function() {
                                                $("#divColRetorno_demadas").fadeOut("slow");
                                            }, 3900);

                                        }
                                    });

                                });
                            });
                        </script>';

            $contDemanda++;

        }

        return $retorno;
    }
}
