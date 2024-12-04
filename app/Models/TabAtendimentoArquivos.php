<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabAtendimentoArquivos extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_atendimento_arquivos';
    protected $primaryKey = 'cod_arquivo';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_arquivo = Uuid::uuid4()->toString();
        });
    }

}
