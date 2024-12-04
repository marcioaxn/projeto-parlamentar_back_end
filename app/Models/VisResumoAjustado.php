<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class VisResumoAjustado extends Model

{

    public $incrementing = false;
    protected $table = 'vis_resumo_ajustado';
    protected $primaryKey = false;
    protected $guarded = array();


}
