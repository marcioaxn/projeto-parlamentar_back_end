<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabTseConsolidada;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class TabTseConsolidadaController extends Controller
{

    public function getSenadorPorSqCandidato1($sqCandidato1 = null)
    {
        return TabTseConsolidada::where('sq_candidato_1', $sqCandidato1)
            ->first();
    }

    public function getMunicipiosDeputadoEstadulDistrital($sglUf = null, $sqCandidato1 = null)
    {
        $sglUf = passarTextoParaMinusculo($sglUf);

        $table = 'tab_tse_consolidada_deputados_estaduais_com_municipios_' . $sglUf;
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        return $model::where('sq_candidato_1', $sqCandidato1)
            ->with('indicadores')
            ->orderBy('qt_votos_nominais', 'DESC')
            ->get();
    }

    public function getGovernadorPorCodMunicipio($sglUf = null, $codMunicipio = null)
    {
        $sglUf = passarTextoParaMinusculo($sglUf);

        $table = 'tab_tse_consolidada_' . $sglUf;
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $retun = $model::where('ds_cargo', 'Governador');

        if (isset($codMunicipio) && !is_null($codMunicipio) && $codMunicipio != '') {
            $retun = $retun->where('cd_mun', $codMunicipio);
        }

        $retun = $retun->where('cd_sit_tot_turno', '1')
            ->first();

        return $retun;
    }

    public function getPrefeitoPorCodMunicipio($sglUf = null, $codMunicipio = null)
    {
        $sglUf = passarTextoParaMinusculo($sglUf);

        $table = 'tab_tse_consolidada_' . $sglUf;
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        return $model::where('cd_mun', $codMunicipio)
            ->where('ds_cargo', 'Prefeito')
            ->where('cd_sit_tot_turno', '1')
            ->first();
    }

    public function getCodMunicipioPorSglUf($sglUf = null)
    {
        return DB::selectOne("SELECT cod_municipio FROM tab_municipio_indicadores WHERE sgl_uf = '" . $sglUf . "';");
    }

    public function getSenadoresPorEstado($sglUf = null)
    {

        $codMunicipio = null;

        $getCodMunicipio = $this->getCodMunicipioPorSglUf($sglUf);

        $codMunicipio = $getCodMunicipio->cod_municipio;

        return DB::select("SELECT
                                ttca.*, tp.nom_parlamentar_sem_formatacao, tp.cod_parlamentar, tp.dsc_situacao AS dsc_situacao_tp, tp.sgl_partido AS sgl_partido_atual
                            FROM
                                tab_tse_consolidada_" . $sglUf . " ttca
                            LEFT JOIN tab_parlamentares tp ON ttca.sq_candidato_1::bigint = tp.num_sequencial_candidato AND tp.dsc_situacao = 'Exercício'
                            WHERE
                                ttca.cd_mun = '" . $codMunicipio . "'
                            AND
                                ttca.ds_cargo IN ('Senador', 'SENADOR','1º Suplente','2º Suplente')
                            AND
                                ttca.ds_sit_tot_turno = 'ELEITO'
                            ORDER by
                                qt_votos_total::integer DESC,
                                ano_eleicao,
                                sq_candidato_titular,
                                dsc_ordem_apresentacao,
                                CASE
                                    WHEN ds_cargo = 'Senador' THEN 1
                                    WHEN ds_cargo = 'SENADOR' THEN 1
                                    WHEN ds_cargo = '1º Suplente' THEN 2
                                    WHEN ds_cargo = '2º Suplente' THEN 3
                                END
                            LIMIT
                                9;");
    }

    public function getSenadoresPorCodMunicipio($sglUf = null, $codMunicipio = null)
    {
        return DB::select("SELECT
                                ttca.*, tp.nom_parlamentar_sem_formatacao, tp.cod_parlamentar, tp.dsc_situacao AS dsc_situacao_tp, tp.sgl_partido AS sgl_partido_atual
                            FROM
                                tab_tse_consolidada_" . $sglUf . " ttca
                            LEFT JOIN tab_parlamentares tp ON ttca.sq_candidato_1::bigint = tp.num_sequencial_candidato AND tp.dsc_situacao = 'Exercício'
                            WHERE
                                ttca.cd_mun = '" . $codMunicipio . "'
                            AND
                                ttca.ds_cargo IN ('Senador', 'SENADOR','1º Suplente','2º Suplente')
                            AND
                                ttca.ds_sit_tot_turno = 'ELEITO'
                            ORDER by
                                qt_votos_nominais::integer DESC,
                                ano_eleicao,
                                sq_candidato_titular,
                                dsc_ordem_apresentacao,
                                CASE
                                    WHEN ds_cargo = 'Senador' THEN 1
                                    WHEN ds_cargo = 'SENADOR' THEN 1
                                    WHEN ds_cargo = '1º Suplente' THEN 2
                                    WHEN ds_cargo = '2º Suplente' THEN 3
                                end;");
    }

    public function getDeputadosFederaisPorEstado($sglUf = null, $codMunicipio = null)
    {

        if (isset($sglUf) && !is_null($sglUf) && $sglUf != '') {
            $return = DB::select("SELECT
                                    ttc.sg_uf,
                                    ttc.sq_candidato_1,
                                    ttc.nm_candidato,
                                    ttc.nm_urna_candidato,
                                    ttc.ds_sit_tot_turno,
                                    ttc.nr_cpf_candidato,
                                    ttc.ano_eleicao,
                                    ttc.st_reeleicao,
                                    ttc.qt_votos_total,
                                    ttc.sg_partido,
                                    ttc.ds_cargo_resumo,
                                    ttc.dsc_situacao,
                                    tp.dsc_situacao AS dsc_situacao_atual,
                                    tp.sgl_partido AS sgl_partido_atual,
                                    tp.cod_parlamentar
                                FROM
                                    tab_tse_consolidada_camara_deputados_" . $sglUf . " ttc
                                LEFT JOIN
                                    tab_parlamentares tp ON ttc.sq_candidato_1::TEXT = tp.num_sequencial_candidato::text
                                ORDER BY
                                    ttc.qt_votos_total DESC;");

            return $return;
        } else {
            return [];
        }

    }

    public function getDeputadosFederaisPorCodMunicipio($sglUf = null, $codMunicipio = null)
    {

        $sglUf = passarTextoParaMinusculo($sglUf);

        $table = 'tab_tse_consolidada_' . $sglUf;
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        return $model::with('parlamentarExeercicio')
            ->where('cd_mun', $codMunicipio)
            ->where('ano_eleicao', '2022')
            ->where('ds_cargo', 'Deputado Federal')
            ->orderBy(DB::raw("qt_votos_nominais::integer"), 'DESC')
            ->get();
    }

    public function getSenadorPorSqCandidato1EPorCodMunicipio($sqCandidato1 = null, $codMunicipio = null)
    {
        return TabTseConsolidada::where('sq_candidato_1', $sqCandidato1)
            ->where('cd_mun', $codMunicipio)
            ->first();
    }

    public function getSenadorPorNrCandidatoEPorCodMunicipio($nrCandidato = null, $codMunicipio = null)
    {
        return TabTseConsolidada::where('cd_mun', $codMunicipio)
            ->where('nr_candidato', $nrCandidato)
            ->where('ds_cargo', 'Senador')
            ->first();
    }

    public function getDeputadoFederalPorCodMunicipio($codMunicipio = null)
    {
        return TabTseConsolidada::where('cd_mun', $codMunicipio)
            ->where('ds_cargo', 'Deputado Federal')
            ->get();
    }

    public function getTseConsolidada($sglUf = null, $codMunicipio = null)
    {

        if (isset($codMunicipio) && !is_null($codMunicipio) && $codMunicipio != '') {
            $condicaoCodMunicipio = "AND
                                ttca.cd_mun = '" . $codMunicipio . "'";
        } else {
            $condicaoCodMunicipio = '';
        }

        return DB::select("SELECT
                                ttca.*
                            FROM
                                tab_tse_consolidada ttca
                            WHERE
                                ttca.sg_uf = '" . $sglUf . "'
                                " . $condicaoCodMunicipio . "
                            ORDER BY
                                CASE
                                    WHEN ds_cargo = 'Prefeito' THEN 1
                                    WHEN ds_cargo = 'Senador' THEN 2
                                    WHEN ds_cargo = '1º Suplente' THEN 3
                                    WHEN ds_cargo = '2º Suplente' THEN 4
                                    WHEN ds_cargo = 'Deputado Federal' THEN 5
                                END;");
    }

}
