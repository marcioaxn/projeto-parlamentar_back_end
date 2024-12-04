<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

// use Ramsey\Uuid\Uuid;

class TabTseResumoParlamentares extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $table = 'tab_tse_resumo_parlamentares';
    protected $primaryKey = false;
    protected $guarded = array();

}
