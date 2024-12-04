<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
// use Ramsey\Uuid\Uuid;

class TabApiCamaraDeputadosRedesSociais extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_api_camara_deputados_redes_sociais';
    protected $primaryKey = 'dsc_rede_social';
    protected $guarded = array();
}
