@extends('layouts.app')

@section('content')
<div class="card d-print-none">

  <div class="card-body" style="margin: 0px !Important; padding: 0px !Important;">

    <ol class="breadcrumb" style="margin: 0px !Important; padding-top: 3px !Important; padding-bottom: 3px !Important; background-color: #FFFFFF !Important;">
      <li class="breadcrumb-item"><a href="{!! url('/') !!}" style="color: #17a2b8 !Important;">Principal</a></li>
      <li class="breadcrumb-item active" aria-current="page">Administraçao - Visualizar usuários das Áreas</li>
  </ol>

</div>

</div>

<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 17px !Important;">
        &nbsp;
    </div>

</div>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#admUsuarios').dataTable(
        {
            "language": {
              "url": "{{ asset('Portuguese-Brasil.json') }}",
              "decimal": ",",
              "thousands": "."
          },
          "order": [[0, "asc"]],
          "lengthMenu": [[-1, 10, 25, 25], ["Todas as linhas", "10 linhas", "25 linhas", "25 linhas"]],
          "paging": false,
          "responsive": true,
          "fixedHeader": true,
      });
    });
</script>

<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        <table id="admUsuarios" class="table table-bordered dt-responsive" style="width:100%">
            <thead>
                <tr style="font-size: 13px !Important;">
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Ações</th>
                    <th>Ativo</th>
                    <th>Permissão</th>
                    <th>E-mail</th>
                    <th>Área (grupo de acesso)</th>
                </tr>
            </thead>
            <tbody>
                <?php ?>
                @foreach($usuarios as $usuario)
                <?php
                $bgAtivo = '';
                $bg = '';

                if ($usuario->ativo == 0) {
                    $bgAtivo = 'text-danger';
                    $bg = '';
                } else {
                    $bgAtivo = '';

                    if ($usuario->adm == 1) {
                        $bg = 'text-primary';
                    } elseif ($usuario->adm == 2) {
                        $bg = 'text-info';
                    } else {
                        $bg = '';
                    }
                }
                ?>
                <tr class="<?php print($bgAtivo); ?> <?php $usuario->id == Auth::user()->id ? print('table-secondary') : '' ?>">
                    <td>
                        {!! decrypt($usuario->name) !!}
                    </td>
                    <td>
                        {!! naoMostrarCpfCompleto($usuario->cpf) !!}
                    </td>
                    <td>
                        @if ($usuario->ativo == 1 && $usuario->id != Auth::user()->id)
                        <div class="row">
                            <div class="col-4 col-md-4 col-lg-4">
                                <span data-toggle="modal" data-target="#modalEditarUsuario<?php print($usuario->id); ?>"><i class="fas fa-edit text-primary" data-toggle="tooltip" title="Editar" style="cursor: pointer"></i></span>
                            </div>
                            <div class="col-4 col-md-4 col-lg-4">
                                <span data-toggle="modal" data-target="#modalEnviarEmail<?php print($usuario->id); ?>"><i class="fas fa-key text-primary" data-toggle="tooltip" title="Resetar a senha do usuário passando a nova senha para o número de CPF" style="cursor: pointer"></i></span>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-4 col-md-4 col-lg-4">
                                -
                            </div>
                            <div class="col-4 col-md-4 col-lg-4">
                                @if ($usuario->trocarsenha == 1)
                                -
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(Auth::user()->adm == 1)
                        <div class="modal fade" id="modalEditarUsuario<?php print($usuario->id); ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditarUsuario<?php print($usuario->id); ?>Label" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                {!! Form::open(['method' => 'PATCH', 'action' => ['UsersController@update']]) !!}
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-info" id="modalEditarUsuario<?php print($usuario->id); ?>Label">Editar informações de usuário</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-left">
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="name"><strong>Nome</strong></label>
                                                    {!! Form::text('name', decrypt($usuario->name), ['class' => 'form-control', 'id' => 'name', 'autofocus' => 'autofocus', 'required' => 'required']) !!}
                                                    <small id="emailHelp" class="form-text text-muted">Nome completo, sem abreviações</small>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="email"><strong>Endereço e-mail</strong></label>
                                                    {!! Form::email('email', $usuario->email, ['class' => 'form-control', 'id' => 'email', 'required' => 'required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="name"><strong>Empresa</strong></label>
                                                    {!! Form::select('organizacaoid', $organizacoes, $usuario->organizacaoid, ['class' => 'form-control', 'id' => 'organizacaoid', 'required' => 'required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="email"><strong>Permissão</strong></label>
                                                    {!! Form::select('adm', $permissoes, $usuario->adm, ['class' => 'form-control', 'id' => 'adm', 'required' => 'required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label for="name"><strong>Permissão de acesso a quais Módulos do Sistema?</strong></label>
                                                    <br>
                                                    <?php
                                                    $modulos = explode(',', $usuario->modulos);
                                                    ?>
                                                    {!! Form::select('modulos[]', ['Formulário'=>'Formulário','Glossário'=>'Glossário'], $modulos, ['class' => 'form-control', 'multiple' => true, 'id' => 'modulos'.$usuario->id,'style' => 'width: 100%;', 'required' => 'required']) !!}
                                                </div>
                                            </div>

                                            <script type="text/javascript">
                                                $('#modulos<?php print($usuario->id); ?>').select2({
                                                  dropdownParent: $('#modalEditarUsuario<?php print($usuario->id); ?>')
                                              });
                                          </script>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    {!! Form::hidden('id', $usuario->id) !!}
                                    {!! Form::submit('Alterar', ['class' => 'btn btn-info']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="modal fade" id="modalEnviarEmail<?php print($usuario->id); ?>" tabindex="-1" role="dialog" aria-labelledby="modalEnviarEmail<?php print($usuario->id); ?>Label" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            {!! Form::open(['method' => 'PATCH', 'action' => ['UsersController@enviaremail']]) !!}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-info" id="modalEnviarEmail<?php print($usuario->id); ?>Label">Passar a senha do usuário para o número do CPF dele</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-left text-white bg-info">
                                    <strong>Nome</strong>: {!! decrypt($usuario->name) !!}
                                    <hr>
                                    Deseja, realmente, alterar a senha desse(a) uauário(a) para o número de CPF dele(a)?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    {!! Form::hidden('id', $usuario->id) !!}
                                    {!! Form::submit('Sim, quero enviar o e-mail', ['class' => 'btn btn-info']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    @endif

                </td>
                <td class="text-center">
                    @if($usuario->ativo == 1 && $usuario->id != Auth::user()->id)
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <span data-toggle="modal" data-target="#modalDesativar<?php print($usuario->id); ?>"><i class="fas fa-check-circle text-primary" data-toggle="tooltip" title="Cadastro do usuário está ativo, deseja desativar?" style="cursor: pointer"></i></span>
                        </div>
                    </div>
                    @elseif($usuario->ativo == 0 && $usuario->id != Auth::user()->id)
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <span data-toggle="modal" data-target="#modalAtivar<?php print($usuario->id); ?>"><i class="fa fa-circle text-danger" data-toggle="tooltip" title="Cadastro do usuário está desativado, deseja ativar?" style="cursor: pointer"></i></span>
                        </div>
                    </div>
                    @endif

                    <div class="modal fade" id="modalAtivar<?php print($usuario->id); ?>" tabindex="-1" role="dialog" aria-labelledby="modalAtivar<?php print($usuario->id); ?>Label" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            {!! Form::open(['method' => 'PATCH', 'action' => ['UsersController@ativar']]) !!}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-info" id="modalAtivar<?php print($usuario->id); ?>Label">Ativar cadastro do usuário</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-left text-white bg-info">
                                    <strong>Nome</strong>: {!! decrypt($usuario->name) !!}
                                    <hr>
                                    Deseja, realmente, ativar o cadastro desse uauário?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    {!! Form::hidden('id', $usuario->id) !!}
                                    {!! Form::submit('Sim, quero ativar', ['class' => 'btn btn-info']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="modal fade" id="modalDesativar<?php print($usuario->id); ?>" tabindex="-1" role="dialog" aria-labelledby="modalDesativar<?php print($usuario->id); ?>Label" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            {!! Form::open(['method' => 'PATCH', 'action' => ['UsersController@desativar']]) !!}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger" id="modalDesativar<?php print($usuario->id); ?>Label">Desativar cadastro do usuário</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-left text-white bg-danger">
                                    <strong>Nome</strong>: {!! decrypt($usuario->name) !!}
                                    <hr>
                                    Deseja, realmente, desativar o cadastro desse uauário?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    {!! Form::hidden('id', $usuario->id) !!}
                                    {!! Form::submit('Sim, quero desativar', ['class' => 'btn btn-outline-danger']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </td>
                <td class="<?php print($bg); ?>">
                    <strong>{!! descricaoCurtaPermissao($usuario->adm) !!}</strong>
                </td>
                <td>{!! $usuario->email !!}</td>
                <td><b>{!! $usuario->sigla !!}</b>-{!! $usuario->organizacao !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

<script>
    $(document).on('scroll', function () {
        if ($(window).scrollTop() >= 155) {
            $("#admUsuarios").slideDown("fast").css("padding-top", 90);
        } else if ($(window).scrollTop() < 155) {
            $("#admUsuarios").css("padding-top", 0);
        }
    });
</script>

@endsection
