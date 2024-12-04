<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabEvolucaoSaldoEmpenhado extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_evolucao_saldo_empenhado';
    protected $primaryKey = 'cod_evolucao_saldo_empenhado';
    protected $guarded = array();

    public function auditoria()
    {

        return $this->hasMany(Audit::class, 'auditable_id')
            ->whereIn('auditable_type', ['App\Models\TabEvolucaoSaldoEmpenhado'])
            ->whereRaw('NOT (new_values ~* ?)', ['"vlr_saldo_empenhado":null'])
            ->orWhereRaw('NOT (new_values ~* ?)', ['"txt_observacao_saldo_empenhado":null'])
            ->latest();

    }

}
