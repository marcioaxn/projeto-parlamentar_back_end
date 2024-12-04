<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// use Ramsey\Uuid\Uuid;

class TabApiSenadoListaColegiadosAtivos extends Model
{

    protected $table = 'tab_api_senado_lista_colegiados_ativos';
    protected $primaryKey = 'Codigo';

    protected $guarded = array();
}
