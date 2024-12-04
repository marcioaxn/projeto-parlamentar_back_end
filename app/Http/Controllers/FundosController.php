<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\TabFundos;
use App\Models\TabFundosConsolidadaFinanciamentoFinalidade;
use App\Models\TabFundosConsolidadaUfFinanciamentoFinalidade;

use App\Fpdf\FfpdfFundos;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Dompdf\Dompdf;
use Dompdf\Options;

use Illuminate\Support\Facades\DB;

use Auth;
use Illuminate\Http\Request;

ini_set('memory_limit', '5096M');
ini_set('max_execution_time', 5500);
set_time_limit(900000000);

class FundosController extends Controller
{

    protected $perfil = null;
    protected $bln_acesso_inrestrito = null;
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getFundosResumoValores($sglUf = null)
    {

        $ufQueNaoFazemParteDosFundos = ['PR', 'RJ', 'RS', 'SC', 'SP'];

        if (!in_array($sglUf, $ufQueNaoFazemParteDosFundos)) {
            $sglUf = passarTextoParaMinusculo($sglUf);

            $table = 'tab_fundos_resumo_valores_' . $sglUf;
            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

            return $model::first();
        } else {
            return null;
        }
    }

    public function getFundosResumoValoresMunicipio($sglUf = null, $codMunicipio = null)
    {

        $ufQueNaoFazemParteDosFundos = ['PR', 'RJ', 'RS', 'SC', 'SP'];

        if (isset($sglUf) && !empty($sglUf) && isset($codMunicipio) && !empty($codMunicipio)) {

            if (!in_array($sglUf, $ufQueNaoFazemParteDosFundos)) {

                return DB::selectOne("SELECT * FROM midr_snfi.fnc_obter_resumo_valores_municipio('" . $sglUf . "', " . $codMunicipio . ");");
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getFundosResumoValoresMunicipios($sglUf = null, $codMunicipio = null)
    {
        $ufQueNaoFazemParteDosFundos = ['PR', 'RJ', 'RS', 'SC', 'SP'];

        if (!in_array($sglUf, $ufQueNaoFazemParteDosFundos)) {
            $sglUf = passarTextoParaMinusculo($sglUf);

            $table = 'tab_fundos_resumo_valores_municipio_' . $sglUf;
            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

            $return = $model::orderBy('cod_municipio')
                ->orderBy('num_ano', 'DESC');

            if (isset($codMunicipio) && !empty($codMunicipio)) {

                $return = $return->where('cod_municipio', $codMunicipio);
            }

            $return = $return->get();

            if ($return) {
                return $return;
            } else {
                return [];
            }
        } else {
            return null;
        }
    }

    public function index(Request $request)
    {
        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        $input = $request->all();

        // Variáveis contendo a matriz de consulta e variáveis de consulta
        $sgl_ufs = $this->getPluckSglUf();
        $sgl_uf = [];

        $filtros = [];

        $getTabFundosConsolidadaFinanciamentoFinalidade = $this->getTabFundosConsolidadaFinanciamentoFinalidade();

        $variaveisConsulta = ['sgl_uf', 'dsc_tipo_fundo', 'dsc_linha_financiamento', 'dsc_finalidade_operacao', 'num_ano'];

        // Início loop para pecorrer as variáveis de consulta
        foreach ($variaveisConsulta as $variavelConsulta) {

            ${$variavelConsulta} = null;

            if ($variavelConsulta != 'sgl_uf') {

                ${$variavelConsulta . 's'} = $this->getPluckFundos($variavelConsulta);
            }

            // Início do IF para tratamento dos itens que vieram do POST
            if ($input) {

                if (isset($input['sgl_uf']) && !is_null($input['sgl_uf']) && $input['sgl_uf'] != '') {
                    $getTabFundosConsolidadaFinanciamentoFinalidade = $this->getTabFundosConsolidadaFinanciamentoFinalidade('sim');
                } else {
                    $getTabFundosConsolidadaFinanciamentoFinalidade = $this->getTabFundosConsolidadaFinanciamentoFinalidade();
                }

                // Início do loop entre os elementos contidos no $input da página de consulta aos parlamentares
                foreach ($input as $key => $value) {

                    if ($key != '_method' && $key != '_token' && $key != 'gerar_relatorio_pdf') {

                        if (isset($value) && !is_null($value) && $value != '') {

                            // Início do trecho para 'SETar' as variáveis $variaveisConsulta, que representam os
                            // filtros feito pelo usuário do sistema

                            ${$key} = $value;
                            $filtros[$key] = $value;

                            if ($variavelConsulta != 'sgl_uf' && $key != $variavelConsulta) {
                                ${$variavelConsulta . 's'} = ${$variavelConsulta . 's'}->whereIn($key, $value);
                            }

                            $getTabFundosConsolidadaFinanciamentoFinalidade = $getTabFundosConsolidadaFinanciamentoFinalidade->whereIn($key, $value);
                        }
                    }
                }
                // Fim do loop entre os elementos contidos no $input da página de consulta aos parlamentares

                // $getParlamentaresParaFiltros = $tabParlamentaresController->getParlamentaresParaFiltros($filtros);

            }
            // Fim do IF para tratamento dos itens que vieram do POST
            else {
                if ($variavelConsulta != 'num_ano') {
                    ${$variavelConsulta} = null;
                } elseif ($variavelConsulta === 'num_ano') {
                    $num_ano = date('Y');
                    $getTabFundosConsolidadaFinanciamentoFinalidade = $getTabFundosConsolidadaFinanciamentoFinalidade->whereIn($variavelConsulta, [$num_ano]);
                }
            }

            ${$variavelConsulta . 's'} = ${$variavelConsulta . 's'}->pluck($variavelConsulta, $variavelConsulta);
        }
        // Fim loop para pecorrer as variáveis de consulta

        $getTabFundosConsolidadaFinanciamentoFinalidade = $getTabFundosConsolidadaFinanciamentoFinalidade->get();

        // $input['gerar_relatorio_pdf'] = 'sim';

        if ($input) {
            if (isset($input['gerar_relatorio_pdf']) && !is_null($input['gerar_relatorio_pdf']) && $input['gerar_relatorio_pdf'] != '') {

                $this->gerarPdfRelatorio($getTabFundosConsolidadaFinanciamentoFinalidade, $filtros);
            }
        }

        if ($getTabFundosConsolidadaFinanciamentoFinalidade) {
            $firstRecord = $getTabFundosConsolidadaFinanciamentoFinalidade->first();
            if ($firstRecord) {
                $columnsCount = count($firstRecord->toArray());
            } else {
                $columnsCount = 0; // Ou qualquer valor padrão que você queira atribuir se não houver registros
            }
        } else {
            $columnsCount = 0; // Ou qualquer valor padrão que você queira atribuir se a consulta falhar
        }

        return view('fundos.index')
            ->with('variaveisConsulta', $variaveisConsulta)
            ->with('sgl_ufs', $sgl_ufs)
            ->with('sgl_uf', $sgl_uf)
            ->with('dsc_tipo_fundos', $dsc_tipo_fundos)
            ->with('dsc_tipo_fundo', $dsc_tipo_fundo)
            ->with('dsc_linha_financiamentos', $dsc_linha_financiamentos)
            ->with('dsc_linha_financiamento', $dsc_linha_financiamento)
            ->with('dsc_finalidade_operacaos', $dsc_finalidade_operacaos)
            ->with('dsc_finalidade_operacao', $dsc_finalidade_operacao)
            ->with('num_anos', $num_anos)
            ->with('num_ano', $num_ano)
            ->with('columnsCount', $columnsCount)
            ->with('filtros', $filtros)
            ->with('getTabFundosConsolidadaFinanciamentoFinalidade', $getTabFundosConsolidadaFinanciamentoFinalidade);
    }

    public function gerarPdf(Request $request, $filtros = null)
    {
        $input = $request->all();

        dd($input, $filtros);
    }

    public function getTabFundosConsolidadaFinanciamentoFinalidade($sgl_uf = null)
    {

        if (isset($sgl_uf) && !is_null($sgl_uf) && $sgl_uf != '') {
            return TabFundosConsolidadaUfFinanciamentoFinalidade::orderBy('sgl_uf')
                ->orderBy('dsc_tipo_fundo');
        } else {
            return TabFundosConsolidadaFinanciamentoFinalidade::orderBy('dsc_tipo_fundo');
        }
    }

    public function getPluckSglUf()
    {
        return TabFundosConsolidadaUfFinanciamentoFinalidade::orderBy('sgl_uf');
    }

    public function getPluckFundos($column_name = null)
    {
        return TabFundosConsolidadaUfFinanciamentoFinalidade::orderBy($column_name)
            ->whereNotNull($column_name);
    }

    public function gerarPdfRelatorio($data = null, $filtros = null)
    {

        if ($data) {
            $firstRecord = $data->first();
            if ($firstRecord) {
                $columnsCount = count($firstRecord->toArray());
            } else {
                $columnsCount = 0; // Ou qualquer valor padrão que você queira atribuir se não houver registros
            }
        } else {
            $columnsCount = 0; // Ou qualquer valor padrão que você queira atribuir se a consulta falhar
        }

        $options = new Options();
        $options->set('isPhpEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'landscape');

        $html = view('fundos.pdf.relatorios.resumo-fundos-por-tipo-investimento-linha-financiamento-e-finalidade-operacao')
            ->with('getTabFundosConsolidadaFinanciamentoFinalidade', $data)->with('columnsCount', $columnsCount)->with('filtros', $filtros)->render();

        $dompdf->loadHtml($html);
        $dompdf->render();

        $nameFile = date('Ymd-His') . '-relatorios.resumo-fundos-por-tipo-investimento-linha-financiamento-e-finalidade-operacao.pdf';

        // return response($dompdf->output())
        //     ->header('Content-Type', 'application/pdf');

        return $dompdf->stream($nameFile);
    }

    public function resumoFundosPorTipoInvestimentoLinhaFinanciamentoEFinalidadeOperacao(Request $request)
    {
        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        $input = $request->all();

        $getTabFundosConsolidadaFinanciamentoFinalidade = $this->getTabFundosConsolidadaFinanciamentoFinalidade();

        return view('fundos.pdf.relatorios.resumo-fundos-por-tipo-investimento-linha-financiamento-e-finalidade-operacao')
            ->with('getTabFundosConsolidadaFinanciamentoFinalidade', $getTabFundosConsolidadaFinanciamentoFinalidade);
    }

    public function pdf(Request $request)
    {

        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        $input = $request->all();

        $getTabFundosConsolidadaFinanciamentoFinalidade = $this->getTabFundosConsolidadaFinanciamentoFinalidade();

        $options = new Options();
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'landscape');

        $str_html = '<table border = "1">';

        $str_html .= '<tr>
						<td>Tipo</td>
						<td>Linha de financiamento</td>
						<td>Finalidade da operação</td>
						<td>Valor do Saldo da Carteira</td>
						<td>Valor do Saldo em Atraso</td>
						<td>Valor Contratado</td>
						<td>Valor Desembolsado</td>
					</tr>
					';

        foreach ($getTabFundosConsolidadaFinanciamentoFinalidade as $value) {
            $str_html .= '<tr>
							<td>' . $value->dsc_tipo_fundo . '</td>
							<td>' . $value->dsc_linha_financiamento . '</td>
							<td>' . $value->dsc_finalidade_operacao . '</td>
							<td>' . $value->vlr_saldo_carteira . '</td>
							<td>' . $value->vlr_saldo_atraso . '</td>
							<td>' . $value->vlr_contratado . '</td>
							<td>' . $value->vlr_desembolsado . '</td>
						</tr>
						';
        }

        $str_html .= '</table>';

        $dompdf->load_html($str_html);

        // (Optional) Setup the paper size and orientation
        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('testing.pdf');

        // return view('fundos.index')
        //     ->with('getTabFundosConsolidadaFinanciamentoFinalidade', $getTabFundosConsolidadaFinanciamentoFinalidade);
    }

    public function indexA4(Request $request)
    {

        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        $input = $request->all();

        $getTabFundosConsolidadaFinanciamentoFinalidade = $this->getTabFundosConsolidadaFinanciamentoFinalidade();

        $pdf = new FfpdfFundos("P", "mm", "A4");

        $pdf->AddPage();

        $pdf->SetTextColor(19, 81, 180);

        $pdf->SetXY(9, 27);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(31, 7, utf8_decode("'Tipo'"), 'T,B,L,R', 0, 'L', 0);
        $pdf->Cell(56, 7, utf8_decode("Linha de financiamento"), 'T,B,L,R', 0, 'L', 0);
        $pdf->Cell(55, 7, utf8_decode("Finalidade da operação"), 'T,B,L,R', 0, 'L', 0);
        $pdf->Cell(53, 7, utf8_decode("Total"), 'T,B,L,R', 0, 'R', 0);

        $pdf->Output();
        exit;

        // return view('fundos.index')
        //     ->with('getTabFundosConsolidadaFinanciamentoFinalidade', $getTabFundosConsolidadaFinanciamentoFinalidade);
    }
}
