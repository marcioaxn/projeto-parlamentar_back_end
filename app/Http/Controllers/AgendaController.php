<?php

namespace App\Http\Controllers;

use App\Models\TabAgenda;
use App\Http\Requests\AgendaRequest; // Vamos criar este Request em seguida
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = TabAgenda::with('parlamentar')->get(); // Carrega os relacionamentos

        return view('agendas.index', compact('agendas'));
    }

    public function create()
    {
        // Aqui você pode passar dados necessários para o formulário de criação, como parlamentares, etc.
        $parlamentares = \App\Models\TabParlamentar::all(); // Exemplo: buscando todos os parlamentares
        return view('agendas.create', compact('parlamentares'));
    }

    public function store(AgendaRequest $request)
    {
        TabAgenda::create($request->validated());

        return redirect()->route('agendas.index')->with('success', 'Agenda criada com sucesso.');
    }

    public function edit(TabAgenda $agenda)
    {
        $parlamentares = \App\Models\TabParlamentar::all();
        return view('agendas.edit', compact('agenda', 'parlamentares'));
    }

    public function update(AgendaRequest $request, TabAgenda $agenda)
    {
        $agenda->update($request->validated());

        return redirect()->route('agendas.index')->with('success', 'Agenda atualizada com sucesso.');
    }

    public function destroy(TabAgenda $agenda)
    {
        $agenda->delete();

        return redirect()->route('agendas.index')->with('success', 'Agenda excluída com sucesso.');
    }

    public function getEvents(Request $request)
    {
        $agendas = TabAgenda::all();

        $events = [];
        foreach ($agendas as $agenda) {
            $events[] = [
                'id' => $agenda->cod_agenda,
                'title' => $agenda->dsc_titulo,
                'start' => $agenda->dat_inicio,
                'end' => $agenda->dat_fim,
                'color' => $agenda->nom_cor,
                'url' => $agenda->des_url,
            ];
        }

        return response()->json($events);
    }
}
