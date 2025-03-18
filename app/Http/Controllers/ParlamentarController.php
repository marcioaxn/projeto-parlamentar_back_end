<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Brick\Math\BigInteger;

use App\Http\Controllers\TabParlamentaresController;
use App\Http\Controllers\TabUltimaAtualizacaoParlamentaresController;
use App\Http\Controllers\TabAtendimentosController;
use App\Http\Controllers\TabAtendimentoInterlocutoresController;
use App\Http\Controllers\TabObservacaoParlamentarAssuntosController;
use App\Http\Controllers\TabAtendimentoAssuntosController;
use App\Http\Controllers\TabAtendimentoCargosController;
use App\Http\Controllers\TabOrganizacaoController;
use App\Http\Controllers\TabAtendimentoDemandaStatusController;
use App\Http\Controllers\TabTseConsolidadaController;
use App\Http\Controllers\TabTseConsolidadaCamaraDeputadosController;
use App\Http\Controllers\TabTseConsolidadaSenadoFederalController;
use App\Http\Controllers\VisTciEmendasController;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Uuid;
use Illuminate\Auth\Passwords\CanResetPassword;

use App\Http\Controllers\TabParlamentaresEstaduaisController;
use App\Http\Controllers\GetOnController;

class ParlamentarController extends Controller
{

    protected $perfil = null;
    protected $bln_acesso_inrestrito = null;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function instanciarTabParlamentaresController()
    {
        return new TabParlamentaresController;
    }

    public function instanciarTabUltimaAtualizacaoParlamentaresController()
    {
        return new TabUltimaAtualizacaoParlamentaresController;
    }

    public function instanciarTabAtendimentosController()
    {
        return new TabAtendimentosController;
    }

