<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabPermissoesModulo extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = true;

    protected $table = 'tab_permissoes_modulo';
    protected $primaryKey = 'cod_permissao_modulo';
    protected $guarded = array();

}
