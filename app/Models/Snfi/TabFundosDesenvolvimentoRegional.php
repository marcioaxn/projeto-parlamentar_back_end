<?php

namespace App\Models\Snfi;

use App\Models\TabIndicadoresEstados;
use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabFundosDesenvolvimentoRegional extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_fundos_desenvolvimento_regional';
    protected $primaryKey = 'cod_projeto';
    protected $guarded = array();

    protected $dates = ['created_at', 'updated_at'];

    public function auditoriaColuna()
    {
        return $this->hasMany(\App\Models\TabAudit::class, 'table_id')
            ->where('table', 'tab_fundos_desenvolvimento_regional')
            ->orderBy('created_at', 'DESC');
    }

    public function auditoria()
    {

        return $this->hasMany(\App\Models\Audit::class, 'auditable_id')
            ->whereIn('auditable_type', ['App\Models\Snfi\TabFundosDesenvolvimentoRegional'])
            ->where('old_values', '!=', '[]')
            ->orWhere('new_values', '!=', '[]')
            ->latest();

    }

    public function orcamento()
    {
        return $this->hasMany(TabOrcamentoFdr::class, 'cod_projeto');
    }

    public function fundos()
    {
        return $this->belongsTo(OpcFundos::class, 'cod_fundo');
    }

    public function instituicaoOperadora()
    {
        return $this->belongsTo(OpcInstituicaoOperadora::class, 'cod_instituicao_operadora');
    }

    public function status()
    {
        return $this->belongsTo(OpcStatusContrato::class, 'cod_status_contrato');
    }

    public function ufs()
    {
        return $this->belongsToMany(
            TabIndicadoresEstados::class,
            'midr_snfi.rel_tab_fundos_desenvolvimento_regional_tab_estados',
            'cod_projeto',
            'cod_ibge'
        );
    }

}
