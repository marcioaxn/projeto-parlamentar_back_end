<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabParlamentarLegislaturas extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $table = 'tab_parlamentar_legislaturas';
    protected $primaryKey = 'id';
    protected $guarded = array();

}
