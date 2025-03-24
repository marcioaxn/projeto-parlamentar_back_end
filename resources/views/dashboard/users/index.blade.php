@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1>Gestão de Usuários</h1>

        <!-- Filtros -->
        <div class="mb-4">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="text" class="form-control" id="filtroNome" placeholder="Buscar por nome...">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" class="form-control" id="filtroEmail" placeholder="Buscar por e-mail...">
                </div>
                <div class="col-md-2 mb-2">
                    <select class="form-select" id="filtroAtivo">
                        <option value="">Ativo (Todos)</option>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select class="form-select" id="filtroAdmin">
                        <option value="">Administrador (Todos)</option>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button class="btn btn-secondary w-100" id="btnLimparFiltros">Limpar</button>
                </div>
            </div>
        </div>

        <!-- Botão para adicionar novo usuário -->
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal"
                id="btnAddUser">
                Novo Usuário
            </button>
        </div>

        <!-- Tabela de usuários -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="tabelaUsuarios">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Ativo</th>
                        <th>Administrador</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal para criar/editar usuário -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Novo Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="cod_usuario" name="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Nome é obrigatório</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">E-mail é obrigatório</div>
                        </div>
                        <div class="mb-3">
                            <label for="ativo" class="form-label">Ativo *</label>
                            <select class="form-select" id="ativo" name="ativo" required>
                                <option value="">Selecione...</option>
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                            <div class="invalid-feedback">Ativo é obrigatório</div>
                        </div>
                        <div class="mb-3">
                            <label for="bln_admin" class="form-label">Administrador *</label>
                            <select class="form-select" id="bln_admin" name="bln_admin" required>
                                <option value="">Selecione...</option>
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                            <div class="invalid-feedback">Administrador é obrigatório</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnExcluir" style="display:none;">Excluir</button>
                    <button type="button" id="btnSalvar" class="btn btn-primary">
                        <span class="btn-text">Salvar</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @include('dashboard.users.js.script')
@endsection
