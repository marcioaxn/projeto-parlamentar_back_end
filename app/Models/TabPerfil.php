<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabPerfil extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_perfil';
    protected $primaryKey = 'cod_perfil';
    protected $guarded = array();

    // Evento 'creating' para gerar e atribuir UUID ao criar o usuário
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_perfil = Uuid::uuid4()->toString();
        });
    }

}
