<?php

namespace App\Http\Controllers;

use App\Models\TabMunicipioIndicadores;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TabMunicipioIndicadoresController extends Controller
{

    /*
    Fpnte de dados
        PNDR:
            1.  https://www.gov.br/mdr/pt-br/assuntos/desenvolvimento-regional/odr
            2.  https://www.gov.br/mdr/pt-br/assuntos/desenvolvimento-regional/RelaodoMunicipiosPortarian34de18.01.2018NovaTipologiaPNDR.csv

            => https://www.gov.br/mdr/pt-br/assuntos/emendasparlamentares/PUBL_relatorios_004_821_municipios_prioritarios.pdf

        Municípios do Semiárido Brasileiro:
            1.  https://www.ibge.gov.br/geociencias/cartas-e-mapas/mapas-regionais/15974-semiarido-brasileiro.html

        Municípios da Faixa de Fronteira:
            1.  https://www.ibge.gov.br/geociencias/organizacao-do-territorio/estrutura-territorial/24073-municipios-da-faixa-de-fronteira.html

        Municípios da RIDE:
            1.   https://www12.senado.leg.br/noticias/materias/2018/06/15/lei-inclui-12-municipios-na-regiao-integrada-do-entorno-do-distrito-federal
            2.   http://www.planalto.gov.br/ccivil_03/leis/lcp/lcp94.htm

        Índice Gini por estado:
            1.  http://tabnet.datasus.gov.br/cgi/ibge/censo/cnv/giniuf.def
    */

    public function getIndicadoresMunicipio($codIbge = null)
    {
        if (isset($codIbge) && !is_null($codIbge) && $codIbge != '') {

            return TabMunicipioIndicadores::select('*', DB::raw("CAST(REPLACE(num_receitas_realizadas_por_1000_em_2017,',','.') AS NUMERIC)::numeric AS num_receitas_realizadas_por_1000_em_2017, CAST(REPLACE(num_despesas_empenhadas_por_1000_em_2017,',','.') AS NUMERIC)::numeric AS num_despesas_empenhadas_por_1000_em_2017, rpad(idc_idh_2010,5,'0') AS idc_idh_2010"))
                ->with('populacao', 'densidadeDemografica', 'pibPerCapita', 'receitaDespesa', 'idh', 'gini')
                ->find($codIbge);
        }
    }

    public function getMunicipios()
    {
        return TabMunicipioIndicadores::orderBy('cod_municipio')
            ->get();
    }

    public function gravarIDHViaIbge()
    {

        $municipios = $this->getMunicipios();

        foreach ($municipios as $value) {

            // Atualizar IDH por meio do site do IBGE

            $schema = 'midr_gestao';
            $table = 'tab_municipio_indicadores';
            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

            $url = 'https://servicodados.ibge.gov.br/api/v1/pesquisas/indicadores/30255/resultados/' . substr($value->cod_municipio, 0, 6);

            $getApi = file_get_contents($url);

            // dd($getApi);

            if ($getApi) {

                if (verificarTipoRetornoApi($url) === 'JSON') {

                    $data = json_decode($getApi);

                    // Verifica se o JSON foi decodificado com sucesso
                    if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                        foreach ($data as $return) {

                            if ($return->id == '30255') {

                                dd("Aqui 9", $data, $return, $return->id, $value);

                            }

                            /*
                            dd("Aqui 9", $data, $return, $return->id);

                            if ($key === 'DetalheParlamentar') {

                            }
                            */

                        }

                    }

                }

            }

            dd('Aqui 8');

            // dd($municipios, $value, $value->cod_municipio, substr($value->cod_municipio, 0, 6));
        }

    }
}
