<?php

namespace App\Http\Controllers;

use App\Models\TabPermissoesModulo;

use Illuminate\Http\Request;

class TabPermissoesModuloController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getPermissoesModulo()
    {
        return TabPermissoesModulo::orderBy('cod_permissao_modulo')
            ->get();
    }
}
