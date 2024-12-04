<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// use Ramsey\Uuid\Uuid;

class TabApiSenadoBlocoSenadores extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_api_senado_bloco_senadores';
    protected $primaryKey = ['CodigoBloco', 'CodigoParlamentar'];

    protected $guarded = array();

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('CodigoBloco', '=', $this->getAttribute('CodigoBloco'))
            ->where('CodigoParlamentar', '=', $this->getAttribute('CodigoParlamentar'));

        return $query;
    }
}
