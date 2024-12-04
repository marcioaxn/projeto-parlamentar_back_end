<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabFundosResumoValoresAc extends Model
{

    public $incrementing = false;

    protected $table = 'tab_fundos_resumo_valores_ap';
    protected $primaryKey = false;
    public $timestamps = false;
    protected $guarded = array();

}
