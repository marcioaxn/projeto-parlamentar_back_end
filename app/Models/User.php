<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Illuminate\Auth\Passwords\CanResetPassword;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword, \OwenIt\Auditing\Auditable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'midr_gestao.users';

    protected $primaryKey = 'cod_user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'ativo',
        'trocarsenha',
        'codigoUnidade',
        'cod_perfil',
        'cod_user',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'name',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Mutator para aplicar Bcrypt no nome de usuário antes de salvá-lo no banco de dados
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value;
        $this->attributes['email_hash'] = Hash::make($value);
    }

    // Mutator para aplicar Bcrypt no nome de usuário antes de salvá-lo no banco de dados
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Crypt::encryptString($value);
    }

    // Accessor para recuperar o nome de usuário descriptografado
    public function getNameAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['name']);
        } catch (DecryptException $e) {
            return $this->attributes['name'];
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_user = Uuid::uuid4()->toString();
        });
    }

    public function perfil()
    {
        return $this->belongsTo(TabPerfil::class, 'cod_perfil', 'cod_perfil');
    }

    public function permissoesModulos()
    {
        return $this->hasMany(RelUserModuloPermissao::class, 'cod_user');
    }

    public function gabinetesAtivos()
    {
        return $this->belongsToMany(TabGabinete::class, 'rel_gabinetes_users', 'cod_user', 'cod_gabinete')
            ->whereHas('contratos', function ($query) {
                $query->where('sta_ativo', 'A')
                    ->where('dat_inicio', '<=', now())
                    ->where('dat_fim', '>=', now());
            });
    }
}
