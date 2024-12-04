<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TabApiCamaraLegislaturas extends Model implements Auditable {

	use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $table = 'tab_api_camara_legislaturas';
    protected $primaryKey = 'id';
    protected $dates = array('deleted_at');
    protected $guarded = array();

}
