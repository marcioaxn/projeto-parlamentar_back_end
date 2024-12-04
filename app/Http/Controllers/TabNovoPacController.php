<?php

namespace App\Http\Controllers;

use App\Models\TabEstruturaTemas;
use App\Models\Audit;
use App\Models\TabNovoPac;
use App\Models\VisResumo;
use App\Models\VisResumoAjustado;

use App\Exports\NovoPacOrcamentarioFinanceiroExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use DB;

use App\Http\Controllers\TabAcaoOrcamentariaController;
use App\Http\Controllers\AtualizarOuCriarPorModeloDadosController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\TabIbgeController;
use App\Http\Controllers\TabEvolucaoFinanceiraController;
use App\Http\Controllers\TabTiposItemOrcamentarioFinanceiroController;
use App\Http\Controllers\TabResultadoPrimarioController;
use App\Models\TabEvolucaoCreditoDisponivel;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Style\Language;

use App\Models\TabEvolucaoFinanceira;
use App\Models\TabEvolucaoSaldoEmpenhado;
use App\Models\TabEvolucaoSuplementacaoOrcamentaria;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Carbon\Carbon;

class TabNovoPacController extends Controller
{

    public function __construct()
    {

        // $this->middleware('auth');
    }

    public function instanciarTabAcaoOrcamentariaController()
    {
        return new TabAcaoOrcamentariaController;
    }

    public function instanciarAtualizarOuCriarPorModeloDadosController()
    {
        return new AtualizarOuCriarPorModeloDadosController;
    }

    public function instanciarTabIbgeController()
    {
        return new TabIbgeController;
    }

    public function instanciarTabEvolucaoFinanceiraController()
    {
        return new TabEvolucaoFinanceiraController;
    }

    public function instanciarAuditController()
    {
        return new AuditController;
    }

    public function instanciarTabTiposItemOrcamentarioFinanceiroController()
    {
        return new TabTiposItemOrcamentarioFinanceiroController;
    }

    public function instanciarTabResultadoPrimarioController()
    {
        return new TabResultadoPrimarioController;
    }

    public function empreendimentosNovoPacParaAuditoria()
    {
        $novosPac = $this->getTabNovoPac('', '');

        foreach ($novosPac as $key => $value) {
            $this->gravarAuditoriaPorCodPac($value->cod_pac);
        }

    }

    public function gravarAuditoriaPorCodPac($codPac = null)
    {
        if (isset($codPac) && !empty($codPac)) {

            /** Este método tem por objetivo recuperar as consultas de auditoria para consolidar e
             * gravá-la numa tabela onde será armazenado o cod_pac e o conteúdo da auditoria já
             * no formato html.
             */

            //

        }
    }

    public function getVisResumoAjustado($codigoUnidade = null)
    {
        $visResumoAjustado = VisResumoAjustado::orderBy('sigla');

        if (Session::get('permissao') != '0000010' && Session::get('permissao') != '0000001') {

            $visResumoAjustado = $visResumoAjustado->where('codigoUnidade', Auth::user()->codigoUnidade);

        }

        $visResumoAjustado = $visResumoAjustado->get();

        return $visResumoAjustado;
    }

    public function getAreasComEmpreendimento()
    {
        $visResumoAjustado = VisResumoAjustado::get();

        $pluck = [];

        foreach ($visResumoAjustado as $key => $value) {
            $pluck[$value->codigoUnidade] = $value->sigla;
        }

        return $pluck;
    }

    public function getDadosPacPorIbge($sglUf = null)
    {
        if (isset($sglUf) && !is_null($sglUf) && $sglUf != '') {

            return TabNovoPac::where('sgl_uf', 'like', '%' . $sglUf . '%')
                ->orderBy('nom_empreendimento_divulgacao')
                ->get();
        } else {
            return [];
        }
    }

    public function getDadosPacPorSglUf($sglUf = null)
    {
        if (isset($sglUf) && !is_null($sglUf) && $sglUf != '') {

            try {

                return TabNovoPac::where('sgl_uf', '~*', $sglUf)
                    ->orderBy('nom_empreendimento_divulgacao')
                    ->get();

            } catch (ModelNotFoundException $e) {
                return null;
            } catch (QueryException $e) {
                return null;
            } catch (\Exception $e) {
                return null;
            }
        } else {
            return [];
        }
    }

    public function getDadosPacPorCodMunicipio($codMunicipio = null)
    {
        if (isset($codMunicipio) && !is_null($codMunicipio) && $codMunicipio != '') {

            $tabIbge = $this->instanciarTabIbgeController();

            $municipio = $tabIbge->getMunicipioPorCodMunicipio($codMunicipio);

            try {
                return TabNovoPac::where('nom_municipio', '~*', $municipio->nom_municipio)
                    ->orWhere('nom_empreendimento_divulgacao', '~*', $municipio->nom_municipio)
                    ->orderBy('nom_empreendimento_divulgacao')
                    ->get();

            } catch (ModelNotFoundException $e) {
                return null;
            } catch (QueryException $e) {
                return null;
            } catch (\Exception $e) {
                return null;
            }
        } else {
            return [];
        }
    }

    public function getEvolucaoCreditoDisponivel($codigoUnidade = null)
    {
        $result = TabEvolucaoCreditoDisponivel::join('midr_pac.tab_novo_pac as tnp', 'tab_evolucao_credito_disponivel.cod_pac', '=', 'tnp.cod_pac')
            ->select('tab_evolucao_credito_disponivel.num_ano')
            ->selectRaw('SUM(tab_evolucao_credito_disponivel.vlr_credito_disponivel) as vlr_total');

        if (Session::get('permissao') != '0000010' && Session::get('permissao') != '0000001') {

            $result = $result->where('tnp.codigoUnidade', Auth::user()->codigoUnidade);

        } else {
            if (isset($codigoUnidade) && !empty($codigoUnidade)) {

                $result = $result->where('tnp.codigoUnidade', $codigoUnidade);

            }
        }

        $result = $result->groupBy('tab_evolucao_credito_disponivel.num_ano')
            ->get();

        return $result;

    }

    public function getEvolucaoSaldoEmpenhado($codigoUnidade = null)
    {
        $result = TabEvolucaoSaldoEmpenhado::join('midr_pac.tab_novo_pac as tnp', 'tab_evolucao_saldo_empenhado.cod_pac', '=', 'tnp.cod_pac')
            ->select('tab_evolucao_saldo_empenhado.num_ano')
            ->selectRaw('SUM(tab_evolucao_saldo_empenhado.vlr_saldo_empenhado) as vlr_total');

        if (Session::get('permissao') != '0000010' && Session::get('permissao') != '0000001') {

            $result = $result->where('tnp.codigoUnidade', Auth::user()->codigoUnidade);

        } else {
            if (isset($codigoUnidade) && !empty($codigoUnidade)) {

                $result = $result->where('tnp.codigoUnidade', $codigoUnidade);

            }
        }


        $result = $result->groupBy('tab_evolucao_saldo_empenhado.num_ano')
            ->get();

        return $result;

    }

