<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabPlano extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    /**
     * Definição da tabela associada ao Model.
     */
    protected $table = 'tab_planos';

    /**
     * Chave primária do Model.
     */
    protected $primaryKey = 'cod_plano';

    /**
     * O tipo de chave primária é string.
     */
    protected $keyType = 'string';

    /**
     * Indica que a chave primária não é incremental.
     */
    public $incrementing = false;

    /**
     * Campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'nom_plano',
        'dsc_plano',
        'val_plano',
        'lim_usuarios',
        'sta_ativo'
    ];

    /**
     * Mutators e Accessors para criptografia de campos.
     */
    public function setNomPlanoAttribute($value)
    {
        $this->attributes['nom_plano'] = Crypt::encryptString($value);
    }

    public function getNomPlanoAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setDscPlanoAttribute($value)
    {
        $this->attributes['dsc_plano'] = Crypt::encryptString($value);
    }

    public function getDscPlanoAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setValPrecoAttribute($value)
    {
        $this->attributes['val_plano'] = Crypt::encryptString($value);
    }

    public function getValPrecoAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Boot model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_plano = Uuid::uuid4()->toString();
        });
    }
}
