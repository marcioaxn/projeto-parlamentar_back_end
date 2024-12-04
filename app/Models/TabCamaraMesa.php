<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabCamaraMesa extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $table = 'tab_camara_mesa';
    protected $primaryKey = 'cod_camara_mesa';
    protected $guarded = array();

}
