<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class TabContratacao extends Model
{
    use SoftDeletes;

    protected $table = 'tab_contratacao';
    protected $primaryKey = 'cod_contratacao';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cod_plano',
        'cod_usuario',
        'val_total',
        'val_desconto_aplicado',
        'dsc_observacoes',
        'sta_status',
        'dat_inicio',
        'dat_fim'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_contratacao = Uuid::uuid4()->toString();
        });
    }

    /**
     * Mutators e Accessors para criptografia de campos sensíveis
     */

    public function setValTotalAttribute($value)
    {
        $this->attributes['val_total'] = Crypt::encryptString($value);
    }

    public function getValTotalAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['val_total']);
        } catch (DecryptException $e) {
            return $this->attributes['val_total'];
        }
    }

    public function setValDescontoAplicadoAttribute($value)
    {
        $this->attributes['val_desconto_aplicado'] = Crypt::encryptString($value);
    }

    public function getValDescontoAplicadoAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['val_desconto_aplicado']);
        } catch (DecryptException $e) {
            return $this->attributes['val_desconto_aplicado'];
        }
    }

    public function setDscObservacoesAttribute($value)
    {
        $this->attributes['dsc_observacoes'] = Crypt::encryptString($value);
    }

    public function getDscObservacoesAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['dsc_observacoes']);
        } catch (DecryptException $e) {
            return $this->attributes['dsc_observacoes'];
        }
    }

    public function setStaStatusAttribute($value)
    {
        $this->attributes['sta_status'] = Crypt::encryptString($value);
    }

    public function getStaStatusAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['sta_status']);
        } catch (DecryptException $e) {
            return $this->attributes['sta_status'];
        }
    }

    public function setDatInicioAttribute($value)
    {
        $this->attributes['dat_inicio'] = Crypt::encryptString($value);
    }

    public function getDatInicioAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['dat_inicio']);
        } catch (DecryptException $e) {
            return $this->attributes['dat_inicio'];
        }
    }

    public function setDatFimAttribute($value)
    {
        $this->attributes['dat_fim'] = Crypt::encryptString($value);
    }

    public function getDatFimAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['dat_fim']);
        } catch (DecryptException $e) {
            return $this->attributes['dat_fim'];
        }
    }

    /**
     * Relationships
     */

    public function plano()
    {
        return $this->belongsTo(TabPlano::class, 'cod_plano', 'cod_plano');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuario', 'cod_user');
    }
}
