<?php

namespace App\Http\Controllers\Contatos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contatos\Contatos as Contato;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Storage;

class ContatosController extends Controller
{
    public function listar()
    {
        try {
            $contatos = Contato::all();
            return response()->json([
                'status' => 'success',
                'data' => $contatos
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao listar contatos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obter(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_contato' => 'required|uuid'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Código do contato inválido',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contato = Contato::find($request->cod_contato);

            if (!$contato) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contato não encontrado'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $contato
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao obter contato: ' . $e->getMessage()
            ], 500);
        }
    }

    public function salvar(Request $request)
    {
        $input = $request->all();
        $fazerJsonTotaisBrasil = json_encode($input);
        Storage::put('a.json', $fazerJsonTotaisBrasil);

        try {
            $codParlamentar = session('cod_parlamentar');
            \Log::info('Valor de cod_parlamentar na sessão: ' . $codParlamentar);
            if (!$codParlamentar) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Código do parlamentar não encontrado na sessão.',
                    'errors' => ['cod_parlamentar' => ['O código do parlamentar é obrigatório.']]
                ], 422);
            }

            $validator = $this->validarDadosContato($request, $codParlamentar);
            \Log::info('Validação realizada. Erros: ' . json_encode($validator->errors()));

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contato = new Contato();
            $this->preencherDadosContato($contato, $request, $codParlamentar);
            \Log::info('Dados preenchidos para salvamento: ' . json_encode($contato->toArray()));
            $contato->cod_contato = \Illuminate\Support\Str::uuid();
            $contato->save(['timestamps' => false]);

            return response()->json([
                'status' => 'success',
                'message' => 'Contato salvo com sucesso',
                'data' => $contato->toArray()
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (Exception $e) {
            \Log::error('Erro ao salvar contato: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao salvar contato: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validarDadosContato(Request $request, $codParlamentar = null)
    {
        $regras = [
            'dsc_tipo_contato' => 'required|in:prefeitura,camara_municipal,orgao_publico,eleitor',
            'txt_nome' => 'required|string|max:255',
            'num_telefone' => 'required|string|max:20',
            'dsc_email' => 'required|email|max:255',
            'num_cep' => 'required|string|regex:/^\d{5}-\d{3}$/',
            'dsc_logradouro' => 'required|string|max:255',
            'dsc_bairro' => 'required|string|max:100',
            'dsc_cidade' => 'required|string|max:100',
            'dsc_estado' => 'required|string|max:2',
            'txt_observacoes' => 'nullable|string'
        ];

        switch ($request->dsc_tipo_contato) {
            case 'prefeitura':
                $regras['dsc_prefeitura'] = 'required|string|max:255';
                break;
            case 'camara_municipal':
                $regras['dsc_camara_municipal'] = 'required|string|max:255';
                break;
            case 'orgao_publico':
                $regras['dsc_orgao_publico'] = 'required|string|max:255';
                break;
            case 'eleitor':
                $regras['dsc_identificador_eleitor'] = 'required|string|max:255';
                break;
        }

        $dados = $request->all();
        if ($codParlamentar) {
            $dados['cod_parlamentar'] = $codParlamentar;
            $regras['cod_parlamentar'] = 'required|integer|exists:tab_parlamentares,cod_parlamentar';
        }

        $validator = Validator::make($dados, $regras);
        \Log::info('Validação realizada. Erros: ' . json_encode($validator->errors()));
        return $validator;
    }

    private function preencherDadosContato(Contato $contato, Request $request, $codParlamentar = null)
    {
        // Só definir cod_parlamentar se ele ainda não estiver definido (ou seja, na criação)
        if (is_null($contato->cod_parlamentar)) {
            $contato->cod_parlamentar = $codParlamentar ?? $request->cod_parlamentar;
        }

        $contato->dsc_tipo_contato = $request->dsc_tipo_contato;
        $contato->txt_nome = $request->txt_nome;
        $contato->num_telefone = $request->num_telefone;
        $contato->dsc_email = $request->dsc_email;
        $contato->num_cep = $request->num_cep;
        $contato->dsc_logradouro = $request->dsc_logradouro;
        $contato->dsc_bairro = $request->dsc_bairro;
        $contato->dsc_cidade = $request->dsc_cidade;
        $contato->dsc_estado = $request->dsc_estado;
        $contato->txt_observacoes = $request->txt_observacoes;

        switch ($request->dsc_tipo_contato) {
            case 'prefeitura':
                $contato->dsc_prefeitura = $request->dsc_prefeitura;
                break;
            case 'camara_municipal':
                $contato->dsc_camara_municipal = $request->dsc_camara_municipal;
                break;
            case 'orgao_publico':
                $contato->dsc_orgao_publico = $request->dsc_orgao_publico;
                break;
            case 'eleitor':
                $contato->dsc_identificador_eleitor = $request->dsc_identificador_eleitor;
                break;
        }
    }

    public function atualizar(Request $request)
    {
        $input = $request->all();
        $fazerJsonTotaisBrasil = json_encode($input);
        Storage::put('a.json', $fazerJsonTotaisBrasil);

        try {
            $validator = Validator::make($request->all(), [
                'cod_contato' => 'required|uuid'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Código do contato inválido',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contato = Contato::find($request->cod_contato);

            if (!$contato) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contato não encontrado'
                ], 404);
            }

            $validatorDados = $this->validarDadosContato($request);

            if ($validatorDados->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro de validação',
                    'errors' => $validatorDados->errors()
                ], 422);
            }

            $this->preencherDadosContato($contato, $request);
            $contato->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Contato atualizado com sucesso',
                'data' => $contato
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar contato: ' . $e->getMessage()
            ], 500);
        }
    }

    public function excluir(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_contato' => 'required|uuid'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Código do contato inválido',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contato = Contato::find($request->cod_contato);

            if (!$contato) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contato não encontrado'
                ], 404);
            }

            $contato->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Contato excluído com sucesso'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao excluir contato: ' . $e->getMessage()
            ], 500);
        }
    }
}