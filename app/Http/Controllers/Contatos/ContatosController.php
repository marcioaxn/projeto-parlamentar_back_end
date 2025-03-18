<?php

namespace App\Http\Controllers\Contatos;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Contatos\Contatos as Contato;
use Illuminate\Support\Facades\Validator;
use Exception;

class ContatosController extends Controller
{
    /**
     * Lista todos os contatos
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Obtém os dados de um contato específico
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obter(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_contato' => 'required|integer'
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

    /**
     * Salva um novo contato
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salvar(Request $request)
    {
        try {
            $validator = $this->validarDadosContato($request);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contato = new Contato();
            $this->preencherDadosContato($contato, $request);
            $contato->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Contato salvo com sucesso',
                'data' => $contato
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao salvar contato: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza um contato existente
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function atualizar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_contato' => 'required|integer'
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

    /**
     * Exclui um contato
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function excluir(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_contato' => 'required|integer'
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

    /**
     * Valida os dados do contato
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validarDadosContato(Request $request)
    {
        $regras = [
            'dsc_tipo_contato' => 'required|in:prefeitura,camara_municipal,orgao_publico,eleitor',
            'txt_nome' => 'required|string|max:255',
            'num_telefone' => 'required|string|max:20',
            'dsc_email' => 'required|email|max:255',
            'num_cep' => 'required|string|max:10',
            'dsc_logradouro' => 'required|string|max:255',
            'dsc_bairro' => 'required|string|max:255',
            'dsc_cidade' => 'required|string|max:255',
            'dsc_estado' => 'required|string|max:2',
            'txt_observacoes' => 'nullable|string'
        ];

        // Adicionar validações específicas por tipo de contato
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

        return Validator::make($request->all(), $regras);
    }

    /**
     * Preenche os dados do contato
     *
     * @param  \App\Models\Contato  $contato
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function preencherDadosContato(Contato $contato, Request $request)
    {
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

        // Campos específicos por tipo
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
}
