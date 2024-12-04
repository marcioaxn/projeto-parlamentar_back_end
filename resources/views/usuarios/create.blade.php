@extends('layouts.app')

@section('content')

<div class="card d-print-none">

  <div class="card-body" style="margin: 0px !Important; padding: 0px !Important;">

    <ol class="breadcrumb" style="margin: 0px !Important; padding-top: 3px !Important; padding-bottom: 3px !Important; background-color: #FFFFFF !Important;">
      <li class="breadcrumb-item"><a href="{!! url('/') !!}" style="color: #17a2b8 !Important;">Principal</a></li>
      <li class="breadcrumb-item active" aria-current="page">Administraçao - Cadastrar Usuário</li>
  </ol>

</div>

</div>

<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 17px !Important;">
        &nbsp;
    </div>

</div>

{!! Form::open(['method' => 'POST', 'url' => ['register'], 'id' => 'formCadastrarUsuario', 'onsubmit' => "return validate_activity();"]) !!}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="name"><strong>Nome</strong></label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'autofocus' => 'autofocus', 'required' => 'required']) !!}
                    <small id="emailHelp" class="form-text text-muted">Nome completo, sem abreviações</small>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <div class="form-group">
                    <label for="cpf"><strong>CPF</strong></label>
                    {!! Form::text('cpf', null, ['class' => 'form-control cpf', 'id' => 'cpf', 'required' => 'required']) !!}
                </div>
                <script type="text/javascript">
                   $('.cpf').mask('000.000.000-00', {reverse: true});
               </script>
           </div>
           <div class="col-12 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="email"><strong>Endereço e-mail</strong></label>
                {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email', 'required' => 'required']) !!}
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-2">
            <div class="form-group">
                <label for="email"><strong>Permissão</strong></label>
                {!! Form::select('adm', $permissoes, null, ['class' => 'form-control', 'id' => 'adm', 'placeholder' => 'Selecione', 'required' => 'required']) !!}
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="form-group">
                <label for="name"><strong>Área (grupo de acesso)</strong></label>
                {!! Form::select('organizacaoId', $organizacoes, null, ['class' => 'form-control', 'id' => 'organizacaoId', 'placeholder' => 'Selecione', 'required' => 'required']) !!}
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label for="name"><strong>Permissão de acesso a quais Módulos do Sistema?</strong></label>
                {!! Form::select('modulos[]', ['Formulário'=>'Formulário','Glossário'=>'Glossário'], null, ['class' => 'form-control', 'multiple' => true, 'id' => 'modulos', 'required' => 'required']) !!}
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#modulos').select2();
            });
        </script>

    </div>
    <div class="row">
        <!--
        <div class="col-12 col-md-12 col-lg-4">
            <div class="form-group">
                <hr>
                <label for="name"><strong>Procedimento</strong></label>
                <br>
                <span class="text-muted" style="font-size: 12px !Important;">
                    Todos os campos são de preenchimento obrigatório.
                    <br>
                    <br>
                    Ao clicar em Salvar o sistema irá gravar as informações e encaminhará, para o novo usuário, uma mensagem de e-mail contendo uma breve explicação e o procedimento para realizar o acesso ao sistema.
                </span>
            </div>
        </div>
    -->
    <div class="col-12 col-md-12 col-lg-12">
        <div class="form-group">
            <hr>
            <label for="name"><strong>Detalhe de cada permissão</strong></label>
            <br>
            <span class="text-muted" style="font-size: 12px !Important;">
                @foreach($detalhePermissoes as $detalhePermissao)
                <p><b>{!! $detalhePermissao->permissao !!}</b>: {!! $detalhePermissao->descricao !!}</p>
                @endforeach
            </span>
        </div>
    </div>
</div>
</div>
<div class="card-footer text-right text-muted bg-white">
    {!! Form::submit('Salvar', ['class' => 'btn btn-info']) !!}
</div>
</div>
{!! Form::close() !!}

@endsection