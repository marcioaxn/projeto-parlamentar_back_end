<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabAtendimentos;
use Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

use App\Models\TabLogErros;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\QueryException;

use App\Http\Controllers\TabParlamentaresController;
use App\Http\Controllers\TabUltimaAtualizacaoParlamentaresController;
use App\Http\Controllers\TabAtendimentoAssuntosController;
use App\Http\Controllers\TabAtendimentoConvidadosController;
use App\Http\Controllers\TabAtendimentoDemandasController;
use App\Http\Controllers\TabAtendimentoArquivosController;
use App\Http\Controllers\AtualizarOuCriarPorModeloDadosController;

class TabAtendimentosController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function instanciarTabParlamentaresController()
    {
        return new TabParlamentaresController;
    }

    public function instanciarTabAtendimentoInterlocutoresController()
    {
        return new TabAtendimentoInterlocutoresController;
    }

    public function instanciarTabAtendimentoConvidadosController()
    {
        return new TabAtendimentoConvidadosController;
    }

    public function instanciarTabAtendimentoDemandasController()
    {
        return new TabAtendimentoDemandasController;
    }

    public function instanciarTabAtendimentoArquivosController()
    {
        return new TabAtendimentoArquivosController;
    }

    public function instanciarTabObservacaoParlamentarAssuntosController()
    {
        return new TabObservacaoParlamentarAssuntosController;
    }

    public function instanciarTabAtendimentoAssuntosController()
    {
        return new TabAtendimentoAssuntosController;
    }

    public function instanciarTabAtendimentoCargosController()
    {
        return new TabAtendimentoCargosController;
    }

    public function instanciarTabOrganizacaoController()
    {
        return new TabOrganizacaoController;
    }

    public function instanciarTabAtendimentoDemandaStatusController()
    {
        return new TabAtendimentoDemandaStatusController;
    }

    public function instanciarAtualizarOuCriarPorModeloDadosController()
    {
        return new AtualizarOuCriarPorModeloDadosController;
    }

    public function index()
    {
        $atendimentos = $this->getAtendimentos();

        $atendimentos = $atendimentos->get();

        $estruturaTableAtendimento = $this->getEstruturaTable();

        return view('atendimento.index')
            ->with('atendimentos', $atendimentos)
            ->with('estruturaTableAtendimento', $estruturaTableAtendimento);
    }

    public function getAtendimentos()
    {
        return TabAtendimentos::with('interlocutor', 'assunto', 'quemAtendeu', 'convidados.interlocutor', 'demandas.orgaoResponsavel', 'demandas.status', 'arquivos')
            ->orderBy('dte_atendimento', 'DESC');
    }

    public function getAtendimentosParlamentar($cod_parlamentar = null)
    {
        return TabAtendimentos::where('cod_parlamentar', $cod_parlamentar)
            ->with('interlocutor', 'assunto', 'quemAtendeu', 'convidados.interlocutor', 'demandas.orgaoResponsavel', 'demandas.status', 'arquivos')
            ->orderBy('dte_atendimento', 'DESC')
            ->get();
    }

    public function getDescricaoChaveEstrangeira($columnNameChaveEstrangeira = null, $chaveEstrangeira = null)
    {

        $dadosTabela = $this->getTablePorColumnNameFK($columnNameChaveEstrangeira);

        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($dadosTabela['table']);

        try {
            $result = $model::find($chaveEstrangeira);

            $columnNameDescricao = $dadosTabela['descricao'];

            $result = $result->$columnNameDescricao;

            return $result;
        } catch (Illuminate\Database\QueryException $e) {
            return [];
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }
    }

    protected function getTablePorColumnNameFK($columnNameChaveEstrangeira = null)
    {

        $data = [];

        switch ($columnNameChaveEstrangeira) {
            case 'cod_interlocutor':
                $data['table'] = 'tab_atendimento_interlocutores';
                $data['descricao'] = 'dsc_interlocutor';
                break;
            case 'cod_parlamentar':
                $data['table'] = 'tab_parlamentares';
                $data['descricao'] = 'nom_parlamentar';
                break;
            case 'cod_assunto':
                $data['table'] = 'tab_atendimento_assuntos';
                $data['descricao'] = 'dsc_assunto';
                break;
            case 'cod_cargo':
                $data['table'] = 'tab_atendimento_cargos';
                $data['descricao'] = 'dsc_cargo';
                break;
            case 'codigoUnidade':
                $data['table'] = 'tab_organizacao';
                $data['descricao'] = 'sigla';
                break;
            case 'cod_status_demanda':
                $data['table'] = 'tab_atendimento_demanda_status';
                $data['descricao'] = 'dsc_status';
                break;
            case 'cod_convidado':
                $data['table'] = 'tab_atendimento_convidados';
                $data['descricao'] = 'nom_convidado';
                break;
            case 'cod_demanda_atendimento':
                $data['table'] = 'tab_atendimento_demandas';
                $data['descricao'] = 'dsc_demanda';
                break;
            case 'cod_arquivo':
                $data['table'] = 'tab_atendimento_arquivos';
                $data['descricao'] = 'txt_assunto';
                break;
            case '':
                $data['table'] = '';
                $data['descricao'] = '';
                break;
            case '':
                $data['table'] = '';
                $data['descricao'] = '';
                break;
            case '':
                $data['table'] = '';
                $data['descricao'] = '';
                break;

            default:
                $data['table'] = '';
                $data['descricao'] = $columnNameChaveEstrangeira;
                break;
        }

        return $data;
    }

    public function edit($codAtendimento = null, $codParlamentar = null)
    {

        if (isset($codAtendimento) && !is_null($codAtendimento) && $codAtendimento !== '') {

            $atendimento = TabAtendimentos::with('auditoria.usuario', 'interlocutor', 'assunto', 'quemAtendeu', 'convidados.audConvidados.usuario', 'convidados.interlocutor', 'convidados.auditoria', 'demandas.auditoria', 'demandas.orgaoResponsavel', 'demandas.status', 'arquivos')
                ->find($codAtendimento);

            if (isset($codParlamentar) && !is_null($codParlamentar) && $codParlamentar !== '') {

                $tabParlamentares = $this->instanciarTabParlamentaresController();

                $getParlamentar = $tabParlamentares->getParlamentarWithOutRelationship($codParlamentar);

            } else {

                $nomParlamentar = null;

            }

            $tabAtendimentoInterlocutores = $this->instanciarTabAtendimentoInterlocutoresController();
            $tabObservacaoParlamentarAssuntos = $this->instanciarTabObservacaoParlamentarAssuntosController();
            $tabAtendimentoAssuntos = $this->instanciarTabAtendimentoAssuntosController();
            $tabAtendimentoCargos = $this->instanciarTabAtendimentoCargosController();
            $tabOrganizacao = $this->instanciarTabOrganizacaoController();
            $tabAtendimentoDemandaStatus = $this->instanciarTabAtendimentoDemandaStatusController();

            $estruturaTableAtendimento = $this->getEstruturaTable();
            $cod_interlocutor_pluck = $tabAtendimentoInterlocutores->getPluckInterlocutoresElegiveis();
            $cod_assunto_pluck = $tabAtendimentoAssuntos->getPluckAssuntos();
            $cod_cargo_pluck = $tabAtendimentoCargos->getPluckCargos();
            $responsaveisDemanda = $tabOrganizacao->getPluckOrganizacaoResponsavelDemanda();
            $statusDemanda = $tabAtendimentoDemandaStatus->getPluckStatus();
            $getStatus = $tabAtendimentoDemandaStatus->getStatus();

            // Início da parte de pegar a auditoria da parte do Convidados

            $tabAtendimentoConvidados = $this->instanciarTabAtendimentoConvidadosController();
            $auditoriaCompletaConvidados = $tabAtendimentoConvidados->getAuditoriaPorChavePrimaria($codAtendimento);

            // Fim da parte de pegar a auditoria da parte do Convidados
            // ---- x ---- x ---- x ---- x ----

            // Início da parte de pegar a auditoria da parte de Demandas

            $tabAtendimentoDemandas = $this->instanciarTabAtendimentoDemandasController();
            $auditoriaCompletaDemandas = $tabAtendimentoDemandas->getAuditoriaPorChavePrimaria($codAtendimento);

            // Fim da parte de pegar a auditoria da parte de Demandas
            // ---- x ---- x ---- x ---- x ----

            // Início da parte de pegar a auditoria da parte de Anexos

            $tabAtendimentoArquivos = $this->instanciarTabAtendimentoArquivosController();
            $auditoriaCompletaArquivos = $tabAtendimentoArquivos->getAuditoriaPorChavePrimaria($codAtendimento);

            // Fim da parte de pegar a auditoria da parte de Anexos
            // ---- x ---- x ---- x ---- x ----

            $colunasEscondidas = ['cod_interlocutor', 'nom_interlocutor'];

            return view('atendimento.editar')
                ->with('codAtendimento', $codAtendimento)
                ->with('atendimento', $atendimento)
                ->with('cod_parlamentar', $codParlamentar)
                ->with('getParlamentar', $getParlamentar)
                ->with('estruturaTableAtendimento', $estruturaTableAtendimento)
                ->with('cod_interlocutor_pluck', $cod_interlocutor_pluck)
                ->with('cod_assunto_pluck', $cod_assunto_pluck)
                ->with('cod_cargo_pluck', $cod_cargo_pluck)
                ->with('responsaveisDemanda', $responsaveisDemanda)
                ->with('getStatus', $getStatus)
                ->with('statusDemanda', $statusDemanda)
                ->with('colunasEscondidas', $colunasEscondidas)
                ->with('colunasEscondidas', $colunasEscondidas)
                ->with('auditoriaCompletaConvidados', $auditoriaCompletaConvidados)
                ->with('auditoriaCompletaDemandas', $auditoriaCompletaDemandas)
                ->with('auditoriaCompletaArquivos', $auditoriaCompletaArquivos);

        } else {
            $atendimento = null;

            $mensagem = 'Houve a tentativa de acessar a página de edição do atendimento sem o ID do atendimento.';

            return view('errors.index', ['mensagem' => $mensagem]);
        }

    }

    public function destroy(Request $request)
    {
        $input = $request->all();

        $codAtendimento = null;
        $codParlamentar = null;

        if ($input) {

            if (isset($input['cod_atendimento']) && !is_null($input['cod_atendimento']) && $input['cod_atendimento'] != '') {

                if (Uuid::isValid($input['cod_atendimento'])) {

                    $codAtendimento = $input['cod_atendimento'];

                }

            }

            if (isset($input['cod_parlamentar']) && !is_null($input['cod_parlamentar']) && $input['cod_parlamentar'] != '') {

                $codParlamentar = $input['cod_parlamentar'];

            }

        }

        // 1.   Início do procedimento para excluir o(s) convidado(s) relacionado(s) ao atendimento

        $tabAtendimentoConvidados = $this->instanciarTabAtendimentoConvidadosController();

        $convidados = $tabAtendimentoConvidados->getPKConvidadosPorCodAtendimento($codAtendimento);

        $tabAtendimentoConvidados->destroyConvidadosPorPK($convidados->toArray());

        // 1.   Fim do procedimento para excluir o(s) convidado(s) relacionado(s) ao atendimento
        // ---- x ---- x ---- x ---- x ----

        // 2.   Início do procedimento para excluir o(s) demanda(s) relacionado(s) ao atendimento

        $tabAtendimentoDemandas = $this->instanciarTabAtendimentoDemandasController();

        $demandas = $tabAtendimentoDemandas->getPKDemandasPorCodAtendimento($codAtendimento);

        $tabAtendimentoDemandas->destroyDemandasPorPK($demandas->toArray());

        // 2.   Fim do procedimento para excluir o(s) demanda(s) relacionado(s) ao atendimento
        // ---- x ---- x ---- x ---- x ----

        // 3.   Início do procedimento para excluir o(s) arquivo(s) relacionado(s) ao atendimento

        $tabAtendimentoArquivos = $this->instanciarTabAtendimentoArquivosController();

        $arquivos = $tabAtendimentoArquivos->getPKArquivosPorCodAtendimento($codAtendimento);

        $tabAtendimentoArquivos->destroyArquivosPorPK($arquivos->toArray());

        // 3.   Fim do procedimento para excluir o(s) arquivo(s) relacionado(s) ao atendimento
        // ---- x ---- x ---- x ---- x ----

        $atendimento = TabAtendimentos::find($codAtendimento);

        // 4.   Início do procedimento para excluir o(s) convidado(s) relacionado(s) ao atendimento

        $atendimento->delete();

        // 4.   Fim do procedimento para excluir o(s) convidado(s) relacionado(s) ao atendimento
        // ---- x ---- x ---- x ---- x ----

        \Session::flash('flash_message', "Atendimento excluído com sucesso!");

        return redirect()->route('parlamentar', [$codParlamentar, 'Atendimento']);
    }

    public function modalTabelaLog($idModal = null, $textoHeader = null, $audit = null, $table = null, $chaveEstrangeira = null, $columnNameChaveEstrangeira = null)
    {

        if ($audit->count()) {

            $quantidadeItensAuditoria = $audit->count();

        } else {

            $quantidadeItensAuditoria = 0;

        }

        $retorno = null;

        $retorno .= '<div class="modal fade" id="modalLog' . $idModal . '" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="padding-top: 119px!Important;">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header" style="background: linear-gradient(135deg,#072048 0%,#4273C3 100%);color: white;">
                                    <p class="modal-title text-white" style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                        <i class="fas fa-eye"></i> ' . $textoHeader . '
                                    </p>
                                </div>
                                <div class="modal-body mt-0 pt-0" style="max-height: 65vh; overflow-y: auto;">';

        $retorno .= '<table class="table table-sm table-fixed-header" style="width: 100%!Important;">
                    <thead>
                        <tr>
                        <th scope="col" class="text-right text-bold">#</th>
                        <th scope="col" class="text-bold">Ação</th>
                        <th scope="col" class="text-bold">Quem</th>
                        <th scope="col" class="text-bold">Quando</th>
                        </tr>
                    </thead>
                    <tbody>';

        $contLog = $audit->count();

        foreach ($audit as $object) {

            $oldValue = json_decode($object->old_values);
            $newValue = json_decode($object->new_values);

            $valorAntigo = null;

            $retorno .= '<tr>
                    <td class="text-right font-numero" style="font-size: 0.8rem!Important;">' . $contLog . '</td>
                    <td style="font-size: 0.8rem!Important;" style="width: 65%!Important;">';

            $column_name = null;
            $event = $object->event;
            $uuidValue = false;

            $matrizColunasNaoPrecisamConstarAuditoria = ['cod_parlamentar', 'cod_atendimento', 'cod_convidado', 'cod_demanda_atendimento', 'cod_arquivo', 'nom_arquivo', 'dsc_tipo'];

            $matrizDadosAntigos = [];
            $matrizDadosNovos = [];

            foreach ($oldValue as $key => $value) {
                if (isUUID($value)) {
                    $value = $this->getDescricaoChaveEstrangeira($key, $value);
                }

                if ($key === 'codigoUnidade') {
                    $value = $this->getDescricaoChaveEstrangeira($key, $value);
                }

                if (validateDate($value, 'Y-m-d')) {
                    $value = formatarDataComCarbonParaBR($value);
                }

                $matrizDadosAntigos[$key] = $value;
            }

            foreach ($newValue as $key => $value) {
                if (isUUID($value) && !in_array($key, $matrizColunasNaoPrecisamConstarAuditoria)) {
                    $value = $this->getDescricaoChaveEstrangeira($key, $value);
                }

                if ($key === 'codigoUnidade') {
                    $value = $this->getDescricaoChaveEstrangeira($key, $value);
                }

                if (validateDate($value, 'Y-m-d')) {
                    $value = formatarDataComCarbonParaBR($value);
                }

                $matrizDadosNovos[$key] = $value;
            }

            foreach ($matrizDadosNovos as $key => $value) {

                if ($event === 'created' && !in_array($key, $matrizColunasNaoPrecisamConstarAuditoria)) {
                    $retorno .= 'Inseriu o(a) <span class="text-success">' . $value . '</span> no campo <span class="text-bold">' . nomeCampoNormalizadoTabAtendimento($key) . '</span><br />';
                }

                if ($event === 'updated') {
                    $retorno .= 'Alterou o(a) <span class="text-bold">' . nomeCampoNormalizadoTabAtendimento($key) . '</span> de <span class="text-danger">' . $matrizDadosAntigos[$key] . '</span> para <span class="text-success">' . $value . '</span><br />';
                }

            }

            if ($object->usuario) {

                $quem = primeiraLetraMaiuscula($object->usuario->name);

            } else {

                $quem = '-';

            }

            $retorno .= '</td>
                    <td style="font-size: 0.8rem!Important; width: 17%!Important;">' . $quem . '</td>
                    <td class="font-numero" style="font-size: 0.8rem!Important; width: 14%!Important;">' . formatarTimeStampComCarbonParaBR($object->created_at) . '</td>
                    </tr>';

            $contLog--;

        }

        $retorno .= '</tbody>
                </table>';


        $retorno .= '</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">
                                        Fechar
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>';

        return $retorno;
    }

    public function store(Request $request)
    {

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        $input = $request->all();

        $cod_parlamentar = $input['atendimento']['cod_parlamentar'];

        // Início do IF para tratamento dos itens que vieram do POST
        if ($input) {

            // Início do loop entre os elementos contidos no $input
            foreach ($input as $key => $value) {

                if ($key != '_method' && $key != '_token') {

                    if ($key === 'atendimento') {

                        $id = [];
                        $campos = [];

                        $nomeProcedimento = 'Gravar dados do atendimento';
                        $schema = 'midr_gestao';
                        $table = 'tab_atendimentos';
                        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                        foreach ($value as $keyAtendimento => $valueAtendimento) {

                            $getColumnTable = $this->getColumnTable($schema, $table, $keyAtendimento);

                            if ($keyAtendimento === 'cod_assunto') {

                                if (!Uuid::isValid($valueAtendimento)) {

                                    $nomeProcedimentoIterno = 'Gravar novo assunto';
                                    $schemaIterno = 'midr_gestao';
                                    $tableIterno = 'tab_atendimento_assuntos';
                                    $modelIterno = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableIterno);

                                    $cod_assunto = $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($modelIterno, '', ['dsc_assunto' => primeiraLetraMaiuscula($valueAtendimento)]);

                                    $valueAtendimento = $cod_assunto;

                                }

                            }

                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                $columnName = $getColumnTable->column_name;

                                $ordinalPosition = $getColumnTable->ordinal_position;

                                if ($ordinalPosition == 1) {

                                    $id[$columnName] = $valueAtendimento;
                                } else {

                                    $campos[$columnName] = $valueAtendimento;
                                }
                            }

                        }

                        $cod_atendimento = $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

                    }

                    if ($key === 'convidado') {

                        $id = [];
                        $campos = [];

                        $nomeProcedimento = 'Gravar dados dos convidados do atendimento';
                        $schema = 'midr_gestao';
                        $table = 'tab_atendimento_convidados';
                        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                        $quantidadeElementosArray = 0;

                        foreach ($value as $keyAtendimento => $valueAtendimento) {
                            $quantidadeElementosArray = count($valueAtendimento) - 1;
                        }

                        $camposConvidados = ['cod_interlocutor', 'nom_convidado'];

                        for ($i = 0; $i <= $quantidadeElementosArray; $i++) {

                            foreach ($camposConvidados as $valueCampoConvidado) {

                                if (isset($value[$valueCampoConvidado][$i]) && !is_null($value[$valueCampoConvidado][$i]) && $value[$valueCampoConvidado][$i] != '') {

                                    $campos[$valueCampoConvidado] = $value[$valueCampoConvidado][$i];

                                }

                            }

                            if (!empty($campos)) {

                                $campos['cod_atendimento'] = $cod_atendimento;

                                $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, '', $campos);

                            }

                        }

                    }

                    if ($key === 'demadas') {

                        $id = [];
                        $campos = [];

                        $nomeProcedimento = 'Gravar dados das demandas recebidas por meio do atendimento';
                        $schema = 'midr_gestao';
                        $table = 'tab_atendimento_demandas';
                        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                        $quantidadeElementosArray = 0;

                        foreach ($value as $keyAtendimento => $valueAtendimento) {
                            $quantidadeElementosArray = count($valueAtendimento) - 1;
                        }

                        $camposDemandas = ['dsc_demanda', 'codigoUnidade', 'dte_prazo', 'cod_status_demanda'];

                        for ($i = 0; $i <= $quantidadeElementosArray; $i++) {

                            foreach ($camposDemandas as $valueCampoDemanda) {

                                if (isset($value[$valueCampoDemanda][$i]) && !is_null($value[$valueCampoDemanda][$i]) && $value[$valueCampoDemanda][$i] != '') {

                                    $campos[$valueCampoDemanda] = $value[$valueCampoDemanda][$i];

                                }

                            }

                            if (!empty($campos)) {

                                $campos['cod_atendimento'] = $cod_atendimento;

                                $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, '', $campos);

                            }

                        }

                    }

                    if ($key === 'anexos') {

                        $id = [];
                        $campos = [];

                        $nomeProcedimento = 'Gravar dados dos anexos (PDFs) do atendimento';
                        $schema = 'midr_gestao';
                        $table = 'tab_atendimento_arquivos';
                        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                        $quantidadeElementosArray = 0;

                        foreach ($value as $keyAtendimento => $valueAtendimento) {
                            $quantidadeElementosArray = count($valueAtendimento) - 1;
                        }

                        $campos['cod_atendimento'] = $cod_atendimento;

                        $files = $request->file('anexos.arquivo');

                        if (is_array($files)) {

                            $contArquivos = 0;
                            foreach ($request->file('anexos.arquivo') as $file) {

                                $campos['txt_assunto'] = $value['txt_assunto'][$contArquivos];

                                // Caminho da pasta que deseja criar
                                $directoryPath = 'public/atendimento/anexos/';
                                $directoryPathForSave = 'storage/atendimento/anexos/';

                                // Cria a pasta se ela não existir
                                Storage::makeDirectory($directoryPath);

                                $uuid = Str::uuid()->toString();
                                $uuid = str_replace('-', '', $uuid);

                                $fileName = date("Y_m_d_H_i_s") . '_' . $uuid . '.' . $file->getClientOriginalExtension();
                                $path = $file->storeAs($directoryPath, $fileName); // Salva o arquivo no caminho especificado

                                $campos['nom_arquivo'] = $directoryPathForSave . $fileName;

                                $campos['dsc_tipo'] = $file->getClientMimeType();

                                $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, '', $campos);

                                $contArquivos++;
                            }

                        }

                    }

                }
            }
            // Fim do loop entre os elementos contidos no $input

        }
        // Fim do IF para tratamento dos itens que vieram do POST

        \Session::flash('flash_message', "Atendimento gravado com sucesso!");
        // return redirect()->back()->with('selecaoTemaAnterior', 'Atendimento');
        return \Redirect::route('parlamentar', ['cod_parlamentar' => $cod_parlamentar]);
    }

    public function update(Request $request)
    {
        $input = $request->all();

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        $id = [];
        $campos = [];

        $nomeProcedimento = 'Gravar dados do detalhe atendimento';
        $schema = 'midr_gestao';
        $table = 'tab_atendimentos';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $id['cod_atendimento'] = $input['cod_atendimento'];

        foreach ($input as $key => $value) {

            if ($key != '_token' && $key != 'cod_atendimento') {

                if ($key === 'cod_assunto') {

                    if (!Uuid::isValid($value)) {

                        $nomeProcedimentoIterno = 'Gravar novo assunto';
                        $schemaIterno = 'midr_gestao';
                        $tableIterno = 'tab_atendimento_assuntos';
                        $modelIterno = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableIterno);

                        $cod_assunto = $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($modelIterno, '', ['dsc_assunto' => primeiraLetraMaiuscula($value)]);

                        $value = $cod_assunto;

                    }

                }

                $campos[$key] = $value;

            }

        }

        $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

    }

    public function ajaxGravarAlteracaoSelect($columnName = null, $value = null, $chavePrimaria = null)
    {

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        if (isset($columnName) && !is_null($columnName) && $columnName != '') {

            $id = [];
            $campos = [];

            if ($columnName === 'cod_assunto') {

                if (!Uuid::isValid($value)) {

                    $nomeProcedimentoIterno = 'Gravar novo assunto';
                    $schemaIterno = 'midr_gestao';
                    $tableIterno = 'tab_atendimento_assuntos';
                    $modelIterno = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableIterno);

                    $value = str_replace(">>barra<<", "/", $value);
                    $value = str_replace(">>virgula<<", ",", $value);

                    $cod_assunto = $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($modelIterno, '', ['dsc_assunto' => primeiraLetraMaiuscula($value)]);

                    $value = $cod_assunto;

                }

            }

            if ($columnName === 'bln_representante' && $value === 'Não') {

                $campos['nom_representante'] = null;
                $campos['dsc_cargo_representante'] = null;

            }

            $tables = [
                'tab_atendimentos' => [
                    'cod_interlocutor',
                    'nom_interlocutor',
                    'cod_assunto',
                    'cod_cargo',
                    'dte_atendimento',
                    'bln_representante',
                    'nom_representante',
                    'dsc_cargo_representante',
                    'cod_parlamentar',
                ],
                'tab_atendimento_convidados' => [
                    'cod_interlocutor',
                    'nom_convidado'
                ],
                'tab_atendimento_demandas' => [
                    'dsc_demanda',
                    'codigoUnidade',
                    'dte_prazo',
                    'cod_status_demanda',
                ]
            ];

            $table = null;

            foreach ($tables as $key => $tema) {

                foreach ($tema as $subtema) {
                    if ($columnName === $subtema) {
                        $table = $key;
                    }
                }
            }

            if (!is_null($table)) {

                $nomeProcedimento = 'Gravar dados do atendimento';
                $schema = 'midr_gestao';
                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                switch ($table) {
                    case 'tab_atendimentos':
                        $id['cod_atendimento'] = $chavePrimaria;
                        break;

                    case 'tab_atendimento_convidados':
                        $id['cod_convidado'] = $chavePrimaria;
                        break;

                    case 'tab_atendimento_demandas':
                        $id['cod_demanda_atendimento'] = $chavePrimaria;
                        break;
                }

                $campos[$columnName] = $value;

                $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

                return true;

            } else {
                return false;
            }

        }

    }

    protected function getColumnTable($schema = null, $table = null, $columnName = null)
    {

        return DB::selectOne("SELECT
            column_name,ordinal_position,is_nullable,data_type
            FROM
            information_schema.columns
            WHERE
            table_schema = '" . $schema . "'
            AND table_name = '" . $table . "'
            AND column_name = '" . $columnName . "';");
    }

    public function getEstruturaTable()
    {

        return DB::select("SELECT
            column_name,ordinal_position,is_nullable,data_type
            FROM
            information_schema.columns
            WHERE
            table_schema = 'midr_gestao'
            AND table_name = 'tab_atendimentos'
            AND column_name NOT IN ('cod_parlamentar','created_at','updated_at','deleted_at');");
    }

}
