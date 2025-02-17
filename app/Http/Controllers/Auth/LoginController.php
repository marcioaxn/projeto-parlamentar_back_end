<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/app';

    protected function redirectTo()
    {
        return $this->redirectTo;
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'email';
    }

    protected function validateLogin(Request $request)
    {
        $messages = [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.string' => 'O e-mail deve ser uma string.',
            'email.email' => 'Por favor, forneça um endereço de e-mail válido.',
            'email.exists' => 'Erro de credenciais',
            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'A senha deve ser uma string.',
        ];

        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'exists:users,email', // Adicionando a validação exists
                function ($attribute, $value, $fail) {
                    $allowedDomains = ['mdr.gov.br', 'midr.gov.br', 'codevasf.gov.br', 'dnocs.gov.br'];
                    $domain = substr(strrchr($value, "@"), 1);
                    if (!in_array($domain, $allowedDomains)) {
                        $fail('Por favor, use um e-mail que termina com @mdr.gov.br, @midr.gov.br, @gmail.com, @codevasf.gov.br ou @dnocs.gov.br');
                    }
                }
            ],
            'password' => 'required|string',
        ], $messages);
    }

    public function authenticate(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $this->credentials($request);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('principal');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        // Fazer logout do usuário
        Auth::logout();

        // Invalidar a sessão atual
        $request->session()->invalidate();

        // Regenerar o token CSRF
        $request->session()->regenerateToken();

        // Adicionar uma mensagem flash para o usuário
        Session::flash('status', 'Você foi desconectado com sucesso.');

        // Redirecionar para a rota de login
        return redirect()->route('landingpage');
    }

    public function alterarEmailParaCrypt()
    {
        $usuarios = User::select('email')
            ->get();

        foreach ($usuarios as $user) {
            $emailCriptografado = Crypt::encryptString($user->email);

            DB::select("UPDATE midr_gestao.users SET email = '" . $emailCriptografado . "' WHERE email = '" . $user->email . "';");
        }

        return 'Atualização feita.';
    }
}
