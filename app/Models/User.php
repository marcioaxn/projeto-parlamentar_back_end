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

    protected $table = 'users';

    protected $primaryKey = 'cod_user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'ativo',
        'trocarsenha',
        'cod_perfil',
        'cod_user',
        'bln_admin',
        'profile_photo_path',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'ativo' => 'integer',
        'trocarsenha' => 'integer',
        'bln_admin' => 'boolean'
    ];

    // Mutator para aplicar Hash no email antes de salvá-lo no banco de dados
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value;
        $this->attributes['email_hash'] = Hash::make($value);
    }

    // Mutator para criptografar o nome antes de salvá-lo no banco de dados
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Crypt::encryptString($value);
    }

    // Accessor para recuperar o nome descriptografado
    public function getNameAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['name']);
        } catch (DecryptException $e) {
            return $this->attributes['name'];
        }
    }

    // Mutator para aplicar Hash na senha
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->cod_user) {
                $model->cod_user = Uuid::uuid4()->toString();
            }
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

    public function isAdmin()
    {
        return $this->bln_admin;
    }

    public function isActive()
    {
        return $this->ativo == 1;
    }

    public function needsPasswordChange()
    {
        return $this->trocarsenha == 1;
    }
}
