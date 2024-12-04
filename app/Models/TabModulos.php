<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabModulos extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_modulos';
    protected $primaryKey = 'cod_modulo';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_modulo = Uuid::uuid4()->toString();
        });
    }

}
