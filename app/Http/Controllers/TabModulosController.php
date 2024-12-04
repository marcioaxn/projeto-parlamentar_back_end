<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabModulos;

use Illuminate\Http\Request;

class TabModulosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $user = Auth::user();

        \Session::put('bln_administrar_usuarios', $user->perfil->bln_administrar_usuarios);
        \Session::put('bln_acesso_inrestrito', $user->perfil->bln_acesso_inrestrito);

        $permissoesModulos = $user->permissoesModulos;

        $modulos = $this->getModulos();

        return view('app')
            ->with('permissoesModulos', $permissoesModulos)
            ->with('modulos', $modulos);
    }

    public function getModulos()
    {
        return TabModulos::where('bln_ativo', true)
            ->orderBy('num_ordem_visualizacao')
            ->get();
    }

    public function getModulo($codModulo = null)
    {
        return TabModulos::find($codModulo);
    }
}
