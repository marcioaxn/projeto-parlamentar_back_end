<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabPisf extends Model
{

    protected $table = 'tab_pisf';
    protected $primaryKey = 'cod_pisf';
    protected $guarded = array();

}
