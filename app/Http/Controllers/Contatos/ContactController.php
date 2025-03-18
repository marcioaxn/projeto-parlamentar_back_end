<?php

namespace App\Http\Controllers\Contatos;

use App\Http\Controllers\Controller;

use App\Models\Contatos\Contatos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Exception;

class ContactController extends Controller
{
    /**
     * Exibe a view inicial para gerenciamento de contatos.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        try {
            return view('contatos.index');
        } catch (Exception $e) {
            Log::error('Erro ao renderizar a view de contatos: ' . $e->getMessage());
            return response()->json(['message' => 'Erro interno ao carregar a página. Tente novamente.'], 500);
        }
    }

    /**
     * Busca e retorna os contatos para a tabela via Ajax, com paginação e filtros.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Contatos::query();

            if ($request->has('dsc_tipo_contato') && $request->dsc_tipo_contato) {
                $query->where('dsc_tipo_contato', $request->dsc_tipo_contato);
            }
            if ($request->has('txt_nome') && $request->txt_nome) {
                $query->where('txt_nome', 'ilike', '%' . $request->txt_nome . '%');
            }

            $contatos = $query->paginate(10);

            return response()->json([
                'data' => $contatos->items(),
                'total' => $contatos->total(),
                'per_page' => $contatos->perPage(),
                'current_page' => $contatos->currentPage(),
                'last_page' => $contatos->lastPage(),
            ]);
        } catch (Exception $e) {
            Log::error('Erro ao buscar contatos: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao carregar os contatos. Tente novamente.'], 500);
        }
    }

    /**
     * Cria um novo contato usando dados da requisição e o cod_parlamentar da sessão.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Debug: Logar os dados da requisição para verificar o que está sendo enviado
            Log::debug('Dados recebidos no store:', $request->all());

            $cod_parlamentar = Session::get('cod_parlamentar');
            if (!$cod_parlamentar) {
                Log::error('Parlamentar não identificado na sessão');
                throw new Exception('Parlamentar não identificado na sessão');
            }

            Log::info('Iniciando criação de contato. Parlamentar: ' . $cod_parlamentar);

            // Preparar os dados para validação, ajustando o num_cep
            $data = $request->all();
            if (isset($data['num_cep'])) {
                // Remover traço ou outros caracteres não numéricos do CEP
                $data['num_cep'] = preg_replace('/\D/', '', $data['num_cep']);
            }

            // Validação dos dados recebidos com mensagens personalizadas
            $validated = $request->validate([
                'dsc_tipo_contato' => 'required|in:prefeitura,camara_municipal,orgao_publico,eleitor',
                'txt_nome' => 'required|string|max:255',
                'num_telefone' => 'nullable|string|max:20',
                'dsc_email' => 'nullable|email|max:255',
                'dsc_logradouro' => 'nullable|string|max:255',
                'dsc_bairro' => 'nullable|string|max:100',
                'dsc_cidade' => 'nullable|string|max:100',
                'dsc_estado' => 'nullable|string|size:2|regex:/^[A-Z]{2}$/',
                'txt_observacoes' => 'nullable|string|max:1000',
                'dsc_prefeitura' => 'nullable|string|max:255|required_if:dsc_tipo_contato,prefeitura',
                'dsc_camara_municipal' => 'nullable|string|max:255|required_if:dsc_tipo_contato,camara_municipal',
                'dsc_orgao_publico' => 'nullable|string|max:255|required_if:dsc_tipo_contato,orgao_publico',
                'dsc_identificador_eleitor' => 'nullable|string|max:255|required_if:dsc_tipo_contato,eleitor',
            ], [
                'dsc_tipo_contato.required' => 'O tipo de contato é obrigatório.',
                'dsc_tipo_contato.in' => 'O tipo de contato deve ser "prefeitura", "camara_municipal", "orgao_publico" ou "eleitor".',
                'txt_nome.required' => 'O nome é obrigatório.',
                'txt_nome.max' => 'O nome não pode ter mais de 255 caracteres.',
                'num_telefone.max' => 'O telefone não pode ter mais de 20 caracteres.',
                'dsc_email.email' => 'O email deve ser válido.',
                'dsc_email.max' => 'O email não pode ter mais de 255 caracteres.',
                'dsc_logradouro.max' => 'O logradouro não pode ter mais de 255 caracteres.',
                'dsc_bairro.max' => 'O bairro não pode ter mais de 100 caracteres.',
                'dsc_cidade.max' => 'A cidade não pode ter mais de 100 caracteres.',
                'dsc_estado.size' => 'O estado deve ser uma sigla de 2 letras (ex.: DF, SP).',
                'dsc_estado.regex' => 'O estado deve conter apenas letras maiúsculas (ex.: DF, SP).',
                'txt_observacoes.max' => 'As observações não podem ter mais de 1000 caracteres.',
                'dsc_prefeitura.required_if' => 'O nome da prefeitura é obrigatório quando o tipo é "prefeitura".',
                'dsc_camara_municipal.required_if' => 'O nome da câmara municipal é obrigatório quando o tipo é "câmara municipal".',
                'dsc_orgao_publico.required_if' => 'O nome do órgão público é obrigatório quando o tipo é "orgão público".',
                'dsc_identificador_eleitor.required_if' => 'O identificador do eleitor é obrigatório quando o tipo é "eleitor".',
            ]);

            // Debug: Logar os dados validados antes de criar
            Log::debug('Dados validados para criação:', $validated);

            // Adicionar cod_parlamentar aos dados validados
            $validated['cod_parlamentar'] = $cod_parlamentar;

            // Criar o contato
            $contato = Contatos::create($validated);

            Log::info('Contato criado com sucesso. ID: ' . $contato->cod_contato);

            return response()->json(['message' => 'Contato criado com sucesso', 'data' => $contato], 201);
        } catch (ValidationException $e) {
            // Logar detalhes específicos para depuração
            Log::error('Erro de validação ao criar contato: ' . $e->getMessage(), [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json(['message' => 'Erro de validação: ' . json_encode($e->errors())], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Erro de banco de dados ao criar contato: ' . $e->getMessage(), ['sql' => $e->getSql(), 'bindings' => $e->getBindings()]);
            return response()->json(['message' => 'Erro ao salvar o contato. Verifique os dados e tente novamente.'], 500);
        } catch (Exception $e) {
            Log::error('Erro geral ao criar contato: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Erro ao salvar o contato. Tente novamente.'], 500);
        }
    }

    /**
     * Exibe os detalhes de um contato específico para edição via Ajax.
     *
     * @param string $cod_contato
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($cod_contato)
    {
        try {
            $contato = Contatos::findOrFail($cod_contato);
            return response()->json($contato);
        } catch (ModelNotFoundException $e) {
            Log::error('Contato não encontrado: ' . $e->getMessage());
            return response()->json(['message' => 'Contato não encontrado.'], 404);
        } catch (Exception $e) {
            Log::error('Erro ao buscar contato: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao carregar o contato. Tente novamente.'], 500);
        }
    }

    /**
     * Atualiza um contato existente com base nos dados fornecidos.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $cod_contato
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $cod_contato)
    {
        try {
            $contato = Contatos::findOrFail($cod_contato);

            $validated = $request->validate([
                'dsc_tipo_contato' => 'required|in:prefeitura,camara_municipal,orgao_publico,eleitor',
                'txt_nome' => 'required|string|max:255',
                'num_telefone' => 'nullable|string|max:20',
                'dsc_email' => 'nullable|email|max:255',
                'num_cep' => 'nullable|string|size:8',
                'dsc_logradouro' => 'nullable|string|max:255',
                'dsc_bairro' => 'nullable|string|max:100',
                'dsc_cidade' => 'nullable|string|max:100',
                'dsc_estado' => 'nullable|string|size:2',
                'txt_observacoes' => 'nullable|string|max:1000',
                'dsc_prefeitura' => 'nullable|string|max:255|required_if:dsc_tipo_contato,prefeitura',
                'dsc_camara_municipal' => 'nullable|string|max:255|required_if:dsc_tipo_contato,camara_municipal',
                'dsc_orgao_publico' => 'nullable|string|max:255|required_if:dsc_tipo_contato,orgao_publico',
                'dsc_identificador_eleitor' => 'nullable|string|max:255|required_if:dsc_tipo_contato,eleitor',
            ], [
                'dsc_tipo_contato.required' => 'O tipo de contato é obrigatório.',
                'txt_nome.required' => 'O nome é obrigatório.',
                'dsc_email.email' => 'O email deve ser válido.',
                'num_cep.size' => 'O CEP deve conter 8 dígitos.',
                'dsc_estado.size' => 'O estado deve ser uma sigla de 2 letras (ex.: SP).',
            ]);

            $contato->update($validated);

            Log::info('Contato atualizado com sucesso. ID: ' . $contato->cod_contato);

            return response()->json(['message' => 'Contato atualizado com sucesso', 'data' => $contato]);
        } catch (ValidationException $e) {
            Log::error('Erro de validação ao atualizar contato: ' . $e->getMessage());
            return response()->json(['message' => 'Erro de validação: ' . $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            Log::error('Contato não encontrado para atualização: ' . $e->getMessage());
            return response()->json(['message' => 'Contato não encontrado.'], 404);
        } catch (Exception $e) {
            Log::error('Erro ao atualizar contato: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar o contato. Tente novamente.'], 500);
        }
    }

    /**
     * Exclui um contato logicamente (soft delete).
     *
     * @param string $cod_contato
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($cod_contato)
    {
        try {
            $contato = Contatos::findOrFail($cod_contato);
            $contato->delete();

            Log::info('Contato excluído com sucesso. ID: ' . $cod_contato);

            return response()->json(['message' => 'Contato excluído com sucesso']);
        } catch (ModelNotFoundException $e) {
            Log::error('Contato não encontrado para exclusão: ' . $e->getMessage());
            return response()->json(['message' => 'Contato não encontrado.'], 404);
        } catch (Exception $e) {
            Log::error('Erro ao excluir contato: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao excluir o contato. Tente novamente.'], 500);
        }
    }
}