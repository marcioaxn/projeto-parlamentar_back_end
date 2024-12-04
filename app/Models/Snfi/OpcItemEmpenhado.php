<?php

namespace App\Models\Snfi;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class OpcItemEmpenhado extends Model
{

    protected $keyType = 'int';
    public $incrementing = false;
    protected $table = 'opc_item_empenhado';
    protected $primaryKey = 'cod_item_empenhado';
    protected $guarded = array();

}
