<?php

namespace App\Models\Snfi;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabEstruturaTemasFundosDesenvolvimentoRegional extends Model {

	protected $table = 'tab_estrutura_temas_fundos_desenvolvimento_regional';

	protected $primaryKey = false;

	public $timestamps = false;

	protected $guarded = array();

}
