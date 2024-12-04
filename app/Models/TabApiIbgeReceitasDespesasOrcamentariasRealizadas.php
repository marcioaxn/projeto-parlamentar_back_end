<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabApiIbgeReceitasDespesasOrcamentariasRealizadas extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_api_ibge_receitas_despesas_orcamentarias_realizadas';
    protected $primaryKey = 'cod_ibge';
    protected $guarded = array();

}
