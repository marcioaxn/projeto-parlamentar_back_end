<?php

namespace App\Http\Controllers;

use App\Models\TabAgenda;
use App\Http\Requests\AgendaRequest; // Vamos criar este Request em seguida
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use RRule\RRule;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = TabAgenda::with('parlamentar')->get(); // Carrega os relacionamentos

        $parlamentares = \App\Models\TabParlamentares::all();

        return view('agendas.index', compact('agendas', 'parlamentares'));
    }

    public function create()
    {
        // Aqui você pode passar dados necessários para o formulário de criação, como parlamentares, etc.
        $parlamentares = \App\Models\TabParlamentares::all(); // Exemplo: buscando todos os parlamentares
        return view('agendas.create', compact('parlamentares'));
    }

    public function store(AgendaRequest $request)
    {

        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->ind_recorrente) {
                $rruleParams = $this->buildRRuleParams($request);
                $data['dsc_rrule'] = (new RRule($rruleParams))->rfcString();
            }

            $agenda = TabAgenda::create($data);

            DB::commit();
            return redirect()
                ->route('agendas.index')
                ->with('success', 'Agenda criada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar agenda: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar agenda. Tente novamente.');
        }
    }

    private function buildRRuleParams(Request $request): array
    {
        $params = [
            'freq' => $request->frequencia,
            'dtstart' => new \DateTime($request->dat_inicio),
        ];

        if ($request->filled('dat_fim_recorrencia')) {
            $params['until'] = new \DateTime($request->dat_fim_recorrencia);
        }

        return $params;
    }

    public function edit(TabAgenda $agenda)
    {
        $parlamentares = \App\Models\TabParlamentares::all();
        return view('agendas.edit', compact('agenda', 'parlamentares'));
    }

    public function update(AgendaRequest $request, TabAgenda $agenda)
    {
        $data = $request->validated();

        if ($request->ind_recorrente) {
            $rruleParams = [
                'freq' => $request->frequencia,
                'dtstart' => new \DateTime($request->dat_inicio),
            ];

            if ($request->has('dat_fim_recorrencia')) {
                $rruleParams['until'] = new \DateTime($request->dat_fim_recorrencia);
            }
            $rrule = new RRule($rruleParams);
            $data['dsc_rrule'] = $rrule->rfcString();
        } else {
            $data['dsc_rrule'] = null;
        }

        $agenda->update($data);

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

        $events = $agendas->map(function ($agenda) {
            return [
                'id' => $agenda->cod_agenda, // UUID como string
                'title' => $agenda->dsc_titulo,
                'start' => $agenda->dat_inicio->format('Y-m-d\TH:i:s'), // Formato ISO 8601
                'end' => $agenda->dat_fim->format('Y-m-d\TH:i:s'), // Formato ISO 8601
                'backgroundColor' => $agenda->nom_cor,
                'url' => route('agendas.edit', $agenda), // URL de edição com o UUID
            ];
        });

        return response()->json($events);
    }
}
