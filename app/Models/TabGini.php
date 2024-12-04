<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabGini extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_gini';
    protected $primaryKey = 'cod_ibge';
    protected $guarded = array();

}
