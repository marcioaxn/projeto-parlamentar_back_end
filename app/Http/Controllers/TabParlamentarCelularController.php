<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabParlamentarCelular;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\QueryException;
use App\Models\TabLogErros;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabParlamentarCelularController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getCelular()
    {
        return TabParlamentarCelular::orderBy('num_celular')
            ->get();
    }

    public function store($numCelular = null, $codParlamentar = null, $codCelular = null, $blnExcluir = null)
    {

        if (auth()->check()) {
            // Início da parte de consulta ao perfil de acesso do cliente
            $user = Auth::user();

            $perfil = $user->perfil;
            $bln_acesso_inrestrito = $perfil->bln_acesso_inrestrito;
            // Fim da parte de consulta ao perfil de acesso do cliente

            if (Session::get('permissao') === '0010000' || Session::get('permissao') === '0000100') {

                if (isset($numCelular) && !is_null($numCelular) && $numCelular != '' && isset($codParlamentar) && !is_null($codParlamentar) && $codParlamentar != '') {

                    $numCelular = retornaTextoTirandoParteDoTexto($numCelular, '(');
                    $numCelular = retornaTextoTirandoParteDoTexto($numCelular, ')');
                    $numCelular = retornaTextoTirandoParteDoTexto($numCelular, ' ');
                    $numCelular = retornaTextoTirandoParteDoTexto($numCelular, '-');

                    if (isset($codCelular) && !is_null($codCelular) && $codCelular != '') {

                        if (isset($blnExcluir) && !is_null($blnExcluir) && $blnExcluir != '' && $blnExcluir === 'Sim') {

                            try {
                                $delete = TabParlamentarCelular::find($codCelular);

                                $delete->delete();

                                return $this->montarConteudoDivCelular($codParlamentar);
                            } catch (Illuminate\Database\QueryException $e) {
                                TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
                            }

                        } else {

                            try {
                                TabParlamentarCelular::updateOrCreate(
                                    ['cod_celular' => $codCelular],
                                    ['num_celular' => $numCelular]
                                );

                                return $this->montarConteudoDivCelular($codParlamentar);
                            } catch (Illuminate\Database\QueryException $e) {
                                TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
                            }

                        }

                    } else {

                        try {

                            $existeNumeroBanco = 0;

                            $consultar = TabParlamentarCelular::where('cod_parlamentar', $codParlamentar)
                                ->get();

                            foreach ($consultar as $keyCelular => $valueCelular) {
                                if ($numCelular == $valueCelular->num_celular) {
                                    $existeNumeroBanco++;
                                }
                            }

                            if ($existeNumeroBanco == 0) {

                                TabParlamentarCelular::firstOrCreate(
                                    ['num_celular' => $numCelular],
                                    ['cod_parlamentar' => $codParlamentar]
                                );

                            }

                            return $this->montarConteudoDivCelular($codParlamentar);
                        } catch (Illuminate\Database\QueryException $e) {
                            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
                        }

                    }

                }

            } else {
                return 'Cliente sem permissão para executar esta função.';
            }

        } else {
            // O usuário não está autenticado
            // Lidar com o caso de usuário não autenticado

            return 'Cliente não está logado no sistema';
        }

    }

    public function montarConteudoDivCelular($codParlamentar = null)
    {

        $content = null;

        if (isset($codParlamentar) && !is_null($codParlamentar) && $codParlamentar != '') {

            try {
                $celulares = TabParlamentarCelular::where('cod_parlamentar', $codParlamentar)
                    ->orderBy('num_celular')
                    ->get();

                $contCelular = 1;

                $content .= '<div class="row">';

                foreach ($celulares as $celular) {

                    $content .= '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">';

                    $content .= '<span class="textoNormalTabela font-numero d-print-none">' . applyMask($celular->num_celular, "(##) #####-####") . '</span>&nbsp;';

                    $content .= '<span>';

                    $content .= '<span data-bs-toggle="modal"
                    data-bs-target="#modalEditarCelular' . $celular->cod_celular . '"
                    class="d-print-none">
                    <i class="fas fa-edit text-primary d-print-none"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Editar número de celular"
                    style="font-size: 0.75rem !Important; cursor: pointer !Important;"></i>
                    </span>';

                    $content .= '<!-- Modal -->
                        <div class="modal fade" id="modalEditarCelular' . $celular->cod_celular . '" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true"
                        data-bs-backdrop="static" data-bs-keyboard="false"
                        style="padding-top: 150px!Important;">
                            <div class="modal-dialog  modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header"
                                    style="background: linear-gradient(135deg,#898989 0%,#acacac 100%);color: white;">
                                        <p class="modal-title text-white"
                                        style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                            Editar número de celular</p>
                                    </div>
                                    <div class="modal-body">
                                        <input id="num_celular' . $celular->cod_celular . '" type="text" class="form-control font-numero"
                                        name="num_celular" value="' . applyMask($celular->num_celular, "(##) #####-####") . '" required autocomplete="num_celular"
                                        autofocus placeholder="Número do celular com DDD">
                                        <div id="" class="form-text pl-3 textoPequeno text-primary font-numero">Ex.: ' . applyMask($celular->num_celular, "(##) #####-####") . '</div>
                                        <script type="text/javascript">
                                            $("#num_celular' . $celular->cod_celular . '").mask("(00) 00000-0000");
                                            </script>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary"
                                        onclick="javascript: editar_celular(\'' . $celular->cod_celular . '\',$(\'#num_celular' . $celular->cod_celular . '\').val(),\'' . $codParlamentar . '\');">Alterar</button>
                                    </div>
                                </div>
                            </div>
                        </div>';

                    $content .= '</span>';

                    $content .= '<span>
                        <span data-bs-toggle="modal" data-bs-target="#modalExcluirCelular' . $celular->cod_celular . '"
                        class="d-print-none">
                            <i class="fas fa-trash-alt text-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Excluir número de celular" style="font-size: 0.75rem !Important; cursor: pointer !Important;"></i>
                        </span>
                        <!-- Modal -->
                        <div class="modal fade" id="modalExcluirCelular' . $celular->cod_celular . '"
                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                        data-bs-keyboard="false" style="padding-top: 150px!Important;">
                            <div class="modal-dialog  modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                        <p class="modal-title text-white" style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                        Excluir número de celular</p>
                                    </div>
                                    <div class="modal-body">
                                        <p>Número: <span class="font-numero">' . applyMask($celular->num_celular, '(##) #####-####') . '</span></p>
                                        <p class="">Deseja realmente excluir este número de celular?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-danger" onclick="javascript: excluir_celular(\'' . $celular->cod_celular . '\',$(\'#num_celular' . $celular->cod_celular . '\').val(),\'' . $codParlamentar . '\');">Sim, excluir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </span>';

                    $content .= '</div>';

                    $contCelular++;

                }

                $content .= '</div>';

                return $content;

            } catch (Illuminate\Database\QueryException $e) {
                TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
            }

        }
    }

}
