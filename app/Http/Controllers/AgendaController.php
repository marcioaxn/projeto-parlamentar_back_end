<?php

namespace App\Http\Controllers;

use App\Models\TabAgenda;
use App\Http\Requests\AgendaRequest; // Vamos criar este Request em seguida
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use RRule\RRule;
use Illuminate\Support\Facades\Validator;
use Session;

class AgendaController extends Controller
{
    public function listar()
    {
        try {
            $codParlamentar = Session::get('cod_parlamentar');

            $agendas = TabAgenda::where('cod_parlamentar', $codParlamentar)
                ->get()
                ->map(function ($agenda) {
                    return [
                        'id' => $agenda->cod_agenda,
                        'title' => $agenda->dsc_titulo,
                        'start' => $agenda->dat_inicio,
                        'end' => $agenda->dat_fim,
                        'backgroundColor' => $agenda->nom_cor,
                        'borderColor' => $agenda->nom_cor,
                        'extendedProps' => [
                            'descricao' => $agenda->dsc_descricao,
                            'local' => $agenda->dsc_local,
                            'url' => $agenda->dsc_url,
                            'recorrente' => $agenda->ind_recorrente
                        ]
                    ];
                });

            return response()->json($agendas);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Salvar novo evento
     */
    public function salvar(Request $request)
    {
        try {
            // Validação dos dados com mensagens customizadas
            $validator = Validator::make($request->all(), [
                'dsc_titulo' => 'required|string|max:255',
                'dat_inicio' => 'required|date',
                'dat_fim' => 'required|date|after_or_equal:dat_inicio',
                'dsc_descricao' => 'nullable|string',
                'dsc_local' => 'nullable|string|max:255',
                'nom_cor' => 'nullable|string|max:7',
            ], [
                'dsc_titulo.required' => 'O campo Título é obrigatório',
                'dat_inicio.required' => 'O campo Data de Início é obrigatório',
                'dat_fim.required' => 'O campo Data de Fim é obrigatório',
                'dat_fim.after_or_equal' => 'A Data de Fim deve ser igual ou posterior à Data de Início'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                    'message' => 'Campos obrigatórios não preenchidos'
                ], 422);
            }

            // Criar nova agenda
            $agenda = new TabAgenda();
            $agenda->dsc_titulo = $request->dsc_titulo;
            $agenda->dat_inicio = $request->dat_inicio;
            $agenda->dat_fim = $request->dat_fim;
            $agenda->dsc_descricao = $request->dsc_descricao;
            $agenda->dsc_local = $request->dsc_local;
            $agenda->nom_cor = $request->nom_cor;
            $agenda->ind_recorrente = $request->has('ind_recorrente') ? $request->ind_recorrente : false;
            $agenda->dsc_url = $request->dsc_url;
            $agenda->cod_parlamentar = Session::get('cod_parlamentar');
            $agenda->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Agenda criada com sucesso',
                'agenda' => $agenda
            ]);
        } catch (\Exception $e) {
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
            // Validação dos dados com mensagens customizadas
            $validator = Validator::make($request->all(), [
                'cod_agenda' => 'required|exists:tab_agenda,cod_agenda',
                'dsc_titulo' => 'required|string|max:255',
                'dat_inicio' => 'required|date',
                'dat_fim' => 'required|date|after_or_equal:dat_inicio',
                'dsc_descricao' => 'nullable|string',
                'dsc_local' => 'nullable|string|max:255',
                'nom_cor' => 'nullable|string|max:7',
            ], [
                'cod_agenda.required' => 'ID da agenda é obrigatório',
                'cod_agenda.exists' => 'Agenda não encontrada',
                'dsc_titulo.required' => 'O campo Título é obrigatório',
                'dat_inicio.required' => 'O campo Data de Início é obrigatório',
                'dat_fim.required' => 'O campo Data de Fim é obrigatório',
                'dat_fim.after_or_equal' => 'A Data de Fim deve ser igual ou posterior à Data de Início'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                    'message' => 'Campos obrigatórios não preenchidos'
                ], 422);
            }

            // Buscar agenda existente
            $agenda = TabAgenda::findOrFail($request->cod_agenda);

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
            $agenda->ind_recorrente = $request->has('ind_recorrente') ? $request->ind_recorrente : false;
            $agenda->dsc_url = $request->dsc_url;
            $agenda->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Agenda atualizada com sucesso',
                'agenda' => $agenda
            ]);
        } catch (\Exception $e) {
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
            ], [
                'cod_agenda.required' => 'ID da agenda é obrigatório',
                'cod_agenda.exists' => 'Agenda não encontrada'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                    'message' => 'Erro de validação'
                ], 422);
            }

            // Buscar agenda existente
            $agenda = TabAgenda::findOrFail($request->cod_agenda);

            // Verificar se pertence ao parlamentar logado
            if ($agenda->cod_parlamentar != Session::get('cod_parlamentar')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Você não tem permissão para excluir esta agenda'
                ], 403);
            }

            // Excluir agenda
            $agenda->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Agenda excluída com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao excluir agenda: ' . $e->getMessage()
            ], 500);
        }
    }
}
