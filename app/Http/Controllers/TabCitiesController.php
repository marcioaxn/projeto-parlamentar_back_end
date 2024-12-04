<?php

namespace App\Http\Controllers;

use App\Models\TabCities;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TabCitiesController extends Controller
{
    public function getCoordenadasPorCodIbge($codIbge = null)
    {
        if (isset($codIbge) && !is_null($codIbge) && $codIbge != '') {

            return TabCities::find($codIbge);
        }
    }
}
