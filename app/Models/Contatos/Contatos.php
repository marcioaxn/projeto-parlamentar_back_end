<?php

namespace App\Models\Contatos;

use App\Models\TabParlamentares;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Contatos extends Model
{
    use SoftDeletes;

    protected $table = 'tab_contatos';
    protected $primaryKey = 'cod_contato';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cod_parlamentar',
        'dsc_tipo_contato',
        'txt_nome',
        'num_telefone',
        'dsc_email',
        'num_cep',
        'dsc_logradouro',
        'dsc_bairro',
        'dsc_cidade',
        'dsc_estado',
        'txt_observacoes',
        'dsc_prefeitura',
        'dsc_camara_municipal',
        'dsc_orgao_publico',
        'dsc_identificador_eleitor',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->cod_contato)) {
                $model->cod_contato = Uuid::uuid4()->toString();
            }
        });
    }

    public function parlamentar()
    {
        return $this->belongsTo(TabParlamentares::class, 'cod_parlamentar', 'cod_parlamentar');
    }
}
