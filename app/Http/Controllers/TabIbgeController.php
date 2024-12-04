<?php

namespace App\Http\Controllers;

use App\Models\TabIbge;
use App\Models\TabLogErros;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TabIbgeController extends Controller
{
    public function getPluckUfs()
    {
        return TabIbge::orderBy('sgl_uf')
            ->pluck('sgl_uf', 'sgl_uf');
    }

    public function getPluckMunicipios()
    {
        return TabIbge::pluck(DB::raw("concat(sgl_uf,'/',nom_municipio_sem_formatacao) as nom_municipio_sem_formatacao"), 'cod_municipio');
    }

    public function getModelUfs($sglUf = null)
    {
        $result = TabIbge::orderBy('sgl_uf');

        if (isset($sglUf) && !is_null($sglUf) && $sglUf != '') {

            $result = $result->where('sgl_uf', $sglUf);

        }

        return $result;
    }

    public function getUfs($sglUf = null)
    {
        $ufs = TabIbge::select('sgl_uf', 'nomeunidadefederacao')
            ->orderBy('sgl_uf')
            ->groupBy('sgl_uf', 'nomeunidadefederacao');

        if ($sglUf != '') {

            $ufs = $ufs->whereIn('sgl_uf', $sglUf);

        }

        $ufs = $ufs->get();

        return $ufs;
    }

    public function getMunicipios($sglUf = null)
    {
        $result = TabIbge::orderBy('sgl_uf')
            ->orderBy('cod_municipio');

        if (isset($sglUf) && !is_null($sglUf) && $sglUf != '') {

            $result = $result->where('sgl_uf', $sglUf);

        }

        $result = $result->get();

        return $result;
    }

    public function getModelMunicipios($sglUf = null)
    {
        $result = TabIbge::select(DB::raw("concat(sgl_uf,'/',nom_municipio_sem_formatacao) as nom_municipio_sem_formatacao"), 'cod_municipio')
            ->orderBy('sgl_uf')
            ->orderBy('nom_municipio_sem_formatacao');

        if (isset($sglUf) && !is_null($sglUf) && $sglUf != '') {

            $result = $result->where('sgl_uf', $sglUf);

        }

        return $result;
    }

    public function getMunicipioPorUfEMunicipio($sglUf = null, $nomMunicipio = null)
    {

        try {
            return TabIbge::where('sgl_uf', $sglUf)
                ->where('nom_municipio_sem_formatacao', $nomMunicipio)
                ->first();
        } catch (ModelNotFoundException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        } catch (QueryException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        } catch (\Exception $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }

    }

    public function getMunicipioPorCodMunicipio($codMunicipio = null)
    {

        try {
            return TabIbge::find($codMunicipio);
        } catch (ModelNotFoundException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        } catch (QueryException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        } catch (\Exception $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }

    }

    public function getCodIbgeMunicipios()
    {
        return TabIbge::select('cod_municipio AS cod_ibge')
            ->where('sgl_uf', 'SP')
            ->groupBy('cod_municipio')
            ->orderBy('sgl_uf')
            ->get();
    }
}
