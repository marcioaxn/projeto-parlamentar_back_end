<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabEvolucaoFinanceira extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_evolucao_financeira';
    protected $primaryKey = 'cod_evolucao_financeira';
    protected $guarded = array();

    public function auditoria()
    {

        return $this->hasMany(Audit::class, 'auditable_id')
            ->whereIn('auditable_type', ['App\Models\TabEvolucaoFinanceira'])
            ->whereRaw('NOT (new_values ~* ?)', ['"vlr_financeiro":null'])
            ->orWhereRaw('NOT (new_values ~* ?)', ['"txt_observacao_financeira":null'])
            ->latest();

    }

}
