<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelGabineteUser extends Model
{
    protected $table = 'rel_gabinetes_users';

    protected $fillable = [
        'cod_gabinete',
        'user_id',
        'acesso_total'
    ];

    // Cast boolean field
    protected $casts = [
        'acesso_total' => 'boolean'
    ];

    public function gabinete()
    {
        return $this->belongsTo(TabGabinete::class, 'cod_gabinete');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
