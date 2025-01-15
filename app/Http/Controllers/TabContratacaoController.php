<?php

namespace App\Http\Controllers;

use App\Models\TabContratacao;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\TabPlanosController;

class TabContratacaoController extends Controller
{
    /**
     * Instanciar TabPlanosController para reutilizar métodos.
     */
    public function instanciarTabPlanosController()
    {
        return new TabPlanosController;
    }

    /**
     * Listar todas as contratações.
     */
    public function index()
    {
        $contratacoes = TabContratacao::with(['plano', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('contratacao.index', compact('contratacoes'));
    }

    /**
     * Exibir formulário de criação de contratação.
     */
    public function create()
    {
        $tabPlanos = $this->instanciarTabPlanosController();

        $planos = $tabPlanos->getPlanosPluck();
        $usuarios = User::orderBy('name')->pluck('name', 'id');

        return view('contratacao.create', compact('planos', 'usuarios'));
    }

    /**
     * Salvar nova contratação.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cod_plano' => 'required|exists:tab_plano,cod_plano',
            'cod_usuario' => 'required|exists:users,id',
            'val_total' => 'required|numeric',
            'val_desconto_aplicado' => 'nullable|numeric',
            'dsc_observacoes' => 'nullable|string',
            'sta_status' => 'required|string',
            'dat_inicio' => 'required|date',
            'dat_fim' => 'nullable|date|after_or_equal:dat_inicio',
        ]);

        TabContratacao::create($validatedData);

        return redirect()->route('contratacao.index')->with('success', 'Contratação criada com sucesso!');
    }

    /**
     * Exibir detalhes de uma contratação específica.
     */
    public function show($id)
    {
        $contratacao = TabContratacao::with(['plano', 'usuario'])->findOrFail($id);

        return view('contratacao.show', compact('contratacao'));
    }

    /**
     * Exibir formulário de edição de contratação.
     */
    public function edit($id)
    {
        $contratacao = TabContratacao::findOrFail($id);
        $tabPlanos = $this->instanciarTabPlanosController();

        $planos = $tabPlanos->getPlanosPluck();
        $usuarios = User::orderBy('name')->pluck('name', 'id');

        return view('contratacao.edit', compact('contratacao', 'planos', 'usuarios'));
    }

    /**
     * Atualizar uma contratação existente.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cod_plano' => 'required|exists:tab_plano,cod_plano',
            'cod_usuario' => 'required|exists:users,id',
            'val_total' => 'required|numeric',
            'val_desconto_aplicado' => 'nullable|numeric',
            'dsc_observacoes' => 'nullable|string',
            'sta_status' => 'required|string',
            'dat_inicio' => 'required|date',
            'dat_fim' => 'nullable|date|after_or_equal:dat_inicio',
        ]);

        $contratacao = TabContratacao::findOrFail($id);
        $contratacao->update($validatedData);

        return redirect()->route('contratacao.index')->with('success', 'Contratação atualizada com sucesso!');
    }

    /**
     * Remover uma contratação.
     */
    public function destroy($id)
    {
        $contratacao = TabContratacao::findOrFail($id);
        $contratacao->delete();

        return redirect()->route('contratacao.index')->with('success', 'Contratação removida com sucesso!');
    }
}
