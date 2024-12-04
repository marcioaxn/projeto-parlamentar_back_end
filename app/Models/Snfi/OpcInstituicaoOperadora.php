<?php

namespace App\Models\Snfi;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class OpcInstituicaoOperadora extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'opc_instituicao_operadora';
    protected $primaryKey = 'cod_instituicao_operadora';
    protected $guarded = array();

}
