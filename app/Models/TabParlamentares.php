<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabParlamentares extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_parlamentares';
    protected $primaryKey = 'cod_parlamentar';
    protected $guarded = array();

    public function celulares()
    {
        return $this->hasMany(TabParlamentarCelular::class, 'cod_parlamentar')
            ->orderBy('num_celular');
    }

    public function observacoes()
    {
        return $this->hasMany(TabObservacoesParlamentar::class, 'cod_parlamentar')
            ->orderBy('created_at');
    }

    public function liderancaDeputados()
    {
        return $this->hasMany(TabApiCamaraLideres::class, 'id')
            ->whereNull('dataFim')
            ->orderBy('nome');
    }

    public function comissoesDeputados()
    {
        return $this->hasMany(TabApiCamaraOrgaos::class, 'deputado_id')
            ->whereNull('dataFim')
            ->whereIn('titulo', ['Titular', 'Presidente', '1º Vice-Presidente'])
            ->orderBy('siglaOrgao');
    }

    public function cargosMesaDiretora()
    {
        return $this->belongsTo(TabCamaraMesa::class, 'cod_parlamentar', 'deputado_id');
    }

    public function legislaturasDeputado()
    {
        return $this->hasMany(TabParlamentarLegislaturas::class, 'cod_parlamentar')
            ->select('cod_parlamentar', 'legislatura')
            ->orderBy('legislatura');
    }

    public function legislaturasSenado()
    {
        return $this->hasMany(TabParlamentarLegislaturas::class, 'cod_parlamentar')
            ->select('cod_parlamentar', 'legislatura')
            ->where('dsc_casa', 'Senado Federal')
            ->orderBy('legislatura');
    }

    public function cargosMesaDiretoraSenado()
    {
        return $this->belongsTo(TabSenadoMesa::class, 'cod_parlamentar', 'CodigoParlamentar');
    }

    public function liderancaSenadores()
    {
        return $this->hasMany(TabApiSenadoLiderancas::class, 'CodigoParlamentar')
            ->whereNull('DataFim')
            ->orderBy('UnidadeLideranca');
    }

    public function comissoesSenadores()
    {
        return $this->hasMany(TabApiSenadoComissoes::class, 'CodigoParlamentar')
            ->where(function ($query) {
                $query->whereYear('DataFim', '>=', date('Y'))
                    ->orWhereNull('DataFim');
            })
            //->where('DescricaoParticipacao', 'Titular')
            ->orderBy('SiglaComissao');
    }

    public function cargosSenadores()
    {
        return $this->hasMany(TabApiSenadoCargos::class, 'CodigoParlamentar')
            ->whereNull('DataFim')
            ->whereIn('CodigoCargo', [1, 2, 6, 21, 108, 109, 112, 115, 116, 129, 154, 160, 186, 189, 256, 187, 259, 260])
            ->with('colegiadoAtivo')
            ->orderBy('CodigoCargo')
            ->orderBy('SiglaComissao');
    }

    public function atendimentos()
    {
        return $this->hasMany(TabAtendimentos::class, 'cod_parlamentar')
            ->orderBy('dte_atendimento', 'DESC');
    }

    public function resumo()
    {
        return $this->belongsTo(TabTseResumoParlamentares::class, 'num_sequencial_candidato', 'sq_candidato');
    }

    public function municipiosMaisVotos()
    {
        return $this->hasMany(TabTseContabilizaVotosParlamentares::class, 'cod_parlamentar')
            ->where('tpo_analise', '+')
            ->orderBy('num_posicao_voto');
    }

    public function municipiosMenosVotos()
    {
        return $this->hasMany(TabTseContabilizaVotosParlamentares::class, 'cod_parlamentar')
            ->where('tpo_analise', '-')
            ->orderBy('num_posicao_voto');
    }

    public function municipiosNenhumVoto()
    {
        return $this->hasMany(TabTseContabilizaVotosParlamentares::class, 'cod_parlamentar')
            ->where('tpo_analise', '0')
            ->orderBy('num_posicao_voto');
    }

}
