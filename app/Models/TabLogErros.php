<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabLogErros extends Model
{

    protected $table = 'tab_log_erros';
    protected $primaryKey = 'cod_log_erro';
    protected $guarded = array();

}
