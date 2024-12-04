<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabCities extends Model
{

    public $incrementing = false;
    protected $table = 'tab_cities';
    protected $primaryKey = 'codigo_ibge';
    protected $guarded = array();

}
