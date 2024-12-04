<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// use Ramsey\Uuid\Uuid;

class TabApiSenadoSegundaLegislaturaMandatoSenadores extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_api_senado_segunda_legislatura_mandato_senadores';
    protected $primaryKey = ['NumeroLegislatura', 'CodigoParlamentar'];

    protected $guarded = array();

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('NumeroLegislatura', '=', $this->getAttribute('NumeroLegislatura'))
            ->where('CodigoParlamentar', '=', $this->getAttribute('CodigoParlamentar'));

        return $query;
    }
}
