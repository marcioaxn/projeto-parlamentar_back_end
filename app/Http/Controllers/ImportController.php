<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use ZipArchive;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\QueryException;

ini_set('memory_limit', '5096M');
ini_set('max_execution_time', 5500);
set_time_limit(900000000);

class ImportController extends Controller
{

    /**
     * Realiza o download de um arquivo ZIP da Caixa Econômica Federal e o armazena no storage.
     *
     * Este método calcula a data do arquivo a ser baixado de acordo com a regra:
     * - Se hoje não for segunda-feira (date('N') diferente de 1), a data será o dia anterior.
     * - Se hoje for segunda-feira, a data será a última sexta-feira (três dias antes).
     *
     * Em seguida, constrói a URL do arquivo baseado na data calculada e faz o download
     * utilizando a biblioteca GuzzleHttp. O arquivo é salvo no diretório especificado no storage.
     *
     * @return \Illuminate\Http\JsonResponse Retorna uma resposta JSON com a mensagem de sucesso ou erro.
     */
    public function downloadCaixaZip()
    {
        // Calcula a data para o nome do arquivo
        $numeroRepresentanteDia = date('N');
        $quantidadeDiasAMenos = ($numeroRepresentanteDia != '1') ? 1 : 3;
        $date = date('d_m_Y', strtotime("-{$quantidadeDiasAMenos} day"));

        // URL base e prefixo do nome do arquivo
        $baseUrl = 'https://www.caixa.gov.br/Downloads/Orcamento-Geral-da-Uniao-Base-de-Dados/';
        $prefix = 'BD_Gestores_';
        $fileName = "{$prefix}{$date}.zip";
        $url = $baseUrl . $fileName;

        // Diretório onde o arquivo será salvo no storage
        $directory = 'cef/zip/';
        $filePath = $directory . 'caixa.zip';

        // Verifica se o diretório existe, se não, cria
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $client = new Client();
        try {
            // Verifica se a URL do arquivo é válida
            $response = $client->head($url);
            if ($response->getStatusCode() !== 200) {
                return response()->json(['message' => "URL não encontrada para download do arquivo {$fileName}."]);
            }

            // Faz o download do arquivo e salva no storage
            $response = $client->get($url);
            if (Storage::put($filePath, $response->getBody()->getContents())) {
                return response()->json(['message' => 'Download do arquivo ' . $fileName . ' feito com sucesso.']);
            }

            return response()->json(['message' => 'Falha ao baixar o arquivo.']);
        } catch (RequestException $e) {
            // Lida com exceções de requisição HTTP
            return response()->json(['message' => 'Erro ao tentar acessar a URL (' . $url . ') para download do arquivo ' . $fileName . '. Erros: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            // Lida com outras exceções
            return response()->json(['message' => 'Ocorreram os seguintes erros: ' . $e->getMessage()]);
        }
    }

}
