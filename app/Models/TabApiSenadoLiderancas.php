<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// use Ramsey\Uuid\Uuid;

class TabApiSenadoLiderancas extends Model
{

    protected $table = 'tab_api_senado_liderancas';
    protected $primaryKey = 'cod_lideranca';

    protected $guarded = array();
}
