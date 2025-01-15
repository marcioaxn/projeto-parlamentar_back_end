<?php

namespace App\Http\Controllers;

use App\Models\TabContrato;
use App\Models\TabGabinete;
use App\Models\TabPlano; // Importe o model TabPlano
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Importe o Validator

class ContratoController extends Controller
{

    public function getPlanoValor(string $cod_plano): JsonResponse
    {
        $plano = TabPlano::find($cod_plano);

        if (!$plano) {
            return response()->json(['error' => 'Plano não encontrado'], 404);
        }

        return response()->json(['valor' => $plano->val_plano]);
    }

    public function index()
    {
        $contratos = TabContrato::with('plano')->paginate(10); // Paginação com 10 itens e eager loading do plano
        return view('contratos.index', compact('contratos')); // Crie a view contratos.index
    }

    public function create()
    {
        $gabinetes = TabGabinete::all(); // Busca todos os gabinetes
        $planos = TabPlano::all(); // Obtém todos os planos para o select no formulário

        return view('contratos.create', compact('planos', 'gabinetes')); // Crie a view contratos.create
    }

    public function store(Request $request)
    {
        // Clona o request para não modificar o original
        $data = $request->all();

        // Função helper para converter valor em Real para formato numérico
        $convertBrlToNumeric = function ($value) {
            if (empty($value)) return null;
            // Remove pontos dos milhares e substitui vírgula por ponto
            return (float) str_replace(['.', ','], ['', '.'], $value);
        };

        // Converte os valores monetários para o formato correto
        $data['val_total'] = $convertBrlToNumeric($request->val_total);
        $data['val_desconto_aplicado'] = $convertBrlToNumeric($request->val_desconto_aplicado);
        $data['val_sub_total'] = $convertBrlToNumeric($request->val_sub_total);

        // Regras de validação
        $rules = [
            'cod_plano' => 'required|exists:tab_planos,cod_plano',
            'cod_gabinete' => 'required|exists:tab_gabinete,cod_gabinete',
            'dat_inicio' => 'required|date',
            'dat_fim' => 'required|date|after_or_equal:dat_inicio',
            'val_total' => 'required|numeric|min:0',
            'val_desconto_aplicado' => 'nullable|numeric|min:0',
            'val_sub_total' => 'required|numeric|min:0',
            'dsc_observacoes' => 'nullable|string|max:1000',
            'sta_ativo' => 'required|in:A,I',
        ];

        // Mensagens de erro personalizadas
        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'exists' => 'O :attribute selecionado é inválido.',
            'date' => 'O campo :attribute deve ser uma data válida.',
            'after_or_equal' => 'A data fim deve ser igual ou posterior à data início.',
            'numeric' => 'O campo :attribute deve ser um valor numérico.',
            'min' => 'O campo :attribute deve ser maior ou igual a :min.',
            'in' => 'O valor selecionado para :attribute é inválido.',
            'max' => 'O campo :attribute não pode exceder :max caracteres.',
        ];

        // Nomes personalizados para os campos
        $attributes = [
            'cod_plano' => 'Plano',
            'cod_gabinete' => 'Gabinete',
            'dat_inicio' => 'Data início',
            'dat_fim' => 'Data fim',
            'val_total' => 'Valor total',
            'val_desconto_aplicado' => 'Valor desconto',
            'val_sub_total' => 'Valor subtotal',
            'dsc_observacoes' => 'Observações',
            'sta_ativo' => 'Status',
        ];

        // Validação
        $validator = Validator::make($data, $rules, $messages);
        $validator->setAttributeNames($attributes);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Criação do contrato
            $contrato = new TabContrato();

            // Preenche os campos validados
            $contrato->fill($data);

            // Salva o contrato
            $contrato->save();

            return redirect()
                ->route('contratos.index')
                ->with('success', 'Contrato criado com sucesso!');
        } catch (\Exception $e) {
            // Log do erro para debug
            \Log::error('Erro ao criar contrato: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro ao criar o contrato. Por favor, tente novamente.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TabContrato $cod_contrato)
    {
        $contrato = $cod_contrato;

        $planos = TabPlano::all();
        $gabinetes = TabGabinete::all();

        return view('contratos.show', compact('contrato', 'planos', 'gabinetes')); // Crie a view contratos.show
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TabContrato $cod_contrato)
    {
        $contrato = $cod_contrato;

        $planos = TabPlano::all();
        $gabinetes = TabGabinete::all();
        return view('contratos.edit', compact('contrato', 'planos', 'gabinetes'));
    }

    public function update(Request $request, TabContrato $cod_contrato)
    {
        $contrato = $cod_contrato;

        // Clona o request para não modificar o original
        $data = $request->all();

        // Função helper para converter valor em Real para formato numérico
        $convertBrlToNumeric = function ($value) {
            if (empty($value)) return null;
            // Remove pontos dos milhares e substitui vírgula por ponto
            return (float) str_replace(['.', ','], ['', '.'], $value);
        };

        // Converte os valores monetários para o formato correto
        $data['val_total'] = $convertBrlToNumeric($request->val_total);
        $data['val_desconto_aplicado'] = $convertBrlToNumeric($request->val_desconto_aplicado);
        $data['val_sub_total'] = $convertBrlToNumeric($request->val_sub_total);

        // Regras de validação
        $rules = [
            'cod_plano' => 'required|exists:tab_planos,cod_plano',
            'cod_gabinete' => 'required|exists:tab_gabinete,cod_gabinete',
            'dat_inicio' => 'required|date',
            'dat_fim' => 'required|date|after_or_equal:dat_inicio',
            'val_total' => 'required|numeric|min:0',
            'val_desconto_aplicado' => 'nullable|numeric|min:0',
            'val_sub_total' => 'required|numeric|min:0',
            'dsc_observacoes' => 'nullable|string|max:1000',
            'sta_ativo' => 'required|in:A,I',
        ];

        // Mensagens de erro personalizadas
        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'exists' => 'O :attribute selecionado é inválido.',
            'date' => 'O campo :attribute deve ser uma data válida.',
            'after_or_equal' => 'A data fim deve ser igual ou posterior à data início.',
            'numeric' => 'O campo :attribute deve ser um valor numérico.',
            'min' => 'O campo :attribute deve ser maior ou igual a :min.',
            'in' => 'O valor selecionado para :attribute é inválido.',
            'max' => 'O campo :attribute não pode exceder :max caracteres.',
        ];

        // Nomes personalizados para os campos
        $attributes = [
            'cod_plano' => 'Plano',
            'cod_gabinete' => 'Gabinete',
            'dat_inicio' => 'Data início',
            'dat_fim' => 'Data fim',
            'val_total' => 'Valor total',
            'val_desconto_aplicado' => 'Valor desconto',
            'val_sub_total' => 'Valor subtotal',
            'dsc_observacoes' => 'Observações',
            'sta_ativo' => 'Status',
        ];

        // Validação
        $validator = Validator::make($data, $rules, $messages);
        $validator->setAttributeNames($attributes);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Atualiza o contrato com os dados validados
            $contrato->fill($data);
            $contrato->save();

            return redirect()
                ->route('contratos.index')
                ->with('success', 'Contrato atualizado com sucesso!');
        } catch (\Exception $e) {
            // Log do erro para debug
            \Log::error('Erro ao atualizar contrato: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro ao atualizar o contrato. Por favor, tente novamente.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TabContrato $contrato)
    {
        $contrato->delete();
        return redirect()->route('contratos.index')->with('success', 'Contrato excluído com sucesso!');
    }
}
