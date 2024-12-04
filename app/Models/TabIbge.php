<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabIbge extends Model
{

    public $incrementing = false;
    protected $table = 'tab_ibge';
    protected $primaryKey = 'cod_municipio';
    protected $guarded = array();

}
