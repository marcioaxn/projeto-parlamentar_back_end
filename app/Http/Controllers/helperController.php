<?php

namespace App\Http\Controllers;

use Session;
use App\Models\TabApiCamaraLegislaturas;
use App\Models\TabApiCamaraDeputadosRedesSociais;
use App\Models\TabAudit;
use App\Models\TabLogErros;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cache;
use App\Imports\TabAutografosImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Storage;
use ZipArchive;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\QueryException;

use App\Http\Controllers\TabApiCamaraLegislaturaController;
use App\Http\Controllers\TabApiCamaraDeputadosController;
use App\Http\Controllers\TabApiSenadoListaAtualSenadoresController;
use App\Http\Controllers\TabIndicadoresEstadosController;
use App\Http\Controllers\TabIbgeController;
use App\Http\Controllers\MunicipiosController;

use App\Exports\BaseParlamentaresFederaisExport;

ini_set('memory_limit', '5096M');
ini_set('max_execution_time', 5500);
set_time_limit(900000000);

class helperController extends Controller
{

    public function updateTheme(Request $request, $theme)
    {
        Session::put('theme', $theme);

        return response()->json(['success' => true]);
    }

    public function instanciarTabApiCamaraLegislaturaController()
    {
        return new TabApiCamaraLegislaturaController;
    }

    public function instanciarTabApiCamaraDeputadosController()
    {
        return new TabApiCamaraDeputadosController;
    }

    public function instanciarTabApiSenadoListaAtualSenadoresController()
    {
        return new TabApiSenadoListaAtualSenadoresController;
    }

    public function instanciarVisDadosCondensadosDeputadosESenadoresController()
    {
        return new VisDadosCondensadosDeputadosESenadoresController;
    }

    public function instanciarTabIndicadoresEstadosController()
    {
        return new TabIndicadoresEstadosController;
    }

    public function instanciarMunicipiosController()
    {
        return new MunicipiosController;
    }

    public function instanciarTabIbgeController()
    {
        return new TabIbgeController;
    }

    public function atualizarDadosApiCamaraEApiSenado()
    {

        // $mucipios = $this->instanciarMunicipiosController();

        $inicio = date("d/m/Y") . " às " . date("H:i:s");

        $schema = 'midr_gestao';
        $table = 'tab_ultima_atualizacao_parlamentares';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        try {
            $campos = [];
            $campos['dsc_tipo_atualizacao'] = 'API do senado federal - Lista atual de senadores';
            $campos['tms_inicio_procedimento'] = date("Y-m-d H:i:s");

            $this->atualizarListaAtualSenadores();

            $campos['tms_fim_procedimento'] = date("Y-m-d H:i:s");
            $campos['tms_atualizacao'] = date("Y-m-d H:i:s");

            $this->criarPorModeloDados($model, $campos);
        } catch (\Exception $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }

        sleep(21);

        try {
            $campos = [];
            $campos['dsc_tipo_atualizacao'] = 'API Câmara dos Deputados - Legislatura';
            $campos['tms_inicio_procedimento'] = date("Y-m-d H:i:s");

            DB::select("TRUNCATE table tab_api_camara_lideres;");
            DB::select("TRUNCATE table tab_api_camara_orgaos;");

            $this->atualizarLegislaturaApiCamaraDeputados();

            $campos['tms_fim_procedimento'] = date("Y-m-d H:i:s");
            $campos['tms_atualizacao'] = date("Y-m-d H:i:s");

            $this->criarPorModeloDados($model, $campos);
        } catch (\Exception $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }

        sleep(21);

        try {
            $campos = [];
            $campos['dsc_tipo_atualizacao'] = 'API da Câmara dos Deputados - Deputados por Legislatura';
            $campos['tms_inicio_procedimento'] = date("Y-m-d H:i:s");

            $this->atualizarDeputadosPorLegislaturaApiCamaraDeputados();

            $campos['tms_fim_procedimento'] = date("Y-m-d H:i:s");
            $campos['tms_atualizacao'] = date("Y-m-d H:i:s");

            $this->criarPorModeloDados($model, $campos);
        } catch (\Exception $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }

        sleep(21);

        try {
            $campos = [];
            $campos['dsc_tipo_atualizacao'] = 'API da Câmara dos Deputados - Deputados por id do deputado';
            $campos['tms_inicio_procedimento'] = date("Y-m-d H:i:s");

            $this->atualizarDeputadosPorIdDeputado();

            $campos['tms_fim_procedimento'] = date("Y-m-d H:i:s");
            $campos['tms_atualizacao'] = date("Y-m-d H:i:s");

            $this->criarPorModeloDados($model, $campos);
        } catch (\Exception $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }

        sleep(21);

        try {
            $campos = [];
            $campos['dsc_tipo_atualizacao'] = 'API da Câmara dos Deputados - Líderes por Legislatura';
            $campos['tms_inicio_procedimento'] = date("Y-m-d H:i:s");

            $this->atualizarLideresDeputadosPorLegislatura();

            $campos['tms_fim_procedimento'] = date("Y-m-d H:i:s");
            $campos['tms_atualizacao'] = date("Y-m-d H:i:s");

            $this->criarPorModeloDados($model, $campos);
        } catch (\Exception $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }

        $fim = date("d/m/Y") . " às " . date("H:i:s");

        $textoResultado = "<html lang='pt-br'><head><title>Atualizacao</title></head><body><b>Versão beta<br><br><b>Data e hora do início do procedimento:</b> " . $inicio . "<br><br><b>Data e hora do fim do procedimento:</b> " . $fim . "<br><br> <span style='color: red'>=> O IP utilizado para atualização foi o " . $_SERVER['REMOTE_ADDR'] . "</span></body></html>";

        return $textoResultado;
    }

