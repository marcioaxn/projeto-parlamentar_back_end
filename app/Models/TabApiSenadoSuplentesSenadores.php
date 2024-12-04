<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

// use Ramsey\Uuid\Uuid;

class TabApiSenadoSuplentesSenadores extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $table = 'tab_api_senado_suplentes_senadores';
    protected $primaryKey = 'CodigoParlamentarSuplente';
    protected $guarded = array();
}
