<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\TabIbgeController;
use App\Http\Controllers\TabTseResumoParlamentaresController;
use App\Http\Controllers\TabTseConsolidadaController;
use App\Http\Controllers\TabMunicipioIndicadoresController;
use App\Http\Controllers\TabCitiesController;
use App\Http\Controllers\VisTciEmendasController;
use App\Http\Controllers\TabRotasController;
use App\Http\Controllers\S2idController;
use App\Http\Controllers\TabNovoPacController;
use App\Http\Controllers\TabPisfController;
use App\Http\Controllers\TabParlamentaresController;

ini_set('memory_limit', '2096M');
ini_set('max_execution_time', 5500);
set_time_limit(900000000);

class MunicipiosController extends Controller
{

    protected $perfil = null;
    protected $bln_acesso_inrestrito = null;
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function instanciarTabIbgeController()
    {
        return new TabIbgeController;
    }

    public function instanciarTabTseResumoParlamentaresController()
    {
        return new TabTseResumoParlamentaresController;
    }

    public function instanciarTabTseConsolidadaController()
    {
        return new TabTseConsolidadaController;
    }

    public function instanciarTabMunicipioIndicadoresController()
    {
        return new TabMunicipioIndicadoresController;
    }

    public function instanciarTabCitiesController()
    {
        return new TabCitiesController;
    }

    public function instaciarTabTseConsolidadaController()
    {
        return new TabTseConsolidadaController;
    }

    public function instanciarVisTciEmendasController()
    {
        return new VisTciEmendasController;
    }

    public function instanciarTabRotasController()
    {
        return new TabRotasController;
    }

    public function instanciarS2idController()
    {
        return new S2idController;
    }

    public function instanciarTabNovoPacController()
    {
        return new TabNovoPacController;
    }

    public function instanciarTabPisfController()
    {
        return new TabPisfController;
    }

    public function instanciarTabParlamentaresController()
    {
        return new TabParlamentaresController;
    }

