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

class TabObservacoesParlamentar extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'txt_observacao_parlamentar',
        'cod_assunto',
        'cod_parlamentar'
    ];

    protected $table = 'tab_observacoes_parlamentar';
    protected $primaryKey = 'cod_observacao_parlamentar';
    protected $dates = array('deleted_at');
    protected $guarded = array();

    // Mutator para aplicar Bcrypt no texto da observação de salvá-lo no banco de dados
    public function setTxtObservacaoParlamentarAttribute($value)
    {
        $this->attributes['txt_observacao_parlamentar'] = Crypt::encryptString($value);
    }

    // Accessor para recuperar o o texto da observação descriptografado
    public function getTxtObservacaoParlamentarAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['txt_observacao_parlamentar']);
        } catch (DecryptException $e) {
            return $this->attributes['txt_observacao_parlamentar'];
        }
    }

    public function assunto()
    {
        return $this->belongsTo(TabObservacaoParlamentarAssuntos::class, 'cod_assunto')
            ->orderBy('dsc_assunto');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_observacao_parlamentar = Uuid::uuid4()->toString();
        });
    }

}
