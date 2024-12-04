<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabTseConsolidadaSenadoFederal;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class TabTseConsolidadaSenadoFederalController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getTsePorNomeCompleto($sglUfRepresentante = null, $nom_parlamentar_completo = null)
    {

        $sglUfRepresentante = passarTextoParaMinusculo($sglUfRepresentante);

        $table = 'tab_tse_consolidada_senado_federal_' . $sglUfRepresentante;
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $nomParlamentarCompletoMaiuculo = tirarAcentuacao(passarTextoParaMaiusculo($nom_parlamentar_completo));

        return $model::whereRaw('mdr_corporativo.fnc_retira_acento(nm_candidato::character varying) = mdr_corporativo.fnc_retira_acento(?)', [$nomParlamentarCompletoMaiuculo])
            ->with('indicadores')
            ->orderBy(DB::raw("qt_votos_nominais::numeric"), 'DESC')
            ->get();
    }
}
