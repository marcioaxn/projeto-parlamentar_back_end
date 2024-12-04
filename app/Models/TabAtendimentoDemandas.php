<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabAtendimentoDemandas extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_atendimento_demandas';
    protected $primaryKey = 'cod_demanda_atendimento';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_demanda_atendimento = Uuid::uuid4()->toString();
        });
    }

    public function auditoria()
    {

        return $this->hasMany(Audit::class, 'auditable_id')
            ->orderBy('created_at', 'desc');

    }

    public function orgaoResponsavel()
    {
        return $this->belongsTo(TabOrganizacao::class, 'codigoUnidade');
    }

    public function status()
    {
        return $this->belongsTo(TabAtendimentoDemandaStatus::class, 'cod_status_demanda');
    }

}
