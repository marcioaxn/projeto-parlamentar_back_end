<?php

namespace App\Http\Controllers;

use App\Models\TabResultadoPrimario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabResultadoPrimarioController extends Controller
{

    public function getPluckResultadoPrimario()
    {
        return TabResultadoPrimario::orderBy('cod_resultado_primario')
            ->whereIn('cod_resultado_primario', [2, 3])
            ->pluck('cod_resultado_primario', 'cod_resultado_primario');
    }

    public function getResultadoPrimario()
    {
        return TabResultadoPrimario::select(DB::raw("concat(cod_resultado_primario, ' - ', dsc_resultado_primario) AS dsc_resultado_primario"), "cod_resultado_primario")
            ->whereIn('cod_resultado_primario', [2, 3])
            ->orderBy('cod_resultado_primario')
            ->get();
    }

}
