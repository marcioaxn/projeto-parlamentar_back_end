<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Uuid;
use OwenIt\Auditing\Contracts\Auditable;

use App\Traits\Encryptable;

class TabGabinete extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Encryptable, \OwenIt\Auditing\Auditable;

    /**
     * Nome da tabela associada ao Model.
     */
    protected $table = 'tab_gabinete';

    /**
     * Chave primária do Model.
     */
    protected $primaryKey = 'cod_gabinete';

    /**
     * O tipo da chave primária é string.
     */
    protected $keyType = 'string';

    /**
     * Indica que a chave primária não é incremental.
     */
    public $incrementing = false;

    /**
     * Campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'cod_parlamentar',
        'nom_gabinete',
        'sta_ativo',
    ];

    protected $encryptable = [ // Defina os campos que serão criptografados
        'nom_gabinete',
    ];

    /**
     * Boot model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->cod_gabinete = Uuid::uuid4()->toString();
        });
    }

    /**
     * Relacionamento com o modelo de parlamentares.
     */
    public function parlamentar()
    {
        return $this->belongsTo(TabParlamentares::class, 'cod_parlamentar', 'cod_parlamentar');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'rel_gabinetes_users', 'cod_gabinete', 'cod_user')
            ->withPivot('acesso_total')
            ->withTimestamps();
    }

    public function contratos()
    {
        return $this->hasMany(TabContrato::class, 'cod_gabinete', 'cod_gabinete');
    }
}
