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
    public function listar()
    {
        try {
            $codParlamentar = Session::get('cod_parlamentar');
            
            $agendas = TabAgenda::where('cod_parlamentar', $codParlamentar)
                ->get()
                ->map(function($agenda) {
                    return [
                        'id' => $agenda->cod_agenda,
                        'title' => $agenda->dsc_titulo,
                        'start' => $agenda->dat_inicio,
                        'end' => $agenda->dat_fim,
                        'backgroundColor' => $agenda->nom_cor,
                        'borderColor' => $agenda->nom_cor,
                        'url' => $agenda->dsc_url,
                        'extendedProps' => [
                            'descricao' => $agenda->dsc_descricao,
                            'local' => $agenda->dsc_local,
                            'recorrente' => $agenda->ind_recorrente,
                            'url' => $agenda->dsc_url
                        ]
                    ];
                });
            
            return response()->json($agendas);
        } catch (Exception $e) {
            return response()->json([], 500);
        }
    }

    /**
     * Salvar novo evento
     */
    public function salvar(Request $request)
    {
        try {
            // Validação dos dados
            $validator = Validator::make($request->all(), [
                'dsc_titulo' => 'required|string|max:255',
                'dat_inicio' => 'required|date',
                'dat_fim' => 'required|date|after_or_equal:dat_inicio',
                'dsc_descricao' => 'nullable|string',
                'dsc_local' => 'nullable|string|max:255',
                'nom_cor' => 'nullable|string|max:7',
                'ind_recorrente' => 'boolean',
                'dsc_url' => 'nullable|url|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Criar nova agenda
            $agenda = new TabAgenda();
            $agenda->cod_agenda = Str::uuid()->toString();
            $agenda->dsc_titulo = $request->dsc_titulo;
            $agenda->dat_inicio = $request->dat_inicio;
            $agenda->dat_fim = $request->dat_fim;
            $agenda->dsc_descricao = $request->dsc_descricao;
            $agenda->dsc_local = $request->dsc_local;
            $agenda->nom_cor = $request->nom_cor;
            $agenda->ind_recorrente = $request->ind_recorrente;
            $agenda->dsc_url = $request->dsc_url;
            $agenda->cod_parlamentar = Session::get('cod_parlamentar');
            $agenda->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Agenda criada com sucesso',
                'agenda' => $agenda
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao salvar agenda: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar evento existente
     */
    public function atualizar(Request $request)
    {
        try {
            // Validação dos dados
            $validator = Validator::make($request->all(), [
                'cod_agenda' => 'required|exists:tab_agenda,cod_agenda',
                'dsc_titulo' => 'required|string|max:255',
                'dat_inicio' => 'required|date',
                'dat_fim' => 'required|date|after_or_equal:dat_inicio',
                'dsc_descricao' => 'nullable|string',
                'dsc_local' => 'nullable|string|max:255',
                'nom_cor' => 'nullable|string|max:7',
                'ind_recorrente' => 'boolean',
                'dsc_url' => 'nullable|url|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buscar agenda existente
            $agenda = TabAgenda::where('cod_agenda', $request->cod_agenda)->firstOrFail();
            
            // Verificar se pertence ao parlamentar logado
            if ($agenda->cod_parlamentar != Session::get('cod_parlamentar')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Você não tem permissão para editar esta agenda'
                ], 403);
            }

            // Atualizar dados
            $agenda->dsc_titulo = $request->dsc_titulo;
            $agenda->dat_inicio = $request->dat_inicio;
            $agenda->dat_fim = $request->dat_fim;
            $agenda->dsc_descricao = $request->dsc_descricao;
            $agenda->dsc_local = $request->dsc_local;
            $agenda->nom_cor = $request->nom_cor;
            $agenda->ind_recorrente = $request->ind_recorrente;
            $agenda->dsc_url = $request->dsc_url;
            $agenda->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Agenda atualizada com sucesso',
                'agenda' => $agenda
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar agenda: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Excluir evento
     */
    public function excluir(Request $request)
    {
        try {
            // Validação dos dados
            $validator = Validator::make($request->all(), [
                'cod_agenda' => 'required|exists:tab_agenda,cod_agenda'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buscar agenda existente
            $agenda = TabAgenda::where('cod_agenda', $request->cod_agenda)->firstOrFail();
            
            // Verificar se pertence ao parlamentar logado
            if ($agenda->cod_parlamentar != Session::get('cod_parlamentar')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Você não tem permissão para excluir esta agenda'
                ], 403);
            }

            // Excluir agenda (soft delete)
            $agenda->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Agenda excluída com sucesso'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao excluir agenda: ' . $e->getMessage()
            ], 500);
        }
    }
}
