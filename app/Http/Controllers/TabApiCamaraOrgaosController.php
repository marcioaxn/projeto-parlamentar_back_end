<?php

namespace App\Http\Controllers;

use App\Models\TabApiCamaraOrgaos;

use Illuminate\Http\Request;

class TabApiCamaraOrgaosController extends Controller
{
    public function getCamaraOrgaos()
    {
        return TabApiCamaraOrgaos::select('siglaOrgao', 'nomeOrgao')
            ->groupBy('siglaOrgao', 'nomeOrgao')
            ->orderBy('siglaOrgao')
            ->get();
    }
}
