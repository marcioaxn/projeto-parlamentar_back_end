<!-- Adicionar o CSS do Toastr -->


<div class="card">
    <div class="card-body" style="background-color: #FFFFFF !important;">
        <div class="mb-4">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <select class="form-select" id="filtroTipoContato">
                        <option value="">Todos os tipos</option>
                        <option value="prefeitura">Prefeitura</option>
                        <option value="camara_municipal">Câmara Municipal</option>
                        <option value="orgao_publico">Órgão Público</option>
                        <option value="eleitor">Eleitor</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <input type="text" class="form-control" id="filtroNome" placeholder="Buscar por nome...">
                </div>
                <div class="col-md-4 mb-2">
                    <input type="text" class="form-control" id="filtroEmail" placeholder="Buscar por email...">
                </div>
                <div class="col-md-1 mb-2">
                    <button class="btn btn-secondary w-100" id="btnLimparFiltros">Limpar</button>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contatoModal">
                Novo Contato
            </button>
        </div>
        <div class="table-responsive">
            <table id="tabelaContatos" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Cadastro/Edição de Contato -->
<div class="modal fade" id="contatoModal" tabindex="-1" aria-labelledby="contatoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contatoModalLabel">Novo Contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contatoForm">
                    <input type="hidden" id="cod_contato" name="cod_contato">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="dsc_tipo_contato" class="form-label">Tipo de Contato *</label>
                            <select class="form-select" id="dsc_tipo_contato" name="dsc_tipo_contato" required>
                                <option value="">Selecione...</option>
                                <option value="prefeitura">Prefeitura</option>
                                <option value="camara_municipal">Câmara Municipal</option>
                                <option value="orgao_publico">Órgão Público</option>
                                <option value="eleitor">Eleitor</option>
                            </select>
                            <div class="invalid-feedback">Tipo de contato é obrigatório</div>
                        </div>
                        <div class="col-md-8">
                            <div class="tipo-especifico prefeitura-campo d-none">
                                <label for="dsc_prefeitura" class="form-label">Nome da Prefeitura *</label>
                                <input type="text" class="form-control" id="dsc_prefeitura" name="dsc_prefeitura">
                                <div class="invalid-feedback">Nome da prefeitura é obrigatório</div>
                            </div>
                            <div class="tipo-especifico camara_municipal-campo d-none">
                                <label for="dsc_camara_municipal" class="form-label">Nome da Câmara Municipal *</label>
                                <input type="text" class="form-control" id="dsc_camara_municipal"
                                    name="dsc_camara_municipal">
                                <div class="invalid-feedback">Nome da câmara municipal é obrigatório</div>
                            </div>
                            <div class="tipo-especifico orgao_publico-campo d-none">
                                <label for="dsc_orgao_publico" class="form-label">Nome do Órgão Público *</label>
                                <input type="text" class="form-control" id="dsc_orgao_publico"
                                    name="dsc_orgao_publico">
                                <div class="invalid-feedback">Nome do órgão público é obrigatório</div>
                            </div>
                            <div class="tipo-especifico eleitor-campo d-none">
                                <label for="dsc_identificador_eleitor" class="form-label">Identificação do Eleitor
                                    *</label>
                                <input type="text" class="form-control" id="dsc_identificador_eleitor"
                                    name="dsc_identificador_eleitor">
                                <div class="invalid-feedback">Identificação do eleitor é obrigatória</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="txt_nome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="txt_nome" name="txt_nome" required>
                            <div class="invalid-feedback">Nome é obrigatório</div>
                        </div>
                        <div class="col-md-3">
                            <label for="num_telefone" class="form-label">Telefone *</label>
                            <input type="text" class="form-control" id="num_telefone" name="num_telefone"
                                required>
                            <div class="invalid-feedback">Telefone é obrigatório</div>
                        </div>
                        <div class="col-md-3">
                            <label for="dsc_email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="dsc_email" name="dsc_email" required>
                            <div class="invalid-feedback">Email é obrigatório</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="num_cep" class="form-label">CEP *</label>
                            <input type="text" class="form-control" id="num_cep" name="num_cep" required>
                            <div class="invalid-feedback">CEP é obrigatório</div>
                        </div>
                        <div class="col-md-5">
                            <label for="dsc_logradouro" class="form-label">Logradouro *</label>
                            <input type="text" class="form-control" id="dsc_logradouro" name="dsc_logradouro"
                                readonly required>
                            <div class="invalid-feedback">Logradouro é obrigatório</div>
                        </div>
                        <div class="col-md-4">
                            <label for="dsc_bairro" class="form-label">Bairro *</label>
                            <input type="text" class="form-control" id="dsc_bairro" name="dsc_bairro" readonly
                                required>
                            <div class="invalid-feedback">Bairro é obrigatório</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dsc_cidade" class="form-label">Cidade *</label>
                            <input type="text" class="form-control" id="dsc_cidade" name="dsc_cidade" readonly
                                required>
                            <div class="invalid-feedback">Cidade é obrigatória</div>
                        </div>
                        <div class="col-md-6">
                            <label for="dsc_estado" class="form-label">Estado *</label>
                            <input type="text" class="form-control" id="dsc_estado" name="dsc_estado" readonly
                                required>
                            <div class="invalid-feedback">Estado é obrigatório</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="txt_observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" id="txt_observacoes" name="txt_observacoes" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnExcluir" style="display:none;">Excluir</button>
                <button type="button" class="btn btn-primary" id="btnSalvar">Salvar</button>
            </div>
        </div>
    </div>
</div>

@include('contatos.js.script')
