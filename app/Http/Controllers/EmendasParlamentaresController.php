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

class EmendasParlamentaresController extends Controller
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

    public function index(Request $request, $codParlamentar = null, $temaSelecionado = null)
    {

        return view('emendas-parlamentares.index');
    }
}
