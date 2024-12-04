<?php

namespace App\Http\Controllers;

use App\Models\TabAtendimentoAssuntos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabAtendimentoAssuntosController extends Controller
{

    public function getAssuntos()
    {
        return TabAtendimentoAssuntos::orderBy('dsc_assunto')
            ->get();
    }

    public function getAssuntoPorCodAssunto($codAssunto = null)
    {
        return TabAtendimentoAssuntos::where('cod_assunto', $codAssunto)
            ->first();
    }

    public function getAssuntoPorDscAssunto($dscAssunto = null)
    {
        return TabAtendimentoAssuntos::where('dsc_assunto', $dscAssunto)
            ->first();
    }

    public function getPluckAssuntos()
    {
        return TabAtendimentoAssuntos::orderBy('dsc_assunto')
            ->pluck('dsc_assunto', 'cod_assunto');
    }

}
