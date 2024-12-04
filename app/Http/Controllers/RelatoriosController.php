<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;

use App\Http\Controllers\TabParlamentaresController;
use App\Http\Controllers\ParlamentarController;
use App\Http\Controllers\TabApiCamaraOrgaosController;
use App\Http\Controllers\TabIbgeController;
use App\Fpdf\Ffpdf;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Alignment;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TableRow;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;
use Illuminate\Support\Facades\Response;
use App\Services\WordService;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BaseParlamentaresFederaisExport;

use Illuminate\Support\Facades\Session;

use Ramsey\Uuid\Uuid;

ini_set('memory_limit', '2096M');
ini_set('max_execution_time', 5500);
set_time_limit(900000000);

class RelatoriosController extends Controller
{

    protected $perfil = null;
    protected $bln_acesso_inrestrito = null;
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function instanciarTabParlamentaresController()
    {
        return new TabParlamentaresController;
    }

    public function instanciarParlamentarController()
    {
        return new ParlamentarController;
    }

    public function instanciarTabApiCamaraOrgaosController()
    {
        return new TabApiCamaraOrgaosController;
    }

    public function instanciarTabIbgeController()
    {
        return new TabIbgeController;
    }

    public function index(Request $request)
    {
        $input = $request->all();

        $tabParlamentares = $this->instanciarTabParlamentaresController();

        $partidosSelect = $tabParlamentares->getPluckPartidos();
        $casaSelect = $tabParlamentares->getPluckCasa();
        $ufRepresentacaoSelect = $tabParlamentares->getPluckUFRepresentacao();

        return view('relatorios.index')
            ->with('partidosSelect', $partidosSelect)
            ->with('casaSelect', $casaSelect)
            ->with('ufRepresentacaoSelect', $ufRepresentacaoSelect);
    }

    public function resultadoPagina(Request $request)
    {
        $input = $request->all();

        $sglPartidos = [];
        $dscCasa = [];
        $sglUfRepresentante = $input['sgl_uf_representante'];

        // dd($input);

        // Início novo
        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        $bln_acesso_inrestrito = $this->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        // Início do tratamento dos dados para gerar o arquivo Word

        $tabParlamentares = $this->instanciarTabParlamentaresController();
        $tabIbge = $this->instanciarTabIbgeController();

        // Fim novo

        return view('relatorios.resultado')
            ->with('sglPartidos', $sglPartidos)
            ->with('dscCasa', $dscCasa)
            ->with('sglUfRepresentante', $sglUfRepresentante)
            ->with('tabParlamentares', $tabParlamentares);

    }

    public function alterarDescricaoLideranca($descricaoTipoLideranca = null)
    {
        switch ($descricaoTipoLideranca) {
            case 'Líder do Congresso Nacional':
                $descricaoTipoLideranca = 'Líder ';
                break;

            case 'Partido Político':
                $descricaoTipoLideranca = 'partido ';
                break;

            default:
                $descricaoTipoLideranca = $descricaoTipoLideranca;
                break;

        }

        return $descricaoTipoLideranca;
    }

    public function carometroPorPartido($sglPartidos = null, $dscCasa = null, $sglUfRepresentante = null)
    {

        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        $bln_acesso_inrestrito = $this->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        $pdf = new Ffpdf("P", "mm", "A4");

        if (isset($sglPartidos) && !is_null($sglPartidos) && $sglPartidos != '' && $sglPartidos != 'Todos') {
            $matrizPartidos = explode(',', $sglPartidos);
        } else {
            $matrizPartidos = null;
        }

        if (isset($dscCasa) && !is_null($dscCasa) && $dscCasa != '' && $dscCasa != 'Todas') {
            $matrizCasas = explode(',', $dscCasa);
        } else {
            $matrizCasas = null;
        }

        if (isset($sglUfRepresentante) && !is_null($sglUfRepresentante) && $sglUfRepresentante != '' && $sglUfRepresentante != 'Todas') {
            $matrizUfs = explode(',', $sglUfRepresentante);
        } else {
            $matrizUfs = null;
        }

        $tabParlamentares = $this->instanciarTabParlamentaresController();

        $partidos = $tabParlamentares->getPartidos($matrizPartidos);

        foreach ($partidos as $partido) {

            $parlamentares = $tabParlamentares->getParlamentaresPorPartido($partido->sgl_partido, $matrizCasas, $matrizUfs);

            if ($parlamentares->count() > 0) {
                $pdf->AddPage();

                $pdf->SetXY(4, 5);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(105, 105, 105);
                $pdf->SetDrawColor(255, 255, 255);
                $pdf->MultiCell(201, 7, utf8_decode($partido->sgl_partido), "B,T,L,R", 'L', TRUE);

                $contLinha = 20;
                $cont = 0;
                $mod = 0;

                $altura1 = 0;
                $altura2 = 0;

                foreach ($parlamentares as $parlamentar) {

                    $parlamentar->nom_parlamentar = substr($parlamentar->nom_parlamentar, 0, 30);

                    // Início do IF para incluir mais páginas por partido
                    if ($cont == 8 || $cont == 16 || $cont == 24 || $cont == 32 || $cont == 40 || $cont == 48 || $cont == 56 || $cont == 64 || $cont == 72 || $cont == 80 || $cont == 88 || $cont == 96 || $cont == 104 || $cont == 112 || $cont == 120 || $cont == 128 || $cont == 136 || $cont == 144 || $cont == 152 || $cont == 160 || $cont == 168 || $cont == 176 || $cont == 184 || $cont == 192 || $cont == 200 || $cont == 208 || $cont == 216 || $cont == 224 || $cont == 232) {

                        $pdf->AddPage();

                        $pdf->SetXY(4, 5);

                        $pdf->SetFont('Arial', 'B', 12);
                        $pdf->SetTextColor(255, 255, 255);
                        $pdf->SetFillColor(105, 105, 105);
                        $pdf->SetDrawColor(255, 255, 255);
                        $pdf->MultiCell(201, 7, utf8_decode($partido->sgl_partido), "B,T,L,R", 'L', TRUE);

                        $altura1 = 13;
                        $altura2 = 17;
                    }
                    // Fim do IF para incluir mais páginas por partido

                    // Início para verificar se o deputado federal exercer alguma liderança
                    $cargosLiderancas = null;
                    // Senadores
                    if ($parlamentar->dsc_casa === 'Senado Federal') {

                        $cargosMesaDiretora = null;

                        if ($parlamentar->cargosMesaDiretoraSenado) {

                            $cargosMesaDiretora = $parlamentar->cargosMesaDiretoraSenado->Cargo;

                            if ($cargosMesaDiretora === 'PRESIDENTE') {
                                $cargosMesaDiretora = 'PRESIDENTE DO SENADO FEDERAL';
                            } else {
                                $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DO SENADO';
                            }

                        }

                        if ($parlamentar->liderancaSenadores) {

                            $contLideranca = 1;

                            foreach ($parlamentar->liderancaSenadores as $key => $lideranca) {
                                if (isset($lideranca->SiglaPartido) && !is_null($lideranca->SiglaPartido) && $lideranca->SiglaPartido != '') {

                                    $cargosLiderancas .= $contLideranca . '. ' . retornaTextoTirandoParteDoTexto($this->alterarDescricaoLideranca($lideranca->DescricaoTipoLideranca), ' do Senado Federal');
                                    $cargosLiderancas .= ' do ' . retornaTextoTirandoParteDoTexto($lideranca->SiglaPartido, 'Congresso Nacional') . ' no ' . $lideranca->SiglaCasaLideranca;
                                    $cargosLiderancas .= '; ';

                                } else {

                                    $cargosLiderancas .= $contLideranca . '. ' . $lideranca->UnidadeLideranca;

                                    isset($lideranca->NomeBloco) && !is_null($lideranca->NomeBloco) && $lideranca->NomeBloco != '' ? $cargosLiderancas .= 'do ' . $lideranca->NomeBloco : '';
                                    $cargosLiderancas .= '; ';

                                }

                                $contLideranca++;
                            }

                            foreach ($parlamentar->cargosSenadores as $cargo) {
                                if (!is_null($cargo->colegiadoAtivo)) {
                                    $cargosLiderancas .= $contLideranca . '. ' . primeiraLetraMaiuscula($cargo->DescricaoCargo) . ' do(a) ' . $cargo->SiglaComissao;
                                    $cargosLiderancas .= '; ';
                                    $contLideranca++;
                                }
                            }

                            $cargosLiderancas = trim($cargosLiderancas, '; ');
                        }

                    }

                    // Deputados federais
                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                        $cargosMesaDiretora = null;

                        if ($parlamentar->cargosMesaDiretora) {

                            $cargosMesaDiretora = $parlamentar->cargosMesaDiretora->titulo;

                            if ($cargosMesaDiretora === 'Presidente') {
                                $cargosMesaDiretora = 'PRESIDENTE DA CÂMARA DOS DEPUTADOS';
                            } else {
                                $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DIRETORA';
                            }

                        }

                        if ($parlamentar->liderancaDeputados) {

                            $contLideranca = 1;

                            foreach ($parlamentar->liderancaDeputados as $key => $lideranca) {
                                $cargosLiderancas .= $contLideranca . '. ' . $lideranca->titulo . ' do(a) ' . $this->alterarDescricaoLideranca($lideranca->tipo);
                                $lideranca->nome != $lideranca->tipo ? $cargosLiderancas .= $this->alterarDescricaoLideranca($lideranca->nome) : '';

                                $contLideranca++;
                            }

                            $cargosLiderancas = trim($cargosLiderancas, ', ');
                        }
                    }
                    // Fim para verificar se o deputado federal exercer alguma liderança

                    // Início de recuperar a legislatura dos Deputados Federais
                    $legislaturas = null;
                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                        if ($parlamentar->legislaturasDeputado->count() > 0) {

                            foreach ($parlamentar->legislaturasDeputado as $legislatura) {
                                $legislaturas .= $legislatura->legislatura . '/';
                            }

                            $legislaturas = trim($legislaturas, '/');

                        }
                    }
                    // Fim de recuperar a legislatura dos Deputados Federais

                    // Início de recuperar a legislatura dos Deputados Federais
                    if ($parlamentar->dsc_casa === 'Senado Federal') {

                        if ($parlamentar->legislaturasSenado->count() > 0) {

                            foreach ($parlamentar->legislaturasSenado as $legislatura) {
                                $legislaturas .= $legislatura->legislatura . '/';
                            }

                            $legislaturas = trim($legislaturas, '/');

                        }
                    }
                    // Fim de recuperar a legislatura dos Deputados Federais

                    // Início de recuperar o celular
                    $celulares = null;
                    if ($bln_acesso_inrestrito == 1) {

                        if ($parlamentar->celulares->count() > 0) {
                            $contCelular = 1;
                            foreach ($parlamentar->celulares as $celular) {
                                if ($contCelular <= 3) {
                                    $celulares .= applyMask($celular->num_celular, '(##) #####-####') . ' / ';
                                }
                                $contCelular++;
                            }

                            $celulares = trim($celulares, ' / ');
                        }

                    }
                    // Fim de recuperar o celular

                    // Início de recuperar o número de telefone do gabinete
                    $telefoneGabinete = null;
                    if ($parlamentar->num_telefone != '') {

                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                            $telefoneGabinete = '(61) ' . $parlamentar->num_telefone;
                        }

                        if ($parlamentar->dsc_casa === 'Senado Federal') {
                            $telefoneGabinete = applyMask('61' . $parlamentar->num_telefone, '(##) ####-####');
                        }

                    }
                    // Fim de recuperar o número de telefone do gabinete

                    // Início de recuperar o e-mail do gabinete do parlamentar
                    $emailGabienete = null;
                    if ($parlamentar->dsc_email != '') {

                        $emailGabienete = $parlamentar->dsc_email;

                    }
                    // Fim de recuperar o e-mail do gabinete do parlamentar

                    // Início de recuperar as comissões onde é titular
                    $comissoes = null;
                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                        if ($parlamentar->comissoesDeputados->count() > 0) {
                            foreach ($parlamentar->comissoesDeputados as $comissao) {
                                if (substr($comissao->siglaOrgao, 0, 1) === 'C') {
                                    $comissoes .= $comissao->siglaOrgao . ', ';
                                }

                            }

                            $comissoes = trim($comissoes, ', ');
                        }

                    }

