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

        if (!$user || $user->cod_perfil !== '9249b1c3-bdb5-28d3-a701-12fa7f16d325') {
            // Redireciona ou retorna erro 403
            return redirect()->route('acesso-negado');
        }

        return $next($request);
    }
}
