<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'midr_gestao.audits';

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id')
        ->select('email','name');
    }

}