    public function getEvolucaoSuplementacaoOrcamentaria($codigoUnidade = null)
    {
        $result = TabEvolucaoSuplementacaoOrcamentaria::join('midr_pac.tab_novo_pac as tnp', 'tab_evolucao_suplementacao_orcamentaria.cod_pac', '=', 'tnp.cod_pac')
            ->select('tab_evolucao_suplementacao_orcamentaria.num_ano')
            ->selectRaw('SUM(tab_evolucao_suplementacao_orcamentaria.vlr_suplementacao_orcamentaria) as vlr_total');

        if (Session::get('permissao') != '0000010' && Session::get('permissao') != '0000001') {

            $result = $result->where('tnp.codigoUnidade', Auth::user()->codigoUnidade);

        } else {
            if (isset($codigoUnidade) && !empty($codigoUnidade)) {

                $result = $result->where('tnp.codigoUnidade', $codigoUnidade);

            }
        }


        $result = $result->groupBy('tab_evolucao_suplementacao_orcamentaria.num_ano')
            ->get();

        return $result;

    }

    public function getEvolucaoFinanceira($codigoUnidade = null)
    {
        $result = TabEvolucaoFinanceira::join('midr_pac.tab_novo_pac as tnp', 'tab_evolucao_financeira.cod_pac', '=', 'tnp.cod_pac')
            ->select('tab_evolucao_financeira.num_ano')
            ->selectRaw('SUM(CASE WHEN tab_evolucao_financeira.num_rp = 2 THEN tab_evolucao_financeira.vlr_financeiro ELSE 0 END) as vlr_rp2_total')
            ->selectRaw('SUM(CASE WHEN tab_evolucao_financeira.num_rp = 3 THEN tab_evolucao_financeira.vlr_financeiro ELSE 0 END) as vlr_rp3_total');


        if (Session::get('permissao') != '0000010' && Session::get('permissao') != '0000001') {

            $result = $result->where('tnp.codigoUnidade', Auth::user()->codigoUnidade);

        } else {
            if (isset($codigoUnidade) && !empty($codigoUnidade)) {

                $result = $result->where('tnp.codigoUnidade', $codigoUnidade);

            }
        }


        $result = $result->groupBy('tab_evolucao_financeira.num_ano')
            ->get();

        return $result;

    }

    public function index($codigoUnidade = null)
    {

        $evolucaoCreditoDisponivelPorAno = $this->getEvolucaoCreditoDisponivel($codigoUnidade);

        $evolucaoSaldoEmpenhadoPorAno = $this->getEvolucaoSaldoEmpenhado($codigoUnidade);

        $evolucaoSuplementacaoOrcamentariaPorAno = $this->getEvolucaoSuplementacaoOrcamentaria($codigoUnidade);

        $evolucaoFinanceira = $this->getEvolucaoFinanceira($codigoUnidade);

        $visResumo = $this->getVisResumo($codigoUnidade);

        $estruturaTableParaEditar = $this->estruturaTableParaEditar();

        $novosPac = $this->getTabNovoPac('', $codigoUnidade);

        $colunasVisiveis = ['cod_pac', 'nom_empreendimento_divulgacao', 'sgl_uf', 'codigoUnidade', 'dte_inicio_empreendimento', 'dte_previsao_conclusao_empreendimento', 'dsc_situacao', 'dsc_fase', 'prc_execucao_fisica', 'updated_at'];

        $areasComEmpreendimento = $this->getAreasComEmpreendimento();

        isset($codigoUnidade) && !empty($codigoUnidade) ? $codigoUnidade = $codigoUnidade : $codigoUnidade = null;

        $visResumoAjustado = $this->getVisResumoAjustado($codigoUnidade);

        $trintaDiasAtras = Carbon::now()->subDays(30)->format('d/m/Y');

        return view('pac.index')
            ->with('visResumo', $visResumo)
            ->with('areasComEmpreendimento', $areasComEmpreendimento)
            ->with('codigoUnidade', $codigoUnidade)
            ->with('visResumoAjustado', $visResumoAjustado)
            ->with('trintaDiasAtras', $trintaDiasAtras)
            ->with('evolucaoCreditoDisponivelPorAno', $evolucaoCreditoDisponivelPorAno)
            ->with('evolucaoSaldoEmpenhadoPorAno', $evolucaoSaldoEmpenhadoPorAno)
            ->with('evolucaoSuplementacaoOrcamentariaPorAno', $evolucaoSuplementacaoOrcamentariaPorAno)
            ->with('evolucaoFinanceira', $evolucaoFinanceira)
            ->with('estruturaTableParaEditar', $estruturaTableParaEditar)
            ->with('novosPac', $novosPac)
            ->with('colunasVisiveis', $colunasVisiveis);
    }

