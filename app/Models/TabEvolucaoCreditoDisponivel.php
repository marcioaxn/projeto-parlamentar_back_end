<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\AuditableRelation;

class TabEvolucaoCreditoDisponivel extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_evolucao_credito_disponivel';
    protected $primaryKey = 'cod_evolucao_credito_disponivel';
    protected $guarded = array();

    public function auditoria()
    {

        return $this->hasMany(Audit::class, 'auditable_id')
            ->whereIn('auditable_type', ['App\Models\TabEvolucaoCreditoDisponivel'])
            ->whereRaw('NOT (new_values ~* ?)', ['"vlr_credito_disponivel":null'])
            ->orWhereRaw('NOT (new_values ~* ?)', ['"txt_observacao_credito_disponivel":null'])
            ->latest();

    }

}
