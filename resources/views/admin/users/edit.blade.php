{{-- Arquivo: resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Usuário</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form-edit-user">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="cod_user" value="{{ $user->cod_user }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nome <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cod_perfil">Perfil <span class="text-danger">*</span></label>
                                    <select class="form-control" id="cod_perfil" name="cod_perfil" required>
                                        <option value="">Selecione...</option>
                                        @foreach($perfis as $perfil)
                                            <option value="{{ $perfil->cod_perfil }}" {{ $user->cod_perfil == $perfil->cod_perfil ? 'selected' : '' }}>
                                                {{ $perfil->des_perfil }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="cod_perfil-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch mt-4">
                                        <input type="checkbox" class="custom-control-input" id="ativo" name="ativo" {{ $user->ativo == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="ativo">Usuário Ativo</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="bln_admin" name="bln_admin" {{ $user->bln_admin ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="bln_admin">Administrador</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-warning mr-2" id="btn-change-password">Alterar Senha</button>
                                <button type="button" class="btn btn-secondary" id="btn-cancel">Cancelar</button>
                                <button type="submit" class="btn btn-primary" id="btn-save">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para alterar senha -->
<div class="modal fade" id="modalChangePassword" tabindex="-1" role="dialog" aria-labelledby="modalChangePasswordLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalChangePasswordLabel">Alterar Senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-change-password">
                    @csrf
                    <div class="form-group">
                        <label for="password">Nova Senha <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback" id="password-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Nova Senha <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        <div class="invalid-feedback" id="password_confirmation-error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-save-password">Salvar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function() {
        // Cancelar e voltar para a listagem
        $('#btn-cancel').on('click', function() {
            window.location.href = "{{ route('users.index') }}";
        });

        // Envio do formulário de edição via Ajax
        $('#form-edit-user').on('submit', function(e) {
            e.preventDefault();
            
            // Limpa mensagens de erro anteriores
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            // Desabilita o botão de envio para evitar cliques duplos
            $('#btn-save').attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
            
            $.ajax({
                url: "{{ route('users.update', $user->cod_user) }}",
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = "{{ route('users.index') }}";
                        }, 1500);
                    } else {
                        $('#btn-save').attr('disabled', false).html('Salvar');
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    $('#btn-save').attr('disabled', false).html('Salvar');
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}-error`).text(errors[key][0]);
                        }
                        toastr.error('Existem erros no formulário. Por favor, verifique.');
                    } else {
                        toastr.error('Ocorreu um erro ao processar a solicitação.');
                    }
                }
            });
        });

        // Abrir modal para alteração de senha
        $('#btn-change-password').on('click', function() {
            $('#modalChangePassword').modal('show');
        });

        // Salvar nova senha
        $('#btn-save-password').on('click', function() {
            // Limpa mensagens de erro anteriores
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            // Desabilita o botão de envio para evitar cliques duplos
            $(this).attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
            
            $.ajax({
                url: "{{ route('users.change-password', $user->cod_user) }}",
                type: 'POST',
                data: $('#form-change-password').serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#modalChangePassword').modal('hide');
                        toastr.success(response.message);
                        $('#btn-save-password').attr('disabled', false).html('Salvar');
                    } else {
                        $('#btn-save-password').attr('disabled', false).html('Salvar');
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    $('#btn-save-password').attr('disabled', false).html('Salvar');
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}-error`).text(errors[key][0]);
                        }
                        toastr.error('Existem erros no formulário. Por favor, verifique.');
                    } else {
                        toastr.error('Ocorreu um erro ao processar a solicitação.');
                    }
                }
            });
        });
    });
</script>
@endsection