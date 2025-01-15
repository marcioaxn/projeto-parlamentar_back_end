<?php

namespace App\Http\Controllers;

use App\Models\TabPlano;
use Illuminate\Http\Request;

class TabPlanosController extends Controller
{
    /**
     * Retornar todos os planos ordenados por nome.
     */
    public function getPlanos()
    {
        return TabPlano::get()->sortBy('nom_plano');
    }

    /**
     * Retornar um plano pelo seu código.
     */
    public function getPlano($codPlano = null)
    {
        return TabPlano::find($codPlano);
    }

    /**
     * Retornar os planos em formato de lista (pluck).
     */
    public function getPlanosPluck()
    {
        return TabPlano::orderBy('nom_plano')
            ->pluck('nom_plano', 'cod_plano');
    }

    /**
     * Listar todos os planos.
     */
    public function index()
    {
        $planos = $this->getPlanos();

        return view('planos.index', compact('planos'));
    }

    /**
     * Exibir formulário de criação de plano.
     */
    public function create()
    {
        return view('planos.create');
    }

    /**
     * Salvar um novo plano.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_plano' => 'required|string|max:255',
            'dsc_plano' => 'nullable|string',
            'val_plano' => 'required|numeric|min:0',
            'lim_usuarios' => 'nullable|integer|min:0',
            'sta_ativo' => 'sometimes|boolean',
        ]);

        // Garantir que 'sta_ativo' esteja definido, mesmo se não enviado
        $validatedData['sta_ativo'] = $request->boolean('sta_ativo');

        TabPlano::create($validatedData);

        return redirect()->route('planos.index')->with('success', 'Plano criado com sucesso!');
    }

    /**
     * Exibir detalhes de um plano específico.
     */
    public function show($codPlano)
    {
        $plano = $this->getPlano($codPlano);

        if (!$plano) {
            abort(404, 'Plano não encontrado.');
        }

        return view('planos.show', compact('plano'));
    }

    /**
     * Exibir formulário de edição de plano.
     */
    public function edit($cod_plano)
    {
        $plano = $this->getPlano($cod_plano);

        if (!$plano) {
            abort(404, 'Plano não encontrado.');
        }

        return view('planos.edit', compact('plano')); // Passa o plano para a view
    }

    /**
     * Atualizar um plano existente.
     */
    public function update(Request $request, $codPlano)
    {
        $validatedData = $request->validate([
            'nom_plano' => 'required|string|max:255',
            'dsc_plano' => 'nullable|string',
            'val_plano' => 'required|numeric|min:0',
            'lim_usuarios' => 'nullable|integer|min:0',
            'sta_ativo' => 'sometimes|boolean',
        ]);

        $validatedData['sta_ativo'] = $request->boolean('sta_ativo');

        $plano = $this->getPlano($codPlano);

        if (!$plano) {
            abort(404, 'Plano não encontrado.');
        }

        $plano->update($validatedData);

        return redirect()->route('planos.index')->with('success', 'Plano atualizado com sucesso!');
    }


    /**
     * Remover um plano.
     */
    public function destroy($codPlano)
    {
        $plano = $this->getPlano($codPlano);

        if (!$plano) {
            abort(404, 'Plano não encontrado.');
        }

        $plano->delete();

        return redirect()->route('planos.index')->with('success', 'Plano removido com sucesso!');
    }
}
