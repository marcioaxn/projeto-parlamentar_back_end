<?php

namespace App\Http\Controllers;

use App\Models\TabPisf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TabPisfController extends Controller
{

    /*
    Fpnte de dados
        1.  https://integracao-my.sharepoint.com/:x:/g/personal/ingrid_silva_integracao_gov_br/EcJ6LJrbbnJBmZYOJ8Fgu5EBHQFBlfTBe6sujsQSFX9hCQ?e=4%3AqlRYDM&fromShare=true&at=9&CID=0580ce50-5750-9fdf-e0a4-15246c136e10
    */

    public function getDadosPisfPorIbge($codIbge = null)
    {
        if (isset($codIbge) && !is_null($codIbge) && $codIbge != '') {

            return TabPisf::where('cod_municipio', $codIbge)
                ->first();
        }
    }

}
