<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabOrganizacao extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;
    protected $table = 'midr_organizacao.tab_organizacao';
    protected $primaryKey = 'codigoUnidade';
    protected $guarded = array();

}
