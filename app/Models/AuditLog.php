<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'table_name',
        'record_id',
        'user_uuid', // ou user_id, dependendo da sua abordagem
        'changes',
    ];

    protected $casts = [
        'changes' => 'array', // A coluna 'changes' será convertida para um array ao ser acessada
    ];

    // Relacionamento opcional com a tabela de usuários, se usar user_uuid como chave estrangeira
    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'id');
    }
}
