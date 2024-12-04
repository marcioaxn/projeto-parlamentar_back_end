<?php

namespace App\Http\Controllers;

use App\Models\TabIndicadoresEstados;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TabIndicadoresEstadosController extends Controller
{

    public function getCodIbgeEstados()
    {
        return TabIndicadoresEstados::select('cod_ibge')
            ->orderBy('sgl_uf')
            ->get();
    }

    public function getIndicadoresEstado($sglUf = null)
    {

        if (isset($sglUf) && !is_null($sglUf) && $sglUf != '') {

            return TabIndicadoresEstados::select('*', DB::raw("CAST(REPLACE(num_receitas_realizadas_por_1000_em_2017,',','.') AS NUMERIC)::numeric AS num_receitas_realizadas_por_1000_em_2017, CAST(REPLACE(num_despesas_empenhadas_por_1000_em_2017,',','.') AS NUMERIC)::numeric AS num_despesas_empenhadas_por_1000_em_2017, rpad(idc_idh_2010,5,'0') AS idc_idh_2010"))
                ->with('populacao', 'densidadeDemografica', 'rendimentoDomiciliarPerCapita', 'receitaDespesa', 'idh', 'gini')
                ->find($sglUf);
        } else {
            return [];
        }
    }

}
