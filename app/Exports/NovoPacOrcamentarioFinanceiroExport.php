<?php

namespace App\Exports;

use Session;
use Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use DB;
use Illuminate\Support\Facades\Storage;

ini_set('memory_limit', '5096M');
ini_set('max_execution_time', 5500);
set_time_limit(900000000);

/**
 * Classe responsável por exportar dados orçamentários e financeiros para uma planilha Excel.
 *
 * @package App\Exports
 */
class NovoPacOrcamentarioFinanceiroExport implements FromView, WithColumnFormatting
{
    /**
     * Ano para o qual os dados serão exportados.
     *
     * @var int
     */
    protected $ano;

    /**
     * Construtor que recebe o parâmetro ano.
     *
     * @param int $ano Ano para a exportação.
     */
    public function __construct($ano)
    {
        $this->ano = $ano;
    }

    /**
     * Mapeia cada linha de dados para a planilha.
     *
     * @param object $row Linha de dados.
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->acao,
            $row->id_pac,
            $row->responsavel,
            $row->empreendimento,
            (float) $row->credito_disponivel,
            (float) $row->saldo_empenhado,
            (float) $row->suplementacao_orcamentaria_necessaria,
            (float) $row->necessidade_financeira,
            // Adicione os meses dinâmicos conforme necessário
            // Exemplo:
            // (float) $row->vlr_financeiro_mes_1_rp2,
            // (float) $row->vlr_financeiro_mes_1_rp3,
            // ...
        ];
    }

    /**
     * Define os formatos das colunas na planilha.
     *
     * @return array
     */
    public function columnFormats(): array
    {
        // Define o formato numérico com vírgula como separador decimal para as colunas especificadas
        $formattedColumns = [
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'Y',
            'Z',
            'AB',
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AH',
            'AI',
            'AJ',
            'AK',
            'AL',
            'AM',
            'AN',
            'AO',
            'AP',
            'AQ',
            'AR',
            'AS'
        ];

        $formats = [];
        foreach ($formattedColumns as $column) {
            $formats[$column] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1;
        }

        return $formats;
    }

    /**
     * Gera a visualização para a exportação.
     *
     * @return View
     */
    public function view(): View
    {
        // Obtém os dados resumidos para o ano especificado
        $getResumoPorAnoOrcamentarioFinanceiro = $this->getResumoPorAnoOrcamentarioFinanceiro($this->ano);

        // Define as colunas da matriz
        $matrizColunas = [
            'cod_acao_orcamentaria',
            'cod_pac',
            'sigla',
            'nom_empreendimento_divulgacao',
            'vlr_credito_disponivel',
            'vlr_saldo_empenhado',
            'vlr_suplementacao_orcamentaria',
            'vlr_necessidade_financeira'
        ];

        // Obtém o mês vigente
        $mesVigente = (int) date('n');

        // Adiciona as colunas dinâmicas para cada mês a partir do mês vigente até dezembro
        for ($i = $mesVigente; $i <= 12; $i++) {
            $matrizColunas[] = 'vlr_financeiro_mes_' . $i . '_rp2';
            $matrizColunas[] = 'vlr_financeiro_mes_' . $i . '_rp3';
        }

        // Retorna a visualização com os dados necessários
        return view('pac.export.orcamentario-financeiro', [
            'ano' => $this->ano,
            'matrizColunas' => $matrizColunas,
            'getResumoPorAnoOrcamentarioFinanceiro' => $getResumoPorAnoOrcamentarioFinanceiro
        ]);
    }

