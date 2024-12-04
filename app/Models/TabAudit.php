<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Uuid;

class TabAudit extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_audit';
    protected $primaryKey = 'cod_audit';
    protected $dates = array('deleted_at');
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_audit = Uuid::uuid4()->toString();
        });
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_user');
    }

}
