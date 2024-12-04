<?php

namespace App\Http\Controllers;

use App\Models\TabLogErros;
use Auth;
use Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Controllers\TabAuditController;

class AtualizarOuCriarPorModeloDadosController extends Controller
{

    public function instanciarTabAuditController()
    {
        return new TabAuditController;
    }

    public function atualizarOuCriarPorModeloDados($model = null, $id = [], $campos = [])
    {

        $tipoAcao = null;

        try {
            $registro = null;

            if (isset($id) && !is_null($id) && $id != '' && count($id) > 0) {
                $tipoAcao = 'Atualização';

                $tabAuditController = $this->instanciarTabAuditController();

                $tabAuditController->gravarAutoriaPorColuna($id, $model, $campos, $tipoAcao);

                $registro = $model::updateOrCreate($id, $campos);

                return $id;
            } else {
                $tipoAcao = 'Inclusão';
                $registro = $model::updateOrCreate($campos);

                // Aqui você pode acessar o valor da chave primária
                $chavePrimaria = $registro->getKey(); // Isso assume que a chave primária é 'id'

                $tabAuditController = $this->instanciarTabAuditController();

                $tabAuditController->gravarAutoriaPorColuna($chavePrimaria, $model, $campos, $tipoAcao);

                return $chavePrimaria;
            }
        } catch (Illuminate\Database\QueryException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }
    }
}
