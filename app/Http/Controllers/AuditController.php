<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Audit;
use App\Models\TabNovoPac;
use App\Models\TabEvolucaoFinanceira;
use Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class AuditController extends Controller
{
    public function getAuditoriaPorTableNameEChavePrimaria($model = null, $chavePrimaria = null)
    {

        $result = Audit::with('usuario')
            ->orderBy('created_at', 'DESC');

        if (isset($model) && !is_null($model) && $model != '') {

            $result = $result->where('auditable_type', $model);

        }

        if (isset($chavePrimaria) && !is_null($chavePrimaria) && $chavePrimaria != '') {

            $result = $result->whereIn('auditable_id', $chavePrimaria);

        }

        $result = $result->get();

        return $result;
    }

}
