<?php

namespace App\Http\Controllers\Snfi;

use App\Http\Controllers\Controller;
use App\Models\Snfi\TabFundosDesenvolvimentoRegional;
use App\Models\Snfi\TabEstruturaTemasFundosDesenvolvimentoRegional;
use App\Models\TabIndicadoresEstados;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use DB;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Style\Language;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Schema;

use Carbon\Carbon;

class TabFundosDesenvolvimentoRegionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $estruturaTableParaEditar = $this->estruturaTableParaEditar();

        $empreendimentos = $this->getEmpreendimento();

        return view('fundos.desenvolvimento-regional.index')
            ->with('estruturaTableParaEditar', $estruturaTableParaEditar)
            ->with('empreendimentos', $empreendimentos);

    }

    public function columnsTableAggregated($codProjeto = null)
    {
        $estruturaFormulario = $this->estruturaFormulario();

        $estruturaTableParaEditar = $this->estruturaTableParaEditar();

        $empreendimento = $this->getEmpreendimento($codProjeto);

        $result = [];

        foreach ($estruturaTableParaEditar as $value) {

            $resultTabAudit = [];

            foreach ($estruturaFormulario as $tema) {

                if ($tema->nom_coluna === $value->column_name) {

                    $column_name = $value->column_name;

                    if ($empreendimento) {

                        // dd("Aqui 9", $value, $tema, $value->column_name);

                        if (isset($codProjeto) && !empty($codProjeto)) {
                            foreach ($empreendimento->auditoriaColuna as $tabAudit) {
                                // $resultTabAudit

                                if ($column_name === $tabAudit->column_name) {
                                    // print ($tabAudit->column_name . ' - ' . $tabAudit->antes . ' => ' . $tabAudit->depois . '<br />');
                                    $resultTabAudit['auditoria'][] = [
                                        'created_at' => formatarTimeStampComCarbonParaBR($tabAudit->created_at),
                                        'quem' => $tabAudit->usuario->name,
                                        'antes' => $tabAudit->antes,
                                        'depois' => $tabAudit->depois
                                    ];
                                }
                            }

                            $result[$tema->dsc_tema][] = [
                                'colunm_name' => $tema->nom_coluna,
                                'data_type' => $value->data_type,
                                'value' => $empreendimento->$column_name,
                                'historico' => $resultTabAudit
                            ];

                        } else {
                            $result[$tema->dsc_tema][] = [
                                'colunm_name' => $tema->nom_coluna,
                                'data_type' => $value->data_type
                            ];
                        }


                    }

                }
            }
        }

        return $result;
    }

    public function getEmpreendimento($codProjeto = null)
    {
        if (isset($codProjeto) && !empty($codProjeto)) {

            $return = TabFundosDesenvolvimentoRegional::with('auditoriaColuna', 'auditoriaColuna.usuario', 'fundos', 'status', 'ufs', 'instituicaoOperadora', 'orcamento');

            $return = $return->find($codProjeto);

            return $return;

        } else {

            $return = TabFundosDesenvolvimentoRegional::with('fundos', 'status', 'ufs', 'instituicaoOperadora', 'orcamento')
                ->orderBy('num_ano_contrato')
                ->orderBy('dsc_empreendimento');

            $return = $return->get();

            return $return;
        }

        return null;

    }

    public function getGrupoTemas()
    {

        // Consulta original
        $originalQuery = TabEstruturaTemasFundosDesenvolvimentoRegional::select('dsc_tema')
            ->groupBy('dsc_tema');

        // Linhas adicionais
        $financeiro = DB::table(DB::raw("(select '4. Orçamentário/Financeiro'::character varying as dsc_tema) as financeiro"));

        // Unindo todas as consultas
        return $originalQuery
            ->union($financeiro)
            ->orderBy('dsc_tema', 'ASC')
            ->get();
    }

    public function estruturaFormulario()
    {

        return TabEstruturaTemasFundosDesenvolvimentoRegional::orderBy('dsc_tema')
            ->get();
    }

    public function estruturaTableParaEditar()
    {
        if (!Schema::hasTable('midr_snfi.tab_fundos_desenvolvimento_regional')) {
            return 'Tabela não encontrada.';
        }

        $colunas = Schema::getColumnListing('midr_snfi.tab_fundos_desenvolvimento_regional');
        if (empty($colunas)) {
            return 'Nenhuma coluna encontrada ou tabela vazia.';
        }

        $estrutura = DB::select("SELECT
            column_name,ordinal_position,is_nullable,data_type
            FROM
            information_schema.columns
            WHERE
            table_schema = 'midr_snfi'
            AND table_name = 'tab_fundos_desenvolvimento_regional'
            AND column_name NOT IN ('created_at','deleted_at')
            ORDER BY
            CASE column_name
            WHEN 'cod_projeto' THEN 1
            WHEN 'num_ano_contrato' THEN 2
            WHEN 'cod_fundo' THEN 3
            WHEN 'dsc_empreendimento' THEN 4
            WHEN 'nom_razao_social' THEN 5
            WHEN 'cod_cnpj' THEN 6
            WHEN 'nom_municipio' THEN 7
            WHEN 'dsc_tipologia' THEN 8
            WHEN 'dsc_dinamismo' THEN 9
            WHEN 'bln_faixa_fronteira' THEN 10
            WHEN 'bln_semiarido_fdne' THEN 11
            WHEN 'bln_ride_fdco_fdne' THEN 12
            WHEN 'cod_status_contrato' THEN 13
            WHEN 'dte_resolucao_aprovacao_cp' THEN 14
            WHEN 'num_resolucao_aprovacao_cp' THEN 15
            WHEN 'dte_resolucao_aprovacao_projeto_diretoria_colegiada' THEN 16
            WHEN 'num_resolucao_participacao_projeto' THEN 17
            WHEN 'dte_contrato' THEN 18
            WHEN 'num_carencia_em_meses' THEN 19
            WHEN 'num_prazo_financiamento_em_meses' THEN 20
            WHEN 'dte_cancelamento_contrato' THEN 21
            WHEN 'vlr_investimento_total' THEN 22
            WHEN 'vlr_participacao_fundo' THEN 23
            WHEN 'vlr_contratado' THEN 24
            WHEN 'bln_situacao_financiamento' THEN 25
            WHEN 'dsc_setor' THEN 26
            WHEN 'dsc_objeto_emprendimento' THEN 27
            WHEN 'cod_cnae' THEN 28
            WHEN 'cod_instituicao_operadora' THEN 29
            WHEN 'created_at' THEN 30
            WHEN 'updated_at' THEN 31
            WHEN 'deleted_at' THEN 32
            END;");

        return $estrutura;
    }
}
