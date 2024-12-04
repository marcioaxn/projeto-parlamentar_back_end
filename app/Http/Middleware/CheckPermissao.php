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
        $codPerfil = null;
        $moduloPath = $request->route()->uri();

        $partesPath = explode('/', $moduloPath);

        if (is_array($partesPath) && count($partesPath) > 0) {
            $nomPath = $partesPath[0];
        }

        $tabModulo = TabModulos::where('nom_path', $nomPath)
            ->exists();

        if ($tabModulo) {

            $resultTabModulo = TabModulos::where('nom_path', $nomPath)
                ->first();

            $relUserModuloPermissao = RelUserModuloPermissao::where('cod_modulo', $resultTabModulo->cod_modulo)
                ->where('cod_user', $user->cod_user)
                ->first();

            $codPerfil = $user->cod_perfil;

            Session::forget('cod_permissao_modulo');

            Session::put('cod_permissao_modulo', $relUserModuloPermissao->cod_permissao_modulo);

            // Tipos de permissão
            // 1 = 0000000
            // 2 = 0100000
            // 3 = 0010000
            // 4 = 0001000
            // 5 = 0000100
            // 6 = 0000010
            // 7 = 0000001

            if ($relUserModuloPermissao->cod_permissao_modulo == 7) {

                Session::forget('permissao');
                Session::put('permissao', '0000001');

            } elseif ($relUserModuloPermissao->cod_permissao_modulo == 6) {

                Session::forget('permissao');
                Session::put('permissao', '0000010');

            } elseif ($relUserModuloPermissao->cod_permissao_modulo == 5) {

                Session::forget('permissao');
                Session::put('permissao', '0000100');

            } elseif ($relUserModuloPermissao->cod_permissao_modulo == 4) {

                Session::forget('permissao');
                Session::put('permissao', '0001000');

            } elseif ($relUserModuloPermissao->cod_permissao_modulo == 3) {

                Session::forget('permissao');
                Session::put('permissao', '0010000');

            } elseif ($relUserModuloPermissao->cod_permissao_modulo == 2) {

                Session::forget('permissao');
                Session::put('permissao', '0100000');

            } else {

                Session::forget('permissao');
                Session::put('permissao', '0000000');

            }

            // Verificar se o usuário tem permissão para acessar o módulo
            $permissao = RelUserModuloPermissao::where('cod_user', $user->cod_user)
                ->where('cod_modulo', function ($query) use ($nomPath) {
                    $query->select('cod_modulo')
                        ->from('midr_gestao.tab_modulos')
                        ->where('nom_path', $nomPath);
                })
                ->where('cod_permissao_modulo', 1)
                ->exists();

            // O codPerfil igual a 9249b1c3-bdb5-28d3-a701-12fa7f16d325 corresponde ao
            // administrador do sistema e dessa forma a permissão por ele herdada é a
            // mais alta, que o nível 5. Esse perfil pode acessar, ler e editar os
            // dados INCLUSIVE os classificados como restrito

            // Em 17/06/2024 essa funcionalidade foi tirada. Ela precisa ser
            // conversada, para observar a viabilidade.

            /*
            if ($codPerfil === '9249b1c3-bdb5-28d3-a701-12fa7f16d325') {
                Session::forget('permissao');
                Session::put('permissao', '0000100');
            }
            */

        } else {
            $permissao = false;
        }

        if ($permissao) {
            // Se o usuário não tiver permissão, redirecionar para uma página de acesso negado
            return redirect()->route('acesso-negado');
        }

        return $next($request);
    }
}
