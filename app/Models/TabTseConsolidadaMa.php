<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

// use Ramsey\Uuid\Uuid;

class TabTseConsolidadaMa extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $table = 'tab_tse_consolidada_ma';
    protected $primaryKey = false;
    public $timestamps = false;
    protected $guarded = array();

    public function parlamentarExeercicio()
    {
        return $this->hasOne(TabParlamentares::class, 'num_sequencial_candidato', 'sq_candidato_1')
            ->where('dsc_situacao', 'Exercício');
    }

}
