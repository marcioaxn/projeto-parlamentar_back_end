<?php

namespace App\Http\Controllers;

use App\Models\TabAtendimentoInterlocutores;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabAtendimentoInterlocutoresController extends Controller
{

    public function getInterlocutoresElegiveis()
    {
        return TabAtendimentoInterlocutores::where('bln_elegivel', 1)
            ->get();
    }

    public function getPluckInterlocutoresElegiveis()
    {
        return TabAtendimentoInterlocutores::where('bln_elegivel', 1)
            ->pluck('dsc_interlocutor', 'cod_interlocutor');
    }

}
