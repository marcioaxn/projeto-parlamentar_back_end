<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabModulos;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        return view('dashboard.index');
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
