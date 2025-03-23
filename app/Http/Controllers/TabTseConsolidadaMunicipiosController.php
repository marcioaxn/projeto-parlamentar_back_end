<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabTseConsolidadaMunicipios;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class TabTseConsolidadaMunicipiosController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getTsePorCodMunicipio($sglUf = null, $codMunicipio = null)
    {

        $result = DB::select("select * FROM tab_tse_consolidada_atual WHERE cd_mun = '" . $codMunicipio . "' AND cd_sit_tot_turno != '4' AND ds_cargo != 'Vereador' AND cd_sit_tot_turno NOT IN ('4','6') ORDER BY CASE ds_cargo WHEN 'Prefeito' THEN 1 WHEN 'Senador' THEN 2 WHEN 'Deputado Federal' THEN 3 ELSE 4 end, qt_votos_total DESC;");

        return $result;
    }
}
