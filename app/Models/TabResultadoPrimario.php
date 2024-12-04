<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabResultadoPrimario extends Model
{

    public $incrementing = true;
    protected $table = 'tab_resultado_primario';
    protected $primaryKey = 'cod_resultado_primario';
    protected $guarded = array();

}
