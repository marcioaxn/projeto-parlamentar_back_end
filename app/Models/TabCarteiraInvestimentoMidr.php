<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class TabCarteiraInvestimentoMidr extends Model implements Auditable {

	use \OwenIt\Auditing\Auditable;

	protected $keyType = 'string';
	public $incrementing = false;

	protected $table = 'tab_carteira_investimento_midr';

	protected $primaryKey = 'cod_mdr';

	public $timestamps = false;

	protected $guarded = array();

}
