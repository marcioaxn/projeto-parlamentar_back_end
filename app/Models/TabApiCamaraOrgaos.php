<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// use Ramsey\Uuid\Uuid;

class TabApiCamaraOrgaos extends Model
{

    protected $primaryKey = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'tab_api_camara_orgaos';

    protected $guarded = array();



}
