<?php

namespace App\Http\Controllers;

use App\Models\TabParlamentares;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabParlamentaresController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }

    public function getParlamentares($filtros = [])
    {

        $parlamentares = TabParlamentares::orderBy('nom_parlamentar')
            ->select('cod_parlamentar', DB::raw("CONCAT(nom_parlamentar, ' - ', sgl_uf_representante, ' - ', case when dsc_tratamento = 'Senadora' then 'Senador' when dsc_tratamento = 'Deputada' then 'Deputado' else dsc_tratamento end, ' - ', sgl_partido) as nom_parlamentar"));

        foreach ($filtros as $keyFiltro => $valueFiltro) {

            if (!is_array($valueFiltro)) {
                $parlamentares = $parlamentares->where($keyFiltro, $valueFiltro);
            } else {
                $parlamentares = $parlamentares->whereIn($keyFiltro, $valueFiltro);
            }

        }

        $parlamentares = $parlamentares->with('liderancaDeputados', 'liderancaSenadores')
            ->get();

        foreach ($parlamentares as $parlamentar) {
            $complemento = null;
            if ($parlamentar->liderancaDeputados->count() > 0) {

                foreach ($parlamentar->liderancaDeputados as $key => $value) {
                    if ($value->titulo === 'Líder') {

                        if ($value->tipo === 'Governo na CD' || $value->tipo === 'Partido Político') {

                            $complemento = ' - Líder';

                        }

                    }
                }

            }

            if ($parlamentar->liderancaSenadores->count() > 0) {

                foreach ($parlamentar->liderancaSenadores as $key => $value) {

                    if (isset($value->NomePartido) && !is_null($value->NomePartido) && $value->NomePartido != '' && $value->UnidadeLideranca === 'Liderança de Partido no Senado Federal' && $value->DescricaoTipoLideranca === 'Líder do Senado Federal') {

                        $complemento = ' - Líder';

                    }

                }

            }

            $result[$parlamentar->cod_parlamentar] = $parlamentar->nom_parlamentar . $complemento;
        }

        return $result;

    }

    public function getParlamentaresParaFiltros($column = '')
    {
        return TabParlamentares::orderBy($column);
    }

    public function getParlamentar($cod_parlamentar = null)
    {
        return TabParlamentares::with('celulares', 'observacoes.assunto', 'liderancaDeputados', 'liderancaSenadores', 'comissoesDeputados', 'cargosMesaDiretora', 'comissoesSenadores', 'cargosSenadores.colegiadoAtivo', 'cargosMesaDiretoraSenado', 'resumo', 'municipiosMaisVotos', 'municipiosMenosVotos', 'municipiosNenhumVoto')
            ->find($cod_parlamentar);
    }

    public function getParlamentarPorNomeCompleto($nomParlamentarCompleto = null)
    {
        return DB::table('tab_parlamentares')
            ->whereRaw('mdr_corporativo.fnc_retira_acento(nom_parlamentar_completo) = mdr_corporativo.fnc_retira_acento(?)', [$nomParlamentarCompleto])
            ->where('dsc_situacao', 'Exercício')
            ->where('dsc_tratamento', 'Senador')
            ->first();
    }

    public function getParlamentarPorSequencialCandidato($sqCandidato = null)
    {
        return TabParlamentares::where('num_sequencial_candidato', $sqCandidato)
            ->where('dsc_situacao', 'Exercício')
            ->where('dsc_casa', 'Senado Federal')
            ->first();
    }

    public function getDeputadoFederalPorNumCpf($numCpf = null)
    {
        return TabParlamentares::where('num_cpf', $numCpf)
            ->first();
    }

    public function getParlamentarWithOutRelationship($cod_parlamentar = null)
    {
        return TabParlamentares::find($cod_parlamentar);
    }

    public function getPluckPartidos()
    {
        return TabParlamentares::orderBy('sgl_partido')
            ->pluck('sgl_partido', 'sgl_partido')
            ->prepend('Todos', 'Todos');
    }

    public function getPartidos($sglPartido = null)
    {
        $partidos = TabParlamentares::select('sgl_partido')
            ->orderBy('sgl_partido')
            ->groupBy('sgl_partido');

        if ($sglPartido != '') {

            $partidos = $partidos->whereIn('sgl_partido', $sglPartido);

        }

        $partidos = $partidos->get();

        return $partidos;
    }

    public function getPluckCasa()
    {
        return TabParlamentares::orderBy('dsc_casa')
            ->pluck('dsc_casa', 'dsc_casa')
            ->prepend('Todas', 'Todas');
    }

    public function getPluckUFRepresentacao()
    {
        return TabParlamentares::orderBy('sgl_uf_representante')
            ->pluck('sgl_uf_representante', 'sgl_uf_representante')
            ->prepend('Todas', 'Todas');
    }

    public function getParlamentaresPorPartido($sglPartido = null, $dscCasa = [], $sglUfRepresentante = [])
    {
        $parlamentares = TabParlamentares::with('celulares', 'observacoes.assunto', 'liderancaDeputados', 'liderancaSenadores', 'comissoesDeputados', 'cargosMesaDiretora', 'comissoesSenadores', 'cargosSenadores', 'cargosMesaDiretoraSenado', 'legislaturasDeputado', 'legislaturasSenado')
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

    public function getParlamentaresPorUF($sglPartido = null, $dscCasa = [], $sglUfRepresentante = [])
    {
        $parlamentares = TabParlamentares::with('celulares', 'observacoes.assunto', 'liderancaDeputados', 'liderancaSenadores', 'comissoesDeputados', 'cargosMesaDiretora', 'comissoesSenadores', 'cargosSenadores', 'cargosMesaDiretoraSenado', 'legislaturasDeputado', 'legislaturasSenado')
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

    public function getParlamentaresEmExercicio()
    {
        $parlamentares = TabParlamentares::with('celulares', 'observacoes.assunto', 'liderancaDeputados', 'liderancaSenadores', 'comissoesDeputados', 'cargosMesaDiretora', 'comissoesSenadores', 'cargosSenadores', 'cargosMesaDiretoraSenado', 'legislaturasDeputado', 'legislaturasSenado')
            ->orderBy('sgl_uf_representante')
            ->orderBy('dsc_casa', 'desc')
            ->orderBy('nom_parlamentar')
            ->whereIn('dsc_situacao', ['Exercício']);

        $parlamentares = $parlamentares->get();

        return $parlamentares;
    }

}
