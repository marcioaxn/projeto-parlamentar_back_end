<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VisDadosCondensadosSenadores extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $table = 'vis_dados_condensados_senadores';
    protected $primaryKey = 'cod_parlamentar';
    protected $guarded = array();

}
