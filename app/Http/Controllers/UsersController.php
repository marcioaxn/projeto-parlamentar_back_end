<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Acoes;
use App\Organizacoes;
use App\Permissoes;
use App\Estados;
use App\Municipios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Lang;
use Auth;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
use Session;

use App\Http\Controllers\TabOrganizacaoController;
use App\Http\Controllers\TabPerfilController;
use App\Http\Controllers\TabModulosController;
use App\Http\Controllers\TabPermissoesModuloController;
use App\Http\Controllers\AtualizarOuCriarPorModeloDadosController;
use App\Http\Controllers\TabTextoMensagemEmailController;

class UsersController extends Controller
{

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function instanciarTabOrganizacaoController()
    {
        return new TabOrganizacaoController;
    }

    public function instanciarTabPerfilController()
    {
        return new TabPerfilController;
    }

    public function instanciarTabModulosController()
    {
        return new TabModulosController;
    }

    public function instanciarTabPermissoesModuloController()
    {
        return new TabPermissoesModuloController;
    }

    public function instanciarAtualizarOuCriarPorModeloDadosController()
    {
        return new AtualizarOuCriarPorModeloDadosController;
    }

    public function instanciarTabTextoMensagemEmailController()
    {
        return new TabTextoMensagemEmailController;
    }

    public function index()
    {
        //
    }

    protected function getParcialClientes()
    {
        return User::select('cod_user', 'name', 'email', 'ativo', 'cod_perfil', 'codigoUnidade')
            ->orderBy('name')
            ->with('perfil', 'lotacao', 'permissoesModulos')
            ->get();
    }

    protected function getCliente($codUser = null)
    {
        return User::select('name', 'email', 'ativo', 'cod_perfil', 'codigoUnidade', 'created_at')
            ->with('perfil', 'lotacao', 'permissoesModulos')
            ->find($codUser);
    }

