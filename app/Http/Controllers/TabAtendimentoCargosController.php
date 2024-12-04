<?php

namespace App\Http\Controllers;

use App\Models\TabAtendimentoCargos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabAtendimentoCargosController extends Controller
{

    public function getCargos()
    {
        return TabAtendimentoCargos::orderBy('dsc_cargo')
            ->get();
    }

    public function getPluckCargos()
    {
        return TabAtendimentoCargos::orderBy('dsc_cargo')
            ->pluck('dsc_cargo', 'cod_cargo');
    }

}
