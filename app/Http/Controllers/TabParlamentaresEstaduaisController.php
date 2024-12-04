<?php

namespace App\Http\Controllers;

use App\Models\TabParlamentaresEstaduais;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabParlamentaresEstaduaisController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getParlamentaresEstaduais($filtros = [])
    {
        return TabParlamentaresEstaduais::select(DB::raw("CONCAT(nom_parlamentar_sem_formatacao, ' - ', sgl_uf_representante, ' - ', sgl_partido) AS nom_parlamentar_sem_formatacao"), 'cod_parlamentar')
            ->orderBy('nom_parlamentar_sem_formatacao')
            ->pluck('nom_parlamentar_sem_formatacao', 'cod_parlamentar');
    }

    public function getParlamentarEstadual($cod_parlamentar = null)
    {
        return TabParlamentaresEstaduais::with('celulares', 'observacoes.assunto')
            ->find($cod_parlamentar);
    }

    public function getParlamentarEstadualWithOutRelationship($cod_parlamentar = null)
    {
        return TabParlamentaresEstaduais::find($cod_parlamentar);
    }

    public function getPluckPartidosEstaduais()
    {
        return TabParlamentaresEstaduais::orderBy('sgl_partido')
            ->pluck('sgl_partido', 'sgl_partido')
            ->prepend('Todos', 'Todos');
    }

    public function getPartidosEstaduais($sglPartido = null)
    {
        $partidos = TabParlamentaresEstaduais::select('sgl_partido')
            ->orderBy('sgl_partido')
            ->groupBy('sgl_partido');

        if ($sglPartido != '') {

            $partidos = $partidos->whereIn('sgl_partido', $sglPartido);
        }

        $partidos = $partidos->get();

        return $partidos;
    }

    public function getParlamentaresEstaduaisPorPartido($sglPartido = null, $dscCasa = [], $sglUfRepresentante = [])
    {
        $parlamentares = TabParlamentaresEstaduais::with('celulares', 'observacoes.assunto', 'liderancaDeputados', 'liderancaSenadores', 'comissoesDeputados', 'cargosMesaDiretora', 'comissoesSenadores', 'cargosSenadores', 'cargosMesaDiretoraSenado', 'legislaturasDeputado', 'legislaturasSenado')
            ->orderBy('sgl_partido')
            ->orderBy('dsc_casa', 'desc')
            ->orderBy('nom_parlamentar')
            ->whereIn('dsc_situacao', ['Exercício', 'Suplência']);

        if ($sglPartido != '') {

            $parlamentares = $parlamentares->where('sgl_partido', $sglPartido);
        }

        if (is_array($dscCasa) && count($dscCasa) > 0) {
            $parlamentares = $parlamentares->whereIn('dsc_casa', $dscCasa);
        }

        if (is_array($sglUfRepresentante) && count($sglUfRepresentante) > 0) {

            $parlamentares = $parlamentares->whereIn('sgl_uf_representante', $sglUfRepresentante);
        }

        $parlamentares = $parlamentares->get();

        return $parlamentares;
    }

    public function getParlamentaresEstaduaisPorUF($sglPartido = null, $dscCasa = [], $sglUfRepresentante = [])
    {
        $parlamentares = TabParlamentaresEstaduais::with('celulares', 'observacoes.assunto', 'liderancaDeputados', 'liderancaSenadores', 'comissoesDeputados', 'cargosMesaDiretora', 'comissoesSenadores', 'cargosSenadores', 'cargosMesaDiretoraSenado', 'legislaturasDeputado', 'legislaturasSenado')
            ->orderBy('sgl_uf_representante')
            ->orderBy('dsc_casa', 'desc')
            ->orderBy('nom_parlamentar')
            ->whereIn('dsc_situacao', ['Exercício', 'Suplência']);

        if (is_array($sglPartido) && count($sglPartido) > 0) {

            $parlamentares = $parlamentares->whereIn('sgl_partido', $sglPartido);
        }

        if (is_array($dscCasa) && count($dscCasa) > 0) {
            $parlamentares = $parlamentares->whereIn('dsc_casa', $dscCasa);
        }

        if ($sglUfRepresentante != '') {

            $parlamentares = $parlamentares->where('sgl_uf_representante', $sglUfRepresentante);
        }

        $parlamentares = $parlamentares->get();

        return $parlamentares;
    }
}
