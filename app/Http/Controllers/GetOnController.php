<?php

namespace App\Http\Controllers;

use App\Models\TabAgenda;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GetOnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAgendaPorParlamentarHoje($codParlamentar = null)
    {
        if (isset($codParlamentar) && !empty($codParlamentar)) {

            $hoje = Carbon::today(); // Obtém a data de hoje

            return TabAgenda::where('cod_parlamentar', $codParlamentar)
                ->whereDate('dat_inicio', $hoje)
                ->orderBy('dat_inicio')
                ->get();
        }
    }
}
