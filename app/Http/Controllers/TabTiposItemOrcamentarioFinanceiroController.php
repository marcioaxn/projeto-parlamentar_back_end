<?php

namespace App\Http\Controllers;

use App\Models\TabTiposItemOrcamentarioFinanceiro;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabTiposItemOrcamentarioFinanceiroController extends Controller
{

    public function getPluckTiposItemOrcamentarioFinanceiro()
    {
        return TabTiposItemOrcamentarioFinanceiro::orderBy('dsc_tipo_item_orcamentario_financeiro')
            ->pluck('dsc_tipo_item_orcamentario_financeiro', 'dsc_tipo_item_orcamentario_financeiro');
    }

}
