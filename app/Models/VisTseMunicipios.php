<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VisTseMunicipios extends Model
{

    public $incrementing = false;

    protected $table = 'vis_tse_municipios';
    protected $primaryKey = false;
    protected $guarded = array();
}
