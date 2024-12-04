<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

class VisTciEmendas extends Model implements Auditable {

	use \OwenIt\Auditing\Auditable;

	protected $keyType = 'string';
	public $incrementing = false;

	protected $table = 'vis_tci_emendas';

	protected $primaryKey = 'cod_mdr';

	public $timestamps = false;

	protected $guarded = array();

}
