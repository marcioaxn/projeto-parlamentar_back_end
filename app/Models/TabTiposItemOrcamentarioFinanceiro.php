<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabTiposItemOrcamentarioFinanceiro extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_tipos_item_orcamentario_financeiro';
    protected $primaryKey = 'cod_tipo_item_orcamentario_financeiro';
    protected $guarded = array();

}
