<!-- CSS do DataTables já está em app.blade.php, então não precisamos repetir -->

<div class="card">
    <div class="card-body" style="background-color: #FFFFFF!Important;">
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
            <button class="btn btn-primary" id="btnNovoContato">Novo Contato</button>
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
                                <input type="text" class="form-control" id="dsc_camara_municipal" name="dsc_camara_municipal">
                                <div class="invalid-feedback">Nome da câmara municipal é obrigatório</div>
                            </div>
                            <div class="tipo-especifico orgao_publico-campo d-none">
                                <label for="dsc_orgao_publico" class="form-label">Nome do Órgão Público *</label>
                                <input type="text" class="form-control" id="dsc_orgao_publico" name="dsc_orgao_publico">
                                <div class="invalid-feedback">Nome do órgão público é obrigatório</div>
                            </div>
                            <div class="tipo-especifico eleitor-campo d-none">
                                <label for="dsc_identificador_eleitor" class="form-label">Identificação do Eleitor *</label>
                                <input type="text" class="form-control" id="dsc_identificador_eleitor" name="dsc_identificador_eleitor">
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
                            <input type="text" class="form-control" id="num_telefone" name="num_telefone" required>
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
                            <input type="text" class="form-control" id="dsc_logradouro" name="dsc_logradouro" readonly required>
                            <div class="invalid-feedback">Logradouro é obrigatório</div>
                        </div>
                        <div class="col-md-4">
                            <label for="dsc_bairro" class="form-label">Bairro *</label>
                            <input type="text" class="form-control" id="dsc_bairro" name="dsc_bairro" readonly required>
                            <div class="invalid-feedback">Bairro é obrigatório</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dsc_cidade" class="form-label">Cidade *</label>
                            <input type="text" class="form-control" id="dsc_cidade" name="dsc_cidade" readonly required>
                            <div class="invalid-feedback">Cidade é obrigatória</div>
                        </div>
                        <div class="col-md-6">
                            <label for="dsc_estado" class="form-label">Estado *</label>
                            <input type="text" class="form-control" id="dsc_estado" name="dsc_estado" readonly required>
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

<!-- Toast Container para Bootstrap 5 -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toastSuccess" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Sucesso</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
    <div id="toastError" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Erro</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<!-- CSS (assumindo que já estão incluídos em app.blade.php) -->
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
                                <input type="text" class="form-control" id="dsc_camara_municipal" name="dsc_camara_municipal">
                                <div class="invalid-feedback">Nome da câmara municipal é obrigatório</div>
                            </div>
                            <div class="tipo-especifico orgao_publico-campo d-none">
                                <label for="dsc_orgao_publico" class="form-label">Nome do Órgão Público *</label>
                                <input type="text" class="form-control" id="dsc_orgao_publico" name="dsc_orgao_publico">
                                <div class="invalid-feedback">Nome do órgão público é obrigatório</div>
                            </div>
                            <div class="tipo-especifico eleitor-campo d-none">
                                <label for="dsc_identificador_eleitor" class="form-label">Identificação do Eleitor *</label>
                                <input type="text" class="form-control" id="dsc_identificador_eleitor" name="dsc_identificador_eleitor">
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
                            <input type="text" class="form-control" id="num_telefone" name="num_telefone" required>
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
                            <input type="text" class="form-control" id="dsc_logradouro" name="dsc_logradouro" readonly required>
                            <div class="invalid-feedback">Logradouro é obrigatório</div>
                        </div>
                        <div class="col-md-4">
                            <label for="dsc_bairro" class="form-label">Bairro *</label>
                            <input type="text" class="form-control" id="dsc_bairro" name="dsc_bairro" readonly required>
                            <div class="invalid-feedback">Bairro é obrigatório</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dsc_cidade" class="form-label">Cidade *</label>
                            <input type="text" class="form-control" id="dsc_cidade" name="dsc_cidade" readonly required>
                            <div class="invalid-feedback">Cidade é obrigatória</div>
                        </div>
                        <div class="col-md-6">
                            <label for="dsc_estado" class="form-label">Estado *</label>
                            <input type="text" class="form-control" id="dsc_estado" name="dsc_estado" readonly required>
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

<!-- Toast Container para Bootstrap 5 -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toastSuccess" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Sucesso</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
    <div id="toastError" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Erro</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<!-- HTML permanece igual até o final -->

