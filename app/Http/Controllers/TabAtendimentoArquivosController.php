<?php

namespace App\Http\Controllers;

use App\Models\TabAtendimentoArquivos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use File;

use App\Http\Controllers\AtualizarOuCriarPorModeloDadosController;
use App\Http\Controllers\AuditController;

class TabAtendimentoArquivosController extends Controller
{

    public function instanciarAtualizarOuCriarPorModeloDadosController()
    {
        return new AtualizarOuCriarPorModeloDadosController;
    }

    public function instanciarAuditController()
    {
        return new AuditController;
    }

    public function getArquivos()
    {
        return TabAtendimentoArquivos::orderBy('txt_assunto')
            ->get();
    }

    public function getArquivosPorCodAtendimento($codAtendimento = null)
    {
        return TabAtendimentoArquivos::where('cod_atendimento', $codAtendimento)
            ->orderBy('txt_assunto')
            ->get();
    }

    public function getPKArquivosPorCodAtendimento($codAtendimento = null)
    {
        return TabAtendimentoArquivos::select('cod_arquivo')
            ->where('cod_atendimento', $codAtendimento)
            ->orderBy('txt_assunto')
            ->get();
    }

    public function destroyArquivosPorPK($codArquivo = null)
    {
        TabAtendimentoArquivos::destroy($codArquivo);
    }

    public function incluirArquivoAjax(Request $request)
    {

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        $input = $request->all();

        if ($request->hasFile('arquivo')) {

            $id = [];
            $campos = [];

            $nomeProcedimento = 'Gravar dados dos anexos (PDFs) do atendimento';
            $schema = 'midr_gestao';
            $table = 'tab_atendimento_arquivos';
            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

            $codAtendimento = $input['cod_atendimento'];

            $campos['cod_atendimento'] = $input['cod_atendimento'];

            $campos['txt_assunto'] = $input['txt_assunto'];

            // Caminho da pasta que deseja criar
            $directoryPath = 'public/atendimento/anexos/';
            $directoryPathForSave = 'storage/atendimento/anexos/';

            // Cria a pasta se ela não existir
            Storage::makeDirectory($directoryPath);

            $uuid = Str::uuid()->toString();
            $uuid = str_replace('-', '', $uuid);

            $fileName = date("Y_m_d_H_i_s") . '_' . $uuid . '.' . $request->file('arquivo')->getClientOriginalExtension();
            $path = $request->file('arquivo')->storeAs($directoryPath, $fileName); // Salva o arquivo no caminho especificado

            $campos['nom_arquivo'] = $directoryPathForSave . $fileName;

            $campos['dsc_tipo'] = $request->file('arquivo')->getClientMimeType();

            $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, '', $campos);

            return $this->montarDivAnexos($codAtendimento);

        }
    }

    public function excluirArquivo($codArquivo = null, $codAtendimento = null)
    {
        // Início da parte de excluir um arquivo

        if (isset($codArquivo) && !is_null($codArquivo) && $codArquivo != '') {

            $excluir = TabAtendimentoArquivos::find($codArquivo);

            $excluir->delete();

        }

        // Fim da parte de excluir um arquivo

        return $this->montarDivAnexos($codAtendimento);
    }

    public function getAuditoriaPorChavePrimaria($chavePrimaria = null)
    {

        $model = 'App\Models\TabAtendimentoArquivos';

        $chaves = $this->getPKsDemandasPorCodAtendimento($chavePrimaria);

        $audit = $this->instanciarAuditController();

        if (isset($chavePrimaria) && !is_null($chavePrimaria) && $chavePrimaria != '') {

            return $audit->getAuditoriaPorTableNameEChavePrimaria($model, $chaves);

        } else {
            return [];
        }

    }

    public function getPKsDemandasPorCodAtendimento($codAtendimento = null)
    {
        $chaves = TabAtendimentoArquivos::select('cod_arquivo')
            ->where('cod_atendimento', $codAtendimento)
            ->get();

        return $chaves->toArray();
    }

    public function montarDivAnexos($codAtendimento = null)
    {

        $arquivos = $this->getArquivosPorCodAtendimento($codAtendimento);

        $retorno = null;

        $contArquivo = 1;

        $retorno .= '<div class="row">';

        foreach ($arquivos as $arquivo) {

            $retorno .= '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2 text-left">';

            $retorno .= '<a href="' . asset($arquivo->nom_arquivo) . '" target="_blank">';

            $retorno .= '<span class="font-numero">' . $contArquivo . '</span>. ' . $arquivo->txt_assunto;

            $retorno .= '</a>';

            $retorno .= '<i class="fas fa-trash-alt text-danger" data-bs-toggle="modal"
                            data-bs-target="#modalConfirmarExclusaoArquivo_' . $arquivo->cod_arquivo . '" style="cursor: pointer;"></i>';

            // Início da modal de confirmação de exclusão do arquivo

            $retorno .= '<div class="modal"
                            id="modalConfirmarExclusaoArquivo_' . $arquivo->cod_arquivo . '"
                            tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true" data-bs-backdrop="static"
                            data-bs-keyboard="false"
                            style="padding-top: 150px!Important;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                    <p class="modal-title text-white" style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                        Excluir Anexo
                                    </p>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Deseja realmente excluir este anexo deste assunto?
                                    </p>
                                    <p>
                                        <span class="text-bold">';

            $retorno .= $arquivo->txt_assunto;
            $retorno .= '</span>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                        Fechar
                                    </button>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="javascript: excluir_arquivo(\'' . $arquivo->cod_arquivo . '\', \'' . $codAtendimento . '\');"
                                        data-bs-dismiss="modal">
                                        Sim, quero excluir!
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>';

            // Fim da modal de confirmação de exclusão do arquivo

            $retorno .= '</div>';

            $contArquivo++;

        }

        $retorno .= '</div>';

        return $retorno;
    }

}
