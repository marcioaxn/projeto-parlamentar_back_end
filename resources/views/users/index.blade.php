{{-- Arquivo: resources/views/admin/users/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cadastrar Novo Usuário</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form-create-user">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nome <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Senha <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback" id="password-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Senha <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <div class="invalid-feedback" id="password_confirmation-error"></div>
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
                                            <option value="{{ $perfil->cod_perfil }}">{{ $perfil->des_perfil }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="cod_perfil-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch mt-4">
                                        <input type="checkbox" class="custom-control-input" id="ativo" name="ativo" checked>
                                        <label class="custom-control-label" for="ativo">Usuário Ativo</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="bln_admin" name="bln_admin">
                                        <label class="custom-control-label" for="bln_admin">Administrador</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-right">
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
@endsection

@section('scripts')
<script>
    $(function() {
        // Cancelar e voltar para a listagem
        $('#btn-cancel').on('click', function() {
            window.location.href = "{{ route('users.index') }}";
        });

        // Envio do formulário via Ajax
        $('#form-create-user').on('submit', function(e) {
            e.preventDefault();
            
            // Limpa mensagens de erro anteriores
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            // Desabilita o botão de envio para evitar cliques duplos
            $('#btn-save').attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
            
            $.ajax({
                url: "{{ route('users.store') }}",
                type: 'POST',
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
    });
</script>
@endsection