{{-- Arquivo: resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestão de Usuários')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Usuários do Sistema</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" id="btn-novo-usuario">
                            <i class="fas fa-plus"></i> Novo Usuário
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabela-usuarios" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Perfil</th>
                                <th>Ativo</th>
                                <th>Admin</th>
                                <th>Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Dados carregados via Ajax --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para visualização dos detalhes do usuário -->
<div class="modal fade" id="modalViewUser" tabindex="-1" role="dialog" aria-labelledby="modalViewUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalViewUserLabel">Detalhes do Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="user-details">
                    <p><strong>Nome:</strong> <span id="view-name"></span></p>
                    <p><strong>Email:</strong> <span id="view-email"></span></p>
                    <p><strong>Perfil:</strong> <span id="view-perfil"></span></p>
                    <p><strong>Ativo:</strong> <span id="view-ativo"></span></p>
                    <p><strong>Administrador:</strong> <span id="view-admin"></span></p>
                    <p><strong>Data de Cadastro:</strong> <span id="view-created-at"></span></p>
                    <p><strong>Gabinetes:</strong> <span id="view-gabinetes"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmação de exclusão/desativação -->
<div class="modal fade" id="modalConfirmAction" tabindex="-1" role="dialog" aria-labelledby="modalConfirmActionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmActionLabel">Confirmar Ação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirm-message">Tem certeza que deseja realizar esta ação?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-action">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para resetar senha -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" role="dialog" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalResetPasswordLabel">Resetar Senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Esta ação irá forçar o usuário a trocar sua senha no próximo login.</p>
                <p>Deseja continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="btn-confirm-reset">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function() {
        let table;
        let actionUserId = null;
        
        // Inicializa o DataTable
        table = $('#tabela-usuarios').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.list') }}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'perfil', name: 'perfil'},
                {data: 'ativo', name: 'ativo'},
                {data: 'admin', name: 'admin'},
                {data: 'created_at', name: 'created_at'},
                {
                    data: 'cod_user',
                    name: 'options',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-sm btn-view" data-id="${data}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm btn-edit" data-id="${data}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm btn-toggle" data-id="${data}">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm btn-reset" data-id="${data}">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="${data}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            }
        });

        // Botão para criar novo usuário
        $('#btn-novo-usuario').on('click', function() {
            window.location.href = "{{ route('users.create') }}";
        });

        // Botão para visualizar usuário
        $(document).on('click', '.btn-view', function() {
            const userId = $(this).data('id');
            
            $.ajax({
                url: `/admin/users/show/${userId}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const user = response.user;
                        $('#view-name').text(user.name);
                        $('#view-email').text(user.email);
                        $('#view-perfil').text(user.perfil);
                        $('#view-ativo').text(user.ativo);
                        $('#view-admin').text(user.admin);
                        $('#view-created-at').text(user.created_at);
                        $('#view-gabinetes').text(user.gabinetes.length > 0 ? user.gabinetes.join(', ') : 'Nenhum');
                        
                        $('#modalViewUser').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Erro ao buscar usuário');
                }
            });
        });

        // Botão para editar usuário
        $(document).on('click', '.btn-edit', function() {
            const userId = $(this).data('id');
            window.location.href = `/admin/users/edit/${userId}`;
        });

        // Botão para alternar status do usuário
        $(document).on('click', '.btn-toggle', function() {
            actionUserId = $(this).data('id');
            $('#confirm-message').text('Tem certeza que deseja alterar o status deste usuário?');
            $('#modalConfirmAction').modal('show');
            
            $('#btn-confirm-action').off('click').on('click', function() {
                $.ajax({
                    url: `/admin/users/toggle-status/${actionUserId}`,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#modalConfirmAction').modal('hide');
                            toastr.success(response.message);
                            table.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Erro ao alterar status do usuário');
                    }
                });
            });
        });

        // Botão para resetar senha
        $(document).on('click', '.btn-reset', function() {
            actionUserId = $(this).data('id');
            $('#modalResetPassword').modal('show');
            
            $('#btn-confirm-reset').off('click').on('click', function() {
                $.ajax({
                    url: `/admin/users/reset-password/${actionUserId}`,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#modalResetPassword').modal('hide');
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Erro ao resetar senha do usuário');
                    }
                });
            });
        });

        // Botão para excluir/desativar usuário
        $(document).on('click', '.btn-delete', function() {
            actionUserId = $(this).data('id');
            $('#confirm-message').text('Tem certeza que deseja desativar este usuário?');
            $('#modalConfirmAction').modal('show');
            
            $('#btn-confirm-action').off('click').on('click', function() {
                $.ajax({
                    url: `/admin/users/destroy/${actionUserId}`,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#modalConfirmAction').modal('hide');
                            toastr.success(response.message);
                            table.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Erro ao desativar usuário');
                    }
                });
            });
        });
    });
</script>