<script>
    // Encapsular o código para evitar conflitos
    (function() {
        // Captura de erros globais
        window.onerror = function(message, source, lineno, colno, error) {
            console.error('Erro global detectado:', message, 'na linha', lineno, 'coluna', colno, error);
        };

        // Definir as rotas
        console.log('Definindo rotas...');
        const routes = {
            listar: '{{ route("contatos.listar") }}',
            obter: '{{ route("contatos.obter") }}',
            salvar: '{{ route("contatos.salvar") }}',
            atualizar: '{{ route("contatos.atualizar") }}',
            excluir: '{{ route("contatos.excluir") }}'
        };
        console.log('Rotas definidas com sucesso');

        // Variáveis globais
        let tabelaContatos = null;
        let toastSuccess = null;
        let toastError = null;

        // Função de inicialização
        function initElements() {
            console.log('Inicializando elementos...');

            try {
                // Inicializar toasts
                toastSuccess = new bootstrap.Toast(document.getElementById('toastSuccess'));
                toastError = new bootstrap.Toast(document.getElementById('toastError'));
                console.log('Toasts inicializados com sucesso');

                // Inicializar DataTable
                tabelaContatos = $('#tabelaContatos').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json" // URL atualizada
                    },
                    order: [[0, 'asc']],
                    columns: [
                        { data: 'txt_nome' },
                        { data: 'dsc_tipo_contato_formatado' },
                        { data: 'dsc_email' },
                        { data: 'num_telefone' },
                        { data: 'dsc_cidade' },
                        { data: 'dsc_estado' },
                        {
                            data: null,
                            orderable: false,
                            render: function(data, type, row) {
                                return '<button class="btn btn-sm btn-info btn-editar" data-id="' + row.cod_contato + '"><i class="fas fa-edit"></i> Editar</button>';
                            }
                        }
                    ]
                });
                console.log('DataTable inicializado com sucesso');

                // Carregar contatos
                carregarContatos();

                // Aplicar máscaras
                $('#num_cep').mask('00000-000');
                $('#num_telefone').mask('(00) 00000-0000');
                console.log('Máscaras aplicadas com sucesso');

                // Eventos
                document.getElementById('dsc_tipo_contato').addEventListener('change', atualizarCamposPorTipo);
                document.getElementById('num_cep').addEventListener('input', function() {
                    const cep = this.value.replace(/\D/g, '');
                    if (cep.length === 8) {
                        buscarCEP(cep);
                    }
                });

                const contatoForm = document.getElementById('contatoForm');
                contatoForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (!validarFormulario()) {
                        return;
                    }
                    salvarContato();
                });

                document.getElementById('btnSalvar').addEventListener('click', salvarContato);
                document.getElementById('btnExcluir').addEventListener('click', excluirContato);
                document.getElementById('filtroTipoContato').addEventListener('change', aplicarFiltros);
                document.getElementById('filtroNome').addEventListener('keyup', aplicarFiltros);
                document.getElementById('filtroEmail').addEventListener('keyup', aplicarFiltros);
                document.getElementById('btnLimparFiltros').addEventListener('click', limparFiltros);

                $('#tabelaContatos').on('click', '.btn-editar', function() {
                    const codContato = $(this).data('id');
                    abrirModalEdicao(codContato);
                });

                // Listener para o evento de abertura do modal
                document.getElementById('contatoModal').addEventListener('show.bs.modal', function(event) {
                    console.log('Modal prestes a ser exibido');
                    document.getElementById('contatoForm').reset();
                    document.getElementById('contatoForm').classList.remove('was-validated');
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    document.getElementById('cod_contato').value = '';
                    document.getElementById('contatoModalLabel').textContent = 'Novo Contato';
                    document.getElementById('btnExcluir').style.display = 'none';
                    document.querySelectorAll('.tipo-especifico').forEach(el => el.classList.add('d-none'));
                });

                console.log('Eventos registrados com sucesso');
            } catch (e) {
                console.error('Erro em initElements:', e);
            }
        }

        function validarFormulario() {
            const form = document.getElementById('contatoForm');
            form.classList.remove('was-validated');
            const tipoContato = document.getElementById('dsc_tipo_contato').value;

            if (!tipoContato) {
                document.getElementById('dsc_tipo_contato').classList.add('is-invalid');
                showToast('error', 'Selecione um tipo de contato');
                return false;
            }

            const campoEspecifico = document.getElementById('dsc_' + tipoContato);
            if (tipoContato === 'eleitor') {
                if (!document.getElementById('dsc_identificador_eleitor').value) {
                    document.getElementById('dsc_identificador_eleitor').classList.add('is-invalid');
                    showToast('error', 'Preencha a identificação do eleitor');
                    return false;
                }
            } else if (campoEspecifico && !campoEspecifico.value) {
                campoEspecifico.classList.add('is-invalid');
                showToast('error', 'Preencha o campo ' + campoEspecifico.previousElementSibling.textContent.trim());
                return false;
            }

            const camposObrigatorios = ['txt_nome', 'num_telefone', 'dsc_email', 'num_cep', 'dsc_logradouro', 'dsc_bairro', 'dsc_cidade', 'dsc_estado'];
            let valido = true;

            camposObrigatorios.forEach(campo => {
                const elemento = document.getElementById(campo);
                if (!elemento.value) {
                    elemento.classList.add('is-invalid');
                    valido = false;
                } else {
                    elemento.classList.remove('is-invalid');
                }
            });

            const email = document.getElementById('dsc_email').value;
            if (email && !validateEmail(email)) {
                document.getElementById('dsc_email').classList.add('is-invalid');
                showToast('error', 'Email inválido');
                valido = false;
            }

            if (!valido) {
                showToast('error', 'Preencha todos os campos obrigatórios');
            }

            return valido;
        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function atualizarCamposPorTipo() {
            const tipoContato = document.getElementById('dsc_tipo_contato').value;
            document.querySelectorAll('.tipo-especifico').forEach(el => {
                el.classList.add('d-none');
                const input = el.querySelector('input');
                if (input) {
                    input.required = false;
                }
            });

            if (tipoContato) {
                const campoEspecifico = document.querySelector('.' + tipoContato + '-campo');
                if (campoEspecifico) {
                    campoEspecifico.classList.remove('d-none');
                    const input = campoEspecifico.querySelector('input');
                    if (input) {
                        input.required = true;
                    }
                }
            }
        }

        function buscarCEP(cep) {
            console.log('Buscando CEP:', cep);
            fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                console.log('Resposta da API:', response);
                if (!response.ok) {
                    throw new Error(`Erro ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Dados do CEP:', data);
                document.getElementById('dsc_logradouro').value = data.street || '';
                document.getElementById('dsc_bairro').value = data.neighborhood || '';
                document.getElementById('dsc_cidade').value = data.city || '';
                document.getElementById('dsc_estado').value = data.state || '';
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
                document.getElementById('dsc_logradouro').value = '';
                document.getElementById('dsc_bairro').value = '';
                document.getElementById('dsc_cidade').value = '';
                document.getElementById('dsc_estado').value = '';
                showToast('error', 'Erro ao buscar o CEP: ' + error.message);
            });
        }

        function carregarContatos() {
            console.log('Carregando contatos...');
            $.ajax({
                url: routes.listar,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Resposta da API listar:', response);
                    if (response.status === 'success') {
                        const contatosFormatados = response.data.map(contato => {
                            const tiposFormatados = {
                                'prefeitura': 'Prefeitura',
                                'camara_municipal': 'Câmara Municipal',
                                'orgao_publico': 'Órgão Público',
                                'eleitor': 'Eleitor'
                            };
                            contato.dsc_tipo_contato_formatado = tiposFormatados[contato.dsc_tipo_contato] || contato.dsc_tipo_contato;
                            return contato;
                        });
                        tabelaContatos.clear().rows.add(contatosFormatados).draw();
                    } else {
                        showToast('error', response.message || 'Erro ao carregar contatos');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar contatos:', xhr, status, error);
                    showToast('error', 'Erro ao carregar contatos: ' + error);
                }
            });
        }

        function aplicarFiltros() {
            const tipoContato = document.getElementById('filtroTipoContato').value;
            const nome = document.getElementById('filtroNome').value.toLowerCase();
            const email = document.getElementById('filtroEmail').value.toLowerCase();

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const rowData = tabelaContatos.row(dataIndex).data();
                const passaTipo = !tipoContato || rowData.dsc_tipo_contato === tipoContato;
                const passaNome = !nome || (rowData.txt_nome && rowData.txt_nome.toLowerCase().includes(nome));
                const passaEmail = !email || (rowData.dsc_email && rowData.dsc_email.toLowerCase().includes(email));
                return passaTipo && passaNome && passaEmail;
            });

            tabelaContatos.draw();
            $.fn.dataTable.ext.search.pop();
        }

        function limparFiltros() {
            document.getElementById('filtroTipoContato').value = '';
            document.getElementById('filtroNome').value = '';
            document.getElementById('filtroEmail').value = '';
            tabelaContatos.search('').columns().search('').draw();
        }

        function abrirModalEdicao(codContato) {
            document.getElementById('contatoForm').reset();
            document.getElementById('contatoForm').classList.remove('was-validated');
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.getElementById('contatoModalLabel').textContent = 'Editar Contato';
            document.getElementById('btnExcluir').style.display = 'block';

            $.ajax({
                url: routes.obter,
                method: 'GET',
                data: { cod_contato: codContato },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const contato = response.data;
                        document.getElementById('cod_contato').value = contato.cod_contato;
                        document.getElementById('dsc_tipo_contato').value = contato.dsc_tipo_contato;
                        document.getElementById('txt_nome').value = contato.txt_nome;
                        document.getElementById('num_telefone').value = contato.num_telefone;
                        document.getElementById('dsc_email').value = contato.dsc_email;
                        document.getElementById('num_cep').value = contato.num_cep;
                        document.getElementById('dsc_logradouro').value = contato.dsc_logradouro;
                        document.getElementById('dsc_bairro').value = contato.dsc_bairro;
                        document.getElementById('dsc_cidade').value = contato.dsc_cidade;
                        document.getElementById('dsc_estado').value = contato.dsc_estado;
                        document.getElementById('txt_observacoes').value = contato.txt_observacoes;

                        if (contato.dsc_tipo_contato === 'prefeitura') {
                            document.getElementById('dsc_prefeitura').value = contato.dsc_prefeitura;
                        } else if (contato.dsc_tipo_contato === 'camara_municipal') {
                            document.getElementById('dsc_camara_municipal').value = contato.dsc_camara_municipal;
                        } else if (contato.dsc_tipo_contato === 'orgao_publico') {
                            document.getElementById('dsc_orgao_publico').value = contato.dsc_orgao_publico;
                        } else if (contato.dsc_tipo_contato === 'eleitor') {
                            document.getElementById('dsc_identificador_eleitor').value = contato.dsc_identificador_eleitor;
                        }

                        atualizarCamposPorTipo();
                        $('#contatoModal').modal('show');
                    } else {
                        showToast('error', response.message || 'Erro ao obter contato');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao obter contato:', xhr, status, error);
                    showToast('error', 'Erro ao obter contato: ' + error);
                }
            });
        }

        function salvarContato() {
            if (!validarFormulario()) {
                return;
            }

            const tipoContato = document.getElementById('dsc_tipo_contato').value;
            const formData = {
                cod_contato: document.getElementById('cod_contato').value,
                dsc_tipo_contato: tipoContato,
                txt_nome: document.getElementById('txt_nome').value,
                num_telefone: document.getElementById('num_telefone').value,
                dsc_email: document.getElementById('dsc_email').value,
                num_cep: document.getElementById('num_cep').value,
                dsc_logradouro: document.getElementById('dsc_logradouro').value,
                dsc_bairro: document.getElementById('dsc_bairro').value,
                dsc_cidade: document.getElementById('dsc_cidade').value,
                dsc_estado: document.getElementById('dsc_estado').value,
                txt_observacoes: document.getElementById('txt_observacoes').value,
                dsc_prefeitura: tipoContato === 'prefeitura' ? document.getElementById('dsc_prefeitura').value : '',
                dsc_camara_municipal: tipoContato === 'camara_municipal' ? document.getElementById('dsc_camara_municipal').value : '',
                dsc_orgao_publico: tipoContato === 'orgao_publico' ? document.getElementById('dsc_orgao_publico').value : '',
                dsc_identificador_eleitor: tipoContato === 'eleitor' ? document.getElementById('dsc_identificador_eleitor').value : '',
                _token: '{{ csrf_token() }}'
            };

            const isEdicao = formData.cod_contato !== '';
            const url = isEdicao ? routes.atualizar : routes.salvar;
            const method = isEdicao ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showToast('success', response.message);
                        carregarContatos();
                        $('#contatoModal').modal('hide');
                    } else {
                        showToast('error', response.message || 'Erro ao salvar o contato');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao salvar contato:', xhr, status, error);
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            showToast('error', errors[field][0]);
                        }
                    } else {
                        showToast('error', 'Erro ao processar a requisição: ' + error);
                    }
                }
            });
        }

        function excluirContato() {
            const cod_contato = document.getElementById('cod_contato').value;

            if (!cod_contato) {
                showToast('error', 'ID do contato não encontrado');
                return;
            }

            if (!confirm('Tem certeza que deseja excluir este contato?')) {
                return;
            }

            $.ajax({
                url: routes.excluir,
                method: 'DELETE',
                data: {
                    cod_contato: cod_contato,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showToast('success', response.message);
                        carregarContatos();
                        $('#contatoModal').modal('hide');
                    } else {
                        showToast('error', response.message || 'Erro ao excluir o contato');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao excluir contato:', xhr, status, error);
                    showToast('error', 'Erro ao excluir o contato: ' + error);
                }
            });
        }

        function showToast(type, message) {
            const toast = type === 'success' ? toastSuccess : toastError;
            toast.element.querySelector('.toast-body').textContent = message;
            toast.show();
        }

        // Inicializar quando o DOM estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Documento pronto, chamando initElements');
            try {
                initElements();
            } catch (e) {
                console.error('Erro ao chamar initElements:', e);
            }
        });
    })();
</script>