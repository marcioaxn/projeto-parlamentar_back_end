<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabCarteiraInvestimentoMidr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\TabIbgeController;
use App\Http\Controllers\TabMunicipioIndicadoresController;
use App\Http\Controllers\TabCitiesController;
use App\Http\Controllers\VisTciEmendasController;

class TabCarteiraInvestimentoMidrController extends Controller
{
    protected $perfil = null;
    protected $bln_acesso_inrestrito = null;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function instanciarTabIbgeController()
    {
        return new TabIbgeController();
    }

    public function instanciarTabMunicipioIndicadoresController()
    {
        return new TabMunicipioIndicadoresController();
    }

    public function instanciarTabCitiesController()
    {
        return new TabCitiesController();
    }

    public function instanciarVisTciEmendasController()
    {
        return new VisTciEmendasController();
    }

    public function getTCIUf($sgl_uf = null)
    {
        return TabCarteiraInvestimentoMidr::where('bln_carteira_mdr', 'SIM')
            ->where('bln_carteira_mdr_ativo', 'SIM')
            ->where('uf', $sgl_uf)
            ->orderBy('uf')
            ->orderBy('municipio')
            ->orderBy('cod_mdr')
            ->get();
    }

    public function show($codMdr = null)
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
        $visTciEmendas = $this->instanciarVisTciEmendasController();
        // Fim da parte de instanciar os Controllers necessários

        $getIndicadoresMunicipio = [];
        $getTabCities = [];

        if (isset($codMdr) && !is_null($codMdr) && $codMdr != '') {
            $empreendimento = TabCarteiraInvestimentoMidr::where('cod_mdr', $codMdr)->first();

            if ($empreendimento) {

                if (isset($empreendimento->ibge) && !is_null($empreendimento->ibge) && $empreendimento->ibge != '') {

                    $codMunicipio = $empreendimento->ibge;

                    // Início da parte para pegar os indicadores do município
                    $getIndicadoresMunicipio = $tabMunicipioIndicadoresController->getIndicadoresMunicipio($codMunicipio);
                    // Fim da parte para pegar os indicadores do município

                    // Início da parte de pegar as coordenadas do município
                    $getTabCities = $tabCitiesController->getCoordenadasPorCodIbge($codMunicipio);
                    // Fim da parte de pegar as coordenadas do município

                }
            }

            return view('empreendimento.index', ['cod_mdr' => $codMdr, 'empreendimento' => $empreendimento])
                ->with('getIndicadoresMunicipio', $getIndicadoresMunicipio)
                ->with('getTabCities', $getTabCities);
        } else {
            return [];
        }
    }
}
