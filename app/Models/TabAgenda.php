<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TabAgenda extends Model
{
    use HasFactory, SoftDeletes;

    // Nome da tabela no banco de dados
    protected $table = 'tab_agenda';

    // Chave primária (UUID)
    protected $primaryKey = 'cod_agenda';
    public $incrementing = false;
    protected $keyType = 'string';

    // Campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'cod_agenda',
        'dsc_titulo',
        'dsc_descricao',
        'dat_inicio',
        'dat_fim',
        'dsc_local',
        'nom_cor',
        'ind_recorrente',
        'dsc_url',
        'dsc_rrule',
        'cod_parlamentar'
    ];

    // Conversão de tipos (casting)
    protected $casts = [
        'dat_inicio' => 'datetime',
        'dat_fim' => 'datetime',
        'ind_recorrente' => 'boolean'
    ];

    // Eventos do Eloquent (boot)
    protected static function boot()
    {
        parent::boot();

        // Gera o UUID antes de criar o registro
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::uuid();
        });

        // Valida datas antes de salvar
        static::saving(function ($model) {
            if ($model->dat_fim < $model->dat_inicio) {
                throw new \Exception("A data de fim não pode ser anterior à data de início.");
            }
        });
    }

    // Relacionamento com o Model TabParlamentares
    public function parlamentar()
    {
        return $this->belongsTo(TabParlamentares::class, 'cod_parlamentar');
    }

    // Converte o evento para o formato do FullCalendar
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

    // Scope para filtrar eventos recorrentes
    public function scopeRecorrentes($query)
    {
        return $query->where('ind_recorrente', true);
    }
}
