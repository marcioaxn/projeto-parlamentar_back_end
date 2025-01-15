<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TabAgenda extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tab_agenda';
    protected $primaryKey = 'cod_agenda';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'dsc_titulo',
        'dsc_descricao',
        'dat_inicio',
        'dat_fim',
        'nom_cor',
        'ind_recorrente',
        'dsc_url',
        'cod_parlamentar',
        'dsc_rrule'
    ];

    protected $casts = [
        'dat_inicio' => 'datetime',
        'dat_fim' => 'datetime',
        'ind_recorrente' => 'boolean',
    ];

    // Generate UUID before creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::uuid();
        });
    }

    // Relationship with Parlamentar
    public function parlamentar()
    {
        return $this->belongsTo(TabParlamentares::class, 'cod_parlamentar');
    }

    // Convert to FullCalendar event format
    public function toEventArray()
    {
        return [
            'id' => $this->cod_agenda,
            'title' => $this->dsc_titulo,
            'description' => $this->dsc_descricao,
            'start' => $this->dat_inicio->toIso8601String(),
            'end' => $this->dat_fim->toIso8601String(),
            'color' => $this->nom_cor,
            'url' => $this->dsc_url,
            'extendedProps' => [
                'cod_parlamentar' => $this->cod_parlamentar,
                'ind_recorrente' => $this->ind_recorrente
            ]
        ];
    }
}
