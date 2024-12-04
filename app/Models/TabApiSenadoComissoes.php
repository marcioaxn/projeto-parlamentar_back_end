<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;

class TabApiSenadoComissoes extends Model
{

    protected $primaryKey = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_api_senado_comissoes';


    protected $guarded = array();

    /*
    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('CodigoComissao', '=', $this->getAttribute('CodigoComissao'))
            ->where('CodigoParlamentar', '=', $this->getAttribute('CodigoParlamentar'));

        return $query;
    }
    */
}
