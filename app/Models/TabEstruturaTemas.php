<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabEstruturaTemas extends Model {

	protected $table = 'tab_estrutura_temas';

	protected $primaryKey = false;

	public $timestamps = false;

	protected $guarded = array();

}
