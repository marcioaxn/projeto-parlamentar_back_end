<?php

namespace App\Http\Controllers;

use App\Models\TabUltimaAtualizacaoParlamentares;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabUltimaAtualizacaoParlamentaresController extends Controller
{

    public function getUltimaAtualizacao($dscTipoAtualizacao = null)
    {
        return TabUltimaAtualizacaoParlamentares::where('dsc_tipo_atualizacao', $dscTipoAtualizacao)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

}
