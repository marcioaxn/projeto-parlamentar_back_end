@extends('layouts.app')

@section('content')
    <!-- Início breadcrumbs -->
    <div id="portal-breadcrumbs-wrapper" class="m-0 pl-0 mb-3 d-print-none">
        <nav id="breadcrumbs" aria-label="Histórico de navegação (Breadcrumbs)">
            <div class="content">
                <span class="sr-only">Você está aqui:</span>
                <span class="home">
                    <a href="{!! url('/') !!}">
                        <span class="fas fa-home" aria-hidden="true"></span>
                        <span class="sr-only">Página Inicial</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                <span dir="ltr" id="breadcrumbs-2">
                    <a href="{!! url('/') !!}">
                        <span id="breadcrumbs-current">Principal</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                <span dir="ltr" id="breadcrumbs-2">
                    <span id="breadcrumbs-current">Administração Clientes</span>
                </span>

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->

    <div class="row" id="informacao">
        <div class="col-xs-12 col-sm-12 col-md-12"
            style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;">
            <i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando...
        </div>
    </div>

    <div class="row" id="div1" style="display: none;">

        <div class="col-xs-12 col-sm-12 col-md-12 pt-2 pb-4 text-right">

            <a href="{!! route('cliente.create') !!}" class="btn btn-outline-primary btn-sm">Cadastrar novo cliente</a>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 mb-4">

            <table class="table" id="tableClientes">

                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Ações</th>
                        <th>Lotação</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $contCliente = 1;
                    @endphp
                    @foreach ($clientes as $cliente)
                        @php
                            $usuarioAdmin = Auth::user()->email;
                            $destacarAdmin = $cliente['email'] === $usuarioAdmin ? 'table-secondary' : '';
                        @endphp

                        <tr class="{{ $destacarAdmin }} <?php $cliente->ativo == 0 ? print 'table-danger' : null; ?>">
                            <td style="width: 19% !Important;">
                                {{ $cliente->name }}
                            </td>
                            <td style="width: 15% !Important;">
                                {{ $cliente->email }}
                            </td>
                            <td style="width: 15% !Important;">
                                {{ $cliente->perfil->nom_perfil }}
                            </td>
                            <td style="width: 25% !Important;">
                                @if ($cliente->cod_user != Auth::user()->cod_user)
                                    <a href="{!! route('cliente.editar', $cliente->cod_user) !!}" class="btn btn-outline-primary btn-sm m-1">Editar
                                        perfil</a>
                                    @if (!$cliente->ativo == 0)
                                        <button id="resetarSenha" data-bs-toggle="modal"
                                            data-bs-target="#modalResetarSenha{{ md5($cliente->cod_user) }}"
                                            class="btn btn-outline-secondary btn-sm">Resetar senha</button>

                                        <div class="modal fade" id="modalResetarSenha{{ md5($cliente->cod_user) }}"
                                            tabindex="-1" role="dialog" style="padding-top: 150px!Important;">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header"
                                                        style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                        <h5 class="modal-title text-white"
                                                            style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                            Confirmação de Redefinição
                                                            de Senha</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        Tem certeza de que deseja redefinir a senha do(a)
                                                        {{ $cliente->name }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <a href="{!! route('resetar-senha', ['cod_user' => $cliente->cod_user]) !!}" class="btn btn-danger btn-sm"
                                                            id="confirmaResetarSenha">Confirmar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td style="width: 20% !Important;">

                            </td>
                            <td>
                                @if ($cliente->ativo == 1)
                                    Ativo
                                @else
                                    Inativo
                                @endif
                            </td>
                        </tr>

                        @php
                            $contCliente++;
                        @endphp
                    @endforeach
                </tbody>

            </table>

            <script type="text/javascript" charset="utf-8">
                document.getElementById('resetarSenha').addEventListener('click', function() {
                    $('#confirmationModal').modal('show');
                });

                document.getElementById('confirmaResetarSenha').addEventListener('click', function() {
                    $('#confirmationModal').modal('hide');
                });
            </script>

            <script type="text/javascript" charset="utf-8">
                $(document).ready(function() {
                    var table = $('#tableClientes').DataTable({
                        "language": {
                            "url": "{{ asset('Portuguese-Brasil.json') }}",
                            "decimal": ",",
                            "thousands": "."
                        },
                        "order": [
                            [0, "asc"]
                        ],
                        "lengthMenu": [
                            [-1, 5, 10, 25, 50, 100],
                            ["Todos ", "5 ", "10 ", "25 ", "50 ", "100 "]
                        ],
                        "paging": true,
                        scrollx: true,
                        fixedHeader: {
                            header: true,
                            headerOffset: $('#header').outerHeight() - 2
                        },
                        scrollCollapse: false,
                    });

                });
            </script>

        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
            Tipos de Perfis de acesso ao sistema:

            <table class="table table-sm" style="font-size: 0.8rem !Important;">
                <thead>
                    <tr style="font-size: 0.8rem !Important;">
                        <th>
                            Perfil
                        </th>
                        <th>
                            Descrição
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($getPerfil as $perfil)
                        <tr style="font-size: 0.8rem !Important;">
                            <td>
                                {{ $perfil->nom_perfil }}
                            </td>
                            <td>
                                {{ $perfil->dsc_perfil }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    </div>
    <!-- Fim apresentação dos cards de entrada -->

    <!-- Início funções javascript -->
    <script>
        setTimeout(function() {
            $("#div1").fadeIn("slow");
        }, 700);

        setTimeout(function() {
            $("#informacao").fadeOut("slow");
        }, 300);
    </script>
    <!-- Fim funções javascript -->
@endsection