    public function atualizarTabParlamentares()
    {

        $inicio = date("d/m/Y") . " às " . date("H:i:s");

        $schema = 'midr_gestao';
        $table = 'tab_ultima_atualizacao_parlamentares';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        try {
            $campos = [];
            $campos['dsc_tipo_atualizacao'] = 'Atualizar tabela tab_parlamentares';
            $campos['tms_inicio_procedimento'] = date("Y-m-d H:i:s");

            $this->atualizarDadosCondensadosDeputadosESenadores();

            $campos['tms_fim_procedimento'] = date("Y-m-d H:i:s");
            $campos['tms_atualizacao'] = date("Y-m-d H:i:s");

            $this->criarPorModeloDados($model, $campos);

            // Início do procedimento de atualização do campo num_sequencial_candidato da tab_parlamentares quando ele está vazio
            DB::select("UPDATE
                                tab_parlamentares
                            SET
                                num_sequencial_candidato = ttrp.sq_candidato::bigint
                            FROM
                                tab_tse_resumo_parlamentares ttrp
                            WHERE
                                mdr_corporativo.fnc_retira_acento(ttrp.nm_candidato) = mdr_corporativo.fnc_retira_acento(tab_parlamentares.nom_parlamentar_completo)
                            AND
                                tab_parlamentares.num_sequencial_candidato IS NULL;");
            // Fim do procedimento de atualização do campo num_sequencial_candidato da tab_parlamentares quando ele está vazio
            // ---- x ---- x ---- x ---- x ----

        } catch (\Exception $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }

        $fim = date("d/m/Y") . " às " . date("H:i:s");

        $textoResultado = "<html lang='pt-br'><head><title>Atualizacao</title></head><body><b>Versão beta<br><br><b>Data e hora do início do procedimento:</b> " . $inicio . "<br><br><b>Data e hora do fim do procedimento:</b> " . $fim . "<br><br> <span style='color: red'>=> O IP utilizado para atualização foi o " . $_SERVER['REMOTE_ADDR'] . "</span></body></html>";

        return $textoResultado;
    }

    public function atualizarLegislaturaApiCamaraDeputados()
    {

        // 1ª Etapa na atualização dos dados dos deputados

        $nomeProcedimento = 'API da Câmara dos Deputados - Legislatura';
        $schema = 'midr_gestao';
        $table = 'tab_api_camara_legislaturas';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $url = 'https://dadosabertos.camara.leg.br/api/v2/legislaturas?ordem=DESC&ordenarPor=id&pagina=1&itens=100';

        $getApi = file_get_contents($url);

        if ($getApi) {

            if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                $data = json_decode($getApi);

                // Verifica se o JSON foi decodificado com sucesso
                if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                    // Utilize os dados retornados
                    // por exemplo, exiba o conteúdo do objeto ou array

                    // Aqui será o novo pedaço do código

                    foreach ($data as $key => $return) {

                        if ($key === 'dados') {

                            $id = [];
                            $campos = [];

                            $gravarForaForeach = 0;

                            foreach ($return as $keyResult => $value) {

                                if (is_numeric($keyResult)) {

                                    foreach ($value as $keyDados => $resultDados) {

                                        $getColumnTable = $this->getColumnTable($schema, $table, $keyDados);

                                        if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                            $columnName = $getColumnTable->column_name;

                                            $ordinalPosition = $getColumnTable->ordinal_position;

                                            if ($ordinalPosition == 1) {

                                                $id[$columnName] = $value->$columnName;
                                            } else {

                                                $campos[$columnName] = $value->$columnName;
                                            }
                                        }
                                    }

                                    $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                } else {

                                    $gravarForaForeach++;

                                    $getColumnTable = $this->getColumnTable($schema, $table, $keyResult);

                                    if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                        $columnName = $getColumnTable->column_name;

                                        $ordinalPosition = $getColumnTable->ordinal_position;

                                        if ($ordinalPosition == 1) {

                                            $id[$columnName] = $value;
                                        } else {

                                            $campos[$columnName] = $value;
                                        }
                                    }
                                }

                            }

                            if ($gravarForaForeach > 0) {

                                $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                            }
                        } else {
                            return "Erro ao gravar, pois a matriz de dados está vazia.";
                        }

                        return "Gravação efetuada com sucesso!";
                    }

                    // Fim

                    // return $gravar;
                } else {
                    // O JSON não pôde ser decodificado
                    return "Erro ao decodificar o JSON => " . $nomeProcedimento . ".";
                }
            }
        } else {
            // Erro na requisição
            return "Erro na requisição da API => " . $nomeProcedimento . ".";
        }

        throw new \Exception("Erro no procedimento => " . $nomeProcedimento . ".");
    }

    public function atualizarDeputadosPorLegislaturaApiCamaraDeputados()
    {

        // 2ª Etapa na atualização dos dados dos deputados

        $nomeProcedimento = 'API da Câmara dos Deputados - Deputados por Legislatura';
        $schema = 'midr_gestao';
        $table = 'tab_api_camara_deputados';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $tabApiCamaraLegislaturaController = $this->instanciarTabApiCamaraLegislaturaController();

        $getLegislaturaPeloAnoAtual = $tabApiCamaraLegislaturaController->getLegislaturaPeloAnoAtual();

        if (isset($getLegislaturaPeloAnoAtual) && !is_null($getLegislaturaPeloAnoAtual) && $getLegislaturaPeloAnoAtual != '') {

            $idLegislatura = $getLegislaturaPeloAnoAtual->id;

            // Início do procedimento de limpeza dos dados da mesa diretora
            DB::select("DELETE FROM tab_camara_mesa;");
            // Fim do procedimento de limpeza dos dados da mesa diretora

            // Início da atualização dos dados da mesa diretora da Câmara dos Deputados
            $this->atualizarMesaDiretoraDeputados($idLegislatura);
            // Fim da atualização dos dados da mesa diretora da Câmara dos Deputados

            if (isset($idLegislatura) && !is_null($idLegislatura) && $idLegislatura != '') {

                $url = 'https://dadosabertos.camara.leg.br/api/v2/deputados?idLegislatura=' . $idLegislatura . '&ordem=ASC&ordenarPor=nome';

                $getApi = file_get_contents($url);

                if ($getApi) {

                    if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                        $data = json_decode($getApi);

                        // Verifica se o JSON foi decodificado com sucesso
                        if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                            // Utilize os dados retornados
                            // por exemplo, exiba o conteúdo do objeto ou array

                            // Aqui será o novo pedaço do código

                            foreach ($data as $key => $return) {

                                if ($key === 'dados') {

                                    $id = [];
                                    $campos = [];

                                    $gravarForaForeach = 0;

                                    foreach ($return as $keyResult => $value) {

                                        if (is_numeric($keyResult)) {

                                            foreach ($value as $keyDados => $resultDados) {

                                                $getColumnTable = $this->getColumnTable($schema, $table, $keyDados);

                                                if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                    $columnName = $getColumnTable->column_name;

                                                    $ordinalPosition = $getColumnTable->ordinal_position;

                                                    if ($ordinalPosition == 1) {

                                                        $id[$columnName] = $value->$columnName;
                                                    } else {

                                                        $campos[$columnName] = $value->$columnName;
                                                    }
                                                }
                                            }

                                            $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                        } else {

                                            $gravarForaForeach++;

                                            $getColumnTable = $this->getColumnTable($schema, $table, $keyResult);

                                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                $columnName = $getColumnTable->column_name;

                                                $ordinalPosition = $getColumnTable->ordinal_position;

                                                if ($ordinalPosition == 1) {

                                                    $id[$columnName] = $value;
                                                } else {

                                                    $campos[$columnName] = $value;
                                                }
                                            }
                                        }
                                    }

                                    if ($gravarForaForeach > 0) {

                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                    }
                                } else {
                                    return "Erro ao gravar, pois a matriz de dados está vazia.";
                                }

                                return "Gravação efetuada com sucesso!";
                            }

                            // Fim

                            // return $gravar;
                        } else {
                            // O JSON não pôde ser decodificado
                            return "Erro ao decodificar o JSON => " . $nomeProcedimento . ".";
                        }
                    }
                } else {
                    // Erro na requisição
                    return "Erro na requisição da API => " . $nomeProcedimento . ".";
                }
            } else {
                return "Erro no retorno da consulta a legislatura atual (variável idLegislatura está vazia) para ter acesso a => " . $nomeProcedimento . ".";
            }
        } else {

            return "Erro ao consultar a legislatura para ter acesso a => " . $nomeProcedimento . ".";
        }

        throw new \Exception("Erro no procedimento => " . $nomeProcedimento . ".");
    }

    public function gravarLegislaturasDeputados()
    {

        // 2ª Etapa na atualização dos dados dos deputados

        $nomeProcedimento = 'API da Câmara dos Deputados - Deputados por Legislatura';
        $schema = 'midr_gestao';
        $table = 'tab_api_camara_deputados';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $tabApiCamaraLegislaturaController = $this->instanciarTabApiCamaraLegislaturaController();

        $getLegislaturas = $tabApiCamaraLegislaturaController->getLegislaturas();

        if ($getLegislaturas) {

            foreach ($getLegislaturas as $legislatura) {
                $idLegislatura = $legislatura->id;

                if ($idLegislatura == 52) {

                    $url = 'https://dadosabertos.camara.leg.br/api/v2/deputados?idLegislatura=' . $idLegislatura . '&ordem=ASC&ordenarPor=nome';

                    $getApi = file_get_contents($url);

                    if ($getApi) {

                        if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                            $data = json_decode($getApi);

                            // Verifica se o JSON foi decodificado com sucesso
                            if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                                // Utilize os dados retornados
                                // por exemplo, exiba o conteúdo do objeto ou array

                                // Aqui será o novo pedaço do código

                                foreach ($data as $key => $return) {

                                    if ($key === 'dados') {

                                        foreach ($return as $keyResult => $value) {

                                            foreach ($value as $keyParlamentar => $valueParlamentar) {

                                                if ($keyParlamentar === 'id') {

                                                    $deputadoId = $valueParlamentar;
                                                }

                                                $url = 'https://dadosabertos.camara.leg.br/api/v2/deputados/' . $deputadoId;

                                                $getApi = file_get_contents($url);

                                                if ($getApi) {

                                                    if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                                                        $data = json_decode($getApi);

                                                        // Verifica se o JSON foi decodificado com sucesso
                                                        if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                                                            // Utilize os dados retornados
                                                            // por exemplo, exiba o conteúdo do objeto ou array

                                                            // Aqui será o novo pedaço do código

                                                            foreach ($data as $key => $return) {

                                                                if ($key === 'dados') {

                                                                    $id = [];
                                                                    $campos = [];

                                                                    $campos['cod_parlamentar'] = $deputadoId;

                                                                    foreach ($return as $keyDetalhe => $valueDetalhe) {

                                                                        if ($keyDetalhe === 'cpf') {
                                                                            $cpf = $valueDetalhe;

                                                                            $campos['num_cpf'] = $valueDetalhe;
                                                                        }

                                                                        if ($keyDetalhe === 'nomeCivil') {
                                                                            $nomeCivil = $valueDetalhe;

                                                                            $campos['nom_parlamentar'] = $nomeCivil;
                                                                        }

                                                                        if ($keyDetalhe === 'ultimoStatus') {

                                                                            foreach ($valueDetalhe as $keyUltimoStatus => $valueUltimoStatus) {
                                                                                if ($keyUltimoStatus === 'idLegislatura') {
                                                                                    $campos['legislatura'] = $idLegislatura;
                                                                                }
                                                                            }
                                                                        }
                                                                    }

                                                                    $schema = 'midr_gestao';
                                                                    $table = 'tab_parlamentar_legislaturas';
                                                                    $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                                    $this->atualizarOuCriarPorModeloDados($model, $id, $campos);

                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        dd("Gravação ocorreu com êxito, 52!");

        throw new \Exception("Erro no procedimento => " . $nomeProcedimento . ".");
    }

    public function atualizarDeputadosPorIdDeputado()
    {

        // 3ª Etapa na atualização dos dados dos deputados

        $nomeProcedimento = 'API da Câmara dos Deputados - Deputados por id do deputado';
        $schema = 'midr_gestao';
        $table = 'tab_api_camara_deputados';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);
        $camposComMatrizManterMesmaTabela = ['ultimoStatus'];
        $camposComMatriz = ['redeSocial'];

        $tabApiCamaraDeputadosController = $this->instanciarTabApiCamaraDeputadosController();
        $getIdDeputados = $tabApiCamaraDeputadosController->getIdDeputados();

        if (isset($getIdDeputados) && !is_null($getIdDeputados) && $getIdDeputados != '') {

            foreach ($getIdDeputados as $valueDeputado) {

                $deputadoId = $valueDeputado->id;

                if (isset($deputadoId) && !is_null($deputadoId) && $deputadoId != '') {

                    $this->salvarFoto('Câmara dos Deputados', $deputadoId);

                    $url = 'https://dadosabertos.camara.leg.br/api/v2/deputados/' . $deputadoId;

                    $getApi = file_get_contents($url);

                    if ($getApi) {

                        if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                            $data = json_decode($getApi);

                            // Verifica se o JSON foi decodificado com sucesso
                            if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                                // Utilize os dados retornados
                                // por exemplo, exiba o conteúdo do objeto ou array

                                // Aqui será o novo pedaço do código

                                foreach ($data as $key => $return) {

                                    if ($key === 'dados') {

                                        $id = [];
                                        $campos = [];

                                        $gravarForaForeach = 0;

                                        foreach ($return as $keyResult => $value) {

                                            if (is_numeric($keyResult)) {

                                                foreach ($value as $keyDados => $resultDados) {

                                                    $getColumnTable = $this->getColumnTable($schema, $table, $keyDados);

                                                    if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                        $columnName = $getColumnTable->column_name;

                                                        $ordinalPosition = $getColumnTable->ordinal_position;

                                                        if ($ordinalPosition == 1) {

                                                            $id[$columnName] = $value->$columnName;
                                                        } else {

                                                            $campos[$columnName] = $value->$columnName;
                                                        }
                                                    }
                                                }

                                                $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                            } else {

                                                $gravarForaForeach++;

                                                if ($table === 'tab_api_camara_deputados' && $keyResult === 'ultimoStatus' || $keyResult === 'redeSocial') {

                                                    if (in_array($keyResult, $camposComMatrizManterMesmaTabela)) {

                                                        foreach ($value as $keyInterno => $valueInterno) {

                                                            $getColumnTable = $this->getColumnTable($schema, $table, $keyInterno);

                                                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                $columnName = $getColumnTable->column_name;

                                                                $ordinalPosition = $getColumnTable->ordinal_position;

                                                                $campos[$columnName] = $valueInterno;
                                                            }

                                                            if ($keyInterno === 'gabinete') {

                                                                foreach ($valueInterno as $keyGabinete => $valueGabinete) {

                                                                    if ($keyGabinete === 'telefone') {

                                                                        $getColumnTable = $this->getColumnTable($schema, $table, $keyGabinete);

                                                                        if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                            $columnName = $getColumnTable->column_name;

                                                                            $ordinalPosition = $getColumnTable->ordinal_position;

                                                                            if ($ordinalPosition != 1) {

                                                                                $campos[$columnName] = $valueGabinete;
                                                                            }
                                                                        }
                                                                    }

                                                                    if ($keyGabinete === 'email') {

                                                                        $getColumnTable = $this->getColumnTable($schema, $table, $keyGabinete);

                                                                        if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                            $columnName = $getColumnTable->column_name;

                                                                            $ordinalPosition = $getColumnTable->ordinal_position;

                                                                            if ($ordinalPosition != 1) {

                                                                                $campos[$columnName] = $valueGabinete;
                                                                            }
                                                                        }

                                                                    }

                                                                }
                                                            }
                                                        }
                                                    }

                                                    if (in_array($keyResult, $camposComMatriz)) {

                                                        foreach ($value as $valueInterno) {

                                                            TabApiCamaraDeputadosRedesSociais::firstOrCreate(
                                                                ['dsc_rede_social' => $valueInterno],
                                                                ['deputado_id' => $deputadoId]
                                                            );
                                                        }
                                                    }
                                                } else {

                                                    $getColumnTable = $this->getColumnTable($schema, $table, $keyResult);

                                                    if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                        $columnName = $getColumnTable->column_name;

                                                        $ordinalPosition = $getColumnTable->ordinal_position;

                                                        if ($ordinalPosition == 1) {

                                                            $id[$columnName] = $value;
                                                        } else {

                                                            $campos[$columnName] = $value;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);

                                        $this->atualizarOrgaosDeputados($deputadoId);
                                    }

                                    // return "Gravação efetuada com sucesso!";
                                }

                                // Fim

                                // return "Gravação efetuada com sucesso!";
                            } else {
                                // O JSON não pôde ser decodificado
                                return "Erro ao decodificar o JSON => " . $nomeProcedimento . ".";
                            }
                        }
                    } else {
                        // Erro na requisição
                        return "Erro na requisição da API => " . $nomeProcedimento . ".";
                    }
                } else {
                    return "Erro no retorno da consulta para retornar os deputados (variável deputadoId está vazia) para ter acesso a => " . $nomeProcedimento . ".";
                }
            }
        } else {

            return "Erro ao consultar a legislatura para ter acesso a => " . $nomeProcedimento . ".";
        }

        // throw new \Exception("Erro no procedimento => " . $nomeProcedimento . ".");
    }

    public function atualizarMesaDiretoraDeputados($idLegislatura = null)
    {

        // Etapa de atualização da mesa diretora da Câmara dos Deputados

        $nomeProcedimento = 'Atualização da mesa diretora da Câmara dos Deputados';
        $schema = 'midr_gestao';
        $table = 'tab_camara_mesa';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $url = 'https://dadosabertos.camara.leg.br/api/v2/legislaturas/' . $idLegislatura . '/mesa';

        $getApi = file_get_contents($url);

        if ($getApi) {

            if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                $data = json_decode($getApi);

                // Verifica se o JSON foi decodificado com sucesso
                if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                    foreach ($data as $key => $return) {

                        if ($key === 'dados') {

                            $id = [];
                            $campos = [];

                            foreach ($return as $keyResult => $value) {
                                $campos['deputado_id'] = $value->id;
                                $campos['uri'] = $value->uri;
                                $campos['nome'] = $value->nome;
                                $campos['siglaPartido'] = $value->siglaPartido;
                                $campos['uriPartido'] = $value->uriPartido;
                                $campos['siglaUf'] = $value->siglaUf;
                                $campos['urlFoto'] = $value->urlFoto;
                                $campos['email'] = $value->email;
                                $campos['dataInicio'] = $value->dataInicio;
                                $campos['dataFim'] = $value->dataFim;
                                $campos['titulo'] = $value->titulo;
                                $campos['codTitulo'] = $value->codTitulo;

                                $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                            }

                        }

                    }

                }

            }

        }

        return 'Dados da mesa diretora da Câmara dos Deputados foram gravados com sucesso!';
    }

    public function atualizarOrgaosDeputados($deputadoId = null)
    {

        if (isset($deputadoId) && !is_null($deputadoId) && $deputadoId != '') {

            // Início da atualização dos dados de Órgãos onde o deputado exerce alguma função

            $schema = 'midr_gestao';
            $table = 'tab_api_camara_orgaos';
            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

            $url = 'https://dadosabertos.camara.leg.br/api/v2/deputados/' . $deputadoId . '/orgaos?ordem=ASC&ordenarPor=dataInicio';

            $getApi = file_get_contents($url);

            if ($getApi) {

                if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                    $data = json_decode($getApi);

                    // Verifica se o JSON foi decodificado com sucesso
                    if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                        // Início do procedimento de limpeza dos dados de Órgão do parlamentar
                        DB::select("DELETE FROM tab_api_camara_orgaos WHERE deputado_id = " . $deputadoId . ";");
                        // Fim do procedimento de limpeza dos dados de Órgão do parlamentar

                        // Utilize os dados retornados
                        // por exemplo, exiba o conteúdo do objeto ou array

                        // Aqui será o novo pedaço do código

                        foreach ($data as $key => $return) {

                            if ($key === 'dados') {

                                $id = [];
                                $campos = [];

                                foreach ($return as $keyResult => $value) {

                                    if (is_numeric($keyResult)) {

                                        foreach ($value as $keyDados => $resultDados) {

                                            $getColumnTable = $this->getColumnTable($schema, $table, $keyDados);

                                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                $columnName = $getColumnTable->column_name;

                                                $ordinalPosition = $getColumnTable->ordinal_position;

                                                $campos['deputado_id'] = $deputadoId;

                                                $campos[$columnName] = $value->$columnName;
                                            }
                                        }

                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                    }
                                }

                                $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                            }
                        }

                        // Fim

                        // return $gravar;
                    } else {
                        // O JSON não pôde ser decodificado
                        return "Erro ao decodificar o JSON.";
                    }
                }
            } // Fim da atualização dos dados de Órgãos onde o deputado exerce alguma função

        }
    }

    public function atualizarLideresDeputadosPorLegislatura()
    {

        // 4ª Etapa na atualização dos dados dos deputados

        $nomeProcedimento = 'API da Câmara dos Deputados - Líderes por Legislatura';
        $schema = 'midr_gestao';
        $table = 'tab_api_camara_lideres';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);
        $camposComMatrizManterMesmaTabela = ['parlamentar', 'bancada'];

        $tabApiCamaraLegislaturaController = $this->instanciarTabApiCamaraLegislaturaController();

        $getLegislaturaPeloAnoAtual = $tabApiCamaraLegislaturaController->getLegislaturaPeloAnoAtual();

        if (isset($getLegislaturaPeloAnoAtual) && !is_null($getLegislaturaPeloAnoAtual) && $getLegislaturaPeloAnoAtual != '') {

            $idLegislatura = $getLegislaturaPeloAnoAtual->id;

            if (isset($idLegislatura) && !is_null($idLegislatura) && $idLegislatura != '') {

                $url = 'https://dadosabertos.camara.leg.br/api/v2/legislaturas/' . $idLegislatura . '/lideres?itens=3000';

                $getApi = file_get_contents($url);

                if ($getApi) {

                    if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                        $data = json_decode($getApi);

                        // Verifica se o JSON foi decodificado com sucesso
                        if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                            // Início do procedimento de limpeza dos dados de Órgão do parlamentar
                            DB::select("DELETE FROM tab_api_camara_lideres;");
                            // Fim do procedimento de limpeza dos dados de Órgão do parlamentar

                            // Utilize os dados retornados
                            // por exemplo, exiba o conteúdo do objeto ou array

                            // Aqui será o novo pedaço do código

                            foreach ($data as $key => $return) {

                                if ($key === 'dados') {

                                    $id = [];
                                    $campos = [];

                                    $gravarForaForeach = 0;

                                    foreach ($return as $keyResult => $value) {

                                        if (is_numeric($keyResult)) {

                                            foreach ($value as $keyDados => $resultDados) {

                                                if (in_array($keyDados, $camposComMatrizManterMesmaTabela)) {

                                                    foreach ($resultDados as $keyInterno => $valueInterno) {

                                                        $getColumnTable = $this->getColumnTable($schema, $table, $keyInterno);

                                                        if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                            $columnName = $getColumnTable->column_name;

                                                            $ordinalPosition = $getColumnTable->ordinal_position;

                                                            if ($ordinalPosition == 1) {

                                                                $id[$columnName] = $valueInterno;
                                                            } else {

                                                                $campos[$columnName] = $valueInterno;
                                                            }
                                                        }
                                                    }
                                                }

                                                $getColumnTable = $this->getColumnTable($schema, $table, $keyDados);

                                                if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                    $columnName = $getColumnTable->column_name;

                                                    $ordinalPosition = $getColumnTable->ordinal_position;

                                                    if ($ordinalPosition == 1) {

                                                        $id[$columnName] = $value->$columnName;
                                                    } else {

                                                        $campos[$columnName] = $value->$columnName;
                                                    }
                                                }
                                            }

                                            $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                        } else {

                                            $gravarForaForeach++;

                                            $getColumnTable = $this->getColumnTable($schema, $table, $keyResult);

                                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                $columnName = $getColumnTable->column_name;

                                                $ordinalPosition = $getColumnTable->ordinal_position;

                                                if ($ordinalPosition == 1) {

                                                    $id[$columnName] = $value;
                                                } else {

                                                    $campos[$columnName] = $value;
                                                }
                                            }
                                        }
                                    }

                                    if ($gravarForaForeach > 0) {

                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                    }
                                } else {
                                    return "Erro ao gravar, pois a matriz de dados está vazia.";
                                }

                                return "Gravação efetuada com sucesso!";
                            }

                            // Fim

                            // return $gravar;
                        } else {
                            // O JSON não pôde ser decodificado
                            return "Erro ao decodificar o JSON => " . $nomeProcedimento . ".";
                        }
                    }
                } else {
                    // Erro na requisição
                    return "Erro na requisição da API => " . $nomeProcedimento . ".";
                }
            } else {
                return "Erro no retorno da consulta a legislatura atual (variável idLegislatura está vazia) para ter acesso a => " . $nomeProcedimento . ".";
            }
        } else {

            return "Erro ao consultar a legislatura para ter acesso a => " . $nomeProcedimento . ".";
        }

        throw new \Exception("Erro no procedimento => " . $nomeProcedimento . ".");
    }

    public function gravarLegislaturaSenadores($numeroLegislatura = null)
    {

        $numeroLegislatura = 57;

        // Etapa de atualização da legislatura dos Senadores

        $nomeProcedimento = 'API do senado federal - Lista atual de senadores';
        $schema = 'midr_gestao';

        $camposComMatrizManterMesmaTabela = ['Parlamentares' => ['Parlamentar']];

        $url = 'https://legis.senado.leg.br/dadosabertos/senador/lista/legislatura/' . $numeroLegislatura . '.json';

        $getApi = file_get_contents($url);

        if ($getApi) {

            if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                $data = json_decode($getApi);

                // Verifica se o JSON foi decodificado com sucesso
                if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                    foreach ($data as $key => $return) {

                        if ($key === 'ListaParlamentarLegislatura') {

                            foreach ($return as $keyResult => $valueResult) {

                                if (array_key_exists($keyResult, $camposComMatrizManterMesmaTabela)) {

                                    foreach ($valueResult as $keyParlamentares => $valueParlamentares) {

                                        if ($keyParlamentares === 'Parlamentar') {

                                            foreach ($valueParlamentares as $valueParlamentar) {

                                                foreach ($valueParlamentar as $keyInterno => $valueInterno) {

                                                    $id = [];
                                                    $campos = [];

                                                    if ($keyInterno === 'IdentificacaoParlamentar') {

                                                        $campos['legislatura'] = $numeroLegislatura;
                                                        $campos['dsc_casa'] = 'Senado Federal';

                                                        foreach ($valueInterno as $keyInternoParlamentar => $valueInternoParlamentar) {
                                                            // dd('Aqui 8', $valueInterno, $keyInternoParlamentar, $valueInternoParlamentar);

                                                            if ($keyInternoParlamentar === 'CodigoParlamentar') {
                                                                $campos['cod_parlamentar'] = $valueInternoParlamentar;
                                                            }

                                                            if ($keyInternoParlamentar === 'NomeCompletoParlamentar') {
                                                                $campos['nom_parlamentar'] = $valueInternoParlamentar;
                                                            }
                                                        }

                                                        $schema = 'midr_gestao';
                                                        $table = 'tab_parlamentar_legislaturas';
                                                        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);

                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }

                        }

                    }

                }

            }

        }

        return "Gravação ocorreu com êxito: Senado, legislatura 57!";

    }

    public function fotosTse()
    {
        // Verifica se a pasta 'fotos/tse' não existe e cria se necessário
        if (!Storage::exists('public/fotos/tse/zip')) {
            Storage::makeDirectory('public/fotos/tse/zip');
        }

        $ufs = [
            'AC',
            'AL',
            'AM',
            'AP',
            'BA',
            'CE',
            'DF',
            'ES',
            'GO',
            'MA',
            'MG',
            'MS',
            'MT',
            'PA',
            'PB',
            'PE',
            'PI',
            'PR',
            'RJ',
            'RN',
            'RO',
            'RR',
            'RS',
            'SC',
            'SE',
            'SP',
            'TO'
        ];

        $exemploUrl = 'https://cdn.tse.jus.br/estatistica/sead/eleicoes/eleicoes2022/fotos/foto_cand2022_AC_div.zip';

        foreach ($ufs as $uf) {

            // Cria uma instância do GuzzleHttp\Client
            $client = new Client();

            // Faz o download do JSON da URL
            $response = $client->get('https://cdn.tse.jus.br/estatistica/sead/eleicoes/eleicoes2022/fotos/foto_cand2022_' . $uf . '_div.zip');

            // Verifica se o download foi bem sucedido
            if ($response->getStatusCode() == 200) {
                $jsonContent = $response->getBody()->getContents();

                // Define o nome do arquivo fixo
                $filename = $uf . '.zip';

                Storage::put('public/fotos/tse/zip/' . $filename, $jsonContent);

                // Descompacta o arquivo
                $zip = new ZipArchive;
                $zip->open(storage_path('app/public/fotos/tse/zip/' . $filename));
                $zip->extractTo(storage_path('app/public/fotos/tse/'));
                $zip->close();

            }

        }

        return response()->json(['message' => 'Os arquivos compactados com as fotos dos parlamentares estaduais e distritais foram baixadas e em seguida foram descompactados!']);

    }

    public function downloadJsonSenado()
    {
        // Verifica se a pasta 'jsonSenado' não existe e cria se necessário
        if (!Storage::exists('public/jsonSenado')) {
            Storage::makeDirectory('public/jsonSenado');
        }

        // Cria uma instância do GuzzleHttp\Client
        $client = new Client();

        // Faz o download do JSON da URL
        $response = $client->get('https://legis.senado.leg.br/dadosabertos/senador/lista/atual.json');

        // Verifica se o download foi bem sucedido
        if ($response->getStatusCode() == 200) {
            $jsonContent = $response->getBody()->getContents();

            // Define o nome do arquivo fixo
            $filename = 'jsonSenado.json';

            // Verifica se o arquivo já existe
            if (Storage::exists('public/jsonSenado/' . $filename)) {
                // Obtém o conteúdo do arquivo existente
                $existingContent = Storage::get('public/jsonSenado/' . $filename);

                // Verifica se o conteúdo é o mesmo
                if (md5($existingContent) === md5($jsonContent)) {
                    // Se for o mesmo, não faz o download novamente
                    return response()->json(['message' => 'JSON do Senado já está atualizado.']);
                }
            }

            // Salva o JSON na pasta 'jsonSenado' com o nome fixo
            Storage::put('public/jsonSenado/' . $filename, $jsonContent);
        }

        return response()->json(['message' => 'JSON do Senado baixado com sucesso!']);
    }


    public function atualizarListaAtualSenadores()
    {

        // 1ª Etapa na atualização dos dados dos senadores

        $nomeProcedimento = 'API do senado federal - Lista atual de senadores';
        $schema = 'midr_gestao';

        $camposComMatrizManterMesmaTabela = ['Parlamentares' => ['Parlamentar']];

        // Caminho do arquivo baixado
        $caminhoArquivo = 'public/jsonSenado/jsonSenado.json';

        // Obtém o conteúdo do arquivo baixado
        $jsonContent = Storage::get($caminhoArquivo);

        // Decodifica o conteúdo JSON em um array associativo
        $getApi = json_decode($jsonContent, true);

        if ($getApi) {

            $data = $getApi;

            // Verifica se o JSON foi decodificado com sucesso
            if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                // Início do procedimento de alterar o campo dsc_situacao para 'Fora de exercício'
                // Essa alteração se faz necessária pois não há um campo lógico na API que diz se está em exercício
                DB::select("UPDATE tab_api_senado_lista_atual_senadores SET \"DescricaoSituacao\" = 'Fora de exercício';");
                DB::select("UPDATE tab_parlamentares SET dsc_situacao = 'Fora de exercício' WHERE dsc_casa = 'Senado Federal';");
                // Fim do procedimento de alterar o campo dsc_situacao para 'Fora de exercício'

                // Utilize os dados retornados
                // por exemplo, exiba o conteúdo do objeto ou array

                // Aqui será o novo pedaço do código

                foreach ($data as $key => $return) {

                    if ($key === 'ListaParlamentarEmExercicio') {

                        foreach ($return as $keyResult => $valueResult) {

                            if (array_key_exists($keyResult, $camposComMatrizManterMesmaTabela)) {

                                foreach ($valueResult as $keyParlamentares => $valueParlamentares) {

                                    if ($keyParlamentares === 'Parlamentar') {

                                        foreach ($valueParlamentares as $valueParlamentar) {

                                            foreach ($valueParlamentar as $keyInterno => $valueInterno) {

                                                if ($keyInterno === 'IdentificacaoParlamentar') {

                                                    $table = 'tab_api_senado_lista_atual_senadores';
                                                    $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                    $id = [];
                                                    $campos = [];

                                                    $camposComMatriz = ['Telefones', 'Bloco'];

                                                    $codigoParlamentar = null;

                                                    foreach ($valueInterno as $keyInternoParlamentar => $valueInternoParlamentar) {

                                                        if (!is_array($valueInternoParlamentar) && !in_array($keyInternoParlamentar, $camposComMatriz)) {

                                                            $getColumnTable = $this->getColumnTable($schema, $table, $keyInternoParlamentar);

                                                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                $columnName = $getColumnTable->column_name;

                                                                $ordinalPosition = $getColumnTable->ordinal_position;

                                                                if ($ordinalPosition == 1) {

                                                                    $id[$columnName] = $valueInternoParlamentar;
                                                                    $codigoParlamentar = $valueInternoParlamentar;
                                                                    $this->salvarDadosBasicosParlamentar($codigoParlamentar);
                                                                } else {

                                                                    $campos[$columnName] = $valueInternoParlamentar;
                                                                }
                                                            }
                                                        }

                                                        if (in_array($keyInternoParlamentar, $camposComMatriz)) {

                                                            $campos['DescricaoSituacao'] = 'Exercício';

                                                            if ($keyInternoParlamentar === 'Bloco') {

                                                                $tableInterna = 'tab_api_senado_bloco_senadores';
                                                                $modelInterna = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableInterna);

                                                                $idInterna = [];
                                                                $camposInterna = [];

                                                                foreach ($valueInternoParlamentar as $keyBloco => $valueBloco) {

                                                                    $getColumnTable = $this->getColumnTable($schema, $tableInterna, $keyBloco);

                                                                    if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                        $columnName = $getColumnTable->column_name;

                                                                        $ordinalPosition = $getColumnTable->ordinal_position;

                                                                        if ($ordinalPosition == 1) {

                                                                            $idInterna = [
                                                                                'CodigoBloco' => $valueBloco,
                                                                                'CodigoParlamentar' => $codigoParlamentar
                                                                            ];
                                                                        } else {

                                                                            $camposInterna[$columnName] = $valueBloco;
                                                                        }
                                                                    }
                                                                }

                                                                $this->atualizarOuCriarPorModeloDados($modelInterna, $idInterna, $camposInterna);
                                                            }
                                                        }
                                                    }

                                                    $this->atualizarOuCriarPorModeloDados($model, $id, $campos);

                                                    $this->cargosSenadores($codigoParlamentar);
                                                }

                                                if ($keyInterno === 'Mandato') {

                                                    $table = 'tab_api_senado_mandato_senadores';
                                                    $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                    $id = [];
                                                    $campos = [];

                                                    $camposComMatriz = ['PrimeiraLegislaturaDoMandato', 'SegundaLegislaturaDoMandato', 'Suplentes', 'Exercicios'];

                                                    foreach ($valueInterno as $keyInternoParlamentar => $valueInternoParlamentar) {

                                                        if (!is_array($valueInternoParlamentar) && !in_array($keyInternoParlamentar, $camposComMatriz)) {

                                                            $getColumnTable = $this->getColumnTable($schema, $table, $keyInternoParlamentar);

                                                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                $columnName = $getColumnTable->column_name;

                                                                $ordinalPosition = $getColumnTable->ordinal_position;

                                                                if ($ordinalPosition == 1) {

                                                                    $id[$columnName] = $valueInternoParlamentar;
                                                                } else {

                                                                    $campos[$columnName] = $valueInternoParlamentar;
                                                                }
                                                            }

                                                            $campos['CodigoParlamentar'] = $codigoParlamentar;
                                                        }

                                                        if (in_array($keyInternoParlamentar, $camposComMatriz)) {

                                                            if ($keyInternoParlamentar === 'PrimeiraLegislaturaDoMandato') {

                                                                $tableInterna = 'tab_api_senado_primeira_legislatura_mandato_senadores';
                                                                $modelInterna = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableInterna);

                                                                $idInterna = [];
                                                                $camposInterna = [];

                                                                foreach ($valueInternoParlamentar as $keyPrimeiroMandato => $valuePrimeiroMandato) {

                                                                    $getColumnTable = $this->getColumnTable($schema, $tableInterna, $keyPrimeiroMandato);

                                                                    if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                        $columnName = $getColumnTable->column_name;

                                                                        $ordinalPosition = $getColumnTable->ordinal_position;

                                                                        if ($ordinalPosition == 1) {

                                                                            $idInterna = [
                                                                                'NumeroLegislatura' => $valuePrimeiroMandato,
                                                                                'CodigoParlamentar' => $codigoParlamentar
                                                                            ];
                                                                        } else {

                                                                            $camposInterna[$columnName] = $valuePrimeiroMandato;
                                                                        }
                                                                    }
                                                                }

                                                                $this->atualizarOuCriarPorModeloDados($modelInterna, $idInterna, $camposInterna);
                                                            }

                                                            if ($keyInternoParlamentar === 'SegundaLegislaturaDoMandato') {

                                                                $tableInterna = 'tab_api_senado_segunda_legislatura_mandato_senadores';
                                                                $modelInterna = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableInterna);

                                                                $idInterna = [];
                                                                $camposInterna = [];

                                                                foreach ($valueInternoParlamentar as $keySegundoMandato => $valueSegundoMandato) {

                                                                    $getColumnTable = $this->getColumnTable($schema, $tableInterna, $keySegundoMandato);

                                                                    if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                        $columnName = $getColumnTable->column_name;

                                                                        $ordinalPosition = $getColumnTable->ordinal_position;

                                                                        if ($ordinalPosition == 1) {

                                                                            $idInterna = [
                                                                                'NumeroLegislatura' => $valueSegundoMandato,
                                                                                'CodigoParlamentar' => $codigoParlamentar
                                                                            ];
                                                                        } else {

                                                                            $camposInterna[$columnName] = $valueSegundoMandato;
                                                                        }
                                                                    }
                                                                }

                                                                $this->atualizarOuCriarPorModeloDados($modelInterna, $idInterna, $camposInterna);
                                                            }

                                                            if ($keyInternoParlamentar === 'Suplentes') {

                                                                foreach ($valueInternoParlamentar as $keyMatrizSuplentes => $valueMatrizSuplentes) {

                                                                    $tableInterna = 'tab_api_senado_suplentes_senadores';
                                                                    $modelInterna = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableInterna);

                                                                    $idInterna = [];
                                                                    $camposInterna = [];

                                                                    foreach ($valueMatrizSuplentes as $keySuplente => $valueSuplente) {

                                                                        foreach ($valueSuplente as $keySuplenteInterno => $valueSuplenteInterno) {

                                                                            $getColumnTable = $this->getColumnTable($schema, $tableInterna, $keySuplenteInterno);

                                                                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                                $columnName = $getColumnTable->column_name;

                                                                                $ordinalPosition = $getColumnTable->ordinal_position;

                                                                                if ($columnName === 'CodigoParlamentar') {

                                                                                    $idInterna['CodigoParlamentarSuplente'] = $valueSuplenteInterno;
                                                                                } else {

                                                                                    $camposInterna[$columnName] = $valueSuplenteInterno;
                                                                                }
                                                                            }

                                                                            $camposInterna['CodigoParlamentar'] = $codigoParlamentar;
                                                                        }

                                                                        $this->atualizarOuCriarPorModeloDados($modelInterna, $idInterna, $camposInterna);
                                                                    }
                                                                }
                                                            }

                                                            if ($keyInternoParlamentar === 'Exercicios') {

                                                                foreach ($valueInternoParlamentar as $keyMatrizExercicios => $valueMatrizExercicios) {

                                                                    $tableInterna = 'tab_api_senado_exercicio_senadores';
                                                                    $modelInterna = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableInterna);

                                                                    $idInterna = [];
                                                                    $camposInterna = [];

                                                                    foreach ($valueMatrizExercicios as $keyExercicio => $valueExercicio) {

                                                                        foreach ($valueExercicio as $keyExercicioInterno => $valueExercicioInterno) {

                                                                            $getColumnTable = $this->getColumnTable($schema, $tableInterna, $keyExercicioInterno);

                                                                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                                $columnName = $getColumnTable->column_name;

                                                                                $ordinalPosition = $getColumnTable->ordinal_position;

                                                                                if ($ordinalPosition == 1) {

                                                                                    $idInterna[$columnName] = $valueExercicioInterno;
                                                                                } else {

                                                                                    $camposInterna[$columnName] = $valueExercicioInterno;
                                                                                }
                                                                            }

                                                                            $camposInterna['CodigoParlamentar'] = $codigoParlamentar;
                                                                        }

                                                                        $this->atualizarOuCriarPorModeloDados($modelInterna, $idInterna, $camposInterna);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }

                                                    $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        return "Erro ao gravar, pois a matriz de dados está vazia.";
                    }

                    return "Gravação efetuada com sucesso!";
                }

                // Fim

                // return $gravar;
            } else {
                // O JSON não pôde ser decodificado
                return "Erro ao decodificar o JSON => " . $nomeProcedimento . ".";
            }
        } else {
            // Erro na requisição
            return "Erro na requisição da API => " . $nomeProcedimento . ".";
        }

        // Início do procedimento de limpeza dos dados da mesa diretora do Senado Federal
        DB::select("DELETE FROM tab_senado_mesa;");
        // Fim do procedimento de limpeza dos dados da mesa diretora do Senado Federal

        // Início da atualização dos dados da mesa diretora do Senado Federal
        $this->atualizarMesaSenado();
        // Fim da atualização dos dados da mesa diretora do Senado Federal

        throw new \Exception("Erro no procedimento => " . $nomeProcedimento . ".");
    }

    public function atualizarMesaSenado()
    {

        // Etapa de atualização da mesa diretora do Senado Federal

        $nomeProcedimento = 'Atualização da mesa diretora do Senado Federal';
        $schema = 'midr_gestao';
        $table = 'tab_senado_mesa';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $url = 'https://legis.senado.leg.br/dadosabertos/dados/MesaSenado.xml?_gl=1*1cjwe91*_ga*MTQxMTc1MDAyLjE2OTkyNzUwMjc.*_ga_CW3ZH25XMK*MTY5OTM3NDY5NC42LjEuMTY5OTM3NTM2OS4wLjAuMA..';

        $getApi = file_get_contents($url);

        if ($getApi) {

            $xml = simplexml_load_string($getApi);

            foreach ($xml as $key => $return) {

                if ($key === 'Colegiados') {

                    foreach ($return as $keyColegiados => $valueColegiados) {

                        foreach ($valueColegiados as $keyCargos => $valueCargos) {

                            if ($keyCargos === 'Cargos') {
                                foreach ($valueCargos as $keyCargo => $valueCargo) {

                                    $id = [];
                                    $campos = [];

                                    foreach ($valueCargo as $keyDetalhe => $valueDetalhe) {

                                        $conteudo = (array) $valueDetalhe;

                                        if ($keyDetalhe === 'Cargo') {
                                            $campos['Cargo'] = $conteudo[0];
                                        }

                                        if ($keyDetalhe === 'NomeParlamentar') {
                                            $campos['NomeParlamentar'] = $conteudo[0];
                                        }

                                        if ($keyDetalhe === 'Bancada') {
                                            $campos['Bancada'] = $conteudo[0];
                                        }

                                        if ($keyDetalhe === 'Http') {
                                            $campos['CodigoParlamentar'] = ($conteudo[0]) * 1;
                                        }

                                        if ($keyDetalhe === 'NumeroOrdemImpressao') {
                                            $campos['NumeroOrdemImpressao'] = ($conteudo[0]) * 1;
                                        }

                                        if ($keyDetalhe === 'Origem') {
                                            $campos['Origem'] = $conteudo[0];
                                        }
                                    }

                                    $this->atualizarOuCriarPorModeloDados($model, $id, $campos);

                                }

                            }


                        }

                    }

                }

            }

        }

        return 'Dados da mesa diretora do Senado Federal foram gravados com sucesso!';

    }

    public function salvarDadosBasicosParlamentar($codigoParlamentar = null)
    {

        $this->salvarFoto('Senado Federal', $codigoParlamentar);

        $url = 'https://legis.senado.leg.br/dadosabertos/senador/' . $codigoParlamentar . '.json';

        $getApi = file_get_contents($url);

        $camposComMatrizManterMesmaTabela = ['Parlamentares' => ['Parlamentar']];

        if ($getApi) {

            if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                $data = json_decode($getApi);

                // Verifica se o JSON foi decodificado com sucesso
                if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                    foreach ($data as $key => $return) {

                        if ($key === 'DetalheParlamentar') {

                            foreach ($return as $keyResult => $valueResult) {

                                if ($keyResult === 'Parlamentar') {

                                    foreach ($valueResult as $keyParlamentar => $valueParlamentar) {

                                        if ($keyParlamentar === 'Telefones') {

                                            foreach ($valueParlamentar as $keyTelefones => $valueTelefones) {

                                                foreach ($valueTelefones as $valueTelefone) {

                                                    $schema = 'midr_gestao';
                                                    $tableInterna = 'tab_api_senado_telefones_senadores';
                                                    $modelInterna = 'App\Models\\' . transformarNomeTabelaParaNomeModel($tableInterna);

                                                    $idInterna = [];
                                                    $camposInterna = [];

                                                    foreach ($valueTelefone as $keyMatrizTelefone => $valueMatrizTelefone) {

                                                        $getColumnTable = $this->getColumnTable($schema, $tableInterna, $keyMatrizTelefone);

                                                        if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                            $columnName = $getColumnTable->column_name;

                                                            $ordinalPosition = $getColumnTable->ordinal_position;

                                                            if ($ordinalPosition == 1) {

                                                                $idInterna[$columnName] = $valueMatrizTelefone;
                                                            } else {

                                                                $camposInterna[$columnName] = $valueMatrizTelefone;
                                                            }
                                                        }

                                                        $camposInterna['CodigoParlamentar'] = $codigoParlamentar;
                                                    }

                                                    $this->atualizarOuCriarPorModeloDados($modelInterna, $idInterna, $camposInterna);
                                                }
                                            }
                                        }

                                        if ($keyParlamentar === 'DadosBasicosParlamentar') {

                                            $schema = 'midr_gestao';
                                            $table = 'tab_api_senado_lista_atual_senadores';
                                            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                            $id = [];
                                            $campos = [];

                                            $id['CodigoParlamentar'] = $codigoParlamentar;

                                            foreach ($valueParlamentar as $keyDadosBasicosParlamentar => $valueDadosBasicosParlamentar) {

                                                $getColumnTable = $this->getColumnTable($schema, $table, $keyDadosBasicosParlamentar);

                                                if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                    $columnName = $getColumnTable->column_name;

                                                    $ordinalPosition = $getColumnTable->ordinal_position;

                                                    if ($ordinalPosition != 1) {

                                                        $campos[$columnName] = $valueDadosBasicosParlamentar;
                                                    }
                                                }
                                            }

                                            $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function salvarFoto($casa = null, $codigoParlamentar = null)
    {

        $client = new Client();

        if ($casa === 'Senado Federal') {
            // Caminho da pasta que deseja criar
            $directoryPath = 'public/fotos/senadores/';

            // Cria a pasta se ela não existir
            Storage::makeDirectory($directoryPath);

            // URL da imagem
            $imageUrl = 'http://www.senado.leg.br/senadores/img/fotos-oficiais/senador' . $codigoParlamentar . '.jpg';

            // Diretório de destino
            $destinationPath = $directoryPath;

            try {
                // Faz a requisição HTTP usando o Guzzle
                $response = $client->get($imageUrl);

                // Verifica se a requisição foi bem-sucedida
                if ($response->getStatusCode() === 200) {
                    // Obtém o conteúdo da imagem
                    $imageContent = $response->getBody()->getContents();

                    // Gera um nome de arquivo único
                    $fileName = $codigoParlamentar . '.jpg';

                    // Salva a imagem no diretório usando o Storage do Laravel
                    Storage::put($destinationPath . $fileName, $imageContent);
                }
            } catch (\Exception $e) {
                TabLogErros::create(array('mensagem' => 'Erro ao gravar a foto do parlamentar: ' . $e->getMessage()));
            }
        }

        if ($casa === 'Câmara dos Deputados') {
            // Caminho da pasta que deseja criar
            $directoryPath = 'public/fotos/deputados/';

            // Cria a pasta se ela não existir
            Storage::makeDirectory($directoryPath);

            // URL da imagem
            $imageUrl = 'https://www.camara.leg.br/internet/deputado/bandep/' . $codigoParlamentar . '.jpg';

            // Diretório de destino
            $destinationPath = $directoryPath;

            try {
                // Faz a requisição HTTP usando o Guzzle
                $response = $client->get($imageUrl);

                // Verifica se a requisição foi bem-sucedida
                if ($response->getStatusCode() === 200) {
                    // Obtém o conteúdo da imagem
                    $imageContent = $response->getBody()->getContents();

                    // Gera um nome de arquivo único
                    $fileName = $codigoParlamentar . '.jpg';

                    // Salva a imagem no diretório usando o Storage do Laravel
                    Storage::put($destinationPath . $fileName, $imageContent);
                }
            } catch (\Exception $e) {
                TabLogErros::create(array('mensagem' => 'Erro ao gravar a foto do parlamentar: ' . $e->getMessage()));
            }
        }
    }

    public function validarURLImagem($url)
    {
        // Inicialize o cliente Guzzle
        $client = new Client();

        try {
            // Faça uma solicitação HTTP GET para a URL
            $response = $client->get($url);

            // Obtenha o tipo de conteúdo (MIME type) da resposta
            $contentType = $response->getHeader('Content-Type')[0];

            // Verifique se o tipo de conteúdo corresponde a uma imagem
            if (strpos($contentType, 'image/') === 0) {
                return true; // A URL contém uma imagem
            } else {
                return false; // A URL não contém uma imagem
            }
        } catch (\Exception $e) {
            // Se ocorrer algum erro ao fazer a solicitação, a URL não é válida
            return false;
        }
    }

    public function validarAPI($url)
    {
        // Inicialize o cliente Guzzle
        $client = new Client();

        try {
            // Faça uma solicitação HTTP GET para a URL da API
            $response = $client->get($url);

            // Verifique se a resposta tem um código de status 2xx, indicando sucesso
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                // Você pode adicionar verificações adicionais aqui, dependendo das características da API
                // Por exemplo, você pode verificar o formato do JSON retornado ou a presença de campos obrigatórios
                $responseData = json_decode($response->getBody(), true);

                if ($responseData && isset($responseData['status']) && $responseData['status'] === 'success') {
                    return true; // A URL contém uma API válida
                }
            }

            return false; // A URL não contém uma API válida
        } catch (\Exception $e) {
            // Se ocorrer algum erro ao fazer a solicitação, a URL não é válida
            return false;
        }
    }

    public function listaColegiados()
    {

        // Etapa na atualização dos dados das comissões que cada senador participa

        $nomeProcedimento = 'API do senado federal - Lista de Colegiados ativos';
        $schema = 'midr_gestao';

        $url = 'https://legis.senado.leg.br/dadosabertos/dados/ListaColegiados.xml';

        $getApi = file_get_contents($url);

        if ($getApi) {

            if (verificarTipoRetornoApi($url) === 'XML') {

                $xml = simplexml_load_string($getApi);

                $json = json_encode($xml);
                $data = json_decode($json, true);

                // Verifica se o JSON foi decodificado com sucesso
                if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                    DB::select("DELETE FROM tab_api_senado_lista_colegiados_ativos;");

                    foreach ($data as $keyMembroComissaoParlamentar => $valueMembroComissaoParlamentar) {

                        if ($keyMembroComissaoParlamentar === 'Colegiados') {

                            foreach ($valueMembroComissaoParlamentar as $keyParlamentar => $valueParlamentar) {

                                if ($keyParlamentar === 'Colegiado') {

                                    foreach ($valueParlamentar as $keyMembroComissoes => $valueMembroComissoes) {

                                        $table = 'tab_api_senado_lista_colegiados_ativos';
                                        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                        $id = [];
                                        $campos = [];

                                        foreach ($valueMembroComissoes as $keyComissao => $valueComissao) {

                                            $getColumnTable = $this->getColumnTable($schema, $table, $keyComissao);

                                            if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                $columnName = $getColumnTable->column_name;

                                                $ordinalPosition = $getColumnTable->ordinal_position;

                                                if ($ordinalPosition == 1) {

                                                    $id = [
                                                        'Codigo' => $valueComissao
                                                    ];
                                                } else {

                                                    $campos[$columnName] = $valueComissao;
                                                }
                                            }

                                        }

                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return "API do senado federal - Lista de Colegiados ativos gravada com sucesso.";
    }

    public function comissoesSenadores()
    {

        DB::select("TRUNCATE table tab_api_senado_comissoes;");

        $tabApiSenadoListaAtualSenadores = $this->instanciarTabApiSenadoListaAtualSenadoresController();

        $getSenadoresEmExercicio = $tabApiSenadoListaAtualSenadores->getSenadoresEmExercicio();

        foreach ($getSenadoresEmExercicio as $value) {

            $codigoParlamentar = $value->CodigoParlamentar;

            // Etapa na atualização dos dados das comissões que cada senador participa

            $nomeProcedimento = 'API do senado federal - Lista as comissões por senador';
            $schema = 'midr_gestao';

            $url = 'https://legis.senado.leg.br/dadosabertos/senador/' . $codigoParlamentar . '/comissoes.json';

            $getApi = file_get_contents($url);

            if ($getApi) {

                if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                    $data = json_decode($getApi);

                    // Verifica se o JSON foi decodificado com sucesso
                    if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                        foreach ($data as $keyMembroComissaoParlamentar => $valueMembroComissaoParlamentar) {

                            if ($keyMembroComissaoParlamentar === 'MembroComissaoParlamentar') {

                                foreach ($valueMembroComissaoParlamentar as $keyParlamentar => $valueParlamentar) {

                                    if ($keyParlamentar === 'Parlamentar') {

                                        foreach ($valueParlamentar as $keyMembroComissoes => $valueMembroComissoes) {

                                            if ($keyMembroComissoes === 'MembroComissoes') {

                                                foreach ($valueMembroComissoes as $keyComissao => $valueComissao) {

                                                    if ($keyComissao === 'Comissao') {

                                                        foreach ($valueComissao as $valueMatrizIdentificacaoComissao) {

                                                            $table = 'tab_api_senado_comissoes';
                                                            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                            $id = [];
                                                            $campos = [];

                                                            foreach ($valueMatrizIdentificacaoComissao as $keyIdentificacaoComissao => $valueIdentificacaoComissao) {

                                                                if ($keyIdentificacaoComissao === 'IdentificacaoComissao') {

                                                                    foreach ($valueIdentificacaoComissao as $keyReturnIdentificacaoComissao => $valueReturnIdentificacaoComissao) {

                                                                        $getColumnTable = $this->getColumnTable($schema, $table, $keyReturnIdentificacaoComissao);

                                                                        if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                            $columnName = $getColumnTable->column_name;

                                                                            $ordinalPosition = $getColumnTable->ordinal_position;

                                                                            $campos['CodigoParlamentar'] = $codigoParlamentar;

                                                                            $campos[$columnName] = $valueReturnIdentificacaoComissao;
                                                                        }
                                                                    }
                                                                } else {

                                                                    $campos[$keyIdentificacaoComissao] = $valueIdentificacaoComissao;
                                                                }
                                                            }

                                                            $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

        }

    }

    public function liderancasSenadores()
    {

        DB::select("TRUNCATE table tab_api_senado_liderancas;");

        $tabApiSenadoListaAtualSenadores = $this->instanciarTabApiSenadoListaAtualSenadoresController();

        $getSenadoresEmExercicio = $tabApiSenadoListaAtualSenadores->getSenadoresEmExercicio();

        foreach ($getSenadoresEmExercicio as $value) {

            $codigoParlamentar = $value->CodigoParlamentar;

            // Etapa na atualização dos dados dos cargos que cada senador participa

            $nomeProcedimento = 'API do senado federal - Lista os cargos por senador';
            $schema = 'midr_gestao';

            $url = 'https://legis.senado.leg.br/dadosabertos/senador/' . $codigoParlamentar . '/liderancas.json';

            $getApi = file_get_contents($url);

            if ($getApi) {

                if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                    $data = json_decode($getApi);

                    // Verifica se o JSON foi decodificado com sucesso
                    if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                        foreach ($data as $keyMembroComissaoParlamentar => $valueMembroComissaoParlamentar) {

                            if ($keyMembroComissaoParlamentar === 'LiderancaParlamentar') {

                                foreach ($valueMembroComissaoParlamentar as $keyParlamentar => $valueParlamentar) {

                                    if ($keyParlamentar === 'Parlamentar') {

                                        foreach ($valueParlamentar as $keyMembroComissoes => $valueMembroComissoes) {

                                            if ($keyMembroComissoes === 'Liderancas') {

                                                foreach ($valueMembroComissoes as $keyComissao => $valueComissao) {

                                                    if ($keyComissao === 'Lideranca') {

                                                        foreach ($valueComissao as $valueMatrizIdentificacaoComissao) {

                                                            $table = 'tab_api_senado_liderancas';
                                                            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                            $id = [];
                                                            $campos = [];

                                                            foreach ($valueMatrizIdentificacaoComissao as $keyIdentificacaoComissao => $valueIdentificacaoComissao) {

                                                                if (getVariableType($valueIdentificacaoComissao) === 'object') {

                                                                    foreach ($valueIdentificacaoComissao as $keyReturnIdentificacaoComissao => $valueReturnIdentificacaoComissao) {

                                                                        $campos[$keyReturnIdentificacaoComissao] = $valueReturnIdentificacaoComissao;
                                                                    }
                                                                } else {
                                                                    $campos[$keyIdentificacaoComissao] = $valueIdentificacaoComissao;
                                                                }
                                                            }

                                                            $campos['CodigoParlamentar'] = $codigoParlamentar;

                                                            $this->atualizarOuCriarPorModeloDados($model, '', $campos);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function cargosSenadores($codigoParlamentar = null)
    {

        if (isset($codigoParlamentar) && !is_null($codigoParlamentar) && $codigoParlamentar != '') {

            // Etapa na atualização dos dados dos cargos que cada senador participa

            $nomeProcedimento = 'API do senado federal - Lista os cargos por senador';
            $schema = 'midr_gestao';

            $url = 'https://legis.senado.leg.br/dadosabertos/senador/' . $codigoParlamentar . '/cargos.json';

            $getApi = file_get_contents($url);

            if ($getApi) {

                if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                    $data = json_decode($getApi);

                    // Verifica se o JSON foi decodificado com sucesso
                    if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                        foreach ($data as $keyMembroComissaoParlamentar => $valueMembroComissaoParlamentar) {

                            if ($keyMembroComissaoParlamentar === 'CargoParlamentar') {

                                foreach ($valueMembroComissaoParlamentar as $keyParlamentar => $valueParlamentar) {

                                    if ($keyParlamentar === 'Parlamentar') {

                                        foreach ($valueParlamentar as $keyMembroComissoes => $valueMembroComissoes) {

                                            if ($keyMembroComissoes === 'Cargos') {

                                                foreach ($valueMembroComissoes as $keyComissao => $valueComissao) {

                                                    if ($keyComissao === 'Cargo') {

                                                        foreach ($valueComissao as $keyTeste => $valueMatrizIdentificacaoComissao) {

                                                            $table = 'tab_api_senado_cargos';
                                                            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                            $id = [];
                                                            $campos = [];
                                                            $codApiSenadoCargo = null;
                                                            $codigoComissao = null;
                                                            $dataInicio = null;

                                                            foreach ($valueMatrizIdentificacaoComissao as $keyIdentificacaoComissao => $valueIdentificacaoComissao) {

                                                                if ($keyIdentificacaoComissao === 'DataInicio') {
                                                                    $dataInicio = $valueIdentificacaoComissao;
                                                                }

                                                                $campos['CodigoParlamentar'] = $codigoParlamentar;

                                                                if ($keyIdentificacaoComissao === 'IdentificacaoComissao') {

                                                                    foreach ($valueIdentificacaoComissao as $keyReturnIdentificacaoComissao => $valueReturnIdentificacaoComissao) {

                                                                        if ($keyReturnIdentificacaoComissao === 'CodigoComissao') {
                                                                            $codigoComissao = $valueReturnIdentificacaoComissao;
                                                                        }

                                                                        $getColumnTable = $this->getColumnTable($schema, $table, $keyReturnIdentificacaoComissao);

                                                                        if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                                                                            $columnName = $getColumnTable->column_name;

                                                                            $ordinalPosition = $getColumnTable->ordinal_position;

                                                                            $campos[$columnName] = $valueReturnIdentificacaoComissao;
                                                                        }
                                                                    }
                                                                } else {

                                                                    $campos[$keyIdentificacaoComissao] = $valueIdentificacaoComissao;
                                                                }

                                                                $id['cod_api_senado_cargo'] = $codigoComissao . $codigoParlamentar . $dataInicio;
                                                            }

                                                            $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function criarPorModeloDados($model = null, $campos = [])
    {

        try {
            $model::create($campos);
            return true;
        } catch (Illuminate\Database\QueryException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }
    }

    protected function getColumnTable($schema = null, $table = null, $columnName = null)
    {

        return DB::selectOne("SELECT
            column_name,ordinal_position,is_nullable,data_type
            FROM
            information_schema.columns
            WHERE
            table_schema = '" . $schema . "'
            AND table_name = '" . $table . "'
            AND column_name = '" . $columnName . "';");
    }

    protected function getEstruturaTable($schema = null, $table = null)
    {

        return DB::select("SELECT
            column_name,ordinal_position,is_nullable,data_type
            FROM
            information_schema.columns
            WHERE
            table_schema = '" . $schema . "'
            AND table_name = '" . $table . "'
            AND column_name NOT IN ('created_at','updated_at','deleted_at');");
    }

    public function atualizarDadosCondensadosDeputadosESenadores()
    {

        $visDadosCondensadosDeputadosESenadores = $this->instanciarVisDadosCondensadosDeputadosESenadoresController();

        // Início da atualização da tab_parlamentares com o foco nos senadores
        $getSenadores = $visDadosCondensadosDeputadosESenadores->getSenadores();

        $schema = 'midr_gestao';
        $table = 'tab_parlamentares';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $getEstruturaTable = $this->getEstruturaTable($schema, $table);

        foreach ($getSenadores as $value) {

            $id = [];
            $valorId = '';
            $campos = [];

            foreach ($getEstruturaTable as $valueEstruturaTable) {
                $column_name = $valueEstruturaTable->column_name;
                $ordinal_position = $valueEstruturaTable->ordinal_position;

                if ($ordinal_position == 1) {

                    $id[$column_name] = $value->$column_name;
                    $valorId = $value->$column_name;
                } else {

                    $campos[$column_name] = $value->$column_name;
                }
            }

            $this->atualizarOuCriarPorModeloDados($model, $id, $campos);

        }
        // Fim da atualização da tab_parlamentares com o foco nos senadores

        // Início da atualização da tab_parlamentares com o foco nos deputados federais
        $getDeputadosFederais = $visDadosCondensadosDeputadosESenadores->getDeputadosFederais();

        $schema = 'midr_gestao';
        $table = 'tab_parlamentares';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $getEstruturaTable = $this->getEstruturaTable($schema, $table);

        foreach ($getDeputadosFederais as $value) {

            $id = [];
            $valorId = '';
            $campos = [];

            foreach ($getEstruturaTable as $valueEstruturaTable) {
                $column_name = $valueEstruturaTable->column_name;
                $ordinal_position = $valueEstruturaTable->ordinal_position;

                if ($ordinal_position == 1) {

                    $id[$column_name] = $value->$column_name;
                    $valorId = $value->$column_name;
                } else {

                    $campos[$column_name] = $value->$column_name;
                }
            }

            $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
        }
        // Fim da atualização da tab_parlamentares com o foco nos deputados federais

        return 'Dados dos Senadores foram atualizados com sucesso na tabela tab_parlamentares.';

    }

    protected function atualizarOuCriarPorModeloDados($model = null, $id = [], $campos = [])
    {

        $camposSemArray = true;

        foreach ($campos as $key => $value) {
            if (is_array($value)) {
                $camposSemArray = false;
            }
        }

        try {

            if ($camposSemArray) {

                $registro = null;

                if (isset($id) && !is_null($id) && $id != '' && is_array($id) && count($id) > 0) {

                    if (count($id) == 1) {

                        $nomeId = null;
                        $contId = 1;

                        foreach ($id as $key => $value) {

                            if ($contId == 1) {
                                $nomeId = $key;
                            }

                            $contId++;

                        }

                        $consulta = null;

                        if (array_key_exists($nomeId, $id)) {

                            $consulta = $model::find($id[$nomeId]);

                        }

                        // Use o método `getTable` para obter o nome da tabela
                        $tableName = (new $model())->getTable();

                        // Consulta SQL para obter o nome do schema
                        $query = "SELECT table_schema FROM information_schema.tables WHERE table_name =?";

                        // Execute a consulta SQL
                        $results = DB::select($query, [$tableName]);

                        // Verifique se há resultados e obtenha o nome do schema
                        if (!empty($results)) {
                            $schemaName = $results[0]->table_schema;
                        } else {
                            $schemaName = 'Nenhum schema encontrado'; // Trate o caso em que nenhum schema foi encontrado
                        }

                        // Início gravar auditoria
                        if ($consulta !== null) {

                            foreach ($campos as $key => $value) {

                                $dadoBase = null;

                                $dadoBase = $consulta->$key;

                                $column_name = null;
                                $data_type = null;

                                if ($schemaName != 'Nenhum schema encontrado') {

                                    $estruturaColumn = $this->getColumnTable($schemaName, $tableName, $key);

                                    $column_name = $estruturaColumn->column_name;
                                    $data_type = $estruturaColumn->data_type;
                                }

                                if ($data_type != 'timestamp with time zone' && $value != $dadoBase) {

                                    $dataHoraAtual = now();

                                    // Adiciona 8 horas
                                    $dataHoraAtual->addHours(8);

                                    $gravarAuditoria = new TabAudit;

                                    $gravarAuditoria->acao = 'Atualização automática';
                                    $gravarAuditoria->antes = $dadoBase;
                                    $gravarAuditoria->depois = $value;
                                    $gravarAuditoria->table = $tableName;
                                    $gravarAuditoria->column_name = $key;
                                    $gravarAuditoria->data_type = $data_type;

                                    if (is_array($id[$nomeId]) && count($id[$nomeId]) > 0) {
                                        $gravarAuditoria->table_id = $id[$nomeId][0];
                                    } else {
                                        $gravarAuditoria->table_id = $id[$nomeId];
                                    }

                                    $gravarAuditoria->ip = $_SERVER['REMOTE_ADDR'];
                                    $gravarAuditoria->dte_expired_at = $dataHoraAtual->format('Y-m-d H:i:s');

                                    $gravarAuditoria->save();
                                }
                            }

                        }
                        // Fim gravar auditoria

                    }

                    $model::updateOrCreate($id, $campos);

                    return true;
                } else {
                    $model::updateOrCreate($campos);

                    return true;
                }

            }

        } catch (Illuminate\Database\QueryException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }
    }

    public function getContentApi($apiUrl = null, $verify = true)
    {
        try {
            $client = new Client();
            $response = $client->get($apiUrl, [
                'verify' => $verify,
                // Ignorar verificação de SSL (não recomendado em produção)
            ]);

            /*
            Ignorar verificação de SSL (não recomendado em produção), mas foi necessário uma vez que o certificado
            da API foi detectado como Não seguro e embora os certificados do SERPRO tenham sido baixados e instalados,
            conforme esta orientação (https://certificados.serpro.gov.br/serprossl/ifr-certificate-chain),
            o problema da não valildade do certificado continuava. Segue detalhes colhidos do certificado inválido, em
            02/08/2023, por meio do equipamento 10.216.4.245:

            Emitido para:
            Nome comum (CN)	estruturaorganizacional.dados.gov.br
            O (Organização)	SERVICO FEDERAL DE PROCESSAMENTO DE DADOS
            Unidade organizacional (OU)	<Não faz parte do certificado>

            Emitido por:
            Nome comum (CN)	Autoridade Certificadora do SERPRO SSLv1
            O (Organização)	ICP-Brasil
            Unidade organizacional (OU)	Autoridade Certificadora Raiz Brasileira v10

            Período de validade:
            Emitido em	sexta-feira, 28 de julho de 2023 às 14:04:23
            Expira em	sábado, 27 de julho de 2024 às 14:04:23

            Assinaturas digitais:
            Assinatura digital SHA-256	2A 15 CA 84 B6 52 19 67 2F D2 0B E7 57 38 28 6B
            21 93 5C 8F AE 6B D7 A7 A8 A6 27 A0 10 F7 5A B7
            Assinatura digital SHA-1	9A 7E C9 E5 B4 FD D4 B4 CF 6D B0 F1 7C B5 BF ED
            37 F1 43 36

            Ao abrir a URL (https://estruturaorganizacional.dados.gov.br/doc/estrutura-organizacional/resumida.json)
            o navegador Google Chrome na Versão 114.0.5735.199 (Versão oficial) 64 bits exibia:
            Sua conexão não é particular
            */

            $data = json_decode($response->getBody(), true);

            return $data;
        } catch (RequestException $e) {
            // Lidar com o erro de conexão à API
            return 'Erro';
        }
    }

    public function readContentApiSiorgPai()
    {
        $apiUrl = 'https://estruturaorganizacional.dados.gov.br/doc/estrutura-organizacional/resumida.json?codigoPoder=1&codigoEsfera=1&codigoUnidade=308799&retornarOrgaoEntidadeVinculados=SIM';

        $data = $this->getContentApi($apiUrl, false);

        // Verifica se o JSON foi decodificado com sucesso
        if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

            foreach ($data as $key => $return) {

                if ($key === 'unidades') {

                    foreach ($return as $valueMatrizUnidades) {

                        $this->atualizarDadosSiorg($valueMatrizUnidades);
                    }
                }
            }
        }
    }

    public function readContentApiSiorgFilho($apiUrl = null)
    {
        $data = $this->getContentApi($apiUrl, false);

        // Verifica se o JSON foi decodificado com sucesso
        if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

            foreach ($data as $key => $return) {

                if ($key === 'unidades') {

                    foreach ($return as $valueMatrizUnidades) {

                        $this->atualizarDadosSiorg($valueMatrizUnidades);
                    }
                }
            }
        }
    }

    public function atualizarDadosSiorg($valueMatrizUnidades = [])
    {

        if (isset($valueMatrizUnidades) && !is_null($valueMatrizUnidades) && count($valueMatrizUnidades) > 0) {
            $nomeProcedimento = 'Leitura da API - SIORG para gravação em banco de dados local';
            $schema = 'midr_organizacao';
            $table = 'tab_organizacao';
            $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

            $camposParaTratamentoTexto = [
                'codigoUnidade' => 'https://estruturaorganizacional.dados.gov.br/id/unidade-organizacional/',
                'codigoUnidadePai' => 'https://estruturaorganizacional.dados.gov.br/id/unidade-organizacional/',
                'codigoOrgaoEntidade' => 'https://estruturaorganizacional.dados.gov.br/id/unidade-organizacional/',
                'codigoTipoUnidade' => 'https://estruturaorganizacional.dados.gov.br/id/tipo-unidade/',
                'codigoEsfera' => 'https://estruturaorganizacional.dados.gov.br/id/esfera/',
                'codigoPoder' => 'https://estruturaorganizacional.dados.gov.br/id/poder/',
                'codigoNaturezaJuridica' => 'https://estruturaorganizacional.dados.gov.br/id/natureza-juridica/',
                'codigoCategoriaUnidade' => 'https://estruturaorganizacional.dados.gov.br/id/categoria-unidade/',
            ];

            foreach ($valueMatrizUnidades as $keyUnidade => $valueUnidade) {

                if (array_key_exists($keyUnidade, $camposParaTratamentoTexto)) {

                    $valueUnidade = retornaTextoTirandoParteDoTexto($valueUnidade, $camposParaTratamentoTexto[$keyUnidade]);

                    if ($keyUnidade === 'codigoUnidade') {

                        $this->readContentApiSiorgFilho('https://estruturaorganizacional.dados.gov.br/doc/estrutura-organizacional/' . $valueUnidade . '/filha.json');
                    }
                }

                if ($valueUnidade === 'CODEVASF') {

                    $valueUnidade = passarTextoParaMaiusculo($valueUnidade);
                }

                if ($keyUnidade === '') {
                    dd($valueMatrizUnidades, $keyUnidade, $valueUnidade, $valueUnidade);
                }

                $getColumnTable = $this->getColumnTable($schema, $table, $keyUnidade);

                if (isset($getColumnTable) && !is_null($getColumnTable) && $getColumnTable != '') {

                    $columnName = $getColumnTable->column_name;

                    $ordinalPosition = $getColumnTable->ordinal_position;

                    if ($ordinalPosition == 1) {

                        $id[$columnName] = $valueUnidade;
                    } else {

                        $campos[$columnName] = $valueUnidade;
                    }
                }
            }

            $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
        }
    }

    public function atualizacaoApiIbge()
    {
        // Dados e indicadores:
        /*
        96385 => População no último censo
        96386 => Densidade demográfica
        97964 => Número de municípios
        30255 => IDH    => OK
        28141 => Receitas orçamentárias realizadas
        29749 => Despesas orçamentárias empenhadas
        47001 => PIB per capita
        48986 => Rendimento nominal mensal domiciliar per capita
        62876 => Governador

        29170 => Prefeito
        */

        $tabIndicadoresEstados = $this->instanciarTabIndicadoresEstadosController();
        $tabIbge = $this->instanciarTabIbgeController();

        // Início da 1ª Etapa de atualização
        // Atualização de indicadores por município

        $municipios = $tabIbge->getCodIbgeMunicipios();

        foreach ($municipios as $municipio) {

            $nomeProcedimento = 'API do IBGE';
            $schema = 'midr_gestao';

            $url = 'https://servicodados.ibge.gov.br/api/v1/pesquisas/indicadores/96385|96386|97964|30255|28141|29749|47001|62876/resultados/' . $municipio->cod_ibge;

            $getApi = file_get_contents($url);

            if ($getApi) {

                if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                    $data = json_decode($getApi);

                    // Verifica se o JSON foi decodificado com sucesso
                    if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                        $nomeIndicador = null;

                        foreach ($data as $valueIbge) {

                            // Início da parte da População no último censo
                            if ($valueIbge->id === 96385) {

                                $nomeIndicador = 'População no último censo';

                                if ($nomeIndicador === 'População no último censo') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_populacao';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $municipio->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_populacao'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte da População no último censo
                            // -- x -- x -- x -- x --

                            // Início da parte da Densidade demográfica
                            if ($valueIbge->id === 96386) {

                                $nomeIndicador = 'Densidade demográfica';

                                if ($nomeIndicador === 'Densidade demográfica') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_densidade_demografica';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $municipio->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_densidade_demografica'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte da Densidade demográfica
                            // -- x -- x -- x -- x --

                            // Início da parte do PIB per capita
                            if ($valueIbge->id === 47001) {

                                $nomeIndicador = 'PIB per capita';

                                if ($nomeIndicador === 'PIB per capita') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_pib_per_capita';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $municipio->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_pib_per_capita'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte do Rendimento nominal mensal domiciliar per capita
                            // -- x -- x -- x -- x --

                            // Início da parte das Receitas orçamentárias realizadas
                            if ($valueIbge->id === 28141) {

                                $nomeIndicador = 'Receitas orçamentárias realizadas';

                                if ($nomeIndicador === 'Receitas orçamentárias realizadas') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_receitas_despesas_orcamentarias_realizadas';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $municipio->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_receita_orcamentaria_realizada'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte das Receitas orçamentárias realizadas
                            // -- x -- x -- x -- x --

                            // Início da parte das Despesas orçamentárias realizadas
                            if ($valueIbge->id === 29749) {

                                $nomeIndicador = 'Despesas orçamentárias empenhadas';

                                if ($nomeIndicador === 'Despesas orçamentárias empenhadas') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_receitas_despesas_orcamentarias_realizadas';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $municipio->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_despesa_orcamentaria_empenhada'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte das Despesas orçamentárias realizadas
                            // -- x -- x -- x -- x --

                            // Início da parte do IDH
                            if ($valueIbge->id === 30255) {

                                $nomeIndicador = 'IDH';

                                if ($nomeIndicador === 'IDH') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_idh';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $municipio->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_idh'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte do IDH
                            // -- x -- x -- x -- x --

                        }

                    }

                }

            }

        }

        return 'Dados e indicadores oriundos do IBGE foram atualizados com sucesso para os municípios.';

        // Fim da 1ª Etapa de atualização

        // Início da 2ª Etapa de atualização
        // Atualização de indicadores por estado

        $estados = $tabIndicadoresEstados->getCodIbgeEstados();

        foreach ($estados as $estado) {

            $nomeProcedimento = 'API do IBGE';
            $schema = 'midr_gestao';

            $url = 'https://servicodados.ibge.gov.br/api/v1/pesquisas/indicadores/96385|96386|97964|30255|28141|29749|48986|62876/resultados/' . $estado->cod_ibge;

            $getApi = file_get_contents($url);

            if ($getApi) {

                if (verificarTipoRetornoApi($url) === 'JSON' || verificarTipoRetornoApi($url) === 'desconhecido') {

                    $data = json_decode($getApi);

                    // Verifica se o JSON foi decodificado com sucesso
                    if (isset($data) && !is_null($data) && $data != '' && $data !== null) {

                        $nomeIndicador = null;

                        foreach ($data as $valueIbge) {

                            // Início da parte da População no último censo
                            if ($valueIbge->id === 96385) {

                                $nomeIndicador = 'População no último censo';

                                if ($nomeIndicador === 'População no último censo') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_populacao';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $estado->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_populacao'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte da População no último censo
                            // -- x -- x -- x -- x --

                            // Início da parte da Densidade demográfica
                            if ($valueIbge->id === 96386) {

                                $nomeIndicador = 'Densidade demográfica';

                                if ($nomeIndicador === 'Densidade demográfica') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_densidade_demografica';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $estado->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_densidade_demografica'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte da Densidade demográfica
                            // -- x -- x -- x -- x --

                            // Início da parte do Rendimento nominal mensal domiciliar per capita
                            if ($valueIbge->id === 48986) {

                                $nomeIndicador = 'Rendimento nominal mensal domiciliar per capita';

                                if ($nomeIndicador === 'Rendimento nominal mensal domiciliar per capita') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_rendimento_nominal_mensal_domiciliar_per_capita';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $estado->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_rnmdpc'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte do Rendimento nominal mensal domiciliar per capita
                            // -- x -- x -- x -- x --

                            // Início da parte das Receitas orçamentárias realizadas
                            if ($valueIbge->id === 28141) {

                                $nomeIndicador = 'Receitas orçamentárias realizadas';

                                if ($nomeIndicador === 'Receitas orçamentárias realizadas') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_receitas_despesas_orcamentarias_realizadas';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $estado->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_receita_orcamentaria_realizada'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte das Receitas orçamentárias realizadas
                            // -- x -- x -- x -- x --

                            // Início da parte das Despesas orçamentárias realizadas
                            if ($valueIbge->id === 29749) {

                                $nomeIndicador = 'Despesas orçamentárias empenhadas';

                                if ($nomeIndicador === 'Despesas orçamentárias empenhadas') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_receitas_despesas_orcamentarias_realizadas';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $estado->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_despesa_orcamentaria_empenhada'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte das Despesas orçamentárias realizadas
                            // -- x -- x -- x -- x --

                            // Início da parte do IDH
                            if ($valueIbge->id === 30255) {

                                $nomeIndicador = 'IDH';

                                if ($nomeIndicador === 'IDH') {

                                    foreach ($valueIbge->res as $valueRes) {

                                        foreach ($valueRes as $keyDetalhe => $valueDetalhe) {

                                            if ($keyDetalhe === 'res') {

                                                $schema = 'midr_gestao';
                                                $table = 'tab_api_ibge_idh';
                                                $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                                                $id = [];
                                                $campos = [];

                                                $contAnos = 0;

                                                $totalElementos = [];

                                                $totalElementos = transformarJsonParaArray($valueDetalhe);

                                                $contAnos = 0;
                                                foreach ($valueDetalhe as $key => $value) {

                                                    $contAnos++;

                                                    if ($contAnos === count($totalElementos)) {
                                                        $id['cod_ibge'] = $estado->cod_ibge;
                                                        $campos['num_ano'] = $key;
                                                        $campos['vlr_idh'] = $value;
                                                        $this->atualizarOuCriarPorModeloDados($model, $id, $campos);
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }
                            // Fim da parte do IDH
                            // -- x -- x -- x -- x --

                        }

                    }

                }

            }

        }

        return 'Dados e indicadores oriundos do IBGE foram atualizados com sucesso para os estados.';

        // Fim da 2ª Etapa de atualização

    }

    public function downloadExportDadosParlamentaresFederais()
    {
        $fileName = 'base_parlamentares_federais' . '.xlsx';
        $directory = 'public/export/parlamentar';

        // Verifica se o diretório existe; caso contrário, cria-o
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        // Define o caminho completo onde o arquivo será salvo
        $path = $directory . '/' . $fileName;

        // Salva o arquivo no storage (no diretório `storage/app/public/export/parlamentar`)
        Excel::store(new BaseParlamentaresFederaisExport(), $path, 'local');

        // Retorna uma mensagem de sucesso ou registra em log se preferir
        return "Arquivo exportado com sucesso para {$path}.";
    }

}
