<?php

namespace App\Fpdf;

use Session;
use App\User;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Codedge\Fpdf\Fpdf\Fpdf;

class Ffpdf extends Fpdf {

    function Header() {


    }

    function Footer() {
        $this->SetTextColor(105, 105, 105);
        $rodape = utf8_decode("Extração às " . date('H:i:s - d/m/Y'));
        //Vai para 1.5 cm da borda inferior
        //Seleciona Arial itálico 8
        //$this->Image("../images/gif/footer-bg.gif", 5,287,200,5);
        //Imprime o número da página centralizado

        $this->SetXY(4, -12);
        $this->SetFont('Arial', '', 6);
        $this->Cell(100, 0, "_________________________________________________________________________________________________________________________________________________________________________", 0, 0, 'L');


        if($this->PageNo() != 1) {

            $this->SetXY(4, -22);
            $this->SetFont('Arial', '', 7);
            $this->Cell(90, 5, utf8_decode(""), '', 0, 'L');

            $this->SetXY(15, -18);
            $this->Cell(90, 5, utf8_decode(""), '', 0, 'L');

            $this->SetXY(15, -14);
            $this->Cell(90, 5, utf8_decode(""), '', 0, 'L');
        }

        $this->SetXY(4, -10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(90, 5, $rodape, '', 0, 'L');

        $this->SetXY(99, -10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(100, 5, utf8_decode('página ') . $this->PageNo(), '', 0, 'R');
    }

    var $widths;
    var $aligns;

    function SetWidths($w) {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            if ($i <= 0) {
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
            } else {
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            }
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function RowSemBorda($data) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $this->SetDrawColor(255, 255, 255);
            $w = $this->widths[$i];
            if ($i <= 1) {
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'J';
            } else {
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'J';
            }
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->SetDrawColor(255, 255, 255);
            $this->MultiCell($w, 5, $data[$i], '', $a, FALSE);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function RowComBorda($data) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 4 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $this->SetDrawColor(105,105,105);
            $w = $this->widths[$i];
            if ($i <= 1) {
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            } else {
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            }
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->SetDrawColor(255, 255, 255);
            $this->MultiCell($w, 4, $data[$i], '', $a, FALSE);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt) {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l+=$cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
            $i++;
        }
        return $nl;
    }

}
