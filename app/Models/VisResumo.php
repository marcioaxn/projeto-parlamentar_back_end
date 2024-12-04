<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisResumo extends Model
{

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'vis_resumo';
    protected $primaryKey = false;

}
