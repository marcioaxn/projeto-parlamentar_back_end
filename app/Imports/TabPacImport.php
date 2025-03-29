<?php

namespace App\Imports;

use App\Models\TabNovoPac;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class TabPacImport implements ToCollection, WithStartRow, WithMultipleSheets, SkipsOnFailure
{
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function startRow(): int
    {
        return 9;
    }

    public function headingRow(): int
    {
        return 0;
    }

    public function columnFormats(): array
    {

        return [
            'BU' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];

    }

    public function transformDate($value, $format = 'd/m/Y')
    {
        try {
            \Carbon\Carbon::setLocale('pt_BR');
            $date = substr(\Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)), 0, 10);
            return $date;
        } catch (\ErrorException $e) {
            return $value;
        }
    }

    public function collection(Collection $collections)
    {

        // Início das querys para efetuar backup e em seguida limpar a tabela tab_pac
        $nomeTabelaBackup = 'tab_pab_bkp_' . date('Ymd_His');
        DB::select("CREATE TABLE " . $nomeTabelaBackup . " AS SELECT * FROM tab_pac;");
        DB::select('ALTER TABLE ' . $nomeTabelaBackup . ' OWNER TO "grp.ggis_own";');
        DB::select("TRUNCATE TABLE ' . $nomeTabelaBackup . ';");
        // Fim das querys para efetuar backup e em seguida limpar a tabela tab_pac

    }

}
