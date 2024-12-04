<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabObservacaoParlamentarAssuntos extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_observacao_parlamentar_assuntos';
    protected $primaryKey = 'cod_assunto';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_assunto = Uuid::uuid4()->toString();
        });
    }

}
