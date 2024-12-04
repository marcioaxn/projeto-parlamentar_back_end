<?php

namespace App\Exports;

use Session;
use Auth;

use App\Http\Controllers\TabParlamentaresController;
use App\Http\Controllers\ParlamentarController;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

ini_set('memory_limit', '5096M');
ini_set('max_execution_time', 5500);
set_time_limit(900000000);

class BaseParlamentaresFederaisExport implements FromView, WithEvents
{

    public function instanciarTabParlamentaresController()
    {
        return new TabParlamentaresController;
    }

    public function view(): View
    {

        $tabParlamentares = $this->instanciarTabParlamentaresController();

        $parlamentares = $tabParlamentares->getParlamentaresEmExercicio();

        return view('parlamentar.export.index', [
            'parlamentares' => $parlamentares,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Define o intervalo para os filtros (exemplo: linha 1 com 4 colunas)
                $cellRange = 'A1:T1';
                $event->sheet->getDelegate()->setAutoFilter($cellRange);

                // Aplica a indentação nas células da coluna A, por exemplo
                $event->sheet->getDelegate()->getStyle('A1:T1000')
                    ->getAlignment()->setIndent(1);

                // Desabilita as linhas de grade
                $event->sheet->getDelegate()->setShowGridlines(false);

                // Formatação da coluna S com separador de milhar e sem casas decimais
                $event->sheet->getDelegate()->getStyle('T2:T1000')
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

            },
        ];
    }

}
