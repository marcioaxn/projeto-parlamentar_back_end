<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabNovoPac extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_novo_pac';
    protected $primaryKey = 'cod_pac';
    protected $guarded = array();

    protected $dates = ['created_at', 'updated_at'];

    public function tabAudit()
    {
        return $this->hasMany(TabAudit::class, 'table_id')
            ->where('table', 'tab_novo_pac')
            ->orderBy('created_at', 'DESC');
    }

    public function auditoria()
    {

        return $this->hasMany(Audit::class, 'auditable_id')
            ->whereIn('auditable_type', ['App\Models\TabNovoPac'])
            ->where('old_values', '!=', '[]')
            ->orWhere('new_values', '!=', '[]')
            ->latest();

    }

    public function areaResponsavel()
    {
        return $this->belongsTo(TabOrganizacao::class, 'codigoUnidade', 'codigoUnidade');
    }

    public function evolucaoFinanceira()
    {
        return $this->hasMany(TabEvolucaoFinanceira::class, 'cod_pac')
            ->with('auditoria')
            ->orderBy('num_ano')
            ->orderBy('num_mes');
    }

    public function evolucaoCreditoDisponivel()
    {
        return $this->hasMany(TabEvolucaoCreditoDisponivel::class, 'cod_pac')
            ->with('auditoria')
            ->orderBy('cod_acao_orcamentaria')
            ->orderBy('num_ano');
    }

    public function evolucaoSaldoEmpenhado()
    {
        return $this->hasMany(TabEvolucaoSaldoEmpenhado::class, 'cod_pac')
            ->with('auditoria')
            ->orderBy('cod_acao_orcamentaria')
            ->orderBy('num_ano');
    }

    public function evolucaoSuplementacaoOrcamentaria()
    {
        return $this->hasMany(TabEvolucaoSuplementacaoOrcamentaria::class, 'cod_pac')
            ->with('auditoria')
            ->orderBy('cod_acao_orcamentaria')
            ->orderBy('num_ano');
    }

    public function areasResponsaveisGestaoOrcamentariaFInanceira()
    {
        return $this->belongsToMany(TabOrganizacao::class, 'rel_pac_organizacao', 'cod_pac', 'codigoUnidade')
        ->select('tab_organizacao.codigoUnidade', 'tab_organizacao.sigla');
    }

}
