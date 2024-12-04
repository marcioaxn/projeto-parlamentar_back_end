<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\VisTciEmendas;
use App\Models\FncObterResumoTciAtivaPorIbgeEAreaInvestimento;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class VisTciEmendasController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getGrupoAreasInvestimento()
    {

        // Retornar as áreas de investimentos da TCI

        return VisTciEmendas::select('dsc_area_investimento')
            ->where('bln_carteira_mdr', 'SIM')
            ->where('bln_carteira_mdr_ativo', 'SIM')
            ->whereNotNull('dsc_area_investimento')
            ->whereNotIn('dsc_area_investimento', ['Habitação', 'Habitação: MCMV/Casa Verde e Amarela', 'Saneamento', 'Transporte e Mobilidade'])
            ->groupBy('dsc_area_investimento')
            ->orderBy('dsc_area_investimento')
            ->get();
    }

    public function getQuantidadeSomaValoresInvestimentoPago($ibge = null, $areaInvestimento = null)
    {

        if (isset($ibge) && !is_null($ibge) && $ibge != '' && isset($areaInvestimento) && !is_null($areaInvestimento) && $areaInvestimento != '') {

            $this->fncRenomearAreaInvestimento();

            return DB::selectOne("SELECT * FROM midr_corporativo.fnc_obter_resumo_tci_ativa_por_ibge_e_area_investimento('" . $ibge . "','" . $areaInvestimento . "');");

        }

    }

    public function getTCIUf($sgl_uf = null)
    {

        return VisTciEmendas::where('bln_carteira_mdr', 'SIM')
            ->where('bln_carteira_mdr_ativo', 'SIM')
            ->where('uf', $sgl_uf)
            ->orderBy('uf')
            ->orderBy('municipio')
            ->orderBy('cod_mdr')
            ->get();
    }

    public function getResumoEstado($sglUf = null)
    {

        if (isset($sglUf) && !is_null($sglUf) && $sglUf != '') {

            return DB::select("SELECT
                                    tci.dsc_area_investimento,
                                    count(tci.dsc_area_investimento) AS num_empreendimentos,
                                    sum(tci.vlr_repasse) AS vlr_repasse,
                                    sum(tci.vlr_pago_conta) AS vlr_pago_conta,
                                    sum(tci.vlr_pago) AS vlr_pago,
                                    sum(tci.vlr_desbloqueado_ajustado) AS vlr_desbloqueado_ajustado
                                FROM
                                    midr_corporativo.tab_carteira_investimento tci
                                WHERE
                                    tci.dsc_area_investimento IS NOT null
                                AND
                                    tci.bln_carteira_mdr = 'SIM'
                                AND
                                    tci.bln_carteira_mdr_ativo = 'SIM'
                                AND
                                    tci.dsc_area_investimento NOT IN ('Habitação', 'Habitação: MCMV/Casa Verde e Amarela', 'Saneamento', 'Transporte e Mobilidade')
                                AND
                                    tci.uf ~* '" . $sglUf . "'
                                GROUP BY
                                    tci.dsc_area_investimento
                                ORDER BY
                                    tci.dsc_area_investimento;");

        } else {
            return [];
        }

    }

    public function getTCIEstado($sgl_uf = null)
    {
        return VisTciEmendas::where('bln_carteira_mdr', 'SIM')
            ->where('bln_carteira_mdr_ativo', 'SIM')
            ->where('uf', 'ilike', $sgl_uf)
            ->whereNotIn('sgl_unidade_responsavel_agrupada', ['SNH', 'SNDUM', 'SEMOB', 'MMA', 'SMDRU-DEMOB', 'SMDRU-DERU', 'SNDU', 'SNDR', 'SNS', 'MCID', 'SNS - FUNASA'])
            ->whereNotIn('dsc_area_investimento', ['Habitação', 'Habitação: MCMV/Casa Verde e Amarela', 'Saneamento', 'Transporte e Mobilidade'])
            ->orderBy('uf')
            ->orderBy('municipio')
            ->orderBy('cod_mdr')
            ->get();
    }

    public function getTCIMunicipio($codMunicipio = null)
    {
        return VisTciEmendas::where('bln_carteira_mdr', 'SIM')
            ->where('bln_carteira_mdr_ativo', 'SIM')
            ->where('ibge', '~*', $codMunicipio)
            ->whereNotIn('sgl_unidade_responsavel_agrupada', ['SNH', 'SNDUM', 'SEMOB', 'MMA', 'SMDRU-DEMOB', 'SMDRU-DERU', 'SNDU', 'SNDR', 'SNS', 'MCID', 'SNS - FUNASA'])
            ->whereNotIn('dsc_area_investimento', ['Habitação', 'Habitação: MCMV/Casa Verde e Amarela', 'Saneamento', 'Transporte e Mobilidade'])
            ->orderBy('uf')
            ->orderBy('municipio')
            ->orderBy('cod_mdr')
            ->get();
    }

    public function getTCIAtivaResumoMunicipio($ibge = null)
    {

        return VisTciEmendas::select('dsc_area_investimento', DB::raw("COUNT(dsc_area_investimento) AS num_quantidade, SUM(vlr_investimento_ajustado) AS vlr_investimento_ajustado, SUM(vlr_pago_ajustado) AS vlr_pago_ajustado"))
            ->where('bln_carteira_mdr', 'SIM')
            ->where('bln_carteira_mdr_ativo', 'SIM')
            ->where('ibge', 'like', $ibge)
            ->groupBy('dsc_area_investimento')
            ->orderBy('dsc_area_investimento')
            ->get();
    }

    public function fncRenomearAreaInvestimento()
    {
        DB::select("UPDATE
                        midr_corporativo.tab_carteira_investimento AS tci
                    SET
                        dsc_area_investimento = 'Defesa Civil'
                    WHERE
                        tci.dsc_area_investimento = 'Defesa civil';");
    }

    public function getResumoMunicipio($codMunicipio = null)
    {

        if (isset($codMunicipio) && !is_null($codMunicipio) && $codMunicipio != '') {

            return DB::select("SELECT
                                    tci.dsc_area_investimento,
                                    count(tci.dsc_area_investimento) AS num_empreendimentos,
                                    sum(tci.vlr_repasse) AS vlr_repasse,
                                    sum(tci.vlr_pago_conta) AS vlr_pago_conta,
                                    sum(tci.vlr_pago) AS vlr_pago,
                                    sum(tci.vlr_desbloqueado_ajustado) AS vlr_desbloqueado_ajustado
                                FROM
                                    midr_corporativo.tab_carteira_investimento tci
                                WHERE
                                    tci.dsc_area_investimento IS NOT null
                                AND
                                    tci.bln_carteira_mdr = 'SIM'
                                AND
                                    tci.bln_carteira_mdr_ativo = 'SIM'
                                AND
                                    tci.dsc_area_investimento NOT IN ('Habitação', 'Habitação: MCMV/Casa Verde e Amarela', 'Saneamento', 'Transporte e Mobilidade')
                                AND
                                    tci.ibge ~* '" . $codMunicipio . "'
                                GROUP BY
                                    tci.dsc_area_investimento
                                ORDER BY
                                    tci.dsc_area_investimento;");

        } else {
            return [];
        }

    }
}
