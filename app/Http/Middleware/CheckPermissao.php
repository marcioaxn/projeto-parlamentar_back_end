<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\RelUserModuloPermissao;
use App\Models\TabModulos;

class CheckPermissao
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        $perfil = $user->perfil;

        if ($perfil->cod_perfil === '9249b1c3-bdb5-28d3-a701-12fa7f16d325') {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
