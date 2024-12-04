<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabTseConsolidadaCamaraDeputados;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class TabTseConsolidadaCamaraDeputadosController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getTsePorCpf($num_cpf = null)
    {

        return TabTseConsolidadaCamaraDeputados::where('nr_cpf_candidato', $num_cpf)
            ->orderBy(DB::raw("qt_votos_nominais::numeric"), 'DESC')
            ->with('indicadores')
            ->get();

    }

}
