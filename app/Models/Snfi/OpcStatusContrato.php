<?php

namespace App\Models\Snfi;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class OpcStatusContrato extends Model
{

    protected $keyType = 'int';
    public $incrementing = true;
    protected $table = 'opc_status_contrato';
    protected $primaryKey = 'cod_status_contrato';
    protected $guarded = array();

}
