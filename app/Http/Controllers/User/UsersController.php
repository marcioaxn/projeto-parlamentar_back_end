<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Exibe a página inicial do módulo de usuários
     */
    public function index()
    {
        return view('dashboard.users.index');
    }

    /**
     * Lista os usuários com filtros (Ajax)
     */
    public function list(Request $request)
    {
        $query = User::query();

        // Removido o filtro por 'name' devido à criptografia
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }
        if ($request->filled('ativo')) {
            $query->where('ativo', $request->input('ativo'));
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        \Log::info('Usuários retornados pela rota users.list:', $users->toArray());

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Armazena um novo usuário (Ajax)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'ativo' => 'required|in:0,1',
            'bln_admin' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Geração de senha aleatória
        $senha = Str::random(10);

        // Criação do usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $senha, // O mutator no model aplica o Hash
            'ativo' => $request->ativo,
            'bln_admin' => $request->bln_admin,
            'trocarsenha' => 1, // Obriga troca de senha no primeiro acesso
        ]);

        // Envio de e-mail de boas-vindas
        $assunto = "Parlamentum - Bem-vindo(a) ao Sistema";
        $textoEmail = "<p>Prezado(a) <b>{$request->name}</b>,</p>
            <p>Seu cadastro foi realizado com sucesso no sistema Parlamentum.</p>
            <p>Endereço: <a href='https://seusite.com.br' target='_blank'>https://seusite.com.br</a></p>
            <p>Sua senha inicial é: <span style='color: #CD3333; font-weight: bold;'>{$senha}</span></p>
            <p>Por questão de segurança, você será obrigado(a) a trocar essa senha no primeiro acesso.</p>
            <p>Em caso de dúvidas, entre em contato: suporte@seusite.com.br</p>
            <p>Atenciosamente,<br><strong>Equipe Parlamentum</strong></p>";

        Mail::send('email.cadastro', ['name' => $request->name, 'textoEmail' => $textoEmail], function ($message) use ($request, $assunto) {
            $message->to($request->email, $request->name)
                ->subject($assunto)
                ->from('suporte@seusite.com.br', 'Parlamentum');
        });

        return response()->json([
            'success' => true,
            'message' => "Usuário {$request->name} cadastrado com sucesso! Um e-mail foi enviado com a senha inicial.",
            'data' => $user
        ]);
    }

    /**
     * Retorna os dados de um usuário para edição (Ajax)
     */
    public function edit($cod_user)
    {
        $user = User::findOrFail($cod_user);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Atualiza um usuário existente (Ajax)
     */
    public function update(Request $request, $cod_user)
    {
        $user = User::findOrFail($cod_user);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->cod_user . ',cod_user',
            'ativo' => 'required|in:0,1',
            'bln_admin' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'ativo' => $request->ativo,
            'bln_admin' => $request->bln_admin,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuário atualizado com sucesso!',
            'data' => $user
        ]);
    }

    /**
     * Exclui um usuário (Ajax)
     */
    public function destroy($cod_user)
    {
        $user = User::findOrFail($cod_user);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuário excluído com sucesso!'
        ]);
    }
}