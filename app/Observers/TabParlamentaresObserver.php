<?php

namespace App\Observers;

use App\Models\TabParlamentares;

class TabParlamentaresObserver
{
    public function created(TabParlamentares $tabParlamentares)
    {
        //
    }

    public function updated(TabParlamentares $tabParlamentares)
    {
        $originalData = $tabParlamentares->getOriginal(); // Obtém os valores originais do modelo antes da atualização
        $changes = [];

        // Verifica se o campo 'dsc_situacao' foi alterado
        if ($tabParlamentares->isDirty('dsc_situacao')) {
            $changes['dsc_situacao'] = [
                'old' => $originalData['dsc_situacao'],
                'new' => $tabParlamentares->dsc_situacao,
            ];
        }
    }

    public function deleted(TabParlamentares $tabParlamentares)
    {
        //
    }

    public function restored(TabParlamentares $tabParlamentares)
    {
        //
    }

    public function forceDeleted(TabParlamentares $tabParlamentares)
    {
        //
    }
}