                    if ($parlamentar->dsc_casa === 'Senado Federal') {
                        if ($parlamentar->comissoesSenadores->count() > 0) {
                            foreach ($parlamentar->comissoesSenadores as $comissao) {

                                if (substr($comissao->SiglaComissao, 0, 1) === 'C') {
                                    $comissoes .= $comissao->SiglaComissao . ', ';
                                }

                            }

                            $comissoes = trim($comissoes, ', ');
                        }
                    }
                    // Fim de recuperar as comissões onde é titular

                    $mod = $cont % 2;

                    $alturaFundo = 68;

                    $alturaFoto = 36.4;

                    $larguraFoto = 30;

                    $paddingLeftA = 36;

                    $paddingLeftB = 137;

                    $fotoParlamentar = null;

                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                        $fotoParlamentar = public_path('storage/fotos/deputados/' . $parlamentar->cod_parlamentar . '.jpg');

                    } else if ($parlamentar->dsc_casa === 'Senado Federal') {
                        $fotoParlamentar = public_path('storage/fotos/senadores/' . $parlamentar->cod_parlamentar . '.jpg');
                    }

                    if ($mod == 0) {

                        $cont == 0 || $cont == 8 || $cont == 16 || $cont == 24 || $cont == 32 || $cont == 40 || $cont == 48 || $cont == 56 || $cont == 64 || $cont == 72 || $cont == 80 || $cont == 88 || $cont == 96 || $cont == 104 || $cont == 112 || $cont == 120 || $cont == 128 || $cont == 136 || $cont == 144 || $cont == 152 || $cont == 160 || $cont == 168 || $cont == 176 || $cont == 184 || $cont == 192 || $cont == 200 || $cont == 208 || $cont == 216 || $cont == 224 || $cont == 232 ? $altura1 = 13 : $altura1 = $altura1 + 67;

                        $cont == 0 || $cont == 8 || $cont == 16 || $cont == 24 || $cont == 32 || $cont == 40 || $cont == 48 || $cont == 56 || $cont == 64 || $cont == 72 || $cont == 80 || $cont == 88 || $cont == 96 || $cont == 104 || $cont == 112 || $cont == 120 || $cont == 128 || $cont == 136 || $cont == 144 || $cont == 152 || $cont == 160 || $cont == 168 || $cont == 176 || $cont == 184 || $cont == 192 || $cont == 200 || $cont == 208 || $cont == 216 || $cont == 224 || $cont == 232 ? $altura2 = 13 : $altura2 = $altura2 + 67;

                        $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'bg9.jpg'), 4, $altura1, 100, $alturaFundo);

                        try {

                            $pdf->Image($fotoParlamentar, 5.4, $altura1 + 2.5, $larguraFoto, $alturaFoto);

                        } catch (\Exception $e) {
                            $fotoParlamentar = public_path('storage/fotos/avatar/avatar.jpg');
                            $pdf->Image($fotoParlamentar, 5.4, $altura1 + 2.5, $larguraFoto, $alturaFoto);
                        }

                        $pdf->Ln(10);
                        $pdf->SetXY($paddingLeftA, $altura1 + 2.1);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', '', 8.5);
                        $pdf->Cell(100, 5, utf8_decode($parlamentar->dsc_tratamento . ' - ' . $parlamentar->dsc_participacao . ' - ' . $partido->sgl_partido . ' - ' . $parlamentar->sgl_uf_representante), '', 0, 'L', 0);

                        $pdf->Ln(4.7);
                        $pdf->SetX($paddingLeftA);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($parlamentar->nom_parlamentar, 'UTF-8')), '', 0, 'L', 0);

                        $pdf->Ln(4.1);
                        $pdf->SetX($paddingLeftA);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->Cell(100, 5, utf8_decode('Legislatura(s): ' . $legislaturas), '', 0, 'L', 0);

                        if (Session::get('permissao') === '0010000' || Session::get('permissao') === '0000100') {

                            if ($celulares != '') {

                                $pdf->Ln(4);

                                $pdf->SetX($paddingLeftA);
                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFont('Arial', '', 7.5);
                                $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($celulares, 'UTF-8')), '', 0, 'L', 0);
                            }

                        }

                        if ($telefoneGabinete != '') {

                            $pdf->Ln(4);

                            $pdf->SetX($paddingLeftA);
                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFont('Arial', '', 7.5);
                            $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($telefoneGabinete, 'UTF-8')), '', 0, 'L', 0);
                        }

                        if ($emailGabienete != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftA);
                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($emailGabienete), '', 0, 'L', 0);
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftA);
                            $pdf->Cell(9, 5, utf8_decode('Nasc.: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($parlamentar->sgl_uf_nascimento . '/' . $parlamentar->nom_municipio_nascimento), '', 0, 'L', 0);
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftA);
                            $pdf->Cell(17 - 1, 5, utf8_decode('Data Nasc.: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, formatarDataComCarbonParaBR($parlamentar->dte_nascimento), '', 0, 'L', 0);
                        }

                        if ($parlamentar->dsc_participacao === 'Titular') {

                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftA);
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(29, 5, utf8_decode('Ano / Votos / Reeleito: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($parlamentar->num_ano_eleicao . ' / ' . formatarNumeroInteiro($parlamentar->num_total_votos) . ' / ' . $parlamentar->dsc_reeleito), '', 0, 'L', 0);
                        }

                        if ($cargosMesaDiretora != '') {

                            if ($cargosMesaDiretora === 'PRESIDENTE DO SENADO FEDERAL') {
                                $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'presidente_senado.jpg'), 5.2, $altura1 + 40.9, 4, 3);

                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftA - 27, $altura1 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(94, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            } elseif ($cargosMesaDiretora === 'PRESIDENTE DA CÂMARA DOS DEPUTADOS') {
                                $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'presidente_camara.jpg'), 5.2, $altura1 + 40.5, 4, 3);

                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftA - 27, $altura1 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(94, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            } else {
                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftA - 30.5, $altura1 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(97.5, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            }
                        }

                        if ($cargosLiderancas != '') {
                            $pdf->Ln(1);

                            if ($cargosMesaDiretora == '') {
                                $pdf->SetXY($paddingLeftA - 30.5, $altura1 + 40);
                            } else {
                                $pdf->SetX($paddingLeftA - 30.5);
                            }

                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFillColor(241, 242, 248);
                            $pdf->SetDrawColor(255, 255, 255);
                            $pdf->SetFont('Arial', '', 7);
                            $pdf->MultiCell(97.5, 4, utf8_decode($cargosLiderancas), "", 'L', TRUE);
                        }

                        if ($comissoes != '') {
                            $pdf->Ln(1);

                            if ($cargosLiderancas == '' && $cargosMesaDiretora == '') {
                                $pdf->SetXY($paddingLeftA - 30.5, $altura1 + 40);
                            } else {
                                $pdf->SetX($paddingLeftA - 30.5);
                            }

                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFillColor(241, 242, 248);
                            $pdf->SetDrawColor(255, 255, 255);
                            $pdf->SetFont('Arial', '', 7);
                            $pdf->MultiCell(97.5, 4, utf8_decode($comissoes), "", 'L', TRUE);
                        }

                    } else {

                        $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'bg9.jpg'), 105, $altura1, 100, $alturaFundo);

                        try {

                            $pdf->Image($fotoParlamentar, 106.4, $altura2 + 2.5, $larguraFoto, $alturaFoto);

                        } catch (\Exception $e) {
                            $fotoParlamentar = public_path('storage/fotos/avatar/avatar.jpg');
                            $pdf->Image($fotoParlamentar, 106.4, $altura2 + 2.5, $larguraFoto, $alturaFoto);
                        }

                        $pdf->Ln(10);
                        $pdf->SetXY($paddingLeftB, $altura2 + 2.1);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', '', 8.5);
                        $pdf->Cell(100, 5, utf8_decode($parlamentar->dsc_tratamento . ' - ' . $parlamentar->dsc_participacao . ' - ' . $partido->sgl_partido . ' - ' . $parlamentar->sgl_uf_representante), '', 0, 'L', 0);

                        $pdf->Ln(4.7);
                        $pdf->SetX($paddingLeftB);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($parlamentar->nom_parlamentar, 'UTF-8')), '', 0, 'L', 0);

                        $pdf->Ln(4.1);
                        $pdf->SetX($paddingLeftB);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->Cell(100, 5, utf8_decode('Legislatura(s): ' . $legislaturas), '', 0, 'L', 0);

                        if (Session::get('permissao') === '0010000' || Session::get('permissao') === '0000100') {

                            if ($celulares != '') {

                                $pdf->Ln(4);

                                $pdf->SetX($paddingLeftB);
                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFont('Arial', '', 7.5);
                                $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($celulares, 'UTF-8')), '', 0, 'L', 0);
                            }

                        }

                        if ($telefoneGabinete != '') {

                            $pdf->Ln(4);

                            $pdf->SetX($paddingLeftB);
                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFont('Arial', '', 7.5);
                            $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($telefoneGabinete, 'UTF-8')), '', 0, 'L', 0);
                        }

                        if ($emailGabienete != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftB);
                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($emailGabienete), '', 0, 'L', 0);
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftB);
                            $pdf->Cell(9, 5, utf8_decode('Nasc.: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($parlamentar->sgl_uf_nascimento . '/' . $parlamentar->nom_municipio_nascimento), '', 0, 'L', 0);
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftB);
                            $pdf->Cell(17 - 1, 5, utf8_decode('Data Nasc.: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, formatarDataComCarbonParaBR($parlamentar->dte_nascimento), '', 0, 'L', 0);
                        }

                        if ($parlamentar->dsc_participacao === 'Titular') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftB);
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(29, 5, utf8_decode('Ano / Votos / Reeleito: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($parlamentar->num_ano_eleicao . ' / ' . formatarNumeroInteiro($parlamentar->num_total_votos) . ' / ' . $parlamentar->dsc_reeleito), '', 0, 'L', 0);
                        }

                        if ($cargosMesaDiretora != '') {

                            if ($cargosMesaDiretora === 'PRESIDENTE DO SENADO FEDERAL') {
                                $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'presidente_senado.jpg'), 106.2, $altura2 + 40.9, 4, 3);

                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftB - 27, $altura2 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(94, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            } elseif ($cargosMesaDiretora === 'PRESIDENTE DA CÂMARA DOS DEPUTADOS') {
                                $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'presidente_camara.jpg'), 106.2, $altura2 + 40.5, 4, 3);

                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftB - 27, $altura2 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(94, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            } else {
                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftB - 30.5, $altura2 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(97.5, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            }
                        }

                        if ($cargosLiderancas != '') {
                            $pdf->Ln(1);

                            if ($cargosMesaDiretora == '') {
                                $pdf->SetXY($paddingLeftB - 30.5, $altura2 + 40);
                            } else {
                                $pdf->SetX($paddingLeftB - 30.5);
                            }

                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFillColor(241, 242, 248);
                            $pdf->SetDrawColor(255, 255, 255);
                            $pdf->SetFont('Arial', '', 7);
                            $pdf->MultiCell(97.5, 4, utf8_decode($cargosLiderancas), "", 'L', TRUE);
                        }

                        if ($comissoes != '') {
                            $pdf->Ln(1);

                            if ($cargosLiderancas == '' && $cargosMesaDiretora == '') {
                                $pdf->SetXY($paddingLeftB - 30.5, $altura2 + 40);
                            } else {
                                $pdf->SetX($paddingLeftB - 30.5);
                            }

                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFillColor(241, 242, 248);
                            $pdf->SetDrawColor(255, 255, 255);
                            $pdf->SetFont('Arial', '', 7);
                            $pdf->MultiCell(97.5, 4, utf8_decode($comissoes), "", 'L', TRUE);
                        }

                    }

                    $cont++;

                }

                // Início do 'siglário' dos Órgãos da Cãmara dos Deputados

                /*
                $tabApiCamaraOrgaos = $this->instanciarTabApiCamaraOrgaosController();

                $getCamaraOrgaos = $tabApiCamaraOrgaos->getCamaraOrgaos();

                $pdf->AddPage();

                $pdf->SetXY(9, 5);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(105, 105, 105);
                $pdf->SetDrawColor(255, 255, 255);
                $pdf->MultiCell(195, 7, utf8_decode('Glossário'), "B,T,L,R", 'L', TRUE);

                $pdf->Ln(3);

                foreach ($getCamaraOrgaos as $key => $value) {

                    $pdf->SetFont('Arial', '', 8);
                    $pdf->SetTextColor(105, 105, 105);

                    $pdf->SetWidths(array(29, 164.5));

                    $pdf->RowComBorda(array(utf8_decode($value->siglaOrgao), utf8_decode($value->nomeOrgao)));

                }
                */

                // Fim do 'siglário' dos Órgãos da Cãmara dos Deputados

            } else {

                /*
                $pdf->AddPage();

                $pdf->SetXY(4, 5);

                $pdf->Ln(5);
                $pdf->SetXY(4, 5);

                $pdf->SetTextColor(32, 36, 67);
                $pdf->SetFillColor(241, 242, 248);
                $pdf->SetDrawColor(255, 255, 255);
                $pdf->SetFont('Arial', '', 12);
                $pdf->MultiCell(195, 5, utf8_decode("Sem resultado para os filtros aplicados."), "", 'L', TRUE);
                */

            }

        }

        $pdf->Output();
        exit;

    }

    public function carometroPorUF($sglPartidos = null, $dscCasa = null, $sglUfRepresentante = null)
    {

        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        $bln_acesso_inrestrito = $this->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        $pdf = new Ffpdf("P", "mm", "A4");

        if (isset($sglPartidos) && !is_null($sglPartidos) && $sglPartidos != '' && $sglPartidos != 'Todos') {
            $matrizPartidos = explode(',', $sglPartidos);
        } else {
            $matrizPartidos = null;
        }

        if (isset($dscCasa) && !is_null($dscCasa) && $dscCasa != '' && $dscCasa != 'Todas') {
            $matrizCasas = explode(',', $dscCasa);
        } else {
            $matrizCasas = null;
        }

        if (isset($sglUfRepresentante) && !is_null($sglUfRepresentante) && $sglUfRepresentante != '' && $sglUfRepresentante != 'Todas') {
            $matrizUfs = explode(',', $sglUfRepresentante);
        } else {
            $matrizUfs = null;
        }

        $tabParlamentares = $this->instanciarTabParlamentaresController();
        $tabIbge = $this->instanciarTabIbgeController();

        $ufs = $tabIbge->getUfs($matrizUfs);

        foreach ($ufs as $uf) {

            $parlamentares = $tabParlamentares->getParlamentaresPorUF($matrizPartidos, $matrizCasas, $uf->sgl_uf);

            if ($parlamentares->count() > 0) {
                $pdf->AddPage();

                $pdf->SetXY(4, 5);

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFillColor(105, 105, 105);
                $pdf->SetDrawColor(255, 255, 255);
                $pdf->MultiCell(201, 7, utf8_decode($uf->nomeunidadefederacao), "B,T,L,R", 'L', TRUE);

                $contLinha = 20;
                $cont = 0;
                $mod = 0;

                $altura1 = 0;
                $altura2 = 0;

                $currentTitle = null;

                foreach ($parlamentares as $parlamentar) {

                    $parlamentar->nom_parlamentar = substr($parlamentar->nom_parlamentar, 0, 30);

                    // Início do IF para incluir mais páginas por partido
                    if ($cont == 8 || $cont == 16 || $cont == 24 || $cont == 32 || $cont == 40 || $cont == 48 || $cont == 56 || $cont == 64 || $cont == 72 || $cont == 80 || $cont == 88 || $cont == 96 || $cont == 104 || $cont == 112 || $cont == 120 || $cont == 128 || $cont == 136 || $cont == 144 || $cont == 152 || $cont == 160 || $cont == 168 || $cont == 176 || $cont == 184 || $cont == 192 || $cont == 200 || $cont == 208 || $cont == 216 || $cont == 224 || $cont == 232) {

                        $pdf->AddPage();

                        $pdf->SetXY(4, 5);

                        $pdf->SetFont('Arial', 'B', 12);
                        $pdf->SetTextColor(255, 255, 255);
                        $pdf->SetFillColor(105, 105, 105);
                        $pdf->SetDrawColor(255, 255, 255);
                        $pdf->MultiCell(201, 7, utf8_decode($uf->nomeunidadefederacao), "B,T,L,R", 'L', TRUE);

                        $altura1 = 13;
                        $altura2 = 17;
                    }
                    // Fim do IF para incluir mais páginas por partido

                    // Início para verificar se o deputado federal exercer alguma liderança
                    $cargosLiderancas = null;
                    // Senadores
                    if ($parlamentar->dsc_casa === 'Senado Federal') {

                        $cargosMesaDiretora = null;

                        if ($parlamentar->cargosMesaDiretoraSenado) {

                            $cargosMesaDiretora = $parlamentar->cargosMesaDiretoraSenado->Cargo;

                            if ($cargosMesaDiretora === 'PRESIDENTE') {
                                $cargosMesaDiretora = 'PRESIDENTE DO SENADO FEDERAL';
                            } else {
                                $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DO SENADO';
                            }

                        }

                        if ($parlamentar->liderancaSenadores) {

                            $contLideranca = 1;

                            foreach ($parlamentar->liderancaSenadores as $key => $lideranca) {
                                if (isset($lideranca->SiglaPartido) && !is_null($lideranca->SiglaPartido) && $lideranca->SiglaPartido != '') {

                                    $cargosLiderancas .= retornaTextoTirandoParteDoTexto($this->alterarDescricaoLideranca($lideranca->DescricaoTipoLideranca), ' do Senado Federal');
                                    $cargosLiderancas .= ' do ' . retornaTextoTirandoParteDoTexto($lideranca->SiglaPartido, 'Congresso Nacional') . ' no ' . $lideranca->SiglaCasaLideranca;
                                    $cargosLiderancas .= '; ';

                                } else {

                                    $cargosLiderancas .= $contLideranca . '. ' . $lideranca->UnidadeLideranca;

                                    isset($lideranca->NomeBloco) && !is_null($lideranca->NomeBloco) && $lideranca->NomeBloco != '' ? $cargosLiderancas .= 'do ' . $lideranca->NomeBloco : '';
                                    $cargosLiderancas .= '; ';

                                }

                                $contLideranca++;
                            }

                            foreach ($parlamentar->cargosSenadores as $cargo) {
                                if (!is_null($cargo->colegiadoAtivo)) {
                                    $cargosLiderancas .= $contLideranca . '. ' . primeiraLetraMaiuscula($cargo->DescricaoCargo) . ' do(a) ' . $cargo->SiglaComissao;
                                    $cargosLiderancas .= '; ';
                                }
                            }

                            $cargosLiderancas = trim($cargosLiderancas, '; ');
                        }

                    }

                    // Deputados federais
                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                        $cargosMesaDiretora = null;

                        if ($parlamentar->cargosMesaDiretora) {

                            $cargosMesaDiretora = $parlamentar->cargosMesaDiretora->titulo;

                            if ($cargosMesaDiretora === 'Presidente') {
                                $cargosMesaDiretora = 'PRESIDENTE DA CÂMARA DOS DEPUTADOS';
                            } else {
                                $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DIRETORA';
                            }

                        }

                        if ($parlamentar->liderancaDeputados) {

                            foreach ($parlamentar->liderancaDeputados as $key => $lideranca) {
                                $cargosLiderancas .= $lideranca->titulo . ' do(a) ' . $this->alterarDescricaoLideranca($lideranca->tipo);
                                $lideranca->nome != $lideranca->tipo ? $cargosLiderancas .= $this->alterarDescricaoLideranca($lideranca->nome) : '';
                            }

                            $cargosLiderancas = trim($cargosLiderancas, ', ');
                        }
                    }
                    // Fim para verificar se o deputado federal exercer alguma liderança

                    // Início de recuperar a legislatura dos Deputados Federais
                    $legislaturas = null;
                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                        if ($parlamentar->legislaturasDeputado->count() > 0) {

                            foreach ($parlamentar->legislaturasDeputado as $legislatura) {
                                $legislaturas .= $legislatura->legislatura . '/';
                            }

                            $legislaturas = trim($legislaturas, '/');

                        }
                    }
                    // Fim de recuperar a legislatura dos Deputados Federais

                    // Início de recuperar a legislatura dos Deputados Federais
                    if ($parlamentar->dsc_casa === 'Senado Federal') {

                        if ($parlamentar->legislaturasSenado->count() > 0) {

                            foreach ($parlamentar->legislaturasSenado as $legislatura) {
                                $legislaturas .= $legislatura->legislatura . '/';
                            }

                            $legislaturas = trim($legislaturas, '/');

                        }
                    }
                    // Fim de recuperar a legislatura dos Deputados Federais

                    // Início de recuperar o celular
                    $celulares = null;
                    if ($bln_acesso_inrestrito == 1) {

                        if ($parlamentar->celulares->count() > 0) {
                            $contCelular = 1;
                            foreach ($parlamentar->celulares as $celular) {
                                if ($contCelular <= 3) {
                                    $celulares .= applyMask($celular->num_celular, '(##) #####-####') . ' / ';
                                }
                                $contCelular++;
                            }

                            $celulares = trim($celulares, ' / ');
                        }

                    }
                    // Fim de recuperar o celular

                    // Início de recuperar o número de telefone do gabinete
                    $telefoneGabinete = null;
                    if ($parlamentar->num_telefone != '') {

                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                            $telefoneGabinete = '(61) ' . $parlamentar->num_telefone;
                        }

                        if ($parlamentar->dsc_casa === 'Senado Federal') {
                            $telefoneGabinete = applyMask('61' . $parlamentar->num_telefone, '(##) ####-####');
                        }

                    }
                    // Fim de recuperar o número de telefone do gabinete

                    // Início de recuperar o e-mail do gabinete do parlamentar
                    $emailGabienete = null;
                    if ($parlamentar->dsc_email != '') {

                        $emailGabienete = $parlamentar->dsc_email;

                    }
                    // Fim de recuperar o e-mail do gabinete do parlamentar

                    // Início de recuperar as comissões onde é titular
                    $comissoes = null;
                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                        if ($parlamentar->comissoesDeputados->count() > 0) {
                            foreach ($parlamentar->comissoesDeputados as $comissao) {
                                if (substr($comissao->siglaOrgao, 0, 1) === 'C') {
                                    $comissoes .= $comissao->siglaOrgao . ', ';
                                }

                            }

                            $comissoes = trim($comissoes, ', ');
                        }

                    }

                    if ($parlamentar->dsc_casa === 'Senado Federal') {
                        if ($parlamentar->comissoesSenadores->count() > 0) {
                            foreach ($parlamentar->comissoesSenadores as $comissao) {

                                if (substr($comissao->SiglaComissao, 0, 1) === 'C') {
                                    $comissoes .= $comissao->SiglaComissao . ', ';
                                }

                            }

                            $comissoes = trim($comissoes, ', ');
                        }
                    }
                    // Fim de recuperar as comissões onde é titular

                    $mod = $cont % 2;

                    $alturaFundo = 68;

                    $alturaFoto = 36.4;

                    $larguraFoto = 30;

                    $paddingLeftA = 36;

                    $paddingLeftB = 137;

                    $fotoParlamentar = null;

                    if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                        $fotoParlamentar = public_path('storage/fotos/deputados/' . $parlamentar->cod_parlamentar . '.jpg');

                    } else if ($parlamentar->dsc_casa === 'Senado Federal') {
                        $fotoParlamentar = public_path('storage/fotos/senadores/' . $parlamentar->cod_parlamentar . '.jpg');
                    }

                    if ($mod == 0) {

                        $cont == 0 || $cont == 8 || $cont == 16 || $cont == 24 || $cont == 32 || $cont == 40 || $cont == 48 || $cont == 56 || $cont == 64 || $cont == 72 || $cont == 80 || $cont == 88 || $cont == 96 || $cont == 104 || $cont == 112 || $cont == 120 || $cont == 128 || $cont == 136 || $cont == 144 || $cont == 152 || $cont == 160 || $cont == 168 || $cont == 176 || $cont == 184 || $cont == 192 || $cont == 200 || $cont == 208 || $cont == 216 || $cont == 224 || $cont == 232 ? $altura1 = 13 : $altura1 = $altura1 + 67;

                        $cont == 0 || $cont == 8 || $cont == 16 || $cont == 24 || $cont == 32 || $cont == 40 || $cont == 48 || $cont == 56 || $cont == 64 || $cont == 72 || $cont == 80 || $cont == 88 || $cont == 96 || $cont == 104 || $cont == 112 || $cont == 120 || $cont == 128 || $cont == 136 || $cont == 144 || $cont == 152 || $cont == 160 || $cont == 168 || $cont == 176 || $cont == 184 || $cont == 192 || $cont == 200 || $cont == 208 || $cont == 216 || $cont == 224 || $cont == 232 ? $altura2 = 13 : $altura2 = $altura2 + 67;

                        $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'bg9.jpg'), 4, $altura1, 100, $alturaFundo);

                        try {

                            $pdf->Image($fotoParlamentar, 5.4, $altura1 + 2.5, $larguraFoto, $alturaFoto);

                        } catch (\Exception $e) {
                            $fotoParlamentar = public_path('storage/fotos/avatar/avatar.jpg');
                            $pdf->Image($fotoParlamentar, 5.4, $altura1 + 2.5, $larguraFoto, $alturaFoto);
                        }

                        $pdf->Ln(10);
                        $pdf->SetXY($paddingLeftA, $altura1 + 2.1);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', '', 8.5);
                        $pdf->Cell(100, 5, utf8_decode($parlamentar->dsc_tratamento . ' - ' . $parlamentar->dsc_participacao . ' - ' . $parlamentar->sgl_partido . ' - ' . $parlamentar->sgl_uf_representante), '', 0, 'L', 0);

                        $pdf->Ln(4.7);
                        $pdf->SetX($paddingLeftA);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($parlamentar->nom_parlamentar, 'UTF-8')), '', 0, 'L', 0);

                        $pdf->Ln(4.1);
                        $pdf->SetX($paddingLeftA);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->Cell(100, 5, utf8_decode('Legislatura(s): ' . $legislaturas), '', 0, 'L', 0);

                        if (Session::get('permissao') === '0010000' || Session::get('permissao') === '0000100') {

                            if ($celulares != '') {

                                $pdf->Ln(4);

                                $pdf->SetX($paddingLeftA);
                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFont('Arial', '', 7.5);
                                $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($celulares, 'UTF-8')), '', 0, 'L', 0);
                            }

                        }

                        if ($telefoneGabinete != '') {

                            $pdf->Ln(4);

                            $pdf->SetX($paddingLeftA);
                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFont('Arial', '', 7.5);
                            $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($telefoneGabinete, 'UTF-8')), '', 0, 'L', 0);
                        }

                        if ($emailGabienete != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftA);
                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($emailGabienete), '', 0, 'L', 0);
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftA);
                            $pdf->Cell(9, 5, utf8_decode('Nasc.: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($parlamentar->sgl_uf_nascimento . '/' . $parlamentar->nom_municipio_nascimento), '', 0, 'L', 0);
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftA);
                            $pdf->Cell(17 - 1, 5, utf8_decode('Data Nasc.: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, formatarDataComCarbonParaBR($parlamentar->dte_nascimento), '', 0, 'L', 0);
                        }

                        if ($parlamentar->dsc_participacao === 'Titular') {

                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftA);
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(29, 5, utf8_decode('Ano / Votos / Reeleito: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($parlamentar->num_ano_eleicao . ' / ' . formatarNumeroInteiro($parlamentar->num_total_votos) . ' / ' . $parlamentar->dsc_reeleito), '', 0, 'L', 0);
                        }

                        if ($cargosMesaDiretora != '') {

                            if ($cargosMesaDiretora === 'PRESIDENTE DO SENADO FEDERAL') {
                                $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'presidente_senado.jpg'), 5.2, $altura1 + 40.9, 4, 3);

                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftA - 27, $altura1 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(94, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            } elseif ($cargosMesaDiretora === 'PRESIDENTE DA CÂMARA DOS DEPUTADOS') {
                                $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'presidente_camara.jpg'), 5.2, $altura1 + 40.5, 4, 3);

                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftA - 27, $altura1 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(94, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            } else {
                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftA - 30.5, $altura1 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(97.5, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            }
                        }

                        if ($cargosLiderancas != '') {
                            $pdf->Ln(1);

                            if ($cargosMesaDiretora == '') {
                                $pdf->SetXY($paddingLeftA - 30.5, $altura1 + 40);
                            } else {
                                $pdf->SetX($paddingLeftA - 30.5);
                            }

                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFillColor(241, 242, 248);
                            $pdf->SetDrawColor(255, 255, 255);
                            $pdf->SetFont('Arial', '', 7);
                            $pdf->MultiCell(97.5, 4, utf8_decode($cargosLiderancas), "", 'L', TRUE);
                        }

                        if ($comissoes != '') {
                            $pdf->Ln(1);

                            if ($cargosLiderancas == '' && $cargosMesaDiretora == '') {
                                $pdf->SetXY($paddingLeftA - 30.5, $altura1 + 40);
                            } else {
                                $pdf->SetX($paddingLeftA - 30.5);
                            }

                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFillColor(241, 242, 248);
                            $pdf->SetDrawColor(255, 255, 255);
                            $pdf->SetFont('Arial', '', 7);
                            $pdf->MultiCell(97.5, 4, utf8_decode($comissoes), "", 'L', TRUE);
                        }

                    } else {

                        $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'bg9.jpg'), 105, $altura1, 100, $alturaFundo);

                        try {

                            $pdf->Image($fotoParlamentar, 106.4, $altura2 + 2.5, $larguraFoto, $alturaFoto);

                        } catch (\Exception $e) {
                            $fotoParlamentar = public_path('storage/fotos/avatar/avatar.jpg');
                            $pdf->Image($fotoParlamentar, 106.4, $altura2 + 2.5, $larguraFoto, $alturaFoto);
                        }

                        $pdf->Ln(10);
                        $pdf->SetXY($paddingLeftB, $altura2 + 2.1);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', '', 8.5);
                        $pdf->Cell(100, 5, utf8_decode($parlamentar->dsc_tratamento . ' - ' . $parlamentar->dsc_participacao . ' - ' . $parlamentar->sgl_partido . ' - ' . $parlamentar->sgl_uf_representante), '', 0, 'L', 0);

                        $pdf->Ln(4.7);
                        $pdf->SetX($paddingLeftB);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($parlamentar->nom_parlamentar, 'UTF-8')), '', 0, 'L', 0);

                        $pdf->Ln(4.1);
                        $pdf->SetX($paddingLeftB);
                        $pdf->SetTextColor(32, 36, 67);
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->Cell(100, 5, utf8_decode('Legislatura(s): ' . $legislaturas), '', 0, 'L', 0);

                        if (Session::get('permissao') === '0010000' || Session::get('permissao') === '0000100') {

                            if ($celulares != '') {

                                $pdf->Ln(4);

                                $pdf->SetX($paddingLeftB);
                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFont('Arial', '', 7.5);
                                $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($celulares, 'UTF-8')), '', 0, 'L', 0);
                            }

                        }

                        if ($telefoneGabinete != '') {

                            $pdf->Ln(4);

                            $pdf->SetX($paddingLeftB);
                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFont('Arial', '', 7.5);
                            $pdf->Cell(100, 5, utf8_decode(mb_strtoupper($telefoneGabinete, 'UTF-8')), '', 0, 'L', 0);
                        }

                        if ($emailGabienete != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftB);
                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($emailGabienete), '', 0, 'L', 0);
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftB);
                            $pdf->Cell(9, 5, utf8_decode('Nasc.: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($parlamentar->sgl_uf_nascimento . '/' . $parlamentar->nom_municipio_nascimento), '', 0, 'L', 0);
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftB);
                            $pdf->Cell(17 - 1, 5, utf8_decode('Data Nasc.: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, formatarDataComCarbonParaBR($parlamentar->dte_nascimento), '', 0, 'L', 0);
                        }

                        if ($parlamentar->dsc_participacao === 'Titular') {
                            $pdf->Ln(4);
                            $pdf->SetX($paddingLeftB);
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(29, 5, utf8_decode('Ano / Votos / Reeleito: '), '', 0, 'L', 0);

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(100, 5, utf8_decode($parlamentar->num_ano_eleicao . ' / ' . formatarNumeroInteiro($parlamentar->num_total_votos) . ' / ' . $parlamentar->dsc_reeleito), '', 0, 'L', 0);
                        }

                        if ($cargosMesaDiretora != '') {

                            if ($cargosMesaDiretora === 'PRESIDENTE DO SENADO FEDERAL') {
                                $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'presidente_senado.jpg'), 106.2, $altura2 + 40.9, 4, 3);

                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftB - 27, $altura2 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(94, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            } elseif ($cargosMesaDiretora === 'PRESIDENTE DA CÂMARA DOS DEPUTADOS') {
                                $pdf->Image(public_path(DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'presidente_camara.jpg'), 106.2, $altura2 + 40.5, 4, 3);

                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftB - 27, $altura2 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(94, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            } else {
                                $pdf->Ln(5);
                                $pdf->SetXY($paddingLeftB - 30.5, $altura2 + 40);

                                $pdf->SetTextColor(32, 36, 67);
                                $pdf->SetFillColor(241, 242, 248);
                                $pdf->SetDrawColor(255, 255, 255);
                                $pdf->SetFont('Arial', 'B', 7);
                                $pdf->MultiCell(97.5, 4, utf8_decode(mb_strtoupper($cargosMesaDiretora)), "", 'L', TRUE);

                            }
                        }

                        if ($cargosLiderancas != '') {
                            $pdf->Ln(1);

                            if ($cargosMesaDiretora == '') {
                                $pdf->SetXY($paddingLeftB - 30.5, $altura2 + 40);
                            } else {
                                $pdf->SetX($paddingLeftB - 30.5);
                            }

                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFillColor(241, 242, 248);
                            $pdf->SetDrawColor(255, 255, 255);
                            $pdf->SetFont('Arial', '', 7);
                            $pdf->MultiCell(97.5, 4, utf8_decode($cargosLiderancas), "", 'L', TRUE);
                        }

                        if ($comissoes != '') {
                            $pdf->Ln(1);

                            if ($cargosLiderancas == '' && $cargosMesaDiretora == '') {
                                $pdf->SetXY($paddingLeftB - 30.5, $altura2 + 40);
                            } else {
                                $pdf->SetX($paddingLeftB - 30.5);
                            }

                            $pdf->SetTextColor(32, 36, 67);
                            $pdf->SetFillColor(241, 242, 248);
                            $pdf->SetDrawColor(255, 255, 255);
                            $pdf->SetFont('Arial', '', 7);
                            $pdf->MultiCell(97.5, 4, utf8_decode($comissoes), "", 'L', TRUE);
                        }

                    }

                    $cont++;

                }

                // Início do 'siglário' dos Órgãos da Cãmara dos Deputados

                // Fim do 'siglário' dos Órgãos da Cãmara dos Deputados

            } else {



            }

        }

        $pdf->Output();
        exit;

    }

    public function carometroWordPorPartido($sglPartidos = null, $dscCasa = null, $sglUfRepresentante = null)
    {

        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        $bln_acesso_inrestrito = $this->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        $uuid = Uuid::uuid4()->toString();
        $fileName = $uuid . '_' . date('Ymd_His') . '.docx';

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language('PT_BR'));

        // Definir o autor do documento
        $phpWord->getDocInfo()->setCreator($uuid);

        // Adicionar seção ao documento
        $section = $this->addSectionToDocument($phpWord);

        // Adicionar rodapé à seção
        $this->addFooterToSection($section, $uuid);

        // Adicionar estilos ao documento
        $this->addStylesToDocument($phpWord);

        // Início do tratamento dos dados para gerar o arquivo Word

        if (isset($sglPartidos) && !is_null($sglPartidos) && $sglPartidos != '' && $sglPartidos != 'Todos') {
            $matrizPartidos = explode(',', $sglPartidos);
        } else {
            $matrizPartidos = null;
        }

        if (isset($dscCasa) && !is_null($dscCasa) && $dscCasa != '' && $dscCasa != 'Todas') {
            $matrizCasas = explode(',', $dscCasa);
        } else {
            $matrizCasas = null;
        }

        if (isset($sglUfRepresentante) && !is_null($sglUfRepresentante) && $sglUfRepresentante != '' && $sglUfRepresentante != 'Todas') {
            $matrizUfs = explode(',', $sglUfRepresentante);
        } else {
            $matrizUfs = null;
        }

        $tabParlamentares = $this->instanciarTabParlamentaresController();

        $partidos = $tabParlamentares->getPartidos($matrizPartidos);

        $contPartido = 1;

        foreach ($partidos as $partido) {

            $parlamentares = $tabParlamentares->getParlamentaresPorPartido($partido->sgl_partido, $matrizCasas, $matrizUfs);

            if ($parlamentares->count() > 0) {

                if (isset($partido->sgl_partido) && !is_null($partido->sgl_partido) && $partido->sgl_partido != '') {

                    // Adicionar uma tabela para simular a cor de fundo
                    $backgroundTable = $section->addTable();
                    $backgroundTable->addRow();
                    $backgroundCell = $backgroundTable->addCell(12000); // Largura da célula em twips
                    // Definir a altura da célula
                    $backgroundCell->setHeight(200); // Definir a altura desejada em twips
                    $backgroundCell->getStyle()->setBorderSize(0); // Definir a espessura da borda como 0 para que não seja visível
                    $backgroundCell->addText($partido->sgl_partido, array('color' => '#FFFFFF', 'size' => 14, 'valign' => 'bottom')); // Definir a cor do texto e o tamanho da fonte

                    // Definir a cor de fundo da célula simulada
                    $backgroundCell->getStyle()->setShading(array('fill' => '#696969'));

                    // Reduzir o espaçamento superior e inferior entre os textos
                    $row = $backgroundCell->getParent();
                    if ($row instanceof TableRow) {
                        foreach ($row->getCells() as $cell) {
                            $cell->getStyle()->setSpaceBetween(0);
                        }
                    }

                    // Adicionar uma quebra de linha após o cabeçalho
                    $section->addTextBreak();


                    // Início da tabela que dividirá a página em duas partes iguais
                    // Adicionar uma tabela principal com 1 linha e 2 colunas
                    $table = $section->addTable();
                    $table->addRow();
                    $cellColuna1 = $table->addCell(12000);

                    $contParlamentar = 1;

                    foreach ($parlamentares as $parlamentar) {

                        $mod = $contParlamentar % 2;

                        $parlamentar->nom_parlamentar = substr($parlamentar->nom_parlamentar, 0, 30);

                        // Início para verificar se o deputado federal exercer alguma liderança
                        $cargosLiderancas = null;
                        // Senadores
                        if ($parlamentar->dsc_casa === 'Senado Federal') {

                            $cargosMesaDiretora = null;

                            if ($parlamentar->cargosMesaDiretoraSenado) {

                                $cargosMesaDiretora = $parlamentar->cargosMesaDiretoraSenado->Cargo;

                                if ($cargosMesaDiretora === 'PRESIDENTE') {
                                    $cargosMesaDiretora = 'PRESIDENTE DO SENADO FEDERAL';
                                } else {
                                    $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DO SENADO';
                                }

                            }

                            if ($parlamentar->liderancaSenadores) {

                                $contLideranca = 1;

                                foreach ($parlamentar->liderancaSenadores as $key => $lideranca) {
                                    if (isset($lideranca->SiglaPartido) && !is_null($lideranca->SiglaPartido) && $lideranca->SiglaPartido != '') {

                                        $cargosLiderancas .= retornaTextoTirandoParteDoTexto($this->alterarDescricaoLideranca($lideranca->DescricaoTipoLideranca), ' do Senado Federal');
                                        $cargosLiderancas .= ' do ' . retornaTextoTirandoParteDoTexto($lideranca->SiglaPartido, 'Congresso Nacional') . ' no ' . $lideranca->SiglaCasaLideranca;
                                        $cargosLiderancas .= '; ';

                                    } else {

                                        $cargosLiderancas .= $contLideranca . '. ' . $lideranca->UnidadeLideranca;

                                        isset($lideranca->NomeBloco) && !is_null($lideranca->NomeBloco) && $lideranca->NomeBloco != '' ? $cargosLiderancas .= 'do ' . $lideranca->NomeBloco : '';
                                        $cargosLiderancas .= '; ';

                                    }

                                    $contLideranca++;
                                }

                                foreach ($parlamentar->cargosSenadores as $cargo) {
                                    if (!is_null($cargo->colegiadoAtivo)) {
                                        $cargosLiderancas .= $contLideranca . '. ' . primeiraLetraMaiuscula($cargo->DescricaoCargo) . ' do(a) ' . $cargo->SiglaComissao;
                                        $cargosLiderancas .= '; ';
                                    }
                                }

                                $cargosLiderancas = trim($cargosLiderancas, '; ');
                            }

                        }

                        // Deputados federais
                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                            $cargosMesaDiretora = null;

                            if ($parlamentar->cargosMesaDiretora) {

                                $cargosMesaDiretora = $parlamentar->cargosMesaDiretora->titulo;

                                if ($cargosMesaDiretora === 'Presidente') {
                                    $cargosMesaDiretora = 'PRESIDENTE DA CÂMARA DOS DEPUTADOS';
                                } else {
                                    $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DIRETORA';
                                }

                            }

                            if ($parlamentar->liderancaDeputados) {

                                foreach ($parlamentar->liderancaDeputados as $key => $lideranca) {
                                    $cargosLiderancas .= $lideranca->titulo . ' do(a) ' . $this->alterarDescricaoLideranca($lideranca->tipo);
                                    $lideranca->nome != $lideranca->tipo ? $cargosLiderancas .= $this->alterarDescricaoLideranca($lideranca->nome) : '';
                                }

                                $cargosLiderancas = trim($cargosLiderancas, ', ');
                            }
                        }
                        // Fim para verificar se o deputado federal exercer alguma liderança

                        // Início de recuperar a legislatura dos Deputados Federais
                        $legislaturas = null;
                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                            if ($parlamentar->legislaturasDeputado->count() > 0) {

                                foreach ($parlamentar->legislaturasDeputado as $legislatura) {
                                    $legislaturas .= $legislatura->legislatura . '/';
                                }

                                $legislaturas = trim($legislaturas, '/');

                            }
                        }
                        // Fim de recuperar a legislatura dos Deputados Federais

                        // Início de recuperar a legislatura dos Deputados Federais
                        if ($parlamentar->dsc_casa === 'Senado Federal') {

                            if ($parlamentar->legislaturasSenado->count() > 0) {

                                foreach ($parlamentar->legislaturasSenado as $legislatura) {
                                    $legislaturas .= $legislatura->legislatura . '/';
                                }

                                $legislaturas = trim($legislaturas, '/');

                            }
                        }
                        // Fim de recuperar a legislatura dos Deputados Federais

                        // Início de recuperar o celular
                        $celulares = null;
                        if ($bln_acesso_inrestrito == 1) {

                            if ($parlamentar->celulares->count() > 0) {
                                $contCelular = 1;
                                foreach ($parlamentar->celulares as $celular) {
                                    if ($contCelular <= 3) {
                                        $celulares .= applyMask($celular->num_celular, '(##) #####-####') . ' / ';
                                    }
                                    $contCelular++;
                                }

                                $celulares = trim($celulares, ' / ');
                            }

                        }
                        // Fim de recuperar o celular

                        // Início de recuperar o número de telefone do gabinete
                        $telefoneGabinete = null;
                        if ($parlamentar->num_telefone != '') {

                            if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                                $telefoneGabinete = '(61) ' . $parlamentar->num_telefone;
                            }

                            if ($parlamentar->dsc_casa === 'Senado Federal') {
                                $telefoneGabinete = applyMask('61' . $parlamentar->num_telefone, '(##) ####-####');
                            }

                        }
                        // Fim de recuperar o número de telefone do gabinete

                        // Início de recuperar o e-mail do gabinete do parlamentar
                        $emailGabienete = null;
                        if ($parlamentar->dsc_email != '') {

                            $emailGabienete = $parlamentar->dsc_email;

                        }
                        // Fim de recuperar o e-mail do gabinete do parlamentar

                        // Início de recuperar as comissões onde é titular
                        $comissoes = null;
                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                            if ($parlamentar->comissoesDeputados->count() > 0) {
                                foreach ($parlamentar->comissoesDeputados as $comissao) {
                                    if (substr($comissao->siglaOrgao, 0, 1) === 'C') {
                                        $comissoes .= $comissao->siglaOrgao . ', ';
                                    }

                                }

                                $comissoes = trim($comissoes, ', ');
                            }

                        }

                        if ($parlamentar->dsc_casa === 'Senado Federal') {
                            if ($parlamentar->comissoesSenadores->count() > 0) {
                                foreach ($parlamentar->comissoesSenadores as $comissao) {

                                    if (substr($comissao->SiglaComissao, 0, 1) === 'C') {
                                        $comissoes .= $comissao->SiglaComissao . ', ';
                                    }

                                }

                                $comissoes = trim($comissoes, ', ');
                            }
                        }
                        // Fim de recuperar as comissões onde é titular

                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                            $fotoParlamentar = public_path('storage/fotos/deputados/' . $parlamentar->cod_parlamentar . '.jpg');

                        } else if ($parlamentar->dsc_casa === 'Senado Federal') {
                            $fotoParlamentar = public_path('storage/fotos/senadores/' . $parlamentar->cod_parlamentar . '.jpg');
                        }

                        // Coluna 1
                        $tabelaColuna1 = $cellColuna1->addTable();
                        $tabelaColuna1->addRow();
                        $cellImagemColuna1 = $tabelaColuna1->addCell(3700);
                        $cellTextoColuna1 = $tabelaColuna1->addCell(5000);

                        // Adicionar imagem à célula da coluna 1
                        $cellImagemColuna1->addImage(
                            $fotoParlamentar,
                            array(
                                'width' => 171, // Defina a largura desejada em pixels
                                'height' => 190, // Defina a altura desejada em pixels
                            )
                        );

                        // Adicionar texto à célula da coluna 1
                        $texto1 = $cellTextoColuna1->addText($parlamentar->dsc_tratamento . ' - ' . $parlamentar->dsc_participacao . ' - ' . $parlamentar->sgl_partido . ' - ' . $parlamentar->sgl_uf_representante, array('size' => 10));

                        $texto2 = $cellTextoColuna1->addText(mb_strtoupper($parlamentar->nom_parlamentar, 'UTF-8'), array('color' => '000000', 'size' => 12, 'bold' => true));

                        $texto3 = $cellTextoColuna1->addText('Legislatura(s): ' . $legislaturas, array('size' => 10));

                        if (Session::get('permissao') === '0010000' || Session::get('permissao') === '0000100') {

                            if ($celulares != '') {
                                $texto4 = $cellTextoColuna1->addText($celulares, array('size' => 10));
                            }

                        }

                        if ($telefoneGabinete != '') {
                            $texto5 = $cellTextoColuna1->addText($telefoneGabinete, array('size' => 10));
                        }

                        if ($emailGabienete != '') {
                            $texto5 = $cellTextoColuna1->addText('E-mail: ' . $emailGabienete, array('size' => 10));
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $texto5 = $cellTextoColuna1->addText('Nasc.: ' . $parlamentar->sgl_uf_nascimento . '/' . $parlamentar->nom_municipio_nascimento, array('size' => 10));
                        }

                        if ($parlamentar->dte_nascimento != '') {
                            $texto5 = $cellTextoColuna1->addText('Data Nasc.: ' . formatarDataComCarbonParaBR($parlamentar->dte_nascimento), array('size' => 10));
                        }

                        if ($parlamentar->dsc_participacao === 'Titular') {
                            $texto5 = $cellTextoColuna1->addText('Ano / Votos / Reeleito: ' . $parlamentar->num_ano_eleicao . ' / ' . formatarNumeroInteiro($parlamentar->num_total_votos) . ' / ' . $parlamentar->dsc_reeleito, array('size' => 10));
                        }

                        // Reduzir o espaçamento superior e inferior entre os textos
                        $row = $cellTextoColuna1->getParent();
                        if ($row instanceof TableRow) {
                            foreach ($row->getCells() as $cell) {
                                $cell->getStyle()->setSpaceBetween(0);
                            }
                        }

                        if ($cargosMesaDiretora != '') {

                            if ($cargosMesaDiretora === 'PRESIDENTE DO SENADO FEDERAL') {
                                $cellColuna1->addText($cargosMesaDiretora, array('size' => 10, 'bold' => true));
                            } elseif ($cargosMesaDiretora === 'PRESIDENTE DA CÂMARA DOS DEPUTADOS') {
                                $cellColuna1->addText($cargosMesaDiretora, array('size' => 10, 'bold' => true));
                            } else {
                                $cellColuna1->addText($cargosMesaDiretora, array('size' => 10));
                            }

                        }

                        if ($cargosLiderancas != '') {
                            $cellColuna1->addText($cargosLiderancas, array('size' => 10));
                        }

                        if ($comissoes != '') {
                            $cellColuna1->addText($comissoes, array('size' => 10));
                        }

                        $cellColuna1->addText(str_repeat("_", 138), ['size' => 7]);

                        $cellColuna1->addTextBreak();

                        // Reduzir o espaçamento superior e inferior entre os textos
                        $row = $cellColuna1->getParent();
                        if ($row instanceof TableRow) {
                            foreach ($row->getCells() as $cell) {
                                $cell->getStyle()->setSpaceBetween(0);
                            }
                        }

                        $contParlamentar++;

                    }

                    if ($contPartido <= $partidos->count() - 1) {
                        $section->addPageBreak();
                    }

                }

            }

            $contPartido++;

        }

        // Fim do tratamento dos dados para gerar o arquivo Word

        $tempFile = tempnam(sys_get_temp_dir(), 'Word');
        $phpWord->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function carometroWordPorUF($sglPartidos = null, $dscCasa = null, $sglUfRepresentante = null)
    {

        // Início da parte de consulta ao perfil de acesso do cliente
        $user = Auth::user();

        $this->perfil = $user->perfil;
        $this->bln_acesso_inrestrito = $this->perfil->bln_acesso_inrestrito;
        $bln_acesso_inrestrito = $this->bln_acesso_inrestrito;
        // Fim da parte de consulta ao perfil de acesso do cliente

        $uuid = Uuid::uuid4()->toString();
        $fileName = $uuid . '_' . date('Ymd_His') . '.docx';

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language('PT_BR'));

        // Definir o autor do documento
        $phpWord->getDocInfo()->setCreator($uuid);

        // Adicionar seção ao documento
        $section = $this->addSectionToDocument($phpWord);

        // Adicionar rodapé à seção
        $this->addFooterToSection($section, $uuid);

        // Adicionar estilos ao documento
        $this->addStylesToDocument($phpWord);

        // Início do tratamento dos dados para gerar o arquivo Word

        if (isset($sglPartidos) && !is_null($sglPartidos) && $sglPartidos != '' && $sglPartidos != 'Todos') {
            $matrizPartidos = explode(',', $sglPartidos);
        } else {
            $matrizPartidos = null;
        }

        if (isset($dscCasa) && !is_null($dscCasa) && $dscCasa != '' && $dscCasa != 'Todas') {
            $matrizCasas = explode(',', $dscCasa);
        } else {
            $matrizCasas = null;
        }

        if (isset($sglUfRepresentante) && !is_null($sglUfRepresentante) && $sglUfRepresentante != '' && $sglUfRepresentante != 'Todas') {
            $matrizUfs = explode(',', $sglUfRepresentante);
        } else {
            $matrizUfs = null;
        }

        $tabParlamentares = $this->instanciarTabParlamentaresController();
        $tabIbge = $this->instanciarTabIbgeController();

        $ufs = $tabIbge->getUfs($matrizUfs);

        $contUf = 1;

        foreach ($ufs as $uf) {

            $parlamentares = $tabParlamentares->getParlamentaresPorUF($matrizPartidos, $matrizCasas, $uf->sgl_uf);

            if ($parlamentares->count() > 0) {

                if (isset($uf->nomeunidadefederacao) && !is_null($uf->nomeunidadefederacao) && $uf->nomeunidadefederacao != '') {

                    // Adicionar uma tabela para simular a cor de fundo
                    $backgroundTable = $section->addTable();
                    $backgroundTable->addRow();
                    $backgroundCell = $backgroundTable->addCell(12000); // Largura da célula em twips
                    // Definir a altura da célula
                    $backgroundCell->setHeight(200); // Definir a altura desejada em twips
                    $backgroundCell->getStyle()->setBorderSize(0); // Definir a espessura da borda como 0 para que não seja visível
                    $backgroundCell->addText($uf->nomeunidadefederacao, array('color' => '#FFFFFF', 'size' => 14, 'valign' => 'bottom')); // Definir a cor do texto e o tamanho da fonte

                    // Definir a cor de fundo da célula simulada
                    $backgroundCell->getStyle()->setShading(array('fill' => '#696969'));

                    // Reduzir o espaçamento superior e inferior entre os textos
                    $row = $backgroundCell->getParent();
                    if ($row instanceof TableRow) {
                        foreach ($row->getCells() as $cell) {
                            $cell->getStyle()->setSpaceBetween(0);
                        }
                    }

                    // Adicionar uma quebra de linha após o cabeçalho
                    $section->addTextBreak();


                    // Início da tabela que dividirá a página em duas partes iguais
                    // Adicionar uma tabela principal com 1 linha e 2 colunas
                    $table = $section->addTable();
                    $table->addRow();
                    $cellColuna1 = $table->addCell(12000);

                    $contParlamentar = 1;

                    foreach ($parlamentares as $parlamentar) {

                        $mod = $contParlamentar % 2;

                        $parlamentar->nom_parlamentar = substr($parlamentar->nom_parlamentar, 0, 30);

                        // Início para verificar se o deputado federal exercer alguma liderança
                        $cargosLiderancas = null;
                        // Senadores
                        if ($parlamentar->dsc_casa === 'Senado Federal') {

                            $cargosMesaDiretora = null;

                            if ($parlamentar->cargosMesaDiretoraSenado) {

                                $cargosMesaDiretora = $parlamentar->cargosMesaDiretoraSenado->Cargo;

                                if ($cargosMesaDiretora === 'PRESIDENTE') {
                                    $cargosMesaDiretora = 'PRESIDENTE DO SENADO FEDERAL';
                                } else {
                                    $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DO SENADO';
                                }

                            }

                            if ($parlamentar->liderancaSenadores) {

                                $contLideranca = 1;

                                foreach ($parlamentar->liderancaSenadores as $key => $lideranca) {
                                    if (isset($lideranca->SiglaPartido) && !is_null($lideranca->SiglaPartido) && $lideranca->SiglaPartido != '') {

                                        $cargosLiderancas .= retornaTextoTirandoParteDoTexto($this->alterarDescricaoLideranca($lideranca->DescricaoTipoLideranca), ' do Senado Federal');
                                        $cargosLiderancas .= ' do ' . retornaTextoTirandoParteDoTexto($lideranca->SiglaPartido, 'Congresso Nacional') . ' no ' . $lideranca->SiglaCasaLideranca;
                                        $cargosLiderancas .= '; ';

                                    } else {

                                        $cargosLiderancas .= $contLideranca . '. ' . $lideranca->UnidadeLideranca;

                                        isset($lideranca->NomeBloco) && !is_null($lideranca->NomeBloco) && $lideranca->NomeBloco != '' ? $cargosLiderancas .= 'do ' . $lideranca->NomeBloco : '';
                                        $cargosLiderancas .= '; ';

                                    }

                                    $contLideranca++;
                                }

                                foreach ($parlamentar->cargosSenadores as $cargo) {
                                    if (!is_null($cargo->colegiadoAtivo)) {
                                        $cargosLiderancas .= $contLideranca . '. ' . primeiraLetraMaiuscula($cargo->DescricaoCargo) . ' do(a) ' . $cargo->SiglaComissao;
                                        $cargosLiderancas .= '; ';
                                    }
                                }

                                $cargosLiderancas = trim($cargosLiderancas, '; ');
                            }

                        }

                        // Deputados federais
                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                            $cargosMesaDiretora = null;

                            if ($parlamentar->cargosMesaDiretora) {

                                $cargosMesaDiretora = $parlamentar->cargosMesaDiretora->titulo;

                                if ($cargosMesaDiretora === 'Presidente') {
                                    $cargosMesaDiretora = 'PRESIDENTE DA CÂMARA DOS DEPUTADOS';
                                } else {
                                    $cargosMesaDiretora = $cargosMesaDiretora . ' DA MESA DIRETORA';
                                }

                            }

                            if ($parlamentar->liderancaDeputados) {

                                foreach ($parlamentar->liderancaDeputados as $key => $lideranca) {
                                    $cargosLiderancas .= $lideranca->titulo . ' do(a) ' . $this->alterarDescricaoLideranca($lideranca->tipo);
                                    $lideranca->nome != $lideranca->tipo ? $cargosLiderancas .= $this->alterarDescricaoLideranca($lideranca->nome) : '';
                                }

                                $cargosLiderancas = trim($cargosLiderancas, ', ');
                            }
                        }
                        // Fim para verificar se o deputado federal exercer alguma liderança

                        // Início de recuperar a legislatura dos Deputados Federais
                        $legislaturas = null;
                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                            if ($parlamentar->legislaturasDeputado->count() > 0) {

                                foreach ($parlamentar->legislaturasDeputado as $legislatura) {
                                    $legislaturas .= $legislatura->legislatura . '/';
                                }

                                $legislaturas = trim($legislaturas, '/');

                            }
                        }
                        // Fim de recuperar a legislatura dos Deputados Federais

                        // Início de recuperar a legislatura dos Deputados Federais
                        if ($parlamentar->dsc_casa === 'Senado Federal') {

                            if ($parlamentar->legislaturasSenado->count() > 0) {

                                foreach ($parlamentar->legislaturasSenado as $legislatura) {
                                    $legislaturas .= $legislatura->legislatura . '/';
                                }

                                $legislaturas = trim($legislaturas, '/');

                            }
                        }
                        // Fim de recuperar a legislatura dos Deputados Federais

                        // Início de recuperar o celular
                        $celulares = null;
                        if ($bln_acesso_inrestrito == 1) {

                            if ($parlamentar->celulares->count() > 0) {
                                $contCelular = 1;
                                foreach ($parlamentar->celulares as $celular) {
                                    if ($contCelular <= 3) {
                                        $celulares .= applyMask($celular->num_celular, '(##) #####-####') . ' / ';
                                    }
                                    $contCelular++;
                                }

                                $celulares = trim($celulares, ' / ');
                            }

                        }
                        // Fim de recuperar o celular

                        // Início de recuperar o número de telefone do gabinete
                        $telefoneGabinete = null;
                        if ($parlamentar->num_telefone != '') {

                            if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                                $telefoneGabinete = '(61) ' . $parlamentar->num_telefone;
                            }

                            if ($parlamentar->dsc_casa === 'Senado Federal') {
                                $telefoneGabinete = applyMask('61' . $parlamentar->num_telefone, '(##) ####-####');
                            }

                        }
                        // Fim de recuperar o número de telefone do gabinete

                        // Início de recuperar o e-mail do gabinete do parlamentar
                        $emailGabienete = null;
                        if ($parlamentar->dsc_email != '') {

                            $emailGabienete = $parlamentar->dsc_email;

                        }
                        // Fim de recuperar o e-mail do gabinete do parlamentar

                        // Início de recuperar as comissões onde é titular
                        $comissoes = null;
                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {

                            if ($parlamentar->comissoesDeputados->count() > 0) {
                                foreach ($parlamentar->comissoesDeputados as $comissao) {
                                    if (substr($comissao->siglaOrgao, 0, 1) === 'C') {
                                        $comissoes .= $comissao->siglaOrgao . ', ';
                                    }

                                }

                                $comissoes = trim($comissoes, ', ');
                            }

                        }

                        if ($parlamentar->dsc_casa === 'Senado Federal') {
                            if ($parlamentar->comissoesSenadores->count() > 0) {
                                foreach ($parlamentar->comissoesSenadores as $comissao) {

                                    if (substr($comissao->SiglaComissao, 0, 1) === 'C') {
                                        $comissoes .= $comissao->SiglaComissao . ', ';
                                    }

                                }

                                $comissoes = trim($comissoes, ', ');
                            }
                        }
                        // Fim de recuperar as comissões onde é titular

                        if ($parlamentar->dsc_casa === 'Câmara dos Deputados') {
                            $fotoParlamentar = public_path('storage/fotos/deputados/' . $parlamentar->cod_parlamentar . '.jpg');

                        } else if ($parlamentar->dsc_casa === 'Senado Federal') {
                            $fotoParlamentar = public_path('storage/fotos/senadores/' . $parlamentar->cod_parlamentar . '.jpg');
                        }

                        // Coluna 1
                        $tabelaColuna1 = $cellColuna1->addTable();
                        $tabelaColuna1->addRow();
                        $cellImagemColuna1 = $tabelaColuna1->addCell(3700);
                        $cellTextoColuna1 = $tabelaColuna1->addCell(5000);

                        // Adicionar imagem à célula da coluna 1
                        $cellImagemColuna1->addImage(
                            $fotoParlamentar,
                            array(
                                'width' => 171, // Defina a largura desejada em pixels
                                'height' => 190, // Defina a altura desejada em pixels
                            )
                        );

                        // Adicionar texto à célula da coluna 1
                        $texto1 = $cellTextoColuna1->addText($parlamentar->dsc_tratamento . ' - ' . $parlamentar->dsc_participacao . ' - ' . $parlamentar->sgl_partido . ' - ' . $parlamentar->sgl_uf_representante, array('size' => 10));

                        $texto2 = $cellTextoColuna1->addText(mb_strtoupper($parlamentar->nom_parlamentar, 'UTF-8'), array('color' => '000000', 'size' => 12, 'bold' => true));

                        $texto3 = $cellTextoColuna1->addText('Legislatura(s): ' . $legislaturas, array('size' => 10));

                        if (Session::get('permissao') === '0010000' || Session::get('permissao') === '0000100') {

                            if ($celulares != '') {
                                $texto4 = $cellTextoColuna1->addText($celulares, array('size' => 10));
                            }

                        }

                        if ($telefoneGabinete != '') {
                            $texto5 = $cellTextoColuna1->addText($telefoneGabinete, array('size' => 10));
                        }

                        if ($emailGabienete != '') {
                            $texto5 = $cellTextoColuna1->addText('E-mail: ' . $emailGabienete, array('size' => 10));
                        }

                        if ($parlamentar->sgl_uf_nascimento != '') {
                            $texto5 = $cellTextoColuna1->addText('Nasc.: ' . $parlamentar->sgl_uf_nascimento . '/' . $parlamentar->nom_municipio_nascimento, array('size' => 10));
                        }

                        if ($parlamentar->dte_nascimento != '') {
                            $texto5 = $cellTextoColuna1->addText('Data Nasc.: ' . formatarDataComCarbonParaBR($parlamentar->dte_nascimento), array('size' => 10));
                        }

                        if ($parlamentar->dsc_participacao === 'Titular') {
                            $texto5 = $cellTextoColuna1->addText('Ano / Votos / Reeleito: ' . $parlamentar->num_ano_eleicao . ' / ' . formatarNumeroInteiro($parlamentar->num_total_votos) . ' / ' . $parlamentar->dsc_reeleito, array('size' => 10));
                        }

                        // Reduzir o espaçamento superior e inferior entre os textos
                        $row = $cellTextoColuna1->getParent();
                        if ($row instanceof TableRow) {
                            foreach ($row->getCells() as $cell) {
                                $cell->getStyle()->setSpaceBetween(0);
                            }
                        }

                        if ($cargosMesaDiretora != '') {

                            if ($cargosMesaDiretora === 'PRESIDENTE DO SENADO FEDERAL') {
                                $cellColuna1->addText($cargosMesaDiretora, array('size' => 10, 'bold' => true));
                            } elseif ($cargosMesaDiretora === 'PRESIDENTE DA CÂMARA DOS DEPUTADOS') {
                                $cellColuna1->addText($cargosMesaDiretora, array('size' => 10, 'bold' => true));
                            } else {
                                $cellColuna1->addText($cargosMesaDiretora, array('size' => 10));
                            }

                        }

                        if ($cargosLiderancas != '') {
                            $cellColuna1->addText($cargosLiderancas, array('size' => 10));
                        }

                        if ($comissoes != '') {
                            $cellColuna1->addText($comissoes, array('size' => 10));
                        }

                        $cellColuna1->addText(str_repeat("_", 138), ['size' => 7]);

                        $cellColuna1->addTextBreak();

                        // Reduzir o espaçamento superior e inferior entre os textos
                        $row = $cellColuna1->getParent();
                        if ($row instanceof TableRow) {
                            foreach ($row->getCells() as $cell) {
                                $cell->getStyle()->setSpaceBetween(0);
                            }
                        }

                        $contParlamentar++;

                    }

                    if ($contUf <= $ufs->count() - 1) {
                        $section->addPageBreak();
                    }

                }

            }

            $contUf++;

        }

        // Fim do tratamento dos dados para gerar o arquivo Word

        $tempFile = tempnam(sys_get_temp_dir(), 'Word');
        $phpWord->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    private function addSectionToDocument($phpWord)
    {
        return $phpWord->addSection([
            'marginTop' => Converter::cmToTwip(1),
            'marginBottom' => Converter::cmToTwip(0.2),
            'marginLeft' => Converter::cmToTwip(1),
            'marginRight' => Converter::cmToTwip(1),
        ]);
    }

    private function addFooterToSection($section, $uuid)
    {
        $footer = $section->addFooter();
        $table = $footer->addTable();

        // Adicionar linha de divisória
        $dividerRow = $table->addRow();
        $dividerCell = $dividerRow->addCell(12000, ['gridSpan' => 2]); // Mesclar duas colunas
        $dividerCell->addText(str_repeat("_", 138), ['size' => 7]); // Adicionar underlines

        // Adicionar primeira linha da tabela
        $firstRow = $table->addRow();
        $firstRow->addCell(4000)->addText('Extração às ' . date('H:i:s') . ' - ' . date('d/m/Y'), ['size' => 8], ['align' => 'left']);
        $firstRow->addCell(8000)->addPreserveText('página {PAGE}', ['size' => 8], ['align' => 'right']);

        // Adicionar segunda linha da tabela
        $secondRow = $table->addRow();
        $secondRow->addCell(4000, ['valign' => 'top'])->addText('');
        $secondRow->addCell(8000, ['valign' => 'top'])->addPreserveText($uuid, ['color' => '999999', 'size' => 9], ['align' => 'right']);
    }

    private function addStylesToDocument($phpWord)
    {
        $phpWord->addFontStyle(
            'oneUserDefinedStyle',
            ['name' => 'Calibri', 'size' => 13, 'color' => '1B2232', 'bold' => true]
        );
        $phpWord->addParagraphStyle('p2Style', ['align' => 'left', 'spaceAfter' => 100]);
    }




    public function carometroWordPorUFAntigo()
    {
        $fileName = 'teste_' . date('Ymd_His') . '.docx';

        $phpWord = new PhpWord();

        $languageFrFr = new Language('PT_BR');
        $phpWord->getSettings()->setThemeFontLang($languageFrFr);

        $section = $phpWord->addSection([
            'marginTop' => Converter::cmToTwip(1), // 1 cm
            'marginBottom' => Converter::cmToTwip(1), // 1 cm
            'marginLeft' => Converter::cmToTwip(1), // 1 cm
            'marginRight' => Converter::cmToTwip(1), // 1 cm
        ]);

        // Adicionar um cabeçalho à seção
        $header = $section->addHeader();

        // Adicionar um parágrafo ao cabeçalho
        $header->addText('Este é o cabeçalho do documento.');

        // Adicionar um rodapé à seção
        $footer = $section->addFooter();
        $footer->addText("Texto rodapé");

        $section->addText('Hello World!');

        $section = $phpWord->addSection([
            'marginTop' => Converter::cmToTwip(1), // 1 cm
            'marginBottom' => Converter::cmToTwip(1), // 1 cm
            'marginLeft' => Converter::cmToTwip(1), // 1 cm
            'marginRight' => Converter::cmToTwip(1), // 1 cm
        ]);

        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array('name' => 'Calibri', 'size' => 13, 'color' => '1B2232', 'bold' => true)
        );
        $phpWord->addParagraphStyle('p2Style', array('align' => 'center', 'spaceAfter' => 100));
        $section->addText(
            "BRIEFING: ",
            $fontStyleName,
            'p2Style'
        );

        $section = $phpWord->addSection([
            'marginTop' => Converter::cmToTwip(1), // 1 cm
            'marginBottom' => Converter::cmToTwip(1), // 1 cm
            'marginLeft' => Converter::cmToTwip(1), // 1 cm
            'marginRight' => Converter::cmToTwip(1), // 1 cm
        ]);

        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array('name' => 'Calibri', 'size' => 13, 'color' => '1B2232', 'bold' => true)
        );
        $phpWord->addParagraphStyle('p2Style', array('align' => 'center', 'spaceAfter' => 100));
        $section->addText(
            "BRIEFING: ",
            $fontStyleName,
            'p2Style'
        );

        $tempFile = tempnam(sys_get_temp_dir(), 'Word');
        $phpWord->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function exportDadosParlamentaresFederaisAntigo()
    {

        $fileName = date("Ymd_His") . '_base_parlamentares_federais_' . '.xlsx';

        return Excel::download(new BaseParlamentaresFederaisExport(), $fileName);

    }

    public function getExportDadosParlamentaresFederais()
    {
        $fileName = 'base_parlamentares_federais' . '.xlsx';
        $filePath = 'public/export/parlamentar/' . $fileName;

        // Verifica se o arquivo existe
        if (Storage::exists($filePath)) {
            // Retorna o arquivo como download
            return Storage::download($filePath);
        }

        // Retorna uma resposta caso o arquivo não seja encontrado
        return response()->json(['message' => 'Arquivo não encontrado.'], 404);
    }

}
