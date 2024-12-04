<?php

namespace App\Http\Controllers;

use App\Models\TabEvolucaoOrcamentaria;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TabEvolucaoOrcamentariaController extends Controller
{

    public function getEvolucaoOrcamentariaPorParamentros($codPac = null, $codAcaoOrcamentaria = null, $numAno = null, $numMes = null)
    {
        return TabEvolucaoOrcamentaria::where('cod_pac', $codPac)
            ->where('cod_acao_orcamentaria', $codAcaoOrcamentaria)
            ->where('num_ano', $numAno)
            ->where('num_mes', $numMes)
            ->first();
    }

    public function modalTabelaLog($idModal = null, $textoHeader = null, $audit = null, $table = null, $chaveEstrangeira = null, $columnNameChaveEstrangeira = null)
    {

        if ($audit) {
            if ($audit && $audit->count()) {

                $quantidadeItensAuditoria = $audit->count();

            } else {

                $quantidadeItensAuditoria = 0;

            }

            $retorno = null;

            $retorno .= '<div class="modal fade" id="modalLog' . $idModal . '" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="padding-top: 119px!Important;">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header" style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                    <p class="modal-title text-white" style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                        <i class="fas fa-eye"></i> ' . $textoHeader . '
                                    </p>
                                </div>
                                <div class="modal-body mt-0 pt-0" style="max-height: 65vh; overflow-y: auto;">';

            $retorno .= '<table class="table table-sm table-fixed-header" style="width: 100%!Important;">
                    <thead>
                        <tr>
                        <th scope="col" class="text-right text-bold">#</th>
                        <th scope="col" class="text-bold">Ação</th>
                        <th scope="col" class="text-bold">Quem</th>
                        <th scope="col" class="text-bold">Quando</th>
                        </tr>
                    </thead>
                    <tbody>';

            $contLog = $audit->count();

            foreach ($audit as $object) {

                $oldValue = json_decode($object->old_values);
                $newValue = json_decode($object->new_values);

                $valorAntigo = null;

                $retorno .= '<tr>
                    <td class="text-right font-numero" style="font-size: 0.8rem!Important;">' . $contLog . '</td>
                    <td style="font-size: 0.8rem!Important;" style="width: 65%!Important;">';

                $column_name = null;
                $event = $object->event;
                $uuidValue = false;

                $matrizColunasNaoPrecisamConstarAuditoria = ['cod_evolucao_orcamentaria', 'cod_pac', 'cod_acao_orcamentaria', 'num_ano', 'num_mes', 'bln_atualizado'];

                $matrizDadosAntigos = [];
                $matrizDadosNovos = [];

                foreach ($oldValue as $key => $value) {
                    $matrizDadosAntigos[$key] = $value;
                }

                foreach ($newValue as $key => $value) {
                    $matrizDadosNovos[$key] = $value;
                }

                foreach ($matrizDadosNovos as $key => $value) {

                    if ($event === 'created' && !in_array($key, $matrizColunasNaoPrecisamConstarAuditoria)) {

                        $preFixoNomeColuna = substr($key, 0, 3);

                        if ($preFixoNomeColuna === 'vlr') {
                            $value = converteValor('MYSQL', 'PTBR', $value);
                        }

                        if (isset($value) && !empty($value)) {

                            $retorno .= 'Inseriu o(a) <span class="text-success">' . $value . '</span> no campo <span class="text-bold">' . nomeCampoTabNovoPacNormalizado($key) . '</span><br />';

                        }
                    }

                    if ($event === 'updated') {

                        $preFixoNomeColuna = substr($key, 0, 3);

                        if ($preFixoNomeColuna === 'vlr') {
                            $value = converteValor('MYSQL', 'PTBR', $value);
                            $matrizDadosAntigos[$key] = converteValor('MYSQL', 'PTBR', $matrizDadosAntigos[$key]);
                        }

                        if (!isset($matrizDadosAntigos[$key]) && empty($matrizDadosAntigos[$key])) {
                            $matrizDadosAntigos[$key] = 'nulo';
                        }

                        $retorno .= 'Alterou o(a) <span class="text-bold">' . nomeCampoTabNovoPacNormalizado($key) . '</span> de <span class="text-danger">' . $matrizDadosAntigos[$key] . '</span> para <span class="text-success">' . $value . '</span><br />';
                    }

                }

                if ($object->usuario) {

                    $quem = primeiraLetraMaiuscula($object->usuario->name);

                } else {

                    $quem = '-';

                }

                $retorno .= '</td>
                    <td style="font-size: 0.8rem!Important; width: 17%!Important;">' . $quem . '</td>
                    <td class="font-numero" style="font-size: 0.8rem!Important; width: 14%!Important;">' . formatarTimeStampComCarbonParaBR($object->created_at) . '</td>
                    </tr>';

                $contLog--;

            }

            $retorno .= '</tbody>
                </table>';


            $retorno .= '</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">
                                        Fechar
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>';

            return $retorno;
        }



        return null;
    }

    public function getDescricaoChaveEstrangeira($columnNameChaveEstrangeira = null, $chaveEstrangeira = null)
    {

        $dadosTabela = $this->getTablePorColumnNameFK($columnNameChaveEstrangeira);

        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($dadosTabela['table']);

        try {
            $result = $model::find($chaveEstrangeira);

            $columnNameDescricao = $dadosTabela['descricao'];

            $result = $result->$columnNameDescricao;

            return $result;
        } catch (Illuminate\Database\QueryException $e) {
            return [];
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }
    }

    protected function getTablePorColumnNameFK($columnNameChaveEstrangeira = null)
    {

        $data = [];

        switch ($columnNameChaveEstrangeira) {
            case 'cod_interlocutor':
                $data['table'] = 'tab_atendimento_interlocutores';
                $data['descricao'] = 'dsc_interlocutor';
                break;
            case 'cod_parlamentar':
                $data['table'] = 'tab_parlamentares';
                $data['descricao'] = 'nom_parlamentar';
                break;
            case 'cod_assunto':
                $data['table'] = 'tab_atendimento_assuntos';
                $data['descricao'] = 'dsc_assunto';
                break;
            case 'cod_cargo':
                $data['table'] = 'tab_atendimento_cargos';
                $data['descricao'] = 'dsc_cargo';
                break;
            case 'codigoUnidade':
                $data['table'] = 'tab_organizacao';
                $data['descricao'] = 'sigla';
                break;
            case 'cod_status_demanda':
                $data['table'] = 'tab_atendimento_demanda_status';
                $data['descricao'] = 'dsc_status';
                break;
            case 'cod_convidado':
                $data['table'] = 'tab_atendimento_convidados';
                $data['descricao'] = 'nom_convidado';
                break;
            case 'cod_demanda_atendimento':
                $data['table'] = 'tab_atendimento_demandas';
                $data['descricao'] = 'dsc_demanda';
                break;
            case 'cod_arquivo':
                $data['table'] = 'tab_atendimento_arquivos';
                $data['descricao'] = 'txt_assunto';
                break;
            case '':
                $data['table'] = '';
                $data['descricao'] = '';
                break;
            case '':
                $data['table'] = '';
                $data['descricao'] = '';
                break;
            case '':
                $data['table'] = '';
                $data['descricao'] = '';
                break;

            default:
                $data['table'] = '';
                $data['descricao'] = $columnNameChaveEstrangeira;
                break;
        }

        return $data;
    }

}
