<?php

namespace App\Http\Controllers;

use App\Models\TabAgenda;
use App\Http\Requests\AgendaRequest; // Vamos criar este Request em seguida
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use RRule\RRule;
use Session;

class AgendaController extends Controller
{
    public function index()
    {

        $sessoesAtivas = Session::get('gabinete');

        dd($sessoesAtivas);

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

            // Geração de RRule para recorrência
            if ($request->ind_recorrente) {
                $rruleParams = $this->buildRRuleParams($request);
                $data['dsc_rrule'] = (new RRule($rruleParams))->rfcString();
            } else {
                $data['dsc_rrule'] = null; // Limpa se não for recorrente
            }

            $agenda = TabAgenda::create($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Evento criado com sucesso!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar evento. Detalhes: ' . $e->getMessage()
            ], 500);
        }
    }

    private function buildRRuleParams(Request $request): array
    {
        $params = [
            'freq' => $request->frequencia,
            'dtstart' => Carbon::parse($request->dat_inicio)->setTimezone('UTC')->toDateTimeString(),
        ];

        if ($request->filled('dat_fim_recorrencia')) {
            $params['until'] = Carbon::parse($request->dat_fim_recorrencia)->setTimezone('UTC')->toDateTimeString();
        }

        return $params;
    }

    public function getEvents(Request $request)
    {
        $events = TabAgenda::all()->map(function ($event) {
            return [
                'id' => $event->cod_agenda,
                'title' => $event->dsc_titulo,
                'start' => $event->dat_inicio->toIso8601String(),
                'end' => $event->dat_fim->toIso8601String(),
                'backgroundColor' => $event->nom_cor, // Corrigido para background
                'url' => $event->dsc_url, // Usando o campo URL corretamente
                'extendedProps' => [
                    'cod_parlamentar' => $event->cod_parlamentar,
                    'ind_recorrente' => $event->ind_recorrente,
                    'description' => $event->dsc_descricao
                ]
            ];
        });

        return response()->json($events);
    }

    public function update(AgendaRequest $request, TabAgenda $agenda)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Geração de RRule para recorrência
            if ($request->ind_recorrente) {
                $rruleParams = $this->buildRRuleParams($request);
                $data['dsc_rrule'] = (new RRule($rruleParams))->rfcString();
            } else {
                $data['dsc_rrule'] = null; // Limpa se não for recorrente
            }

            $agenda->update($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Evento atualizado com sucesso!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar evento. Detalhes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(TabAgenda $agenda)
    {
        DB::beginTransaction();
        try {
            $agenda->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Evento excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir evento. Detalhes: ' . $e->getMessage()
            ], 500);
        }
    }
}
