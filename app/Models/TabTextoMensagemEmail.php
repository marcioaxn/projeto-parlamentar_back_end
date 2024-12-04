<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Uuid;

class TabTextoMensagemEmail extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_texto_mensagem_email';
    protected $primaryKey = 'cod_texto_mensagem_email';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_audit = Uuid::uuid4()->toString();
        });
    }

}
