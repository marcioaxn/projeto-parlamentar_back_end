<?php

namespace App\Models\Snfi;

use App\Models\TabIndicadoresEstados;
use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RelTabFundosDesenvolvimentoRegionalTabEstados extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'rel_tab_fundos_desenvolvimento_regional_tab_estados';
    protected $primaryKey = false;

    protected $guarded = array();

}
