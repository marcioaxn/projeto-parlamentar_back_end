<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabApiIbgeRendimentoNominalMensalDomiciliarPerCapita extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_api_ibge_rendimento_nominal_mensal_domiciliar_per_capita';
    protected $primaryKey = 'cod_ibge';
    protected $guarded = array();

}
