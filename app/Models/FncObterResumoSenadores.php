<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FncObterResumoSenadores extends Model {

	protected $table = 'fnc_obter_resumo_senador';

	protected $primaryKey = false;

	public $timestamps = false;

	protected $guarded = array();

}
