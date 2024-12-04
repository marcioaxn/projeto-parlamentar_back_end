<?php

namespace App\Http\Controllers;

use App\Models\TabRota;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabRotasController extends Controller
{

    /*
    Fonte de dados
        1.  https://www.gov.br/mdr/pt-br/assuntos/desenvolvimento-regional/rotas-de-integracao-nacional/rotas-de-integracao-nacional
        2.  https://antigo.mdr.gov.br/images/stories/ArquivosSDRU/ArquivosPDF/Polos-Atualizados_Dez_2019.pdf
    */

    public function getRotas()
    {
        return TabRota::get();
    }

    public function getRotasPorEstado($codMunicipio = null)
    {

        if (isset($codMunicipio) && !is_null($codMunicipio) && $codMunicipio != '') {
            return DB::select("SELECT tr.nom_rota, COUNT(tr.nom_rota) AS num_quantidade FROM midr_gestao.tab_rota tr WHERE substring(tr.cod_municipio, '[0-9]{2}') = '" . $codMunicipio . "' GROUP BY tr.nom_rota");
        } else {
            return [];
        }

    }

    public function getRotasPorCodMunicipio($codMunicipio = null)
    {

        if (isset($codMunicipio) && !is_null($codMunicipio) && $codMunicipio != '') {
            return TabRota::where('cod_municipio', $codMunicipio)
                ->orderBy('nom_rota')
                ->get();
        } else {
            return [];
        }

    }
}
