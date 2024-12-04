<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabSenadoMesa extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $table = 'tab_senado_mesa';
    protected $primaryKey = 'cod_senado_mesa';
    protected $guarded = array();

}
