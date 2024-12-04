<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabEvolucaoOrcamentaria extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_evolucao_orcamentaria';
    protected $primaryKey = 'cod_evolucao_orcamentaria';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_evolucao_orcamentaria = Uuid::uuid4()->toString();
        });
    }

    public function auditoria()
    {

        return $this->hasMany(Audit::class, 'auditable_id')
            ->whereIn('auditable_type', ['App\Models\TabEvolucaoOrcamentaria'])
            ->whereRaw('NOT (new_values ~* ?)', ['"vlr_orcamentario":null'])
            ->orWhereRaw('NOT (new_values ~* ?)', ['"txt_observacao_orcamentario":null'])
            ->latest();

    }

}
