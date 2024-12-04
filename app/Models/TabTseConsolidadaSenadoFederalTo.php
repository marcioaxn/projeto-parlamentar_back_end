<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\DB;

// use Ramsey\Uuid\Uuid;

class TabTseConsolidadaSenadoFederalTo extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $table = 'tab_tse_consolidada_senado_federal_to';
    protected $primaryKey = false;
    protected $guarded = array();

    public function indicadores()
    {
        return $this->belongsTo(TabMunicipioIndicadores::class, 'cd_mun', 'cod_municipio')
            ->select('*', DB::raw("rpad(idc_idh_2010,5,'0') AS idc_idh_2010"));
    }
}
