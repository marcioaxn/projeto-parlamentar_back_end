<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabAtendimentoCargos extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_atendimento_cargos';
    protected $primaryKey = 'cod_cargo';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_cargo = Uuid::uuid4()->toString();
        });
    }

}
