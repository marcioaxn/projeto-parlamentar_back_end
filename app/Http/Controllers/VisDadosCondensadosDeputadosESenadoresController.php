<?php

namespace App\Http\Controllers;

use App\Models\VisDadosCondensadosDeputado;
use App\Models\VisDadosCondensadosSenadores;
use App\Models\VisDadosCondensadosDeputadosESenadores;

use Illuminate\Http\Request;

class VisDadosCondensadosDeputadosESenadoresController extends Controller
{

    public function getDeputadosFederais()
    {
        return VisDadosCondensadosDeputado::get();
    }

    public function getSenadores()
    {
        return VisDadosCondensadosSenadores::get();
    }

    public function getParlamentares()
    {
        return VisDadosCondensadosDeputadosESenadores::get();
    }
}
