<?php

namespace App\Http\Controllers;

use App\Models\TabAtendimentoDemandaStatus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabAtendimentoDemandaStatusController extends Controller
{

    public function getStatus()
    {
        return TabAtendimentoDemandaStatus::orderBy('dsc_status')
            ->get();
    }

    public function getPluckStatus($dscTipoAtualizacao = null)
    {
        return TabAtendimentoDemandaStatus::orderBy('dsc_status')
            ->pluck('dsc_status','cod_status_demanda');
    }

}
