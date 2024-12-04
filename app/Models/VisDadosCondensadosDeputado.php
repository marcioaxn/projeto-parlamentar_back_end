<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VisDadosCondensadosDeputado extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $table = 'vis_dados_condensados_deputado';
    protected $primaryKey = 'cod_parlamentar';
    protected $guarded = array();

}