    public function index($sglUf = null, $codNomMunicipio = null)
    {

        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        // Início da parte de instanciar os Controllers necessários
        $tabIbgeController = $this->instanciarTabIbgeController();
        $tabMunicipioIndicadoresController = $this->instanciarTabMunicipioIndicadoresController();
        $tabCitiesController = $this->instanciarTabCitiesController();
        $tabTseConsolidada = $this->instaciarTabTseConsolidadaController();
        $visTciEmendas = $this->instanciarVisTciEmendasController();
        $tabRotas = $this->instanciarTabRotasController();
        $pisf = $this->instanciarTabPisfController();
        $pac = $this->instanciarTabNovoPacController();
        // Fim da parte de instanciar os Controllers necessários

        $sgl_uf_select = $tabIbgeController->getPluckUfs();

        $nom_municipio_select = [];
        $getMunicipio = [];
        $getIndicadoresMunicipio = [];
        $rotas = [];
        $getTabCities = [];
        $codMunicipio = null;
        $tseGovernador = [];
        $tsePrefeito = [];
        $tseSenadores = [];
        $tseDeputadosFederais = [];
        $resumoAreaInvestimento = [];
        $areasInvestimento = [];
        $resumoTci = [];
        $tci = [];
        $sglUfNomMunicipio = null;
        $getReconhecimentosSeisPorCodIbge = [];
        $getPisf = [];

        // Início da verificação se a variável $sglUf contém algo
        if (isset($sglUf) && !is_null($sglUf) && $sglUf != '') {

            $getModelMunicipios = $tabIbgeController->getModelMunicipios($sglUf);

            $municipios = $getModelMunicipios->get();

            foreach ($municipios as $value) {
                $nom_municipio_select[$value->cod_municipio] = $value->nom_municipio_sem_formatacao;
            }

            // Início da verificação se a variável $codNomMunicipio contém algo
            if (isset($codNomMunicipio) && !is_null($codNomMunicipio) && $codNomMunicipio != '') {

                if (is_numeric($codNomMunicipio)) {

                    $codMunicipio = $codNomMunicipio;
                    $getMunicipio = $tabIbgeController->getMunicipioPorCodMunicipio($codMunicipio);
                } else {
                    $getMunicipio = $tabIbgeController->getMunicipioPorUfEMunicipio($sglUf, $codNomMunicipio);

                    if ($getMunicipio) {

                        $codMunicipio = $getMunicipio->cod_municipio;
                    } else {
                        $codMunicipio = null;
                    }

                    if (isset($codMunicipio) && !is_null($codMunicipio) && $codMunicipio != '') {

                        // Necessário passar o nome do município vindo da url para o código do município
                        $codNomMunicipio = $codMunicipio;
                    }
                }

                // Início da parte para pegar os indicadores do município
                $getIndicadoresMunicipio = $tabMunicipioIndicadoresController->getIndicadoresMunicipio($codMunicipio);
                // Fim da parte para pegar os indicadores do município

                // Início da parte para pegar as rotas
                $rotas = $tabRotas->getRotasPorCodMunicipio($codMunicipio);
                // Fim da parte para pegar as rotas

                // Início da parte de pegar as coordenadas do município
                $getTabCities = $tabCitiesController->getCoordenadasPorCodIbge($codMunicipio);
                // Fim da parte de pegar as coordenadas do município

                $areasInvestimento = $visTciEmendas->getGrupoAreasInvestimento();

                $quantidadeTotal = 0;
                $vlrInvestimentoTotal = 0;
                $vlrPagoTotal = 0;

                foreach ($areasInvestimento as $key => $value) {

                    $getQuantidadeSomaValoresInvestimentoPago = $visTciEmendas->getQuantidadeSomaValoresInvestimentoPago($codMunicipio, $value->dsc_area_investimento);

                    if ($value->dsc_area_investimento === 'Desenvolvimento Regional e Urbano') {
                        $value->dsc_area_investimento = 'Desenv. Regional e Urbano';
                    }

                    if ($getQuantidadeSomaValoresInvestimentoPago) {

                        $resumoAreaInvestimento[$value->dsc_area_investimento] = [
                            'quantidade_emp' => $getQuantidadeSomaValoresInvestimentoPago->quantidade_emp,
                            'soma_valor_investimento' => $getQuantidadeSomaValoresInvestimentoPago->soma_valor_investimento,
                            'soma_valor_pago' => $getQuantidadeSomaValoresInvestimentoPago->soma_valor_pago,
                        ];

                        $quantidadeTotal = $quantidadeTotal + $getQuantidadeSomaValoresInvestimentoPago->quantidade_emp;
                        $vlrInvestimentoTotal = $vlrInvestimentoTotal + $getQuantidadeSomaValoresInvestimentoPago->soma_valor_investimento;
                        $vlrPagoTotal = $vlrPagoTotal + $getQuantidadeSomaValoresInvestimentoPago->soma_valor_pago;

                    } else {

                        if (isset($value->dsc_area_investimento) && !is_null($value->dsc_area_investimento) && $value->dsc_area_investimento != '') {
                            $resumoAreaInvestimento[$value->dsc_area_investimento] = [
                                'quantidade_emp' => 0,
                                'soma_valor_investimento' => 0,
                                'soma_valor_pago' => 0,
                            ];
                        }

                    }
                }

                if (count($resumoAreaInvestimento) > 1) {
                    $resumoAreaInvestimento['Total'] = [
                        'quantidade_emp' => $quantidadeTotal,
                        'soma_valor_investimento' => $vlrInvestimentoTotal,
                        'soma_valor_pago' => $vlrPagoTotal,
                    ];
                }

                $s2id = $this->instanciarS2idController();

                $getReconhecimentosSeisPorCodIbge = $s2id->getGrupoReconhecimentosPorCodIbgePorTempo($codMunicipio);

                $getPisf = $pisf->getDadosPisfPorIbge($codMunicipio);

                // Início da parte para pegar os dados do TSE por tipo de parlamentar
                $tseGovernador = $tabTseConsolidada->getGovernadorPorCodMunicipio($sglUf, $codMunicipio);
                $tsePrefeito = $tabTseConsolidada->getPrefeitoPorCodMunicipio($sglUf, $codMunicipio);
                $tseSenadores = $tabTseConsolidada->getSenadoresPorCodMunicipio($sglUf, $codMunicipio);
                $tseDeputadosFederais = $tabTseConsolidada->getDeputadosFederaisPorCodMunicipio($sglUf, $codMunicipio);
                // Fim da parte para pegar os dados do TSE por tipo de parlamentar

                // dd($tseGovernador, $tsePrefeito, $tseSenadores, $tseDeputadosFederais);

            }
            // Fim da verificação se a variável $codNomMunicipio contém algo

            // Início da parte para pegar os empreendimentos relativos a UF de representação do parlamentar
            $tci = $visTciEmendas->getTCIUf($sglUf);
            // Fim da parte para pegar os empreendimentos relativos a UF de representação do parlamentar

        } else {
            $nom_municipio_select = [];
        }
        // Fim da verificação se a variável $sglUf contém algo

        if (isset($codMunicipio) && !is_null($codMunicipio) && $codMunicipio != '') {

            $sglUfNomMunicipio = $sglUf . '/' . $getMunicipio->nom_municipio_sem_formatacao;
        }

        // Início da parte da construção da matriz com os temas que serão visualizados na página da consulta parlamentar'
        $temas = ['Novo PAC', 'TSE', 'Carteira de Investimento'];
        // Fim da parte da construção da matriz com os temas que serão visualizados na página da consulta parlamentar'

        return view('estados.index', ['sgl_uf' => $sglUf, 'cod_nom_municipio' => $codNomMunicipio])
            ->with('perfil', $this->perfil)
            ->with('bln_acesso_inrestrito', $this->bln_acesso_inrestrito)
            ->with('sgl_uf_select', $sgl_uf_select)
            ->with('nom_municipio_select', $nom_municipio_select)
            ->with('getMunicipio', $getMunicipio)
            ->with('getIndicadoresMunicipio', $getIndicadoresMunicipio)
            ->with('rotas', $rotas)
            ->with('getTabCities', $getTabCities)
            ->with('temas', $temas)
            ->with('tseGovernador', $tseGovernador)
            ->with('tsePrefeito', $tsePrefeito)
            ->with('tseSenadores', $tseSenadores)
            ->with('tseDeputadosFederais', $tseDeputadosFederais)
            ->with('resumoAreaInvestimento', $resumoAreaInvestimento)
            ->with('tci', $tci)
            ->with('sglUfNomMunicipio', $sglUfNomMunicipio)
            ->with('getReconhecimentosSeisPorCodIbge', $getReconhecimentosSeisPorCodIbge)
            ->with('getPisf', $getPisf);
    }

