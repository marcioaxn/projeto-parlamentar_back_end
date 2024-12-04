<?php

namespace App\Models\Snfi;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabOrcamentoFdr extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'tab_orcamento_fdr';
    protected $primaryKey = 'cod_orcamento_fdr';
    protected $guarded = array();

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_orcamento_fdr = Uuid::uuid4()->toString();
        });
    }

    public function itemOrcamento()
    {
        $this->belongsTo(OpcItemOrcamento::class, 'cod_item_orcamento');
    }

    public function itemEmpenho()
    {
        $this->belongsTo(OpcItemEmpenhado::class, 'cod_item_empenhado');
    }

}
