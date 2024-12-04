<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabAtendimentos extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_atendimentos';
    protected $primaryKey = 'cod_atendimento';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_atendimento = Uuid::uuid4()->toString();
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

    public function assunto()
    {
        return $this->belongsTo(TabAtendimentoAssuntos::class, 'cod_assunto');
    }

    public function quemAtendeu()
    {
        return $this->belongsTo(TabAtendimentoCargos::class, 'cod_cargo');
    }

    public function convidados()
    {
        return $this->hasMany(TabAtendimentoConvidados::class, 'cod_atendimento')
            ->orderBy('nom_convidado');
    }

    public function demandas()
    {
        return $this->hasMany(TabAtendimentoDemandas::class, 'cod_atendimento')
            ->orderBy('dte_prazo');
    }

    public function arquivos()
    {
        return $this->hasMany(TabAtendimentoArquivos::class, 'cod_atendimento')
            ->orderBy('txt_assunto');
    }

}
