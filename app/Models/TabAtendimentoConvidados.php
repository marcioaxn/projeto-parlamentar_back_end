<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabAtendimentoConvidados extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_atendimento_convidados';
    protected $primaryKey = 'cod_convidado';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_convidado = Uuid::uuid4()->toString();
        });
    }

    public function auditoria()
    {

        return $this->hasMany(Audit::class, 'auditable_id')
            ->orderBy('created_at', 'desc');

    }

    public function interlocutor()
    {
        return $this->belongsTo(TabAtendimentoInterlocutores::class, 'cod_interlocutor');
    }

    public function audConvidados()
    {

        return $this->hasMany(Audit::class, 'auditable_id')
            ->orderBy('created_at', 'desc');

    }

}
