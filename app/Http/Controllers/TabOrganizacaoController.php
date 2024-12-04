<?php

namespace App\Http\Controllers;

use App\Models\TabOrganizacao;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabOrganizacaoController extends Controller
{
    public function getPluckOrganizacao()
    {

        $organizacoes = TabOrganizacao::where('nome','!~*', 'Divisão')
        ->where('nome','!~*', 'Coordenação de')
        ->where('nome','!~*', 'Comitê')
        ->where('nome','!~*', 'Seção')
        ->where('nome','!~*', 'Câmara')
        ->where('nome','!~*', 'Comissão')
        ->where('nome','!~*', 'Plenário')
        ->where('nome','!~*', 'Conselho')
        ->where('nome','!~*', 'Serviço')
        ->where('nome','!~*', 'Centro')
        ->where('nome','!~*', 'Representação')
        ->where('nome','!~*', 'Coordenação-Geral')
        ->where('sigla','!~*', 'CGPISF')
        ->where('sigla','!=', 'SE DO CNRH')
        ->where('sigla','!=', 'CAO')
        ->select('codigoUnidade', DB::raw("CONCAT(sigla, ' - ', nome) as nome"))
        ->orderBy('codigoUnidadePai', 'DESC')
        ->orderBy('codigoUnidade', 'DESC')
        ->get();

        $result = [];

        foreach ($organizacoes as $organizacao) {
            $result[$organizacao->codigoUnidade] = $organizacao->nome;
        }

        return $result;
    }

    public function getPluckOrganizacaoResponsavelDemanda()
    {
        $organizacoes = TabOrganizacao::where('nome','!~*', 'Divisão')
        ->where('nome','!~*', 'Coordenação de')
        ->where('nome','!~*', 'Comitê')
        ->where('nome','!~*', 'Seção')
        ->where('nome','!~*', 'Câmara')
        ->where('nome','!~*', 'Comissão')
        ->where('nome','!~*', 'Plenário')
        ->where('nome','!~*', 'Conselho')
        ->where('nome','!~*', 'Serviço')
        ->where('nome','!~*', 'Departamento')
        ->where('nome','!~*', 'Centro')
        ->where('nome','!~*', 'Representação')
        ->where('nome','!~*', 'Coordenação-Geral')
        ->where('sigla','!=', 'SE DO CNRH')
        ->select('codigoUnidade', DB::raw("CONCAT(sigla, ' - ', nome) as nome"))
        ->orderBy('sigla')
        ->get();

        $result = [];

        foreach ($organizacoes as $organizacao) {
            $result[$organizacao->codigoUnidade] = $organizacao->nome;
        }

        return $result;
    }
}
