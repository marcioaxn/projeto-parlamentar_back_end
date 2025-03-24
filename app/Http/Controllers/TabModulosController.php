<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabModulos;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

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

        $gabinetesAtivos = $user->gabinetesAtivos;

        if ($gabinetesAtivos->isEmpty()) {
            $mensagem = 'Você não possui gabinetes ativos.';
            return view('errors.index')
                ->with('mensagem', $mensagem);
        } else {

            $this->getCodParlamentar($gabinetesAtivos->first()->cod_parlamentar);
        }

        \Session::put('bln_administrar_usuarios', $user->bln_admin);
        \Session::put('bln_acesso_inrestrito', $user->bln_admin);

        $codParlamentar = Session::get('cod_parlamentar');

        return redirect()->route('parlamentar');
    }

    public function getCodParlamentar($codParlamentar = null)
    {

        if (isset($codParlamentar) && !empty($codParlamentar)) {
            Session::forget('cod_parlamentar');

            Session::put('cod_parlamentar', $codParlamentar, 7200);
        } else {
            Session::forget('cod_parlamentar');

            Session::put('cod_parlamentar', null, 7200);
        }

        // return redirect()->route('principal');
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