    public function getVisResumo($codUnidade = null)
    {

        if (Session::get('permissao') != '0000010' && Session::get('permissao') != '0000001') {

            $codUnidade = Auth::user()->codigoUnidade;

        } else {

            if (isset($codUnidade) && !empty($codUnidade)) {

                $codUnidade = $codUnidade;

            }

        }

        if (isset($codUnidade) && !empty($codUnidade)) {
            $return = TabNovoPac::select(DB::raw("count(*) AS qte_empreendimentos, sum(vlr_a_executar) AS vlr_a_executar, sum(vlr_investimento_planejado_2023_a_2026) AS vlr_investimento_planejado_2023_a_2026, sum(vlr_ogu_empenhado_loa_2024) AS vlr_ogu_empenhado_loa_2024, sum(vlr_ogu_pago_repassado_loa_2024) AS vlr_ogu_pago_repassado_loa_2024, CURRENT_DATE AS dte_atual, CURRENT_DATE - '30 days'::interval AS dte_atual_menos_trinta_dias, (SELECT count(*) AS count
            FROM midr_pac.tab_novo_pac tnpi WHERE tnpi.updated_at >= (CURRENT_DATE - '30 days'::interval) AND \"codigoUnidade\" = " . $codUnidade . ") AS qte_empreendimentos_atualizados_30_dias"));
        } else {
            $return = TabNovoPac::select(DB::raw("count(*) AS qte_empreendimentos, sum(vlr_a_executar) AS vlr_a_executar, sum(vlr_investimento_planejado_2023_a_2026) AS vlr_investimento_planejado_2023_a_2026, sum(vlr_ogu_empenhado_loa_2024) AS vlr_ogu_empenhado_loa_2024, sum(vlr_ogu_pago_repassado_loa_2024) AS vlr_ogu_pago_repassado_loa_2024, CURRENT_DATE AS dte_atual, CURRENT_DATE - '30 days'::interval AS dte_atual_menos_trinta_dias, (SELECT count(*) AS count
            FROM midr_pac.tab_novo_pac tnpi WHERE tnpi.updated_at >= (CURRENT_DATE - '30 days'::interval)) AS qte_empreendimentos_atualizados_30_dias"));
        }

        if (Session::get('permissao') != '0000010' && Session::get('permissao') != '0000001') {

            $return = $return->where('codigoUnidade', Auth::user()->codigoUnidade);

        } else {

            if (isset($codUnidade) && !empty($codUnidade)) {

                $return = $return->where('codigoUnidade', $codUnidade);

            }

        }

        $return = $return->first();

        return $return;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($cod_pac)
    {
        //
    }

    public function storeOrcamentarioFinanceiro(Request $request)
    {
        $input = $request->all();

        if (isset($input['cod_item']) && !empty($input['cod_item'])) {
            $codItem = $input['cod_item'];
        } else {
            $codItem = null;
        }

        $blnGravar = true;

        if ($input) {

            $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

            $modificacoes = '';

            $id = [];
            $campos = [];

            $nomeProcedimento = 'Gravar dados do Orçamentário/Financeiro do novo PAC';
            $schema = 'midr_pac';

            $dscTipoItemOrcamentarioFinanceiro = null;

            $dscTipoItemOrcamentarioFinanceiro = $input['dsc_tipo_item_orcamentario_financeiro'];

            $campos['cod_pac'] = $input['cod_pac'];
            $campos['cod_acao_orcamentaria'] = $input['cod_acao_orcamentaria'];

            isset($input['vlr_dinheiro']) && !empty($input['vlr_dinheiro']) ? $vlrDinheiro = converteValor('PTBR', 'MYSQL', $input['vlr_dinheiro']) : $vlrDinheiro = null;

            if ($dscTipoItemOrcamentarioFinanceiro === 'Necessidade Financeira') {
                $table = 'tab_evolucao_financeira';

                if (isset($codItem) && !empty($codItem)) {

                    $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                    $id['cod_evolucao_financeira'] = $codItem;

                    $resultGravacao = $model::find($codItem);

                    if ($resultGravacao->num_ano != $input['num_ano']) {
                        $campos['num_ano'] = $input['num_ano'];
                        $blnGravar = true;
                    }

                    if ($resultGravacao->num_mes != $input['num_mes']) {
                        $campos['num_mes'] = $input['num_mes'];
                        $blnGravar = true;
                    }

                    if ($resultGravacao->num_rp != $input['num_rp']) {
                        $campos['num_rp'] = $input['num_rp'];
                        $blnGravar = true;
                    }

                    if ($resultGravacao->vlr_financeiro != $vlrDinheiro) {
                        $campos['vlr_financeiro'] = $vlrDinheiro;
                        $blnGravar = true;
                    }

                    if ($resultGravacao->txt_observacao_financeira != $input['txt_observacao']) {
                        $campos['txt_observacao_financeira'] = $input['txt_observacao'];
                        $blnGravar = true;
                    }

                } else {

                    /** Necessidade de consultar com os parâmetros recebidos
                     * para verificar se já existe os dados gravados, pois
                     * há um problema de uma nova inserção.
                     */

                    $resultNecessidadeFinanceira = TabEvolucaoFinanceira::select('cod_evolucao_financeira')
                        ->where('cod_pac', $input['cod_pac'])
                        ->where('cod_acao_orcamentaria', $input['cod_acao_orcamentaria'])
                        ->where('num_ano', $input['num_ano'])
                        ->where('num_mes', $input['num_mes'])
                        ->where('num_rp', $input['num_rp'])
                        ->first();

                    if ($resultNecessidadeFinanceira) {
                        $blnGravar = false;
                    } else {

                        $id['cod_evolucao_financeira'] = generateUUID();

                        if (isset($vlrDinheiro) && !empty($vlrDinheiro) || isset($input['txt_observacao_financeira']) && !empty($input['txt_observacao_financeira']) || isset($input['num_ano']) && !empty($input['num_ano']) || isset($input['num_mes']) && !empty($input['num_mes']) || isset($input['num_rp']) && !empty($input['num_rp'])) {

                            $campos['num_ano'] = $input['num_ano'];
                            $campos['num_mes'] = $input['num_mes'];
                            $campos['num_rp'] = $input['num_rp'];
                            $campos['vlr_financeiro'] = $vlrDinheiro;
                            $campos['txt_observacao_financeira'] = $input['txt_observacao'];

                            $blnGravar = true;

                        } else {
                            $blnGravar = false;
                        }

                    }

                }

            }

            if ($dscTipoItemOrcamentarioFinanceiro === 'Saldo Empenhado') {
                $table = 'tab_evolucao_saldo_empenhado';
                $id['cod_evolucao_saldo_empenhado'] = 'se-' . $input['cod_pac'] . '-' . $input['cod_acao_orcamentaria'] . '-' . $input['num_ano'];

                if (isset($codItem) && !empty($codItem)) {

                    $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                    $resultGravacao = $model::find($codItem);

                    if ($resultGravacao->num_ano != $input['num_ano']) {
                        $campos['num_ano'] = $input['num_ano'];
                        $blnGravar = true;
                    }

                    if ($resultGravacao->vlr_saldo_empenhado != $vlrDinheiro) {
                        $campos['vlr_saldo_empenhado'] = $vlrDinheiro;
                        $blnGravar = true;
                    }

                    if ($resultGravacao->txt_observacao_saldo_empenhado != $input['txt_observacao']) {
                        $campos['txt_observacao_saldo_empenhado'] = $input['txt_observacao'];
                        $blnGravar = true;
                    }

                } else {

                    if (isset($vlrDinheiro) && !empty($vlrDinheiro) || isset($input['txt_observacao_saldo_empenhado']) && !empty($input['txt_observacao_saldo_empenhado']) || isset($input['num_ano']) && !empty($input['num_ano'])) {

                        $campos['num_ano'] = $input['num_ano'];
                        $campos['vlr_saldo_empenhado'] = $vlrDinheiro;
                        $campos['txt_observacao_saldo_empenhado'] = $input['txt_observacao'];
                        $blnGravar = true;

                    } else {

                        $blnGravar = false;

                    }

                }

            }

            if ($dscTipoItemOrcamentarioFinanceiro === 'Crédito Disponível (Não Empenhado)') {
                $table = 'tab_evolucao_credito_disponivel';
                $id['cod_evolucao_credito_disponivel'] = 'cd-' . $input['cod_pac'] . '-' . $input['cod_acao_orcamentaria'] . '-' . $input['num_ano'];

                if (isset($codItem) && !empty($codItem)) {

                    $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                    $resultGravacao = $model::find($codItem);

                    if ($resultGravacao->num_ano != $input['num_ano']) {
                        $campos['num_ano'] = $input['num_ano'];
                        $blnGravar = true;
                    }

                    if ($resultGravacao->vlr_credito_disponivel != $vlrDinheiro) {
                        $campos['vlr_credito_disponivel'] = $vlrDinheiro;
                        $blnGravar = true;
                    }

                    if ($resultGravacao->txt_observacao_credito_disponivel != $input['txt_observacao']) {
                        $campos['txt_observacao_credito_disponivel'] = $input['txt_observacao'];
                        $blnGravar = true;
                    }

                } else {

                    if (isset($vlrDinheiro) && !empty($vlrDinheiro) || isset($input['txt_observacao_credito_disponivel']) && !empty($input['txt_observacao_credito_disponivel']) || isset($input['num_ano']) && !empty($input['num_ano'])) {

                        $campos['num_ano'] = $input['num_ano'];
                        $campos['vlr_credito_disponivel'] = $vlrDinheiro;
                        $campos['txt_observacao_credito_disponivel'] = $input['txt_observacao'];
                        $blnGravar = true;

                    } else {

                        $blnGravar = false;

                    }

                }

            }

            if ($dscTipoItemOrcamentarioFinanceiro === 'Suplementação Orçamentária Necessária') {
                $table = 'tab_evolucao_suplementacao_orcamentaria';
                $id['cod_evolucao_suplementacao_orcamentaria'] = 'so-' . $input['cod_pac'] . '-' . $input['cod_acao_orcamentaria'] . '-' . $input['num_ano'];


                if (isset($codItem) && !empty($codItem)) {

                    $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                    $resultGravacao = $model::find($codItem);

                    if ($resultGravacao->num_ano != $input['num_ano']) {
                        $campos['num_ano'] = $input['num_ano'];
                        $blnGravar = true;
                    }

                    if ($resultGravacao->vlr_suplementacao_orcamentaria != $vlrDinheiro) {
                        $campos['vlr_suplementacao_orcamentaria'] = $vlrDinheiro;
                        $blnGravar = true;
                    }

                    if ($resultGravacao->txt_observacao_suplementacao_orcamentaria != $input['txt_observacao']) {
                        $campos['txt_observacao_suplementacao_orcamentaria'] = $input['txt_observacao'];
                        $blnGravar = true;
                    }

                } else {

                    if (isset($vlrDinheiro) && !empty($vlrDinheiro) || isset($input['txt_observacao_suplementacao_orcamentaria']) && !empty($input['txt_observacao_suplementacao_orcamentaria']) || isset($input['num_ano']) && !empty($input['num_ano'])) {

                        $campos['num_ano'] = $input['num_ano'];
                        $campos['vlr_suplementacao_orcamentaria'] = $vlrDinheiro;
                        $campos['txt_observacao_suplementacao_orcamentaria'] = $input['txt_observacao'];
                        $blnGravar = true;

                    } else {

                        $blnGravar = false;

                    }

                }

            }

            if ($blnGravar) {
                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

                $atualizarUpdatedAtNovoPac = TabNovoPac::find($input['cod_pac']);

                if (isset($atualizarUpdatedAtNovoPac->updated_at) && !empty($atualizarUpdatedAtNovoPac->updated_at)) {

                    $upDatedAt = $atualizarUpdatedAtNovoPac->updated_at->format('Y-m-d');

                    if ($upDatedAt != date('Y-m-d')) {
                        $atualizarUpdatedAtNovoPac->updated_at = date("Y-m-d H:i:s");

                        $atualizarUpdatedAtNovoPac->save();
                    }
                } else {
                    $atualizarUpdatedAtNovoPac->updated_at = date("Y-m-d H:i:s");

                    $atualizarUpdatedAtNovoPac->save();
                }

                return true;
            }

        }

        return true;

    }

    public function edit($codPac = null)
    {

        if (isset($codPac) && !empty($codPac)) {

            $anos = [];
            $meses = [];
            $rps = [];
            $rpsLegenda = [];

            $tabAcaoOrcamentaria = $this->instanciarTabAcaoOrcamentariaController();

            $acoesOrcamentarias = $tabAcaoOrcamentaria->getPluck();

            $getGrupoTemas = $this->getGrupoTemas();

            $result = $this->columnsTableAggregated($codPac);

            $novoPac = $this->getTabNovoPac($codPac, '');

            if ($novoPac) {
                $nomEmpreendimento = $novoPac->nom_empreendimento;

                $columnsBlnSimNao = ['bln_emblematico', 'bln_paralisado', 'bln_em_obras_com_data_inicio_futuro', 'bln_obras_com_fase_nao_iniciado', 'bln_nao_iniciado_com_percentual_de_execucao', 'bln_nao_iniciado_com_fases_em_andamento', 'bln_em_obras_com_percentual_execucao_igual_100_porcento', 'bln_concluido_com_percentual_de_execucao_menor_que_100_porcento', 'bln_posterior_a_conclusao_ou_identicos'];

                $colunasComDominioProprio = ['cod_acao_orcamentaria' => 'acoesOrcamentarias'];

                $colunasComTabelaPropia = ['txt_comentario'];

                /** Início dos dados específicos para a construção
                 * da parte Orçamentária/Financeira
                 */

                $tabTiposItemOrcamentarioFinanceiro = $this->instanciarTabTiposItemOrcamentarioFinanceiroController();
                $tabResultadoPrimario = $this->instanciarTabResultadoPrimarioController();

                $tiposItemOrcamentarioFinanceiro = $tabTiposItemOrcamentarioFinanceiro->getPluckTiposItemOrcamentarioFinanceiro();

                for ($int = 2023; $int <= date('Y') + 1; $int++) {
                    $anos[$int] = $int;
                }

                for ($int = 1; $int <= 12; $int++) {
                    $meses[$int] = mesNumeralParaExtensoCurto($int);
                }

                $rps = $tabResultadoPrimario->getPluckResultadoPrimario();
                $rpsLegenda = $tabResultadoPrimario->getResultadoPrimario();

                // Fim dos dados específicos para a construção

                $evolucaoFinanceira = [];

                foreach ($novoPac->evolucaoFinanceira as $item) {
                    $ano = $item['num_ano'];
                    $mes = $item['num_mes'];

                    // Se o ano ainda não existe no array, cria-o
                    if (!isset($evolucaoFinanceira[$ano])) {
                        $evolucaoFinanceira[$ano] = [];
                    }

                    // Se o mês ainda não existe no ano, adiciona-o
                    if (!isset($evolucaoFinanceira[$ano][$mes])) {
                        $evolucaoFinanceira[$ano][$mes] = [];
                    }

                    // Adiciona o item ao mês correspondente
                    $evolucaoFinanceira[$ano][$mes][] = $item;
                }

                return view('pac.edit')
                    ->with('codPac', $codPac)
                    ->with('getGrupoTemas', $getGrupoTemas)
                    ->with('colunasComDominioProprio', $colunasComDominioProprio)
                    ->with('columnsBlnSimNao', $columnsBlnSimNao)
                    ->with('nomEmpreendimento', $nomEmpreendimento)
                    ->with('result', $result)
                    ->with('tiposItemOrcamentarioFinanceiro', $tiposItemOrcamentarioFinanceiro)
                    ->with('anos', $anos)
                    ->with('meses', $meses)
                    ->with('rps', $rps)
                    ->with('rpsLegenda', $rpsLegenda)
                    ->with('novoPac', $novoPac)
                    ->with('acoesOrcamentarias', $acoesOrcamentarias)
                    ->with('evolucaoFinanceira', $evolucaoFinanceira);
            } else {
                return view('acesso-negado');
            }

        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request, $codPac = null)
    {

        if (isset($codPac) && !empty($codPac)) {

            $tabEvolucaoFinanceira = $this->instanciarTabEvolucaoFinanceiraController();

            $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

            $input = $request->all();

            $result = $this->columnsTableAggregated($codPac);

            $modificacoes = '';

            $id = [];
            $campos = [];

            $nomeProcedimento = 'Gravar dados do novo PAC';
            $schema = 'midr_pac';
            $table = 'tab_novo_pac';
            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

            $id['cod_pac'] = $codPac;

            $contItem = 0;

            foreach ($result as $key => $value) {

                if ($key != '1. Não alterar') {

                    $data_type = null;

                    foreach ($value as $resultNovoPac) {

                        $data_type = $resultNovoPac['data_type'];

                        if ($resultNovoPac['colunm_name'] === 'cod_acao_orcamentaria') {

                            if (isset($input[$resultNovoPac['colunm_name']]) && !empty($input[$resultNovoPac['colunm_name']]) && is_array($input[$resultNovoPac['colunm_name']]) && count($input[$resultNovoPac['colunm_name']]) > 0) {
                                $input[$resultNovoPac['colunm_name']] = implode(',', $input[$resultNovoPac['colunm_name']]);
                            } else {
                                $input[$resultNovoPac['colunm_name']] = '';
                            }

                        }

                        if ($data_type === 'numeric' || $data_type === 'double precision') {

                            $input[$resultNovoPac['colunm_name']] = converteValor('PTBR', 'MYSQL', $input[$resultNovoPac['colunm_name']]);
                        }

                        // Normalização dos valores
                        $valorBanco = normalizeText($resultNovoPac['value']);
                        $valorFormulario = normalizeText($input[$resultNovoPac['colunm_name']]);

                        if ($valorBanco != $valorFormulario) {

                            $contItem++;

                            $campos[$resultNovoPac['colunm_name']] = $input[$resultNovoPac['colunm_name']];

                            if ($data_type === 'numeric' || $data_type === 'double precision') {

                                $resultNovoPac['value'] = converteValor('MYSQL', 'PTBR', $resultNovoPac['value']);
                                $input[$resultNovoPac['colunm_name']] = converteValor('MYSQL', 'PTBR', $input[$resultNovoPac['colunm_name']]);
                            }

                            if ($data_type === 'date') {
                                $resultNovoPac['value'] = converterData('EN', 'PTBR', $resultNovoPac['value']);
                                $input[$resultNovoPac['colunm_name']] = converterData('EN', 'PTBR', $input[$resultNovoPac['colunm_name']]);
                            }

                            if (is_null($resultNovoPac['value']) || empty($resultNovoPac['value'])) {
                                $resultNovoPac['value'] = 'nulo(a)';
                            }

                            $modificacoes .= 'Alterou <span class="text-bold">' . nomeCampoTabNovoPacNormalizado($resultNovoPac['colunm_name']) . '</span> de <span style="color: red; font-weight: bold;">' . $resultNovoPac['value'] . '</span> para <span style="color: green; font-weight: bold;">' . $input[$resultNovoPac['colunm_name']] . '</span>;<br>';
                        }
                    }

                }

            }

            if (isset($modificacoes) && !empty($modificacoes)) {

                $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

                $fraseRetorno = null;

                if ($contItem > 1) {
                    $fraseRetorno = "<span class='font-numero'>As seguintes alterações foram feitas com sucesso!<br /><br />";
                } else {
                    $fraseRetorno = "<span class='font-numero'>A seguinte alteração foi feita com sucesso!<br /><br />";
                }

                \Session::flash('flash_message', $fraseRetorno . $modificacoes . "</span>");
            } else {
                \Session::flash('flash_message_errors', "Nao foi detectada nenhuma alteração e por esse motivo nada foi feito.");
            }

            return redirect()->back();
            // return \Redirect::route('novo-pac.edit', ['codPac' => $codPac, 'div1' => 'div1']);
        }
    }

    public function columnsTableAggregated($codPac = null)
    {
        $estruturaFormulario = $this->estruturaFormulario();

        $estruturaTableParaEditar = $this->estruturaTableParaEditar();

        $novoPac = $this->getTabNovoPac($codPac, '');

        $result = [];

        foreach ($estruturaTableParaEditar as $value) {

            $resultTabAudit = [];

            foreach ($estruturaFormulario as $tema) {

                if ($tema->nom_coluna === $value->column_name) {

                    $column_name = $value->column_name;

                    if ($novoPac) {
                        foreach ($novoPac->tabAudit as $tabAudit) {
                            // $resultTabAudit

                            if ($column_name === $tabAudit->column_name) {
                                // print ($tabAudit->column_name . ' - ' . $tabAudit->antes . ' => ' . $tabAudit->depois . '<br />');
                                $resultTabAudit['auditoria'][] = [
                                    'created_at' => formatarTimeStampComCarbonParaBR($tabAudit->created_at),
                                    'quem' => $tabAudit->usuario->name,
                                    'antes' => $tabAudit->antes,
                                    'depois' => $tabAudit->depois
                                ];
                            }
                        }

                        $result[$tema->dsc_tema][] = [
                            'colunm_name' => $tema->nom_coluna,
                            'data_type' => $value->data_type,
                            'value' => $novoPac->$column_name,
                            'historico' => $resultTabAudit
                        ];
                    }

                }
            }
        }

        return $result;
    }

    public function getTabNovoPac($codPac = null, $codigoUnidade = null)
    {
        if (isset($codPac) && !empty($codPac)) {

            $return = TabNovoPac::with('auditoria', 'tabAudit', 'tabAudit.usuario', 'evolucaoFinanceira', 'evolucaoFinanceira.auditoria', 'evolucaoCreditoDisponivel', 'evolucaoCreditoDisponivel.auditoria', 'evolucaoSaldoEmpenhado', 'evolucaoSaldoEmpenhado.auditoria', 'evolucaoSuplementacaoOrcamentaria', 'evolucaoSuplementacaoOrcamentaria.auditoria', 'areasResponsaveisGestaoOrcamentariaFInanceira');
            // $return = TabNovoPac::with('auditoria', 'tabAudit', 'tabAudit.usuario');

            if (Session::get('permissao') != '0000010' && Session::get('permissao') != '0000001') {

                $return = $return->whereHas('areasResponsaveisGestaoOrcamentariaFInanceira', function ($query) {
                    $query->where('rel_pac_organizacao.codigoUnidade', Auth::user()->codigoUnidade);
                });

            }

            $return = $return->find($codPac);

            return $return;

        } else {

            $return = TabNovoPac::with('auditoria', 'tabAudit', 'tabAudit.usuario')
                ->orderBy('nom_empreendimento_divulgacao')
                ->with('areaResponsavel', 'areasResponsaveisGestaoOrcamentariaFInanceira');

            if (Session::get('permissao') != '0000010' && Session::get('permissao') != '0000001') {

                // $return = $return->where('codigoUnidade', Auth::user()->codigoUnidade);

                $return = $return->whereHas('areasResponsaveisGestaoOrcamentariaFInanceira', function ($query) {
                    $query->where('rel_pac_organizacao.codigoUnidade', Auth::user()->codigoUnidade);
                });

            } else {

                if (isset($codigoUnidade) && !empty($codigoUnidade)) {

                    $return = $return->where('codigoUnidade', $codigoUnidade);

                }

            }

            $return = $return->get();

            return $return;
        }

        return null;

    }

    public function destroy(Request $request)
    {
        $input = $request->all();

        if ($input) {

            if (isset($input['table']) && !empty($input['table'])) {

                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($input['table']);

            }

            if (isset($input['cod_item']) && !empty($input['cod_item'])) {

                $codItem = $input['cod_item'];

            }

            $item = $model::find($codItem);

            $item->delete();

        }
    }

    public function getGrupoTemas()
    {

        // Consulta original
        $originalQuery = TabEstruturaTemas::select('dsc_tema')
            ->groupBy('dsc_tema');

        // Linhas adicionais
        $financeiro = DB::table(DB::raw("(select '4. Orçamentário/Financeiro'::character varying as dsc_tema) as financeiro"));

        // Unindo todas as consultas
        return $originalQuery
            ->union($financeiro)
            ->orderBy('dsc_tema', 'ASC')
            ->get();
    }

    public function estruturaFormulario()
    {

        return TabEstruturaTemas::orderBy('dsc_tema')
            ->get();
    }

    public function estruturaTableParaEditar()
    {

        $estrutura = DB::select("SELECT
            column_name,ordinal_position,is_nullable,data_type
            FROM
            information_schema.columns
            WHERE
            table_schema = 'midr_pac'
            AND table_name = 'tab_novo_pac'
            AND column_name NOT IN ('created_at','deleted_at')
            ORDER BY
            CASE column_name
            WHEN 'cod_pac' THEN 1
            WHEN 'dsc_eixo' THEN 2
            WHEN 'dsc_subeixo' THEN 3
            WHEN 'dsc_modalidade_site' THEN 4
            WHEN 'nom_ministerio' THEN 5
            WHEN 'sgl_area_responsavel' THEN 6
            WHEN 'nom_empreendimento' THEN 7
            WHEN 'nom_empreendimento_divulgacao' THEN 8
            WHEN 'txt_descricao' THEN 9
            WHEN 'vlr_a_executar' THEN 10
            WHEN 'vlr_investimento_planejado_2023_a_2026' THEN 11
            WHEN 'vlr_investimento_planejado_pos_2026' THEN 12
            WHEN 'nom_executor' THEN 13
            WHEN 'num_meta_fisica' THEN 14
            WHEN 'dsc_unidade_de_medida' THEN 15
            WHEN 'sgl_uf' THEN 16
            WHEN 'codigoUnidade' THEN 17
            WHEN 'nom_municipio' THEN 18
            WHEN 'num_latitude' THEN 19
            WHEN 'num_longitude' THEN 20
            WHEN 'txt_observacao' THEN 21
            WHEN 'nom_sistema_de_referencia' THEN 22
            WHEN 'cod_sistema_de_referencia' THEN 23
            WHEN 'cod_acao_orcamentaria' THEN 24
            WHEN 'cod_plano_orcamentario' THEN 25
            WHEN 'dsc_natureza_empreendimento_ajustado' THEN 26
            WHEN 'dsc_situacao' THEN 27
            WHEN 'dsc_fase' THEN 28
            WHEN 'prc_execucao_fisica' THEN 29
            WHEN 'dte_inicio_empreendimento' THEN 30
            WHEN 'dte_previsao_conclusao_empreendimento' THEN 31
            WHEN 'txt_proxima_entrega_planejada' THEN 32
            WHEN 'dte_proxima_entrega_planejada' THEN 33
            WHEN 'bln_paralisado' THEN 34
            WHEN 'txt_motivo_paralisacao' THEN 35
            WHEN 'vlr_ogu_empenhado_loa_2024' THEN 36
            WHEN 'vlr_ogu_pago_repassado_loa_2024' THEN 37
            WHEN 'vlr_pago_rap_2024' THEN 38
            WHEN 'vlr_priv_pago_2024' THEN 39
            WHEN 'vlr_fin_pago_desbloqueado_2024' THEN 40
            WHEN 'vlr_pago_fundos_setoriais_2024' THEN 41
            WHEN 'vlr_pago_estatal_2024' THEN 42
            WHEN 'txt_resultado' THEN 43
            WHEN 'txt_restricao' THEN 44
            WHEN 'txt_providencia' THEN 45
            WHEN 'txt_comentario' THEN 46
            END;");

        return $estrutura;
    }

    public function getAuditoriaPorauditableTypeEAuditableId($auditabletype = null, $auditableId = null)
    {
        $audit = $this->instanciarAuditController();

        $getTabNovoPac = $this->getTabNovoPac($auditableId, '');

        $evolucaoCreditoDisponivelIds = [];
        $evolucaoSaldoEmpenhadoIds = [];
        $evolucaoSuplementacaoOrcamentariaIds = [];
        $evolucaoNecessidadeFinanceiraIds = [];

        $auditoriaNovoPac = null;
        $auditoriaCreditoDisponivel = null;
        $auditoriaSaldoEmpenhado = null;
        $auditoriaSuplementacaoOrcamentaria = null;
        $auditoriaNecessidadeFinanceira = null;

        if (isset($getTabNovoPac->evolucaoCreditoDisponivel)) {
            foreach ($getTabNovoPac->evolucaoCreditoDisponivel as $valueInterno) {
                array_push($evolucaoCreditoDisponivelIds, $valueInterno->cod_evolucao_credito_disponivel);
            }
        }

        if (isset($getTabNovoPac->evolucaoSaldoEmpenhado)) {
            foreach ($getTabNovoPac->evolucaoSaldoEmpenhado as $valueInterno) {
                array_push($evolucaoSaldoEmpenhadoIds, $valueInterno->cod_evolucao_saldo_empenhado);
            }
        }

        if (isset($getTabNovoPac->evolucaoSuplementacaoOrcamentaria)) {
            foreach ($getTabNovoPac->evolucaoSuplementacaoOrcamentaria as $valueInterno) {
                array_push($evolucaoSuplementacaoOrcamentariaIds, $valueInterno->cod_evolucao_suplementacao_orcamentaria);
            }
        }

        if (isset($getTabNovoPac->evolucaoFinanceira)) {
            foreach ($getTabNovoPac->evolucaoFinanceira as $valueInterno) {
                array_push($evolucaoNecessidadeFinanceiraIds, $valueInterno->cod_evolucao_financeira);
            }
        }

        $auditoriaNovoPac = Audit::where('auditable_type', 'App\Models\TabNovoPac')
            ->where('auditable_id', $auditableId);

        if (count($evolucaoCreditoDisponivelIds) > 0) {
            $auditoriaCreditoDisponivel = Audit::where('auditable_type', 'App\Models\TabEvolucaoCreditoDisponivel')
                ->whereIn('auditable_id', $evolucaoCreditoDisponivelIds); // Remover colchetes []
        }

        if (count($evolucaoSaldoEmpenhadoIds) > 0) {
            $auditoriaSaldoEmpenhado = Audit::where('auditable_type', 'App\Models\TabEvolucaoSaldoEmpenhado')
                ->whereIn('auditable_id', $evolucaoSaldoEmpenhadoIds); // Remover colchetes []
        }

        if (count($evolucaoSuplementacaoOrcamentariaIds) > 0) {
            $auditoriaSuplementacaoOrcamentaria = Audit::where('auditable_type', 'App\Models\TabEvolucaoSuplementacaoOrcamentaria')
                ->whereIn('auditable_id', $evolucaoSuplementacaoOrcamentariaIds); // Remover colchetes []
        }

        if (count($evolucaoNecessidadeFinanceiraIds) > 0) {
            $auditoriaNecessidadeFinanceira = Audit::where('auditable_type', 'App\Models\TabEvolucaoFinanceira')
                ->whereIn('auditable_id', $evolucaoNecessidadeFinanceiraIds); // Remover colchetes []
        }

        $union = $auditoriaNovoPac;

        if ($auditoriaCreditoDisponivel) {
            $union = $union->union($auditoriaCreditoDisponivel);
        }

        if ($auditoriaSaldoEmpenhado) {
            $union = $union->union($auditoriaSaldoEmpenhado);
        }

        if ($auditoriaSuplementacaoOrcamentaria) {
            $union = $union->union($auditoriaSuplementacaoOrcamentaria);
        }

        if ($auditoriaNecessidadeFinanceira) {
            $union = $union->union($auditoriaNecessidadeFinanceira);
        }

        $union = $union->where(DB::raw("(old_values != '[]' OR new_values != '[]')"), true)
            ->orderBy('created_at', 'DESC')
            ->get();

        return $union;
    }

    public function modalTabelaLog($idModal = null, $textoHeader = null, $audit = null, $table = null, $chaveEstrangeira = null, $columnNameChaveEstrangeira = null)
    {

        $auditableId = null;
        $nomEmpreendimentoDivulgacao = null;

        foreach ($audit as $key => $value) {
            $auditableId = $value->auditable_id;
            break;
        }

        if ($audit) {
            $audit = $this->getAuditoriaPorauditableTypeEAuditableId('App\Models\TabNovoPac', $auditableId);

            $textoHeader = null;

            $textoHeader = $audit->count() . ' ação(ões) realizada(s)';

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
                                <div class="modal-header" style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
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

            $matrizColunasNaoPrecisamConstarAuditoria = ['cod_pac', 'cod_evolucao_credito_disponivel', 'cod_acao_orcamentaria', 'cod_evolucao_suplementacao_orcamentaria', 'num_ano', 'num_mes', 'num_rp', 'cod_evolucao_saldo_empenhado', 'cod_evolucao_financeira'];

            foreach ($audit as $keyObject => $object) {

                $oldValue = json_decode($object->old_values);
                $newValue = json_decode($object->new_values);

                $valorAntigo = null;

                $retorno .= '<tr>
                    <td class="text-right font-numero" style="font-size: 0.8rem!Important;">' . $contLog . '</td>
                    <td style="font-size: 0.8rem!Important;" style="width: 65%!Important;">';

                $column_name = null;
                $event = $object->event;
                $uuidValue = false;

                $matrizDadosAntigos = [];
                $matrizDadosNovos = [];

                foreach ($oldValue as $key => $value) {
                    if (isUUID($value)) {
                        $value = $this->getDescricaoChaveEstrangeira($key, $value);
                    }

                    if ($key === 'codigoUnidade') {
                        $value = $this->getDescricaoChaveEstrangeira($key, $value);
                    }

                    if (validateDoublePrecision($value)) {
                        $value = converteValor('MYSQL', 'PTBR', $value);
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

                    if (validateDoublePrecision($value)) {
                        $value = converteValor('MYSQL', 'PTBR', $value);
                    }

                    if (validateDate($value, 'Y-m-d')) {
                        $value = formatarDataComCarbonParaBR($value);
                    }

                    $matrizDadosNovos[$key] = $value;
                }

                foreach ($matrizDadosNovos as $key => $value) {

                    if ($event === 'created' && !in_array($key, $matrizColunasNaoPrecisamConstarAuditoria)) {

                        if (isset($value) && !empty($value)) {
                            $retorno .= 'Inseriu o(a) <span class="text-success">' . $value . '</span> no campo <span class="text-bold">' . nomeCampoTabNovoPacNormalizado($key) . '</span><br />';
                        }

                    }

                    if ($event === 'updated') {
                        $retorno .= 'Alterou o(a) <span class="text-bold">' . nomeCampoTabNovoPacNormalizado($key) . '</span> de <span class="text-danger">' . $matrizDadosAntigos[$key] . '</span> para <span class="text-success">' . $value . '</span><br />';
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
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">
                                        Fechar
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>';

            return $retorno;
        } else {
            return 'Não houve alteração cadastrada.';
        }

    }

    public function tabelaAuditoria($codPac = null)
    {

        $auditableId = null;

        $auditableId = $codPac;

        $audit = $this->getAuditoriaPorauditableTypeEAuditableId('App\Models\TabNovoPac', $auditableId);

        $textoHeader = null;

        $textoHeader = $audit->count() . ' ação(ões) realizada(s)';

        if ($audit->count()) {

            $quantidadeItensAuditoria = $audit->count();

        } else {

            $quantidadeItensAuditoria = 0;

        }

        $retorno = null;

        $retorno .= '<table class="table table-sm table-hover table-fixed-header" style="width: 100%!Important;">
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

        $matrizColunasNaoPrecisamConstarAuditoria = ['cod_pac', 'cod_evolucao_credito_disponivel', 'cod_acao_orcamentaria', 'cod_evolucao_suplementacao_orcamentaria', 'num_ano', 'num_mes', 'num_rp', 'cod_evolucao_saldo_empenhado', 'cod_evolucao_financeira'];

        foreach ($audit as $keyObject => $object) {

            $oldValue = json_decode($object->old_values);
            $newValue = json_decode($object->new_values);

            $valorAntigo = null;

            $retorno .= '<tr>
                    <th class="text-right font-numero p-2" style="font-size: 0.8rem!Important;">' . $contLog . '</th>
                    <th class="text-justify p-2" style="font-size: 0.8rem!Important;" style="width: 65%!Important;">';

            $column_name = null;
            $event = $object->event;
            $uuidValue = false;

            $matrizDadosAntigos = [];
            $matrizDadosNovos = [];

            foreach ($oldValue as $key => $value) {
                if (isUUID($value)) {
                    $value = $this->getDescricaoChaveEstrangeira($key, $value);
                }

                if ($key === 'codigoUnidade') {
                    $value = $this->getDescricaoChaveEstrangeira($key, $value);
                }

                if (validateDoublePrecision($value)) {
                    $value = converteValor('MYSQL', 'PTBR', $value);
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

                if (validateDoublePrecision($value)) {
                    $value = converteValor('MYSQL', 'PTBR', $value);
                }

                if (validateDate($value, 'Y-m-d')) {
                    $value = formatarDataComCarbonParaBR($value);
                }

                $matrizDadosNovos[$key] = $value;
            }

            foreach ($matrizDadosNovos as $key => $value) {

                if ($event === 'created' && !in_array($key, $matrizColunasNaoPrecisamConstarAuditoria)) {

                    if (isset($value) && !empty($value)) {
                        $retorno .= 'Inseriu o(a) <span class="text-success">' . $value . '</span> no campo <span class="text-bold">' . nomeCampoTabNovoPacNormalizado($key) . '</span><br />';
                    }

                }

                if ($event === 'updated') {
                    $retorno .= 'Alterou o(a) <span class="text-bold">' . nomeCampoTabNovoPacNormalizado($key) . '</span> de <span class="text-danger">' . $matrizDadosAntigos[$key] . '</span> para <span class="text-success">' . $value . '</span><br />';
                }

            }

            if ($object->usuario) {

                $quem = primeiraLetraMaiuscula($object->usuario->name);

            } else {

                $quem = '-';

            }

            $retorno .= '</th>
                    <th class="text-left p-2" style="font-size: 0.8rem!Important; width: 17%!Important;">' . $quem . '</th>
                    <th class="text-left font-numero p-2" style="font-size: 0.8rem!Important; width: 14%!Important;">' . formatarTimeStampComCarbonParaBR($object->created_at) . '</th>
                    </tr>';

            $contLog--;

        }

        $retorno .= '</tbody>
                </table>';

        return $retorno;
    }

    public function showModalContent(Request $request)
    {

        $input = $request->all();

        if ($input) {


            if (isset($input['cod_pac']) && !empty($input['cod_pac'])) {
                return $this->tabelaAuditoria($input['cod_pac']);
            }

        }

    }

    public function modalTabelaLogOrcamentarioFinanceiro($idModal = null, $textoHeader = null, $audit = null, $table = null, $chaveEstrangeira = null, $columnNameChaveEstrangeira = null)
    {

        if ($audit->count()) {

            $quantidadeItensAuditoria = $audit->count();

        } else {

            $quantidadeItensAuditoria = 0;

        }

        $retorno = null;

        $retorno .= '<div class="modal fade" id="modalLogOrcamentarioFinanceiro' . $idModal . '" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="padding-top: 119px!Important;">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header" style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
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

            $matrizColunasNaoPrecisamConstarAuditoria = ['cod_pac', 'cod_evolucao_credito_disponivel', 'cod_acao_orcamentaria', 'cod_evolucao_suplementacao_orcamentaria', 'num_ano', 'num_mes', 'num_rp', 'cod_evolucao_saldo_empenhado', 'cod_evolucao_financeira'];

            $matrizDadosAntigos = [];
            $matrizDadosNovos = [];

            foreach ($oldValue as $key => $value) {
                if (isUUID($value)) {
                    $value = $this->getDescricaoChaveEstrangeira($key, $value);
                }

                if ($key === 'codigoUnidade') {
                    $value = $this->getDescricaoChaveEstrangeira($key, $value);
                }

                if (validateDoublePrecision($value)) {
                    $value = converteValor('MYSQL', 'PTBR', $value);
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

                if (validateDoublePrecision($value)) {
                    $value = converteValor('MYSQL', 'PTBR', $value);
                }

                if (validateDate($value, 'Y-m-d')) {
                    $value = formatarDataComCarbonParaBR($value);
                }

                $matrizDadosNovos[$key] = $value;
            }

            foreach ($matrizDadosNovos as $key => $value) {

                if ($event === 'created' && !in_array($key, $matrizColunasNaoPrecisamConstarAuditoria) && !empty($value)) {
                    $retorno .= 'Inseriu o(a) <span class="text-success">' . $value . '</span> no campo <span class="text-bold">' . nomeCampoTabNovoPacNormalizado($key) . '</span><br />';
                }

                if ($event === 'updated') {
                    $retorno .= 'Alterou o(a) <span class="text-bold">' . nomeCampoTabNovoPacNormalizado($key) . '</span> de <span class="text-danger">' . $matrizDadosAntigos[$key] . '</span> para <span class="text-success">' . $value . '</span><br />';
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
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">
                                        Fechar
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>';

        return $retorno;
    }

    public function exportNovoPacOrcamentarioFinanceiro($ano = null)
    {

        $fileName = 'MIDR - PAC - Previsão das Necessidades Financeiras em ' . date("d-m-Y") . ' - ' . date("H") . 'h' . date("i") . '-' . date("s") . '.xlsx';

        return Excel::download(new NovoPacOrcamentarioFinanceiroExport($ano), $fileName);

    }

}
