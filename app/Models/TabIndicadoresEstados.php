<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class TabIndicadoresEstados extends Model
{

    protected $table = 'tab_indicadores_estados';
    protected $primaryKey = 'cod_ibge'; // Defina a chave primária explicitamente
    public $incrementing = false; // Especifique que a chave primária não é autoincremental
    protected $keyType = 'int'; // Especifique o tipo se 'cod_ibge' é string
    protected $guarded = array();

    public function populacao()
    {
        return $this->belongsTo(TabApiIbgePopulacao::class, 'cod_ibge');
    }

    public function densidadeDemografica()
    {
        return $this->belongsTo(TabApiIbgeDensidadeDemografica::class, 'cod_ibge');
    }

    public function rendimentoDomiciliarPerCapita()
    {
        return $this->belongsTo(TabApiIbgeRendimentoNominalMensalDomiciliarPerCapita::class, 'cod_ibge');
    }

    public function receitaDespesa()
    {
        return $this->belongsTo(TabApiIbgeReceitasDespesasOrcamentariasRealizadas::class, 'cod_ibge');
    }

    public function idh()
    {
        return $this->belongsTo(TabApiIbgeIdh::class, 'cod_ibge');
    }

    public function gini()
    {
        return $this->belongsTo(TabGini::class, 'cod_ibge');
    }

}
