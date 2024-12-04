<?php

namespace App\Http\Controllers;

use App\Models\TabObservacaoParlamentarAssuntos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabObservacaoParlamentarAssuntosController extends Controller
{

    public function getAssuntos()
    {
        return TabObservacaoParlamentarAssuntos::orderBy('dsc_assunto')
            ->get();
    }

    public function getAssuntoPorCodAssunto($codAssunto = null)
    {
        return TabObservacaoParlamentarAssuntos::where('cod_assunto', $codAssunto)
            ->first();
    }

    public function getAssuntoPorDscAssunto($dscAssunto = null)
    {
        return TabObservacaoParlamentarAssuntos::where('dsc_assunto', $dscAssunto)
            ->first();
    }

    public function getPluckAssuntos()
    {
        return TabObservacaoParlamentarAssuntos::orderBy('dsc_assunto')
            ->pluck('dsc_assunto', 'cod_assunto');
    }

}
