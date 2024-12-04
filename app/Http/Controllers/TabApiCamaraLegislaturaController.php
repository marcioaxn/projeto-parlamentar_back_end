<?php

namespace App\Http\Controllers;

use App\Models\TabApiCamaraLegislaturas;

use Illuminate\Http\Request;

class TabApiCamaraLegislaturaController extends Controller
{
    public function getLegislaturaPeloAnoAtual()
    {
        return TabApiCamaraLegislaturas::whereYear('dataFim', '>=', date('Y'))
            ->whereYear('dataInicio', '<=', date('Y'))
            ->first();
    }

    public function getLegislaturas()
    {
        return TabApiCamaraLegislaturas::orderBy('id')
            ->get();
    }
}
