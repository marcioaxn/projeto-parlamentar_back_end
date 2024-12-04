<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Mail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use App\Http\Controllers\TabOrganizacaoController;
use App\Http\Controllers\TabPerfilController;

class RegisterController extends Controller
{
    use RegistersUsers;

    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/';

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function instanciarTabOrganizacaoController()
    {
        return new TabOrganizacaoController;
    }

    public function instanciarTabPerfilController()
    {
        return new TabPerfilController;
    }

    public function cadastrar()
    {

        $tabOrganizacaoController = $this->instanciarTabOrganizacaoController();
        $tabPerfilController = $this->instanciarTabPerfilController();

        $getPluckOrganizacao = $tabOrganizacaoController->getPluckOrganizacao();
        $getPluckPerfil = $tabPerfilController->getPluckPerfil();

        return view('auth.register')
            ->with('getPluckOrganizacao', $getPluckOrganizacao)
            ->with('getPluckPerfil', $getPluckPerfil);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'codigoUnidade' => ['required', 'numeric'],
            'cod_perfil' => ['required', 'string'],
        ], [
            'name.required' => 'É necessário informar o Nome do novo cliente do sistema.',
            'name.string' => 'O campo Nome não aceita texto diferente de palavras compostas pelo alfabeto.',
            'name.min' => 'O campo Nome tem que ter no mínimo três caracteres.',
            'name.max' => 'O campo Nome não pode exceder a quantidade de 255 caracteres.',
            'email.required' => 'É necessário informar o endereço de e-mail do novo usuário.',
            'email.string' => 'O campo E-mail não aceita texto diferente de palavras compostas pelo alfabeto.',
            'email.email' => 'É necessário informar um endereço de e-mail válido',
            'email.max' => 'O campo E-mail aceita no máximo 255 caracteres.',
            'email.unique' => 'Só é permitido usar um determinado endereço de e-mail uma única vez e esse e-mail já é utilizado em outro cadastro.',
            'codigoUnidade.required' => 'É necessário informar a lotação de exercício do novo cliente do sistema.',
            'codigoUnidade.numeric' => 'Ops, o código da unidade de lotação do(a) servidor(a) está divergente do padrão.',
            'cod_perfil.required' => 'É necessário selecionar o tipo de perfil de acesso.'
        ]);

        $input = $request->all();

        $senha = gerar_senha();

        User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'ativo' => 1,
            'trocarsenha' => 1,
            'codigoUnidade' => $input['codigoUnidade'],
            'cod_perfil' => $input['cod_perfil'],
            'password' => Hash::make($senha),
        ]);

        $email = $input['email'];
        $nome = $input['name'];

        $assunto = "Visão 360° - Cadastro";
        $textoEmail = "<p>Prezado(a)</p><p><b>" . $nome . "</b></p><p>É um prazer tê-lo(a) conosco. O seu cadastro no <b>Visão 360°</b> foi realizado com sucesso.</p><p>O <b>Visão 360°</b> é uma plataforma desenvolvida para mapear e conectar os principais atores políticos, econômicos e sociais no território brasileiro. Na fase atual do projeto, você pode explorar informações detalhadas sobre os Parlamentares federais e estaduais. Também é possível registrar dados de atendimento e pautas para potencializar suas estratégias de articulação.</p><p>Endereço: <a href='https://visao360.mdr.gov.br' target='_blank'>https://visao360.mdr.gov.br</a></p><p>Esta é a sua senha inicial:</p><p><span style='color: #CD3333; padding-left: 9px;'><b>" . $senha . "</b></span></p><p></p>Por questão de segurança, o sistema obriga a troca dessa senha inicial no primeiro acesso.<p>Em caso de dúvidas envie uma mensagem para: visao.360@mdr.gov.br</p><p>Respeitosamente,<br><strong>Equipe Visão 360°<br>Coordenação-Geral de Informações Estratégicas e Geoespaciais<br>CGIGeo/DIGEC/SE</strong></p>";

        Mail::send('email.cadastro', ['name' => $nome, 'textoEmail' => $textoEmail], function ($message) use ($email, $nome, $assunto) {
            $message->to($email, $nome)->subject($assunto);
            $message->from('visao.360@mdr.gov.br', 'Visão 360°');
        });

        \Session::flash('flash_message', "Cadastro do(a) <code>" . $input['name'] . "</code> foi feito com sucesso.");
        return redirect()->back();

    }

    protected function create(array $data)
    {

        $senha = limpaString($data['email']);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'ativo' => 1,
            'adm' => 1,
            'trocarsenha' => 1,
            'codigoUnidade' => $data['codigoUnidade'],
            'cod_perfil' => $data['cod_perfil'],
            'password' => Hash::make($senha),
        ]);
    }
}
