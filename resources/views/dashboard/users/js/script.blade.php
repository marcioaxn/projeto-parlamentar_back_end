<script>
    (function() {
        var ModuloUsuarios = {
            userModal: null,
            tabelaUsuarios: null,
            isInitialized: false,

            routes: {
                listar: '{{ route('users.list') }}',
                obter: '{{ url('/dashboard/users/edit') }}',
                salvar: '{{ route('users.store') }}',
                atualizar: '{{ url('/dashboard/users/update') }}',
                excluir: '{{ url('/dashboard/users/delete') }}'
            },

            logInfo: function(message, data) {
                console.log(`[Usuarios] INFO: ${message}`, data || '');
            },

            logError: function(message, error) {
                console.error(`[Usuarios] ERRO: ${message}`, error || '');
            },

            waitForToastr: function(callback) {
                const maxAttempts = 10; // Tentar por até 5 segundos (10 tentativas de 500ms)
                let attempts = 0;

                const checkToastr = () => {
                    if (typeof toastr !== 'undefined') {
                        callback();
                    } else {
                        attempts++;
                        if (attempts >= maxAttempts) {
                            this.logError(
                                'Toastr não está disponível após várias tentativas. Certifique-se de que o script do Toastr foi carregado.'
                                );
                            console.log(
                            `[Toastr Fallback] ${callback.message}`); // Fallback para o console
                            return;
                        }
                        setTimeout(checkToastr, 500); // Tenta novamente após 500ms
                    }
                };

                checkToastr();
            },

            showToast: function(type, message) {
                this.logInfo(`Exibindo toast: ${type} - ${message}`);
                this.waitForToastr(() => {
                    if (type === 'success') {
                        toastr.success(message);
                    } else {
                        toastr.error(message);
                    }
                });
            },

            init: function() {
                this.userModal = new bootstrap.Modal(document.getElementById('userModal'));
                this.initElements();

                this.tabelaUsuarios = $('#tabelaUsuarios').DataTable({
                    ajax: {
                        url: this.routes.listar,
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}';
                        },
                        error: function(xhr, error, thrown) {
                            console.log('Erro na requisição Ajax do DataTables:', xhr
                                .responseText);
                            ModuloUsuarios.showToast('error', 'Erro ao carregar usuários: ' +
                                xhr.statusText);
                        }
                    },
                    language: {
                        url: "{{ asset('js/i18n/pt-BR.json') }}"
                    },
                    pageLength: 10,
                    responsive: true,
                    order: [
                        [0, 'asc']
                    ],
                    columns: [{
                            data: 'name'
                        },
                        {
                            data: 'email'
                        },
                        {
                            data: 'ativo',
                            render: function(data) {
                                return data == 1 ? 'Sim' : 'Não';
                            }
                        },
                        {
                            data: 'bln_admin',
                            render: function(data) {
                                return data == 1 ? 'Sim' : 'Não';
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            render: (data, type, row) =>
                                `<button class="btn btn-sm btn-warning btn-editar" data-id="${row.id}"><i class="fas fa-edit"></i> Editar</button>
                                 <button class="btn btn-sm btn-danger btn-excluir" data-id="${row.id}"><i class="fas fa-trash"></i> Excluir</button>`
                        }
                    ]
                });
            },

            initElements: function() {
                document.getElementById('btnSalvar').addEventListener('click', this.salvarUsuario.bind(
                    this));
                document.getElementById('btnExcluir').addEventListener('click', this.excluirUsuario.bind(
                    this));
                document.getElementById('btnAddUser').addEventListener('click', this.abrirModalNovoUsuario
                    .bind(this));
                $('#tabelaUsuarios').on('click', '.btn-editar', function() {
                    const id = $(this).data('id');
                    ModuloUsuarios.abrirModalEdicao(id);
                });
                $('#tabelaUsuarios').on('click', '.btn-excluir', function() {
                    const id = $(this).data('id');
                    ModuloUsuarios.excluirUsuarioDireto(id);
                });
                document.getElementById('userModal').addEventListener('hidden.bs.modal', this.resetForm
                    .bind(this));
                document.getElementById('userForm').addEventListener('submit', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    this.salvarUsuario();
                });

                // Eventos para os filtros
                document.getElementById('filtroNome').addEventListener('input', this.aplicarFiltros.bind(
                    this));
                document.getElementById('filtroEmail').addEventListener('input', this.aplicarFiltros.bind(
                    this));
                document.getElementById('filtroAtivo').addEventListener('change', this.aplicarFiltros.bind(
                    this));
                document.getElementById('filtroAdmin').addEventListener('change', this.aplicarFiltros.bind(
                    this));
                document.getElementById('btnLimparFiltros').addEventListener('click', this.limparFiltros
                    .bind(this));
            },

            abrirModalNovoUsuario: function() {
                document.getElementById('userForm').reset();
                document.getElementById('userForm').classList.remove('was-validated');
                document.getElementById('cod_usuario').value = '';
                document.getElementById('userModalLabel').textContent = 'Novo Usuário';
                document.getElementById('btnExcluir').style.display = 'none';
                this.userModal.show();
            },

            resetForm: function() {
                this.logInfo('Resetando formulário');
                const form = document.getElementById('userForm');
                form.classList.remove('was-validated');
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                document.getElementById('cod_usuario').value = '';
                document.getElementById('name').value = '';
                document.getElementById('email').value = '';
                document.getElementById('ativo').value = '';
                document.getElementById('bln_admin').value = '';

                document.getElementById('userModalLabel').textContent = 'Novo Usuário';
                document.getElementById('btnExcluir').style.display = 'none';
            },

            validarFormulario: function() {
                this.logInfo('Validando formulário');
                const form = document.getElementById('userForm');
                form.classList.remove('was-validated');
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                const camposObrigatorios = ['name', 'email', 'ativo', 'bln_admin'];
                let valido = true;

                camposObrigatorios.forEach(campo => {
                    const elemento = document.getElementById(campo);
                    if (!elemento.value.trim()) {
                        elemento.classList.add('is-invalid');
                        valido = false;
                    }
                });

                const email = document.getElementById('email').value;
                if (email && !(/^[^\s@]+@[^\s@]+\.[^\s@]+$/).test(email)) {
                    document.getElementById('email').classList.add('is-invalid');
                    this.showToast('error', 'Email inválido');
                    valido = false;
                }

                if (!valido) {
                    this.showToast('error', 'Preencha todos os campos obrigatórios corretamente');
                }

                this.logInfo(`Validação concluída. Resultado: ${valido}`);
                return valido;
            },

            aplicarFiltros: function() {
                const nome = document.getElementById('filtroNome').value.toLowerCase();
                const email = document.getElementById('filtroEmail').value.toLowerCase();
                const ativo = document.getElementById('filtroAtivo').value;
                const admin = document.getElementById('filtroAdmin').value;

                this.logInfo('Aplicando filtros:', {
                    nome,
                    email,
                    ativo,
                    admin
                });

                $.fn.dataTable.ext.search.pop();
                $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
                    const rowData = this.tabelaUsuarios.row(dataIndex).data();
                    const passaNome = !nome || (rowData.name && rowData.name.toLowerCase().includes(
                        nome));
                    const passaEmail = !email || (rowData.email && rowData.email.toLowerCase()
                        .includes(email));
                    const passaAtivo = !ativo || rowData.ativo.toString() === ativo;
                    const passaAdmin = !admin || rowData.bln_admin.toString() === admin;
                    return passaNome && passaEmail && passaAtivo && passaAdmin;
                });

                this.tabelaUsuarios.draw();
            },

            limparFiltros: function() {
                this.logInfo('Limpando filtros');
                document.getElementById('filtroNome').value = '';
                document.getElementById('filtroEmail').value = '';
                document.getElementById('filtroAtivo').value = '';
                document.getElementById('filtroAdmin').value = '';
                $.fn.dataTable.ext.search.pop();
                this.tabelaUsuarios.search('').columns().search('').draw();
            },

            abrirModalEdicao: async function(id) {
                this.logInfo(`Abrindo modal para edição do usuário ${id}`);
                this.resetForm();
                document.getElementById('userModalLabel').textContent = 'Editar Usuário';
                document.getElementById('btnExcluir').style.display = 'block';

                if (!id) {
                    this.logError('ID do usuário não fornecido');
                    this.showToast('error', 'ID do usuário não fornecido.');
                    return;
                }

                try {
                    const response = await fetch(`${this.routes.obter}/${id}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        if (response.status === 422) {
                            const errorData = await response.json();
                            const errorMessage = errorData.message || 'Erro de validação no servidor.';
                            const errorDetails = errorData.errors ? Object.values(errorData.errors)[0][0] :
                                null;
                            this.logError('Erro 422 ao obter usuário:', errorData);
                            throw new Error(errorDetails || errorMessage);
                        }
                        throw new Error(`Erro ${response.status}: ${response.statusText}`);
                    }

                    const data = await response.json();
                    this.logInfo('Resposta recebida:', data);

                    if (data.success) {
                        const usuario = data.data;
                        this.logInfo('Preenchendo formulário com dados do usuário:', usuario);

                        document.getElementById('cod_usuario').value = usuario.id;
                        document.getElementById('name').value = usuario.name;
                        document.getElementById('email').value = usuario.email;
                        document.getElementById('ativo').value = usuario.ativo;
                        document.getElementById('bln_admin').value = usuario.bln_admin;

                        this.userModal.show();
                    } else {
                        this.showToast('error', data.message || 'Erro ao obter dados do usuário');
                    }
                } catch (error) {
                    this.logError('Erro ao obter usuário:', error);
                    this.showToast('error', error.message || 'Erro ao obter dados do usuário.');
                }
            },

            salvarUsuario: function() {
                if (!this.validarFormulario()) {
                    return;
                }

                // Desabilitar o botão e exibir o spinner
                const btnSalvar = document.getElementById('btnSalvar');
                const btnText = btnSalvar.querySelector('.btn-text');
                const spinner = btnSalvar.querySelector('.spinner-border');
                btnSalvar.disabled = true;
                btnText.textContent = 'Salvando...';
                spinner.classList.remove('d-none');

                const id = document.getElementById('cod_usuario').value;
                const isEdicao = id !== '';
                const method = isEdicao ? 'PUT' : 'POST';
                const url = isEdicao ? `${this.routes.atualizar}/${id}` : this.routes.salvar;

                const formData = {
                    id: id,
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    ativo: document.getElementById('ativo').value,
                    bln_admin: document.getElementById('bln_admin').value,
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: (response) => {
                        if (response.success) {
                            this.showToast('success', response.message ||
                                'Usuário salvo com sucesso');
                            this.userModal.hide();
                            this.tabelaUsuarios.ajax.reload();
                        } else {
                            this.showToast('error', response.message ||
                                'Erro ao salvar o usuário');
                        }
                    },
                    error: (xhr) => {
                        this.logError('Erro na requisição:', xhr);
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                this.showToast('error', `${field}: ${errors[field][0]}`);
                            }
                        } else {
                            this.showToast('error', 'Erro ao processar a requisição: ' + xhr
                                .statusText);
                        }
                    },
                    complete: () => {
                        // Reabilitar o botão e esconder o spinner
                        btnSalvar.disabled = false;
                        btnText.textContent = 'Salvar';
                        spinner.classList.add('d-none');
                    }
                });
            },

            excluirUsuario: function() {
                const id = document.getElementById('cod_usuario').value;
                if (!id) {
                    this.showToast('error', 'ID do usuário não encontrado');
                    return;
                }

                if (confirm('Tem certeza que deseja excluir este usuário?')) {
                    $.ajax({
                        url: `${this.routes.excluir}/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: (response) => {
                            if (response.success) {
                                this.showToast('success', response.message ||
                                    'Usuário excluído com sucesso');
                                this.userModal.hide();
                                this.tabelaUsuarios.ajax.reload();
                            } else {
                                this.showToast('error', response.message ||
                                    'Erro ao excluir usuário');
                            }
                        },
                        error: (xhr) => {
                            this.showToast('error', 'Erro ao excluir usuário: ' + xhr
                                .statusText);
                        }
                    });
                }
            },

            excluirUsuarioDireto: function(id) {
                if (!id) {
                    this.showToast('error', 'ID do usuário não encontrado');
                    return;
                }

                if (confirm('Tem certeza que deseja excluir este usuário?')) {
                    $.ajax({
                        url: `${this.routes.excluir}/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: (response) => {
                            if (response.success) {
                                this.showToast('success', response.message ||
                                    'Usuário excluído com sucesso');
                                this.tabelaUsuarios.ajax.reload();
                            } else {
                                this.showToast('error', response.message ||
                                    'Erro ao excluir usuário');
                            }
                        },
                        error: (xhr) => {
                            this.showToast('error', 'Erro ao excluir usuário: ' + xhr
                                .statusText);
                        }
                    });
                }
            }
        };

        window.onload = function() {
            ModuloUsuarios.init();
        };
    })();
</script>
