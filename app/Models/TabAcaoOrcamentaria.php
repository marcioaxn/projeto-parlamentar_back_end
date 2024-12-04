<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TabAcaoOrcamentaria extends Model {

	protected $keyType = 'string';
	public $incrementing = false;

	protected $table = 'tab_acao_orcamentaria';

	protected $primaryKey = 'cod_acao_orcamentaria';

	public $timestamps = false;

	protected $guarded = array();

}
