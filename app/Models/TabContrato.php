<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabContrato extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'tab_contratos';
    protected $primaryKey = 'cod_contrato';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'cod_plano',
        'cod_gabinete',
        'dat_inicio',
        'dat_fim',
        'val_total',
        'val_desconto_aplicado',
        'val_sub_total',
        'dsc_observacoes',
        'sta_ativo'
    ];

    protected $casts = [
        'dat_inicio' => 'date',
        'dat_fim' => 'date',
        'val_total' => 'decimal:2',
        'val_desconto_aplicado' => 'decimal:2',
        'val_sub_total' => 'decimal:2'
    ];

    public function gabinete()
    {
        return $this->belongsTo(TabGabinete::class, 'cod_gabinete', 'cod_gabinete');
    }

    public function plano()
    {
        return $this->belongsTo(TabPlano::class, 'cod_plano', 'cod_plano');
    }

    public function getRouteKeyName()
    {
        return 'cod_contrato';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_contrato = Uuid::uuid4()->toString();
        });
    }
}
