<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabTseResumoParlamentares;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class TabTseResumoParlamentaresController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getTseResumoSuplentesSenadoresPorSiglaUf($sglUf = null)
    {

        return DB::select("SELECT
                                ttrp.*
                            FROM
                                tab_tse_resumo_parlamentares ttrp
                            WHERE
                                ttrp.sg_uf = '" . $sglUf . "'
                            AND
                                ds_cargo IN ('SENADOR', '1º SUPLENTE', '2º SUPLENTE')
                            ORDER BY
                                ano_eleicao,
                                sq_candidato,
                            CASE
                                WHEN ds_cargo = 'SENADOR' THEN 1
                                WHEN ds_cargo = '1º SUPLENTE' THEN 2
                                WHEN ds_cargo = '2º SUPLENTE' THEN 3
                            END;");
    }

}