    public function montarTabelaMunicipios()
    {
        // Iniciar instâncias de outros controladores
        $tabIbge = $this->instanciarTabIbgeController();
        $tabTseResumoParlamentares = $this->instanciarTabTseResumoParlamentaresController();
        $tabTseConsolidada = $this->instanciarTabTseConsolidadaController();
        $tabParlamentares = $this->instanciarTabParlamentaresController();
        // ---- x ---- x ---- x ---- x ----

        $uf = '';

        // Início para limpar o campo dsc_situacao da tab_tse_consolidade
        // DB::selectOne("UPDATE midr_gestao.tab_tse_consolidada SET dsc_situacao = null WHERE dsc_situacao IS NOT NULL;");
        DB::selectOne("UPDATE midr_gestao.tab_tse_consolidada SET dsc_situacao = null WHERE dsc_situacao IS NOT NULL AND sg_uf = '" . $uf . "';");
        // Fim para limpar o campo dsc_situacao da tab_tse_consolidade
        // ---- x ---- x ---- x ---- x ----

        // Retornar matriz de municípios
        $municipios = $tabIbge->getMunicipios($uf);
        // ---- x ---- x ---- x ---- x ----

        // Início no loop na matriz de municipios
        foreach ($municipios as $valueMunicipio) {

            // Retornar matriz de senadores e suplentes de senadores da tab_tse_resumo_parlamentares
            $senadoresESuplentesSenadores = $tabTseResumoParlamentares->getTseResumoSuplentesSenadoresPorSiglaUf($valueMunicipio->sgl_uf);
            // ---- x ---- x ---- x ---- x ----

            // Início no loop na matriz de senadores e suplentes de senadores
            foreach ($senadoresESuplentesSenadores as $valueSenadorSuplente) {

                $dscSituacao = null;

                // Retornar colection do senador da tab_tse_consolidada
                $senador = $tabTseConsolidada->getSenadorPorSqCandidato1($valueSenadorSuplente->sq_candidato);
                // ---- x ---- x ---- x ---- x ----

                // Retornar colection do parlamentar da tab_parlamentares
                // $consultaParlamentar = $tabParlamentares->getParlamentarPorNomeCompleto($valueSenadorSuplente->nm_candidato);
                $consultaParlamentar = $tabParlamentares->getParlamentarPorSequencialCandidato($valueSenadorSuplente->sq_candidato);
                if ($consultaParlamentar && $consultaParlamentar->dsc_situacao === 'Exercício') {
                    $dscSituacao = 'Exercício';
                }
                // ---- x ---- x ---- x ---- x ----

                // Início para atualizar alguns atributos do senador para o município
                if ($valueSenadorSuplente->ds_cargo === 'SENADOR') {

                    // Gravar o ds_cargo e a dsc_situacao específico do senador
                    DB::select("UPDATE midr_gestao.tab_tse_consolidada SET ds_cargo_resumo = '" . $valueSenadorSuplente->ds_cargo . "', dsc_situacao = '" . $dscSituacao . "', sq_candidato_titular = '" . $valueSenadorSuplente->sq_candidato . "', dsc_ordem_apresentacao = '1a' WHERE sq_candidato_1 = '" . $valueSenadorSuplente->sq_candidato . "' AND cd_mun = '$valueMunicipio->cod_municipio';");

                }
                // Fim para atualizar alguns atributos do senador para o município
                // ---- x ---- x ---- x ---- x ----

                // Início para inserir o 1º suplente do senador para o município
                if ($valueSenadorSuplente->ds_cargo === '1º SUPLENTE') {

                    // Consultar se esse parlamentar já existe na tab_tse_consolidada para esse município
                    $constarSeExiste = DB::selectOne("SELECT sq_candidato_1 FROM midr_gestao.tab_tse_consolidada WHERE cd_mun = '" . $valueMunicipio->cod_municipio . "' AND sq_candidato_1 = '" . $valueSenadorSuplente->sq_candidato . "' AND ano_eleicao = '" . $valueSenadorSuplente->ano_eleicao . "';");
                    // ---- x ---- x ---- x ---- x ----

                    // Consultar o senador titular para que esse suplente herde alguns atributos do titular
                    $senadorTitular = $tabTseConsolidada->getSenadorPorSqCandidato1EPorCodMunicipio($valueSenadorSuplente->sq_candidato_titular, $valueMunicipio->cod_municipio);
                    // ---- x ---- x ---- x ---- x ----

                    if (!$constarSeExiste && isset($senadorTitular->qt_votos_nominais) && !is_null($senadorTitular->qt_votos_nominais) && $senadorTitular->qt_votos_nominais != '') {
                        DB::select("INSERT INTO midr_gestao.tab_tse_consolidada (sg_uf, cd_mun, ds_cargo, nm_candidato, nr_partido, qt_votos_nominais, cd_sit_tot_turno, ds_sit_tot_turno, sq_candidato_1, nr_cpf_candidato, ano_eleicao, qt_votos_total, ds_grau_instrucao, ds_ocupacao, sg_partido, ds_cargo_resumo, dsc_situacao, sq_candidato_titular, dsc_ordem_apresentacao) VALUES ('" . $valueMunicipio->sgl_uf . "', '" . $valueMunicipio->cod_municipio . "', '1º Suplente', '" . $valueSenadorSuplente->nm_candidato . "', '" . $valueSenadorSuplente->nr_partido . "', '" . $senadorTitular->qt_votos_nominais . "', '" . $valueSenadorSuplente->cd_sit_tot_turno . "', '" . $valueSenadorSuplente->ds_sit_tot_turno . "', '" . $valueSenadorSuplente->sq_candidato . "', '" . $valueSenadorSuplente->nr_cpf_candidato . "', '" . $valueSenadorSuplente->ano_eleicao . "', '" . $senadorTitular->qt_votos_total . "', '" . $valueSenadorSuplente->ds_grau_instrucao . "', '" . $valueSenadorSuplente->ds_ocupacao . "', '" . $valueSenadorSuplente->sg_partido . "', '" . $valueSenadorSuplente->ds_cargo . "', '" . $dscSituacao . "', '" . $valueSenadorSuplente->sq_candidato_titular . "', '1b')");
                    } else {

                        if (isset($dscSituacao) && !is_null($dscSituacao) && $dscSituacao != '') {

                            DB::selectOne("UPDATE midr_gestao.tab_tse_consolidada SET dsc_situacao = '" . $dscSituacao . "' WHERE cd_mun = '" . $valueMunicipio->cod_municipio . "' AND sq_candidato_1 = '" . $valueSenadorSuplente->sq_candidato . "';");

                        }

                    }

                }
                // Fim para inserir o 1º suplente do senador para o município
                // ---- x ---- x ---- x ---- x ----

                // Início para inserir o 2º suplente do senador para o município
                if ($valueSenadorSuplente->ds_cargo === '2º SUPLENTE') {

                    // Consultar se esse parlamentar já existe na tab_tse_consolidada para esse município
                    $constarSeExiste = DB::selectOne("SELECT sq_candidato_1 FROM midr_gestao.tab_tse_consolidada WHERE cd_mun = '" . $valueMunicipio->cod_municipio . "' AND sq_candidato_1 = '" . $valueSenadorSuplente->sq_candidato . "' AND ano_eleicao = '" . $valueSenadorSuplente->ano_eleicao . "';");
                    // ---- x ---- x ---- x ---- x ----

                    // Consultar o senador titular para que esse suplente herde alguns atributos do titular
                    $senadorTitular = $tabTseConsolidada->getSenadorPorSqCandidato1EPorCodMunicipio($valueSenadorSuplente->sq_candidato_titular, $valueMunicipio->cod_municipio);
                    // ---- x ---- x ---- x ---- x ----

                    if (!$constarSeExiste && isset($senadorTitular->qt_votos_nominais) && !is_null($senadorTitular->qt_votos_nominais) && $senadorTitular->qt_votos_nominais != '') {
                        DB::select("INSERT INTO midr_gestao.tab_tse_consolidada (sg_uf, cd_mun, ds_cargo, nm_candidato, nr_partido, qt_votos_nominais, cd_sit_tot_turno, ds_sit_tot_turno, sq_candidato_1, nr_cpf_candidato, ano_eleicao, qt_votos_total, ds_grau_instrucao, ds_ocupacao, sg_partido, ds_cargo_resumo, dsc_situacao, sq_candidato_titular, dsc_ordem_apresentacao) VALUES ('" . $valueMunicipio->sgl_uf . "', '" . $valueMunicipio->cod_municipio . "', '2º Suplente', '" . $valueSenadorSuplente->nm_candidato . "', '" . $valueSenadorSuplente->nr_partido . "', '" . $senadorTitular->qt_votos_nominais . "', '" . $valueSenadorSuplente->cd_sit_tot_turno . "', '" . $valueSenadorSuplente->ds_sit_tot_turno . "', '" . $valueSenadorSuplente->sq_candidato . "', '" . $valueSenadorSuplente->nr_cpf_candidato . "', '" . $valueSenadorSuplente->ano_eleicao . "', '" . $senadorTitular->qt_votos_total . "', '" . $valueSenadorSuplente->ds_grau_instrucao . "', '" . $valueSenadorSuplente->ds_ocupacao . "', '" . $valueSenadorSuplente->sg_partido . "', '" . $valueSenadorSuplente->ds_cargo . "', '" . $dscSituacao . "', '" . $valueSenadorSuplente->sq_candidato_titular . "', '1c')");
                    } else {

                        if (isset($dscSituacao) && !is_null($dscSituacao) && $dscSituacao != '') {

                            DB::selectOne("UPDATE midr_gestao.tab_tse_consolidada SET dsc_situacao = '" . $dscSituacao . "' WHERE cd_mun = '" . $valueMunicipio->cod_municipio . "' AND sq_candidato_1 = '" . $valueSenadorSuplente->sq_candidato . "';");

                        }

                    }

                }
                // Fim para inserir o 2º suplente do senador para o município
                // ---- x ---- x ---- x ---- x ----

            }
            // Fim no loop na matriz de senadores e suplentes de senadores
            // ---- x ---- x ---- x ---- x ----

            // Retornar matriz de senadores e suplentes de senadores da tab_tse_resumo_parlamentares
            $deputadosFederais = $tabTseConsolidada->getDeputadoFederalPorCodMunicipio($valueMunicipio->cod_municipio);
            // ---- x ---- x ---- x ---- x ----

            // Início no loop na matriz de deputados federais
            foreach ($deputadosFederais as $valueDeputadoFederal) {

                $dscSituacao = null;

                // Início para atualizar o campo dsc_situacao do deputado federal
                // Retornar colection do parlamentar da tab_parlamentares
                $consultaParlamentar = $tabParlamentares->getDeputadoFederalPorNumCpf($valueDeputadoFederal->nr_cpf_candidato);
                if ($consultaParlamentar && $consultaParlamentar->dsc_situacao === 'Exercício') {
                    $dscSituacao = 'Exercício';
                }
                // ---- x ---- x ---- x ---- x ----

                if ($dscSituacao != '') {
                    DB::selectOne("UPDATE midr_gestao.tab_tse_consolidada SET dsc_situacao = '" . $dscSituacao . "', ds_cargo_resumo = '" . $valueDeputadoFederal->ds_sit_tot_turno . "' WHERE cd_mun = '" . $valueMunicipio->cod_municipio . "' AND nr_cpf_candidato = '" . $valueDeputadoFederal->nr_cpf_candidato . "';");
                }

                // Fim para atualizar o campo dsc_situacao do deputado federal
                // ---- x ---- x ---- x ---- x ----

                // dd($valueDeputadoFederal->nr_cpf_candidato);
            }
            // Fim no loop na matriz de deputados federais
            // ---- x ---- x ---- x ---- x ----

        }
        // Fim no loop na matriz de municipios
        // ---- x ---- x ---- x ---- x ----

        return "Foi feita a inclusão dos 1º e 2º suplentes dos senadores e atualização de alguns dados dos senadores e dos deputados federais => " . date('Ymd_His');
    }