    public function instanciarTabAtendimentoInterlocutoresController()
    {
        return new TabAtendimentoInterlocutoresController;
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

    public function instanciarTabTseConsolidadaController()
    {
        return new TabTseConsolidadaController;
    }

    public function instanciarTabTseConsolidadaCamaraDeputadosController()
    {
        return new TabTseConsolidadaCamaraDeputadosController;
    }

    public function instanciarTabTseConsolidadaSenadoFederalController()
    {
        return new TabTseConsolidadaSenadoFederalController;
    }

    public function instanciarVisTciEmendasController()
    {
        return new VisTciEmendasController;
    }

    public function instanciarTabParlamentaresEstaduaisController()
    {
        return new TabParlamentaresEstaduaisController;
    }

    public function instanciarGetOnController()
    {
        return new GetOnController;
    }

    public function index(Request $request)
    {

        $codParlamentar = Session('cod_parlamentar');
        $cod_parlamentar = $codParlamentar;

        $temaSelecionado = null;

        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        // Crypt::encryptString(Auth::user()->email);

        // dd("Aqui 8", Crypt::encryptString(Auth::user()->email));

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        $input = $request->all();

        if (isset($codParlamentar) && !is_null($codParlamentar) && $codParlamentar != '') {

            $input['cod_parlamentar'] = $codParlamentar;
        }

        // Início da parte de instanciar os Controllers para operacionalizar os dados parlamentares

        $tabParlamentaresController = $this->instanciarTabParlamentaresController();

        $getParlamentares = $tabParlamentaresController->getParlamentares();

        $tabUltimaAtualizacaoParlamentaresController = $this->instanciarTabUltimaAtualizacaoParlamentaresController();

        $tabAtendimentos = $this->instanciarTabAtendimentosController();

        $tabTseConsolidada = $this->instanciarTabTseConsolidadaController();

        $tabTseConsolidadaCamaraDeputados = $this->instanciarTabTseConsolidadaCamaraDeputadosController();

        $tabTseConsolidadaSenadoFederal = $this->instanciarTabTseConsolidadaSenadoFederalController();

        $visTciEmendas = $this->instanciarVisTciEmendasController();

        $tabParlamentarEstadual = $this->instanciarTabParlamentaresEstaduaisController();

        $getParlamentaresEstaduais = $tabParlamentarEstadual->getParlamentaresEstaduais();

        $tabAgendas = $this->instanciarGetOnController();

        $agendas = $tabAgendas->getAgendaPorParlamentarHoje($codParlamentar);

        // Fim da parte de instanciar os Controllers para operacionalizar os dados parlamentares

        $atendimentos = [];

        $ultimaAtualizacaoCamaraDeputados = $tabUltimaAtualizacaoParlamentaresController->getUltimaAtualizacao('Atualizar tabela tab_parlamentares');

        $ultimaAtualizacaoSenadoFederal = $tabUltimaAtualizacaoParlamentaresController->getUltimaAtualizacao('Atualizar tabela tab_parlamentares');

        $dteAtualizacaoCD = null;
        $dteAtualizacaoSF = null;

        if ($ultimaAtualizacaoCamaraDeputados !== null) {

            $dteAtualizacaoCD = $ultimaAtualizacaoCamaraDeputados->tms_atualizacao;
        }

        if ($ultimaAtualizacaoSenadoFederal !== null) {

            $dteAtualizacaoSF = $ultimaAtualizacaoSenadoFederal->tms_atualizacao;
        }

        $getParlamentar = [];
        $parlamentarEstadualSelecionado = false;
        $tse = [];
        $tci = [];
        $observacao_cod_assunto_pluck = [];

        $variaveisConsulta = ['cod_parlamentar', 'cod_parlamentar_estadual'];

        // Início loop para pecorrer as variáveis de consulta
        foreach ($variaveisConsulta as $variavelConsulta) {

            // Início do IF para tratamento dos itens que vieram do POST
            if ($input) {

                $filtros = [];

                // Início do loop entre os elementos contidos no $input da página de consulta aos parlamentares
                foreach ($input as $key => $value) {

                    if ($key != '_method' && $key != '_token') {

                        if (isset($value) && !is_null($value) && $value != '') {

                            // Início do trecho para 'SETar' as variáveis $variaveisConsulta, que representam os
                            // filtros feito pelo usuário do sistema

                            ${$variavelConsulta} = $value;
                            $filtros[$key] = $value;

                            if ($key === 'cod_parlamentar') {

                                if (isBigInt($value) == true) {
                                    $getParlamentar = $tabParlamentarEstadual->getParlamentarEstadual($value);
                                    $parlamentarEstadualSelecionado = true;

                                    $tse = $tabTseConsolidada->getMunicipiosDeputadoEstadulDistrital($getParlamentar->sgl_uf_representante, $cod_parlamentar);
                                } else {
                                    $getParlamentar = $tabParlamentaresController->getParlamentar($value);
                                    $parlamentarEstadualSelecionado = false;
                                }

                                $atendimentos = $tabAtendimentos->getAtendimentosParlamentar($value);

                                if (!empty($getParlamentar)) {

                                    // Início da parte para pegar os dados do TSE
                                    if ($getParlamentar->dsc_casa === 'Câmara dos Deputados') {

                                        $tse = $tabTseConsolidadaCamaraDeputados->getTsePorCpf($getParlamentar->num_cpf);
                                    } else {

                                        $nomParlamentarCompletoMaiuculo = tirarAcentuacao(passarTextoParaMaiusculo($getParlamentar->nom_parlamentar_completo));

                                        if (!$parlamentarEstadualSelecionado) {
                                            $tse = $tabTseConsolidadaSenadoFederal->getTsePorNomeCompleto($getParlamentar->sgl_uf_representante, $nomParlamentarCompletoMaiuculo);
                                        }
                                    }
                                    // Fim da parte para pegar os dados do TSE

                                    // Início da parte para pegar os empreendimentos relativos a UF de representação do parlamentar

                                    $tci = $visTciEmendas->getTCIUf($getParlamentar->sgl_uf_representante);

                                    // Fim da parte para pegar os empreendimentos relativos a UF de representação do parlamentar
                                }
                            }
                        }
                    }
                }
                // Fim do loop entre os elementos contidos no $input da página de consulta aos parlamentares

            }
            // Fim do IF para tratamento dos itens que vieram do POST
            else {
                ${$variavelConsulta} = null;
            }
        }
        // Fim loop para pecorrer as variáveis de consulta

        // Início da parte da construção da matriz com os temas que serão visualizados na página da consulta parlamentar'
        $temas = ['Contatos', 'Pleitos/Demandas/Solicitações', 'Agenda/Audiências/Eventos', 'TSE'];
        // Fim da parte da construção da matriz com os temas que serão visualizados na página da consulta parlamentar'

        // Início da parte dos atendimentos

        // Início de declaração de variáveis para o atendimento
        $cod_interlocutor_pluck = [];
        $cod_assunto_pluck = [];
        $cod_cargo_pluck = [];
        // Fim de declaração de variáveis para o atendimento

        $tabAtendimentoInterlocutores = $this->instanciarTabAtendimentoInterlocutoresController();
        $tabObservacaoParlamentarAssuntos = $this->instanciarTabObservacaoParlamentarAssuntosController();
        $tabAtendimentoAssuntos = $this->instanciarTabAtendimentoAssuntosController();
        $tabAtendimentoCargos = $this->instanciarTabAtendimentoCargosController();
        $tabOrganizacao = $this->instanciarTabOrganizacaoController();
        $tabAtendimentoDemandaStatus = $this->instanciarTabAtendimentoDemandaStatusController();

        $observacao_cod_assunto_pluck = $tabObservacaoParlamentarAssuntos->getPluckAssuntos();

        $estruturaTableAtendimento = $tabAtendimentos->getEstruturaTable();
        $cod_interlocutor_pluck = $tabAtendimentoInterlocutores->getPluckInterlocutoresElegiveis();
        $cod_assunto_pluck = $tabAtendimentoAssuntos->getPluckAssuntos();
        $cod_cargo_pluck = $tabAtendimentoCargos->getPluckCargos();
        $responsaveisDemanda = $tabOrganizacao->getPluckOrganizacaoResponsavelDemanda();
        $statusDemanda = $tabAtendimentoDemandaStatus->getPluckStatus();
        $getStatus = $tabAtendimentoDemandaStatus->getStatus();

        $colunasEscondidas = ['cod_interlocutor', 'nom_interlocutor'];

        // Fim da parte dos atendimentos

        return view('parlamentar.index', ['cod_parlamentar' => $cod_parlamentar, 'cod_parlamentar_estadual' => $cod_parlamentar_estadual])
            ->with('perfil', $this->perfil)
            ->with('bln_acesso_inrestrito', $this->bln_acesso_inrestrito)
            ->with('getParlamentares', $getParlamentares)
            ->with('getParlamentaresEstaduais', $getParlamentaresEstaduais)
            ->with('temaSelecionado', $temaSelecionado)
            ->with('getParlamentar', $getParlamentar)
            ->with('agendas', $agendas)
            ->with('parlamentarEstadualSelecionado', $parlamentarEstadualSelecionado)
            ->with('dteAtualizacaoCD', $dteAtualizacaoCD)
            ->with('dteAtualizacaoSF', $dteAtualizacaoSF)
            ->with('temas', $temas)
            ->with('estruturaTableAtendimento', $estruturaTableAtendimento)
            ->with('observacao_cod_assunto_pluck', $observacao_cod_assunto_pluck)
            ->with('cod_interlocutor_pluck', $cod_interlocutor_pluck)
            ->with('cod_assunto_pluck', $cod_assunto_pluck)
            ->with('cod_cargo_pluck', $cod_cargo_pluck)
            ->with('responsaveisDemanda', $responsaveisDemanda)
            ->with('getStatus', $getStatus)
            ->with('statusDemanda', $statusDemanda)
            ->with('colunasEscondidas', $colunasEscondidas)
            ->with('atendimentos', $atendimentos)
            ->with('tse', $tse)
            ->with('tci', $tci);
    }
}
