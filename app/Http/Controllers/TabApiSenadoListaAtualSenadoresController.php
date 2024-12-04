<?php

namespace App\Http\Controllers;

use App\Models\TabApiSenadoListaAtualSenadores;

use Illuminate\Http\Request;

class TabApiSenadoListaAtualSenadoresController extends Controller
{
    public function getSenadores()
    {
        return TabApiSenadoListaAtualSenadores::get();
    }

    public function getSenadoresEmExercicio()
    {
        return TabApiSenadoListaAtualSenadores::where('DescricaoSituacao', 'Exercício')
            ->orderBy('CodigoParlamentar')
            ->get();
    }

}