    /**
     * Executa a consulta SQL otimizada para obter o resumo orçamentário e financeiro.
     *
     * @param int|null $ano Ano para a consulta. Se nulo, utiliza o ano atual.
     * @return array
     */
    protected function getResumoPorAnoOrcamentarioFinanceiro($ano = null)
    {
        // Define o ano para a consulta
        $ano = isset($ano) && !empty($ano) ? (int) $ano : (int) date('Y');

        // Inicializa a consulta com CTE (Common Table Expression) para cálculo financeiro
        $consulta = "WITH calculo_vlr_financeiro AS (
                        SELECT
                            tef.cod_acao_orcamentaria,
                            tef.cod_pac,";

        // Define o mês vigente
        $mesVigente = (int) date('n');

        // Constrói dinamicamente os cálculos para cada mês a partir do mês vigente até dezembro
        $sumMesRp = '';
        for ($i = $mesVigente; $i <= 12; $i++) {
            $sumMesRp .= "
                            SUM(CASE WHEN tef.num_rp = 2 AND tef.num_mes = $i THEN tef.vlr_financeiro ELSE 0 END) AS vlr_financeiro_mes_{$i}_rp2,";
            $sumMesRp .= "
                            SUM(CASE WHEN tef.num_rp = 3 AND tef.num_mes = $i THEN tef.vlr_financeiro ELSE 0 END) AS vlr_financeiro_mes_{$i}_rp3,";
        }

        // Remove a última vírgula
        $sumMesRp = rtrim($sumMesRp, ',');

        // Adiciona os cálculos à consulta
        $consulta .= $sumMesRp;

        // Continua a construção da consulta
        $consulta .= "
                        FROM midr_pac.tab_evolucao_financeira tef
                        WHERE tef.num_ano = $ano AND tef.deleted_at IS NULL
                        GROUP BY tef.cod_acao_orcamentaria, tef.cod_pac
                    )
                    SELECT
                        vaocp.cod_acao_orcamentaria,
                        vaocp.cod_pac,
                        vaocp.nom_empreendimento_divulgacao,
                        vaocp.sigla,
                        COALESCE(tcd.vlr_credito_disponivel, 0.00) AS vlr_credito_disponivel,
                        COALESCE(tese.vlr_saldo_empenhado, 0.00) AS vlr_saldo_empenhado,
                        COALESCE(teso.vlr_suplementacao_orcamentaria, 0.00) AS vlr_suplementacao_orcamentaria,
                        -- Cálculo da necessidade financeira otimizado
                        (";

        // Constrói a soma das necessidades financeiras para cada mês
        $sumNecessidadeFinanceira = '';
        for ($i = $mesVigente; $i <= 12; $i++) {
            $sumNecessidadeFinanceira .= "calculo_vlr_financeiro.vlr_financeiro_mes_{$i}_rp2 + ";
            $sumNecessidadeFinanceira .= "calculo_vlr_financeiro.vlr_financeiro_mes_{$i}_rp3 + ";
        }
        // Remove a última " + "
        $sumNecessidadeFinanceira = rtrim($sumNecessidadeFinanceira, ' + ');

        // Adiciona o cálculo à consulta
        $consulta .= $sumNecessidadeFinanceira;

        $consulta .= ") AS vlr_necessidade_financeira,";

        // Constrói as colunas dinâmicas para cada mês
        $sumMesRpColumns = '';
        for ($i = $mesVigente; $i <= 12; $i++) {
            $sumMesRpColumns .= "calculo_vlr_financeiro.vlr_financeiro_mes_{$i}_rp2, ";
            $sumMesRpColumns .= "calculo_vlr_financeiro.vlr_financeiro_mes_{$i}_rp3, ";
        }
        // Remove a última vírgula e espaço
        $sumMesRpColumns = rtrim($sumMesRpColumns, ', ');

        // Adiciona as colunas dinâmicas à consulta
        $consulta .= "
                        $sumMesRpColumns
                    FROM
                        midr_pac.vis_acoes_orcamentarias_por_cod_pac vaocp
                    LEFT JOIN midr_pac.tab_evolucao_credito_disponivel tcd
                        ON vaocp.cod_acao_orcamentaria = tcd.cod_acao_orcamentaria
                        AND vaocp.cod_pac = tcd.cod_pac
                        AND tcd.num_ano = $ano
                        AND tcd.deleted_at IS NULL
                    LEFT JOIN midr_pac.tab_evolucao_saldo_empenhado tese
                        ON vaocp.cod_acao_orcamentaria = tese.cod_acao_orcamentaria
                        AND vaocp.cod_pac = tese.cod_pac
                        AND tese.num_ano = $ano
                        AND tese.deleted_at IS NULL
                    LEFT JOIN midr_pac.tab_evolucao_suplementacao_orcamentaria teso
                        ON vaocp.cod_acao_orcamentaria = teso.cod_acao_orcamentaria
                        AND vaocp.cod_pac = teso.cod_pac
                        AND teso.num_ano = $ano
                        AND teso.deleted_at IS NULL
                    LEFT JOIN calculo_vlr_financeiro
                        ON vaocp.cod_acao_orcamentaria = calculo_vlr_financeiro.cod_acao_orcamentaria
                        AND vaocp.cod_pac = calculo_vlr_financeiro.cod_pac";

        // Obtém a sigla do usuário autenticado, se disponível
        $sigla = Auth::user()->lotacao->sigla ?? null;

        // Adiciona condição de filtro com base na permissão do usuário
        if (Session::get('permissao') != '0000010' && $sigla) {
            // Utiliza prepared statements para evitar SQL Injection
            $consulta .= "
                        WHERE vaocp.sigla = ?";
            $bindings = [$sigla];
        } else {
            $bindings = [];
        }

        // Finaliza a consulta com GROUP BY e ORDER BY
        $consulta .= "
                        GROUP BY
                            vaocp.cod_acao_orcamentaria,
                            vaocp.cod_pac,
                            vaocp.sigla,
                            vaocp.nom_empreendimento_divulgacao,
                            tcd.vlr_credito_disponivel,
                            tese.vlr_saldo_empenhado,
                            teso.vlr_suplementacao_orcamentaria, ";

        // Adiciona as colunas dinâmicas ao GROUP BY
        $groupByColumns = '';
        for ($i = $mesVigente; $i <= 12; $i++) {
            $groupByColumns .= "
                            calculo_vlr_financeiro.vlr_financeiro_mes_{$i}_rp2,
                            calculo_vlr_financeiro.vlr_financeiro_mes_{$i}_rp3, ";
        }
        // Remove a última vírgula e espaço
        $groupByColumns = rtrim($groupByColumns, ', ');

        // Adiciona as colunas dinâmicas ao GROUP BY
        $consulta .= $groupByColumns;

        // Adiciona a cláusula ORDER BY
        $consulta .= "
                        ORDER BY vaocp.sigla, vaocp.cod_pac, vaocp.cod_acao_orcamentaria;";

        // Executa a consulta com ou sem bindings, dependendo se há filtro de sigla
        if (!empty($bindings)) {
            return DB::select($consulta, $bindings);
        } else {
            return DB::select($consulta);
        }
    }
}
