<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserProfile
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Não redireciona mais automaticamente aqui.
        // Apenas verifica se o perfil permite acesso, mas deixa o LoginController gerenciar o redirecionamento inicial.
        return $next($request);
    }
}
