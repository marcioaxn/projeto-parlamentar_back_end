<?php

namespace App\Http\Controllers;

use App\Models\TabPerfil;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabPerfilController extends Controller
{
    public function getPluckPerfil()
    {
        return TabPerfil::orderBy('nom_perfil')
            ->pluck('nom_perfil', 'cod_perfil');
    }

    public function getPerfil()
    {
        return TabPerfil::orderBy('nom_perfil')
            ->get();
    }
}
