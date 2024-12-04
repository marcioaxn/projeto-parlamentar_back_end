<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

// use Ramsey\Uuid\Uuid;

class TabApiSenadoTelefonesSenadores extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $table = 'tab_api_senado_telefones_senadores';
    protected $primaryKey = 'NumeroTelefone';
    protected $guarded = array();
}
