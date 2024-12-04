<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

// use Ramsey\Uuid\Uuid;

class TabTseConsolidadaMunicipios extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $table = 'tab_tse_consolidada_municipios';
    protected $primaryKey = false;
    protected $guarded = array();

    public function indicadores()
    {
        return $this->belongsTo(TabMunicipioIndicadores::class, 'cd_mun', 'cod_municipio');
    }

    public function dadosParlamentar()
    {
        return $this->belongsTo(TabParlamentares::class, 'cod_parlamentar');
    }
}