    public function dashboardClientes()
    {

        $tabPerfil = $this->instanciarTabPerfilController();

        $getPerfil = $tabPerfil->getPerfil();

        if (!Auth::guest()) {
            if (\Session::has('bln_administrar_usuarios')) {

                if (\Session::get('bln_administrar_usuarios') == 1) {

                    $clientes = $this->getParcialClientes();

                    return view('clientes.dashboard')
                        ->with('clientes', $clientes)
                        ->with('getPerfil', $getPerfil);
                }
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->action('Auth\AuthController@login');
        }
    }

    public function paginaTrocarSenha(Request $request)
    {

        $consulta = $this->getCliente(Auth::user()->cod_user);

        return view('usuarios.trocarsenha')
            ->with('consulta', $consulta);
    }

    public function resetarSenha($codUser = null)
    {

        $usuario = User::find($codUser);

        if ($usuario->ativo == 0) {
            return redirect()->route('dashboard-clientes')->with(\Session::flash('flash_message_errors', 'Ative o usuário para resetar a senha!'));
        } else {

            $senha = generateUUID();

            $usuario->update(array('trocarsenha' => 1, 'password' => Hash::make($senha)));

            $assunto = "Visão 360° - Redefinição de senha";
            $textoEmail = "<p>Foi feita a redefinição da sua senha.</p><p>Esta é a sua nova senha:</p><p><span style='color: #CD3333;'>" . $senha . "</span></p><p>Em caso de dúvidas envie uma mensagem para: visao.360@mdr.gov.br</p><p>Respeitosamente,<br><strong>Equipe Visão 360°</strong></p>";

            $email = $usuario->email;
            $nome = $usuario->name;

            Mail::send('email.cadastro', ['name' => $nome, 'textoEmail' => $textoEmail], function ($message) use ($email, $nome, $assunto) {
                $message->to($email, $nome)->subject($assunto);
                $message->from('visao.360@mdr.gov.br', 'Visão 360°');
            });

            return redirect()->route('dashboard-clientes')->with(\Session::flash('flash_message', 'Senha resetada com sucesso!'));
        }
    }

    public function usuarioNaoAtivo(Request $request)
    {
        return redirect()->route('login');
    }

    public function updateSenha(Request $request)
    {

        $request->validate([
            'passwordOld' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('A senha atual não está correta.');
                    }
                },
            ],
            'password' => 'required|min:6|different:passwordOld',
            'password_confirmation' => 'required|same:password',
        ], [
            'passwordOld.required' => 'O seguinte campo é obrigatório: Sua senha temporária.',
            'password.required' => 'É necessário incluir uma nova senha.',
            'password_confirmation.required' => 'É necessário digitar a nova senha no campo de confirmação.',
            'password.min' => 'Sua nova senha tem que conter no mínimo 6 caracteres. Qualquer caracter, inclusive com caracteres especiais.',
            'password.different' => 'Sua nova senha tem que ser diferente da sua senha atual.',
            'password_confirmation.same' => 'Sua nova senha não confirma com a digitada no campo ( Nova senha ).',
        ]);

        $input = $request->all();

        if ($this->guard()->attempt(['cod_user' => Auth::user()->cod_user, 'password' => $input["passwordOld"]])) {
            $user = User::find(Auth::user()->cod_user);

            $user->update(array('password' => bcrypt($input["password"]), 'trocarsenha' => 0));

            $informacao = 'Senha alterada com sucesso. Acesse o Sistema com a sua nova senha.';

            $this->guard()->logout();

            $request->session()->invalidate();

            \Session::flash('flash_message', $informacao);

            return redirect('/login')->with('informacao', $informacao);
        } else {

            \Session::flash('flash_message_errors', 'Senha atual não confere');
            return back()->withInput();
        }
    }

    public function show()
    {

        if (!Auth::guest() && Auth::user()->trocarsenha == 1) {
            return redirect()->action('UsersController@paginaTrocarSenha');
        }

        if (!Auth::guest()) {
            if (Auth::user()->adm == 1) {

                $consultarUsuariosSemOrganizacaoId = User::whereNull('organizacaoid')
                    ->update(['organizacaoid' => '2c6b5880-2013-4324-90a1-ad73e84c3a4c']);

                $usuarios = DB::table('users as a')
                    ->leftJoin('organizacoes as b', 'a.organizacaoid', '=', 'b.id')
                    //->where('a.id', '!=', Auth::user()->id)
                    ->orderBy('b.organizacao')
                    ->orderBy('a.name')
                    ->select('a.*', 'b.organizacao', 'b.sigla')
                    ->get();

                $organizacoes = DB::table('organizacoes as a')
                    ->whereNull('a.deleted_at')
                    ->select(DB::raw('a.organizacao AS organizacao'), 'a.id')
                    ->orderBy('a.organizacao')
                    ->pluck('organizacao', 'id');

                $permissoes = Permissoes::orderBy('permissao')
                    ->pluck('permissao', 'id');

                $detalhePermissoes = Permissoes::orderBy('permissao')
                    ->get();

                return view('usuarios.show')
                    ->with('usuarios', $usuarios)
                    ->with('organizacoes', $organizacoes)
                    ->with('permissoes', $permissoes)
                    ->with('detalhePermissoes', $detalhePermissoes);
            } elseif (Auth::user()->adm == 2) {
                return view('erros.semPermissao')
                    ->with('pagina', 'Administração - Visualizar Usuários');
            } else {
                return view('erros.semPermissao')
                    ->with('pagina', 'Administração - Visualizar Usuários');
            }
        } else {
            return redirect()->action('Auth\AuthController@login');
        }
    }

    public function create(Request $request)
    {
        $tabPerfil = $this->instanciarTabPerfilController();

        $getPerfil = $tabPerfil->getPerfil();

        $tabModulos = $this->instanciarTabModulosController();

        $tabPermissoesModulos = $this->instanciarTabPermissoesModuloController();

        $getModulos = $tabModulos->getModulos();

        $getPermissoesModulo = $tabPermissoesModulos->getPermissoesModulo();

        $tabOrganizacaoController = $this->instanciarTabOrganizacaoController();
        $getPluckOrganizacao = $tabOrganizacaoController->getPluckOrganizacao();

        $tabPerfilController = $this->instanciarTabPerfilController();
        $getPluckPerfil = $tabPerfilController->getPluckPerfil();

        return view('clientes.create')
            ->with('getPerfil', $getPerfil)
            ->with('getModulos', $getModulos)
            ->with('getPermissoesModulo', $getPermissoesModulo)
            ->with('getPluckOrganizacao', $getPluckOrganizacao)
            ->with('getPluckPerfil', $getPluckPerfil);
    }

    public function store(Request $request)
    {

        $input = $request->all();

        $colunasPassiveisDeModificacoes = ['name', 'email', 'codigoUnidade', 'cod_perfil', 'ativo'];

        $colunasQuePrecisamConsultaNaTabelaFonte = ['codigoUnidade', 'cod_perfil', 'ativo'];

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        // Início da parte de gravação dos Dados pessoais, lotação e os da Configuração básica do(a) cliente no sistema

        $modificacoes = '';

        $idUser = [];
        $camposUser = [];

        $modificacoes = null;

        foreach ($colunasPassiveisDeModificacoes as $column_name) {

            $valorAntes = null;
            $valorApos = null;

            $camposUser[$column_name] = $input[$column_name];

            $valorApos = $input[$column_name];

            if (in_array($column_name, $colunasQuePrecisamConsultaNaTabelaFonte)) {

                if ($column_name === 'codigoUnidade') {

                    $table = 'tab_organizacao';
                    $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                    $consultarAposAlteracao = $model::select('sigla', 'nome')->find($input[$column_name]);

                    $valorApos = $consultarAposAlteracao->sigla . ' - ' . $consultarAposAlteracao->nome;

                }

                if ($column_name === 'cod_perfil') {

                    $table = 'tab_perfil';
                    $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                    $consultarAposAlteracao = $model::select('nom_perfil', 'dsc_perfil')->find($input[$column_name]);

                    $valorApos = $consultarAposAlteracao->nom_perfil;

                }

                if ($column_name === 'ativo') {

                    $input[$column_name] == 1 ? $valorApos = 'Ativo' : $valorApos = 'Inativo';

                }

            }

            $modificacoes .= 'Inseriu <span style="color: green; font-weight: bold;">' . $valorApos . '</span> para o(a) <span class="text-bold">' . nomeCampoUsersNormalizado($column_name) . '</span>;<br>';

        }

        $senha = gerar_senha();

        $camposUser['password'] = Hash::make($senha);

        $nomeProcedimento = 'Gravar dados da relação entre o cliente, o módulo com o nível da permissão e o órgão de lotação';
        $schema = 'midr_gestao';
        $table = 'user';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $cliente = $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $idUser, $camposUser);

        // Fim da parte de gravação dos Dados pessoais, lotação e os da Configuração básica do(a) cliente no sistema

        // Início da parte de gravação da permissão por módulos

        $tabModulos = $this->instanciarTabModulosController();

        $tabPermissoesModulos = $this->instanciarTabPermissoesModuloController();

        $getModulos = $tabModulos->getModulos();

        $getPermissoesModulo = $tabPermissoesModulos->getPermissoesModulo();

        if (isset($input['modulos']) && is_array($input['modulos']) && !empty($input['modulos'])) {
            $modulos = $input['modulos'];
        }

        $id = [];
        $campos = [];

        $nomeProcedimento = 'Gravar dados da relação entre o cliente, o módulo com o nível da permissão e o órgão de lotação';
        $schema = 'midr_gestao';
        $table = 'rel_user_modulo_permissao';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        foreach ($getModulos as $modulo) {

            $concatCodRelUserModuloPermissao = $cliente . '=' . $modulo->cod_modulo;

            $id['cod_rel_user_modulo_permissao'] = $concatCodRelUserModuloPermissao;

            $campos['cod_user'] = $cliente;
            $campos['cod_modulo'] = $modulo->cod_modulo;
            $campos['cod_permissao_modulo'] = $input['modulos'][$modulo->cod_modulo];

            $getModulo = $tabModulos->getModulo($modulo->cod_modulo);

            $modificacoes .= 'Inseriu o nível <span style="color: green; font-weight: bold;">' . $input['modulos'][$modulo->cod_modulo] . '</span> de permissão de acesso ao módulo <span class="text-bold">' . $getModulo->nom_modulo . '</span>;<br>';

            $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

        }

        // Fim da parte de gravação da permissão por módulos

        $tabTextoMensagemEmail = $this->instanciarTabTextoMensagemEmailController();

        $getMensagemQueEstaraNoEmailBoasVindas = $tabTextoMensagemEmail->getMensagemQueEstaraNoEmailBoasVindas();

        $email = $input['email'];
        $nome = $input['name'];

        $assunto = "Visão 360° - Cadastro";
        $textoEmail = "<p>Prezado(a)</p><p><b>" . $nome . "</b></p><p>" . $getMensagemQueEstaraNoEmailBoasVindas->txt_mensagem_email . "</p><p>Endereço: <a href='https://visao360.mdr.gov.br' target='_blank'>https://visao360.mdr.gov.br</a></p><p>Esta é a sua senha inicial:</p><p><span style='color: #CD3333; padding-left: 9px;'><b>" . $senha . "</b></span></p><p></p>Por questão de segurança, o sistema obriga a troca dessa senha inicial no primeiro acesso.<p>Em caso de dúvidas envie uma mensagem para: visao.360@mdr.gov.br</p><p>Respeitosamente,<br><strong>Equipe Visão 360°<br>Coordenação-Geral de Informações Estratégicas e Geoespaciais - CGIGeo<br>Diretoria de Gestão Estratégica - DIGEC<br>Secretaria Executiva - SE<br>Ministério da Integração e do Desenvolvimento Regional - MIDR</strong></p>";

        Mail::send('email.cadastro', ['name' => $nome, 'textoEmail' => $textoEmail], function ($message) use ($email, $nome, $assunto) {
            $message->to($email, $nome)->subject($assunto);
            $message->from('visao.360@mdr.gov.br', 'Visão 360°');
        });

        \Session::flash('flash_message', "Cadastro do(a) <code>" . $input['name'] . "</code> foi feito com sucesso.");
        return redirect()->route('dashboard-clientes');
    }

    public function edit(Request $request, $codUser = null)
    {

        if (User::where('cod_user', $codUser)->exists()) {
            $usuario = User::with('lotacao', 'permissoesModulos')->find($codUser);

            $tabPerfil = $this->instanciarTabPerfilController();

            $getPerfil = $tabPerfil->getPerfil();

            $tabModulos = $this->instanciarTabModulosController();

            $tabPermissoesModulos = $this->instanciarTabPermissoesModuloController();

            $getModulos = $tabModulos->getModulos();

            $getPermissoesModulo = $tabPermissoesModulos->getPermissoesModulo();

            $tabOrganizacaoController = $this->instanciarTabOrganizacaoController();
            $getPluckOrganizacao = $tabOrganizacaoController->getPluckOrganizacao();

            $tabPerfilController = $this->instanciarTabPerfilController();
            $getPluckPerfil = $tabPerfilController->getPluckPerfil();

            return view('clientes.editar')
                ->with('usuario', $usuario)
                ->with('getPerfil', $getPerfil)
                ->with('getModulos', $getModulos)
                ->with('getPermissoesModulo', $getPermissoesModulo)
                ->with('getPluckOrganizacao', $getPluckOrganizacao)
                ->with('getPluckPerfil', $getPluckPerfil);

        } else {
            \Session::flash('flash_message_error', "Algo deu errado ao editar usuário!");

            return view('usuarios.editar');
        }
    }

    public function update(Request $request, $codUser = null)
    {

        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255',
            'codigoUnidade' => 'required'
        ], [
            'name.required' => 'É necessário informar o Nome do novo usuario.',
            'name.string' => 'O campo Nome não aceita texto diferente de palavras compostas pelo alfabeto.',
            'email.required' => 'É necessário informar o endereço de e-mail do novo usuário.',
            'email.string' => 'O campo E-mail não aceita texto diferente de palavras compostas pelo alfabeto.',
            'email.email' => 'É necessário informar um endereço de e-mail válido',
            'email.max' => 'O campo E-mail aceita no máximo 255 caracteres.',
            'codigoUnidade.required' => 'É necessário informar a Organização onde esse usuário trabalha.'
        ]);

        $input = $request->all();

        $colunasPassiveisDeModificacoes = ['name', 'email', 'codigoUnidade', 'cod_perfil', 'ativo'];

        $colunasQuePrecisamConsultaNaTabelaFonte = ['codigoUnidade', 'cod_perfil', 'ativo'];

        $atualizarOuCriarPorModeloDados = $this->instanciarAtualizarOuCriarPorModeloDadosController();

        // Início da parte de gravação dos Dados pessoais, lotação e os da Configuração básica do(a) cliente no sistema

        $modificacoes = '';

        $idUser['cod_user'] = $codUser;
        $camposUser = [];

        $user = User::select('cod_user', 'name', 'email', 'codigoUnidade', 'cod_perfil', 'ativo')
            ->with('permissoesModulos')
            ->find($codUser);

        $modificacoes = null;

        foreach ($colunasPassiveisDeModificacoes as $column_name) {

            $valorAntes = null;
            $valorApos = null;

            if ($user->$column_name != $input[$column_name]) {

                $camposUser[$column_name] = $input[$column_name];

                $valorAntes = $user->$column_name;
                $valorApos = $input[$column_name];

                if (in_array($column_name, $colunasQuePrecisamConsultaNaTabelaFonte)) {

                    if ($column_name === 'codigoUnidade') {

                        $table = 'tab_organizacao';
                        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                        $consultarAntesAlteracao = $model::select('sigla', 'nome')->find($user->$column_name);
                        $consultarAposAlteracao = $model::select('sigla', 'nome')->find($input[$column_name]);

                        $valorAntes = $consultarAntesAlteracao->sigla . ' - ' . $consultarAntesAlteracao->nome;
                        $valorApos = $consultarAposAlteracao->sigla . ' - ' . $consultarAposAlteracao->nome;

                    }

                    if ($column_name === 'cod_perfil') {

                        $table = 'tab_perfil';
                        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

                        $consultarAntesAlteracao = $model::select('nom_perfil', 'dsc_perfil')->find($user->$column_name);
                        $consultarAposAlteracao = $model::select('nom_perfil', 'dsc_perfil')->find($input[$column_name]);

                        $valorAntes = $consultarAntesAlteracao->nom_perfil;
                        $valorApos = $consultarAposAlteracao->nom_perfil;

                    }

                    if ($column_name === 'ativo') {

                        $user->$column_name == 1 ? $valorAntes = 'Ativo' : $valorAntes = 'Inativo';
                        $input[$column_name] == 1 ? $valorApos = 'Ativo' : $valorApos = 'Inativo';

                    }

                }

                $modificacoes .= 'Alterou o(a) <span class="text-bold">' . nomeCampoUsersNormalizado($column_name) . '</span> de <span style="color: red; font-weight: bold;">' . $valorAntes . '</span> para <span style="color: green; font-weight: bold;">' . $valorApos . '</span>;<br>';
            }

        }

        $nomeProcedimento = 'Gravar dados da relação entre o cliente, o módulo com o nível da permissão e o órgão de lotação';
        $schema = 'midr_gestao';
        $table = 'user';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $idUser, $camposUser);

        // Fim da parte de gravação dos Dados pessoais, lotação e os da Configuração básica do(a) cliente no sistema

        // Início da parte de gravação da permissão por módulos

        $tabModulos = $this->instanciarTabModulosController();

        $tabPermissoesModulos = $this->instanciarTabPermissoesModuloController();

        $getModulos = $tabModulos->getModulos();

        $getPermissoesModulo = $tabPermissoesModulos->getPermissoesModulo();

        $permissao = [];

        foreach ($user->permissoesModulos as $permissaoModulo) {
            $permissao[$permissaoModulo->cod_modulo] = $permissaoModulo->cod_permissao_modulo;
        }

        if (isset($input['modulos']) && is_array($input['modulos']) && !empty($input['modulos'])) {
            $modulos = $input['modulos'];
        }

        $id = [];
        $campos = [];

        $nomeProcedimento = 'Gravar dados da relação entre o cliente, o módulo com o nível da permissão e o órgão de lotação';
        $schema = 'midr_gestao';
        $table = 'rel_user_modulo_permissao';
        $model = 'App\Models\\' . transformarNomeTabelaParaNomeModel($table);

        foreach ($getModulos as $modulo) {
            if (isset($permissao) && is_array($permissao) && count($permissao) > 0 && array_key_exists($modulo->cod_modulo, $permissao) && $permissao[$modulo->cod_modulo] != $input['modulos'][$modulo->cod_modulo]) {
                // Parte da criação do cod_rel_user_modulo_permissao, que será a concatenação do cod_user, cod_modulo e codigoUnidade
                $concatCodRelUserModuloPermissao = $codUser . '=' . $modulo->cod_modulo;

                $id['cod_rel_user_modulo_permissao'] = $concatCodRelUserModuloPermissao;

                $campos['cod_user'] = $codUser;
                $campos['cod_modulo'] = $modulo->cod_modulo;
                $campos['cod_permissao_modulo'] = $input['modulos'][$modulo->cod_modulo];

                $getModulo = $tabModulos->getModulo($modulo->cod_modulo);

                $modificacoes .= 'Alterou o nível de permissão de acesso ao módulo <span class="text-bold">' . $getModulo->nom_modulo . '</span> de <span style="color: red; font-weight: bold;">' . $permissao[$modulo->cod_modulo] . '</span> para <span style="color: green; font-weight: bold;">' . $input['modulos'][$modulo->cod_modulo] . '</span>;<br>';

                $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);
            } else {

                if (isset($permissao) && is_array($permissao) && count($permissao) > 0 && !array_key_exists($modulo->cod_modulo, $permissao)) {

                    $concatCodRelUserModuloPermissao = $codUser . '=' . $modulo->cod_modulo;

                    $id['cod_rel_user_modulo_permissao'] = $concatCodRelUserModuloPermissao;

                    $campos['cod_user'] = $codUser;
                    $campos['cod_modulo'] = $modulo->cod_modulo;
                    $campos['cod_permissao_modulo'] = $input['modulos'][$modulo->cod_modulo];

                    $getModulo = $tabModulos->getModulo($modulo->cod_modulo);

                    $modificacoes .= 'Inseriu o nível <span style="color: green; font-weight: bold;">' . $input['modulos'][$modulo->cod_modulo] . '</span> de permissão de acesso ao módulo <span class="text-bold">' . $getModulo->nom_modulo . '</span>;<br>';

                    $atualizarOuCriarPorModeloDados->atualizarOuCriarPorModeloDados($model, $id, $campos);

                }

            }
        }

        // Fim da parte de gravação da permissão por módulos

        if ($modificacoes != '') {

            \Session::flash('flash_message', "Alterações no cadastro do(a) usuário(a) " . $input['name'] . ' gravadas com sucesso.<br><br>' . $modificacoes);
            return redirect()->back();
        } else {
            \Session::flash('flash_message_errors', "Nao foi detectada nenhuma alteração e por esse motivo nada foi feito.");
            return redirect()->back();
        }
    }

    protected function getEstruturaUsers()
    {
        $user = new User();
        $connection = $user->getConnection();
        $schema = $connection->getSchemaBuilder();

        $columns = $schema->getColumnListing($user->getTable());

        return $columns;
    }

    public function ativar(Request $request)
    {

        if (!Auth::guest() && Auth::user()->trocarsenha == 1) {
            return redirect()->action('UsersController@paginaTrocarSenha');
        }

        if (!Auth::guest()) {

            $input = $request->all();

            if (Auth::user()->adm == 1) {
                $usuario = User::where('id', '=', $input['id'])
                    ->first();

                $usuario->update(array('ativo' => 1));

                // Início   gravar histórico da ação
                $acao = Acoes::create(
                    array(
                        'table' => 'users',
                        'id_table' => $input['id'],
                        'id_user' => Auth::user()->id,
                        'acao' => 'Reativou o cadastro de usuário'
                    )
                );

                \Session::flash('flash_message', "Cadastro do(a) <code>" . decrypt($usuario->name) . "</code> foi reativado com sucesso.");
                return redirect()->back();
            } elseif (Auth::user()->adm == 2) {
            } else {
                return view('erros.semPermissao')
                    ->with('pagina', 'Administração - Visualizar Usuários');
            }
        } else {
            return redirect()->action('Auth\AuthController@login');
        }
    }

    public function desativar(Request $request)
    {

        if (!Auth::guest() && Auth::user()->trocarsenha == 1) {
            return redirect()->action('UsersController@paginaTrocarSenha');
        }

        if (!Auth::guest()) {

            $input = $request->all();

            if (Auth::user()->adm == 1) {
                $usuario = User::where('id', '=', $input['id'])
                    ->first();

                $usuario->update(array('ativo' => 0));

                // Início   gravar histórico da ação
                $acao = Acoes::create(
                    array(
                        'table' => 'users',
                        'id_table' => $input['id'],
                        'id_user' => Auth::user()->id,
                        'acao' => 'Desativou o cadastro de usuário'
                    )
                );

                \Session::flash('flash_message', "Cadastro do(a) <code>" . decrypt($usuario->name) . "</code> foi desativado com sucesso.");
                return redirect()->back();
            } elseif (Auth::user()->adm == 2) {
            } else {
                return view('erros.semPermissao')
                    ->with('pagina', 'Administração - Visualizar Usuários');
            }
        } else {
            return redirect()->action('Auth\AuthController@login');
        }
    }

    public function enviaremail(Request $request)
    {

        if (!Auth::guest() && Auth::user()->trocarsenha == 1) {
            return redirect()->action('UsersController@paginaTrocarSenha');
        }

        if (!Auth::guest()) {

            $input = $request->all();

            if (Auth::user()->adm == 1) {
                $usuario = User::where('id', '=', $input['id'])
                    ->first();

                //$senha = gerar_senha();
                $senha = $usuario->email;

                $permissao = descricaoCurtaPermissao($usuario->adm);

                $usuario->update(array('trocarsenha' => 1, 'password' => Hash::make($senha)));

                // Início   gravar histórico da ação
                $acao = Acoes::create(
                    array(
                        'table' => 'users',
                        'id_table' => $input['id'],
                        'id_user' => Auth::user()->id,
                        'acao' => 'Enviou para o(a) usuário(a) uma mensagem de e-mail contendo uma nova senha.'
                    )
                );

                $assunto = "Envio de nova senha";
                $textoEmail = "<p>Foi solicitado o envio de uma mensagem contendo uma nova senha.</p><p>Esta é a sua nova senha <span style='color: #CD3333;'>" . $senha . "</span></p><p>Troque a senha para acessar todas as funcionalidades do Sistema.</p><p>Sua permissão de acesso é de <code>" . $permissao . "</code></p><p>Endereço de acesso: " . env('APP_URL') . "</p><p>Em caso de dúvidas envie uma mensagem para: </p><p>Atenciosamente,<br><strong>Equipe</strong></p>";

                $email = $usuario->email;
                $nome = $usuario->name;

                /*
                Mail::send('email.cadastro', ['name' => $nome, 'textoEmail' => $textoEmail], function($message) use($email, $nome, $assunto) {
                    $message->to($email, $nome)->subject($assunto);
                    $message->from('maxnprojetos@gmail.com', 'maxn projetos');
                });

                $emailAdm = 'marcio.xavierneto@gmail.com';
                $nomeAdm = 'Marcio A. Xavier Neto - Adm';

                Mail::send('email.cadastro', ['name' => $nome, 'textoEmail' => $textoEmail], function($message) use($emailAdm, $nomeAdm, $assunto) {
                    $message->to($emailAdm, $nomeAdm)->subject('ADM - ' . $assunto);
                    $message->from('maxnprojetos@gmail.com', 'maxn projetos');
                });
                */

                //\Session::flash('flash_message', "Mensagem contendo uma nova senha foi encaminhada para <code>" . $usuario->name . "</code>.");
                \Session::flash('flash_message', "A senha do(a) <code>" . decrypt($usuario->name) . "</code> foi passada para o número do CPF, sem ponto e sem traço, somente os números.");
                return redirect()->back();
            } elseif (Auth::user()->adm == 2) {
                return view('erros.semPermissao')
                    ->with('pagina', 'Administração - Visualizar Usuários');
            } else {
                return view('erros.semPermissao')
                    ->with('pagina', 'Administração - Visualizar Usuários');
            }
        } else {
            return redirect()->action('Auth\AuthController@login');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
