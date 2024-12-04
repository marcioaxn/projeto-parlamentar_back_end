<?php

namespace App\Listeners;

use App\Events\TabParlamentaresUpdated;
use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TabParlamentaresUpdatedListener
{
    public function __construct()
    {
        //
    }

    public function handle(TabParlamentaresUpdated $event)
    {
        $tableName = $event->tableName;
        $changes = $tableName->getChanges();

        if (!empty($changes)) {
            $auditLog = new AuditLog();
            $auditLog->table_name = $tableName->getTable();
            $auditLog->record_id = $tableName->getKey();
            $auditLog->user_id = auth()->user()->id; // Se você estiver usando autenticação de usuário

            // Armazene as alterações como JSON para a coluna 'changes'
            $auditLog->changes = json_encode($changes);

            $auditLog->save();
        }
    }
}
