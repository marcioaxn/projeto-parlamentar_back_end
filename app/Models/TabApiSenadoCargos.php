<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// use Ramsey\Uuid\Uuid;

class TabApiSenadoCargos extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_api_senado_cargos';
    protected $primaryKey = 'cod_api_senado_cargo';

    protected $guarded = array();

    public function colegiadoAtivo()
    {
        return $this->belongsTo(TabApiSenadoListaColegiadosAtivos::class, 'CodigoComissao');
    }
}