    public function montarTabelaMunicipiosAntiga()
    {

        $tabIbgeController = $this->instanciarTabIbgeController();
        $tabParlamentares = $this->instanciarTabParlamentaresController();

        $getUfs = $tabIbgeController->getUfs();

        foreach ($getUfs as $value) {

            dd($value);

            $municipios = $tabIbgeController->getMunicipios($value->sgl_uf);

            $parlamentaresResumo = $this->getTseResumoPorCodMunicipio($value->sgl_uf);

            dd($parlamentaresResumo);

            foreach ($municipios as $valueMunicipio) {

                $getTseConsolidadaPorCodMunicipio = $this->getTseConsolidadaPorCodMunicipio($valueMunicipio->cod_municipio);

                foreach ($getTseConsolidadaPorCodMunicipio as $valueTseConsolidada) {

                    if ($valueTseConsolidada->ds_cargo === 'Senador' || $valueTseConsolidada->ds_cargo === '1º SUPLENTE' || $valueTseConsolidada->ds_cargo === '2º SUPLENTE') {

                        print($value->sgl_uf . ' - ' . $valueTseConsolidada->ds_cargo . ' => ' . $valueTseConsolidada->nm_candidato . '<br />');

                    }

                }

                dd($valueMunicipio->cod_municipio, $getTseConsolidadaPorCodMunicipio);
            }

            dd("Aqui 8");

            $senadoresSuplentes = $this->getSenadoresSuplentesTabTseResumoParlamentares($value->sgl_uf);
            dd($municipios, $senadoresSuplentes);
        }

        // $parlamentares = $tabParlamentares->getParlamentaresPorUF($matrizPartidos, $matrizCasas, $uf->sgl_uf);
    }

}
