<?php

namespace App\Http\Controllers;

use App\Models\TabAcaoOrcamentaria;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabAcaoOrcamentariaController extends Controller
{
    public function getPluck()
    {
        return TabAcaoOrcamentaria::select('cod_acao_orcamentaria', DB::raw("CONCAT(cod_acao_orcamentaria,' - ', dsc_acao_orcamentaria) AS dsc_acao_orcamentaria"))
        ->orderBy('cod_acao_orcamentaria')
            ->pluck('dsc_acao_orcamentaria', 'cod_acao_orcamentaria');
    }

    public function getPerfil()
    {
        return TabAcaoOrcamentaria::orderBy('nom_perfil')
            ->get();
    }
}
