<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermissao
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user->bln_admin) {
            return redirect()->route('principal')->with('error', 'Acesso negado. Você não tem permissão de administrador.');
        }

        return $next($request);
    }
}
