<?php

namespace App\Http\Controllers;

use App\Models\TabGabinete;
use App\Models\TabParlamentares;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class TabGabineteController extends Controller
{
    public function index()
    {
        $gabinetes = TabGabinete::with(['parlamentar', 'users' => function ($query) {
            $query->select('users.*', 'rel_gabinetes_users.acesso_total');
        }])->get();
        return view('gabinete.index', compact('gabinetes'));
    }

    public function create()
    {
        $parlamentaresComGabinete = TabGabinete::pluck('cod_parlamentar')->toArray();
        $parlamentares = TabParlamentares::whereNotIn('cod_parlamentar', $parlamentaresComGabinete)->get();
        $users = User::orderBy('name')->get();
        return view('gabinete.create', compact('parlamentares', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cod_parlamentar' => 'required|exists:tab_parlamentares,cod_parlamentar',
                'nom_gabinete' => 'required|string|max:255',  // Removido unique temporariamente
                'users' => 'array',
                'users.*' => 'exists:users,cod_user',
                'acesso_total' => 'array',
                'acesso_total.*' => 'boolean'
            ],
            [
                'cod_parlamentar.required' => 'O campo Parlamentar é obrigatório.',
                'cod_parlamentar.exists' => 'O Parlamentar selecionado não existe.',
                'nom_gabinete.required' => 'O campo Nome do Gabinete é obrigatório.',
                'users.*.exists' => 'Um ou mais usuários selecionados não existem.'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Criar o gabinete
            $gabinete = TabGabinete::create([
                'cod_parlamentar' => $request->cod_parlamentar,
                'nom_gabinete' => $request->nom_gabinete,
                'sta_ativo' => $request->boolean('sta_ativo')
            ]);

            // Processa os usuários e seus acessos
            if ($request->has('users')) {
                $userAccess = [];
                foreach ($request->users as $userId) {
                    $userAccess[$userId] = [
                        'acesso_total' => isset($request->acesso_total[$userId])
                    ];
                }

                try {
                    $gabinete->users()->sync($userAccess);
                } catch (\Exception $e) {
                    \Log::error('Erro ao sincronizar usuários:', [
                        'error' => $e->getMessage(),
                        'userAccess' => $userAccess
                    ]);
                    throw $e;
                }
            }

            DB::commit();
            return redirect()->route('gabinetes.index')->with('success', 'Gabinete criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Erro ao criar gabinete:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao criar gabinete: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(TabGabinete $gabinete)
    {
        $gabinete->load(['parlamentar', 'users' => function ($query) {
            $query->select('users.*', 'rel_gabinetes_users.acesso_total');
        }]);
        return view('gabinete.show', compact('gabinete'));
    }

    public function edit(TabGabinete $cod_gabinete)
    {
        $gabinete = $cod_gabinete;
        $parlamentaresComGabinete = TabGabinete::where('cod_parlamentar', '!=', $gabinete->cod_parlamentar)
            ->pluck('cod_parlamentar')
            ->toArray();

        $parlamentares = TabParlamentares::whereNotIn('cod_parlamentar', $parlamentaresComGabinete)->get();
        $users = User::orderBy('name')->get();
        $selectedUsers = $gabinete->users()->pluck('rel_gabinetes_users.cod_user')->toArray();
        $userAccessTotal = $gabinete->users()->wherePivot('acesso_total', true)->pluck('rel_gabinetes_users.cod_user')->toArray();

        return view('gabinete.edit', compact('gabinete', 'parlamentares', 'users', 'selectedUsers', 'userAccessTotal'));
    }

    public function update(Request $request, TabGabinete $cod_gabinete)
    {
        $gabinete = $cod_gabinete;
        $validator = Validator::make(
            $request->all(),
            [
                'cod_parlamentar' => 'required|exists:tab_parlamentares,cod_parlamentar',
                'nom_gabinete' => ['required', 'string', 'max:255', Rule::unique('tab_gabinete')->ignore($gabinete->cod_gabinete, 'cod_gabinete')],
                'users' => 'array',
                'users.*' => 'exists:users,cod_user',
                'acesso_total' => 'array',
                'acesso_total.*' => 'boolean'
            ],
            [
                'cod_parlamentar.required' => 'O campo Parlamentar é obrigatório.',
                'cod_parlamentar.exists' => 'O Parlamentar selecionado não existe.',
                'nom_gabinete.required' => 'O campo Nome do Gabinete é obrigatório.',
                'nom_gabinete.unique' => 'Este nome de Gabinete já está em uso.',
                'users.*.exists' => 'Um ou mais usuários selecionados não existem.'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $validatedData = $request->all();
            $validatedData['sta_ativo'] = $request->boolean('sta_ativo');
            $gabinete->update($validatedData);

            // Atualiza os usuários e seus acessos
            if ($request->has('users')) {
                $userAccess = [];
                foreach ($request->users as $key => $userId) {
                    $userAccess[$userId] = [
                        'acesso_total' => isset($request->acesso_total[$userId]) ? true : false
                    ];
                }
                $gabinete->users()->sync($userAccess);
            } else {
                $gabinete->users()->detach();
            }

            DB::commit();
            return redirect()->route('gabinetes.index')->with('success', 'Gabinete atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erro ao atualizar gabinete: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(TabGabinete $cod_gabinete)
    {
        $gabinete = $cod_gabinete;
        DB::beginTransaction();
        try {
            $gabinete->users()->detach();
            $gabinete->delete();
            DB::commit();
            return redirect()->route('gabinetes.index')->with('success', 'Gabinete removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Erro ao remover gabinete: ' . $e->getMessage());
        }
    }
}
