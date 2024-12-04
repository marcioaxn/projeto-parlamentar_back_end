<?php

namespace App\Http\Controllers;

use App\Models\TabApiCamaraDeputados;

use Illuminate\Http\Request;

class TabApiCamaraDeputadosController extends Controller
{
    public function getDeputados()
    {
        return TabApiCamaraDeputados::get();
    }

    public function getIdDeputados()
    {
        return TabApiCamaraDeputados::select('id')
            ->orderBy('id')
            ->get();
    }
}
