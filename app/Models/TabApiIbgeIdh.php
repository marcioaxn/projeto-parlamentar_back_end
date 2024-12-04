<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabApiIbgeIdh extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_api_ibge_idh';
    protected $primaryKey = 'cod_ibge';
    protected $guarded = array();

}
