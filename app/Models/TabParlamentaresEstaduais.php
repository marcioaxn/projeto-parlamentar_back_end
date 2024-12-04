<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabParlamentaresEstaduais extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_parlamentares_estaduais';
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

    public function atendimentos()
    {
        return $this->hasMany(TabAtendimentos::class, 'cod_parlamentar')
            ->orderBy('dte_atendimento', 'DESC');
    }

}
