<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabApiCamaraLideres extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $table = 'tab_api_camara_lideres';
    protected $primaryKey = 'id';
    protected $guarded = array();

}
