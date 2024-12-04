<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabMunicipioIndicadores extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_municipio_indicadores';
    protected $primaryKey = 'cod_municipio';
    protected $guarded = array();

    public function populacao()
    {
        return $this->belongsTo(TabApiIbgePopulacao::class, 'cod_municipio', 'cod_ibge');
    }

    public function densidadeDemografica()
    {
        return $this->belongsTo(TabApiIbgeDensidadeDemografica::class, 'cod_municipio', 'cod_ibge');
    }

    public function pibPerCapita()
    {
        return $this->belongsTo(TabApiIbgePibPerCapita::class, 'cod_municipio', 'cod_ibge');
    }

    public function receitaDespesa()
    {
        return $this->belongsTo(TabApiIbgeReceitasDespesasOrcamentariasRealizadas::class, 'cod_municipio', 'cod_ibge');
    }

    public function idh()
    {
        return $this->belongsTo(TabApiIbgeIdh::class, 'cod_municipio', 'cod_ibge');
    }

    public function gini()
    {
        return $this->belongsTo(TabGini::class, 'cod_municipio', 'cod_ibge');
    }

}
