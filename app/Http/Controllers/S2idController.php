<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class S2idController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getGrupoReconhecimentosPorCodIbgePorTempo($codIbge = null)
    {

        if (isset($codIbge) && !is_null($codIbge) && $codIbge != '') {

            return DB::select("SELECT
                                tc.nm_desastre AS nom_desastre,
                                COUNT(tc.nm_desastre) AS num_quantidade,
                                CASE
                                    WHEN tf.dt_ocorrencia >= CURRENT_TIMESTAMP - INTERVAL '72 hours' THEN true
                                    ELSE false
                                END AS bln_ocorreu_ultimos_sete_dias
                            FROM
                                mdr_s2id.protocolo tp
                            INNER JOIN
                                mdr_s2id.fide tf ON tp.id_protocolo = tf.id_protocolo
                            INNER JOIN
                                mdr_s2id.cobrade tc ON tf.id_cobrade = tc.id_cobrade
                            WHERE
                                tp.cd_protocolo ~* '" . $codIbge . "'
                            AND
                                tp.id_status_processo = '6'
                            AND
                                tf.dt_ocorrencia >= CURRENT_TIMESTAMP - INTERVAL '1 years'
                            GROUP BY
                                tc.nm_desastre, bln_ocorreu_ultimos_sete_dias
                            ORDER BY
                                tc.nm_desastre;");

        }

    }

    public function getGrupoReconhecimentosPorCodIbgeEstadoPorTempo($codIbge = null)
    {

        if (isset($codIbge) && !is_null($codIbge) && $codIbge != '') {

            return DB::select("SELECT
                                tc.nm_desastre AS nom_desastre,
                                COUNT(tc.nm_desastre) AS num_quantidade,
                                CASE
                                    WHEN tf.dt_ocorrencia >= CURRENT_TIMESTAMP - INTERVAL '72 hours' THEN true
                                    ELSE false
                                END AS bln_ocorreu_ultimos_sete_dias
                            FROM
                                mdr_s2id.protocolo tp
                            INNER JOIN
                                mdr_s2id.fide tf ON tp.id_protocolo = tf.id_protocolo
                            INNER JOIN
                                mdr_s2id.cobrade tc ON tf.id_cobrade = tc.id_cobrade
                            WHERE
                                substring(tp.cd_protocolo, '[0-9]{2}') ~* '" . $codIbge . "'
                            AND
                                tp.id_status_processo = '6'
                            AND
                                tf.dt_ocorrencia >= CURRENT_TIMESTAMP - INTERVAL '1 years'
                            GROUP BY
                                tc.nm_desastre, bln_ocorreu_ultimos_sete_dias
                            ORDER BY
                                tc.nm_desastre;");

        }

    }

}
