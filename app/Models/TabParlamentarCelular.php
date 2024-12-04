<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabParlamentarCelular extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'num_celular',
        'cod_parlamentar'
    ];

    protected $table = 'tab_parlamentar_celular';
    protected $primaryKey = 'cod_celular';
    protected $dates = array('deleted_at');
    protected $guarded = array();

    // Mutator para aplicar Bcrypt no número de celular antes de salvá-lo no banco de dados
    public function setNumCelularAttribute($value)
    {
        $this->attributes['num_celular'] = Crypt::encryptString($value);
    }

    // Accessor para recuperar o número de celular descriptografado
    public function getNumCelularAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['num_celular']);
        } catch (DecryptException $e) {
            return $this->attributes['num_celular'];
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_celular = Uuid::uuid4()->toString();
        });
    }

    public function auditoria()
    {

        return $this->hasMany(Audit::class, 'auditable_id')
            ->orderBy('created_at', 'desc');

    }

}
