<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TabEvolucaoFinanceira;

use App\Http\Controllers\TabCarteiraInvestimentoMidrController;
use App\Http\Controllers\TabAtendimentoConvidadosController;

use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function instanciarTabCarteiraInvestimentoMidrController()
    {
        return new TabCarteiraInvestimentoMidrController;
    }

    public function instanciarTabAtendimentoConvidadosController()
    {
        return new TabAtendimentoConvidadosController;
    }

    public function tci($uf = null)
    {

        $tabCarteiraInvestimentoMidr = $this->instanciarTabCarteiraInvestimentoMidrController();

        return $tabCarteiraInvestimentoMidr->getTCIUf($uf)->toJson();
    }

    public function getApiConvidadosPorAtendimento($codAtendimento = null)
    {
        $tabAtendimentoConvidados = $this->instanciarTabAtendimentoConvidadosController();

        return $tabAtendimentoConvidados->getConvidadosPorAtendimento($codAtendimento);
    }

    public function getDescricaoChaveEstrangeira($columnNameChaveEstrangeira = null, $chaveEstrangeira = null)
    {

        $dadosTabela = $this->getTablePorColumnNameFK($columnNameChaveEstrangeira);

        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($dadosTabela['table']);

        $result = $model::find($chaveEstrangeira);

        $columnNameDescricao = $dadosTabela['descricao'];

        $result = $result->$columnNameDescricao;

        return $result;
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
                $data['descricao'] = '';
                break;
        }

        return $data;
    }

    public function getFundosAnoAtual($ano = null)
    {
        try {
            if (!isset($ano) && empty($ano)) {
                $ano = date('Y');
            }

            $result = DB::selectOne("SELECT * FROM midr_snfi.fnc_obter_resultado_por_ano(?)", [$ano]);

            // Adicionando headers, se necessário
            $headers = [
                'Content-Type' => 'application/json',
                'X-Your-Header-Name' => 'Header Json',
            ];

            // Retornando a resposta JSON com os headers
            return response()->json(
                [
                    'message' => 'Resultado do Fundo Constitucional de Investimento do ano de ' . $ano . '.',
                    'data' => $result
                ],
                200,
                $headers
            );

        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Erro ao consultar a função para retornar o resultado do Fundo Constitucional de Investimento do ano de ' . $ano . '.'
                ],
                500
            );
        }
    }

    public function getEvolucaoFinanceira($codPac = null, $codAcaoOrcamentaria = null, $numAno = null, $numMes = null)
    {
        try {
            $result = TabEvolucaoFinanceira::select('vlr_financeiro', 'txt_observacao_financeira')
                ->where('cod_pac', $codPac)
                ->where('cod_acao_orcamentaria', $codAcaoOrcamentaria)
                ->where('num_ano', $numAno)
                ->where('num_mes', $numMes)
                ->first();

            // Adicionando headers, se necessário
            $headers = [
                'Content-Type' => 'application/json',
                'X-Your-Header-Name' => 'Header Json Fundos',
            ];

            // Retornando a resposta JSON com os headers
            return response()->json(
                [
                    'message' => 'Evolução financeira ' . $numAno . '/' . $numMes,
                    'data' => $result
                ],
                200,
                $headers
            );

        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Erro ao consultar a função para retornar o resultado da evolução financeira do novo pac em ' . $numAno . '/' . $numMes
                ],
                500
            );
        }
    }


}
