<script>
    // contatos.js.script
    (function() {
        var ModuloContatos = {
            contatoModal: null,
            tabelaContatos: null,
            isInitialized: false,

            routes: {
                listar: '{{ route('contatos.listar') }}',
                obter: '{{ route('contatos.obter') }}',
                salvar: '{{ route('contatos.salvar') }}',
                atualizar: '{{ route('contatos.atualizar') }}',
                excluir: '{{ route('contatos.excluir') }}'
            },

            logInfo: function(message, data) {
                console.log(`[Contatos] INFO: ${message}`, data || '');
            },

            logError: function(message, error) {
                console.error(`[Contatos] ERRO: ${message}`, error || '');
            },

            showToast: function(type, message) {
                this.logInfo(`Exibindo toast: ${type} - ${message}`);
                if (type === 'success') {
                    toastr.success(message);
                } else {
                    toastr.error(message);
                }
            },

            carregarDependencias: function() {
                return new Promise((resolve, reject) => {
                    if (typeof jQuery === 'undefined') {
                        const jqueryScript = document.createElement('script');
                        jqueryScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
                        jqueryScript.onload = checkToastr;
                        jqueryScript.onerror = () => reject('Falha ao carregar jQuery');
                        document.head.appendChild(jqueryScript);
                    } else {
                        checkToastr();
                    }

                    function checkToastr() {
                        if (typeof toastr === 'undefined') {
                            const toastrScript = document.createElement('script');
                            toastrScript.src = 'https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js';
                            toastrScript.onload = () => {
                                toastr.options = {
                                    closeButton: true,
                                    progressBar: true,
                                    positionClass: "toast-top-right",
                                    timeOut: 3000
                                };
                                resolve();
                            };
                            toastrScript.onerror = () => reject('Falha ao carregar Toastr');
                            document.head.appendChild(toastrScript);
                        } else {
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                                positionClass: "toast-top-right",
                                timeOut: 3000
                            };
                            resolve();
                        }
                    }
                });
            },

            init: function() {
                this.contatoModal = new bootstrap.Modal(document.getElementById('contatoModal'));
                this.initElements();

                this.tabelaContatos = $('#tabelaContatos').DataTable({
                    ajax: {
                        url: this.routes.listar,
                        dataSrc: 'data'
                    },
                    language: {
                        url: "{{ asset('js/i18n/pt-BR.json') }}"
                    },
                    pageLength: 10,
                    responsive: true,
                    order: [[0, 'asc']],
                    columns: [
                        { data: 'txt_nome' },
                        { 
                            data: 'dsc_tipo_contato',
                            render: function(data) {
                                const tipos = {
                                    'prefeitura': 'Prefeitura',
                                    'camara_municipal': 'Câmara Municipal',
                                    'orgao_publico': 'Órgão Público',
                                    'eleitor': 'Eleitor'
                                };
                                return tipos[data] || data;
                            }
                        },
                        { data: 'dsc_email' },
                        { data: 'num_telefone' },
                        { data: 'dsc_cidade' },
                        { data: 'dsc_estado' },
                        {
                            data: null,
                            orderable: false,
                            render: (data, type, row) =>
                                `<button class="btn btn-sm btn-info btn-editar" data-id="${row.cod_contato}"><i class="fas fa-edit"></i> Editar</button>`
                        }
                    ]
                });
            },

            initElements: function() {
                document.getElementById('btnSalvar').addEventListener('click', this.salvarContato.bind(this));
                document.getElementById('btnExcluir').addEventListener('click', this.excluirContato.bind(this));
                document.getElementById('dsc_tipo_contato').addEventListener('change', this.toggleCamposEspecificos.bind(this));
                document.querySelector('button[data-bs-target="#contatoModal"]').addEventListener('click', this.abrirModalNovoContato.bind(this));
                document.getElementById('num_cep').addEventListener('input', function() {
                    const cep = this.value.replace(/\D/g, '');
                    if (cep.length === 8) ModuloContatos.buscarCEP(cep);
                });

                document.getElementById('dsc_tipo_contato').addEventListener('change', this.atualizarCamposPorTipo.bind(this));
                document.getElementById('filtroTipoContato').addEventListener('change', this.aplicarFiltros.bind(this));
                document.getElementById('filtroNome').addEventListener('input', this.aplicarFiltros.bind(this));
                document.getElementById('filtroEmail').addEventListener('input', this.aplicarFiltros.bind(this));
                document.getElementById('btnLimparFiltros').addEventListener('click', this.limparFiltros.bind(this));
                $('#tabelaContatos').on('click', '.btn-editar', function() {
                    const codContato = $(this).data('id');
                    ModuloContatos.abrirModalEdicao(codContato);
                });
                document.getElementById('contatoModal').addEventListener('hidden.bs.modal', this.resetForm.bind(this));
                document.getElementById('contatoForm').addEventListener('submit', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    this.salvarContato();
                });

                this.applyMask(document.getElementById('num_cep'), 'cep');
                this.applyMask(document.getElementById('num_telefone'), 'telefone');
            },

            toggleCamposEspecificos: function() {
                const tipo = document.getElementById('dsc_tipo_contato').value;
                document.querySelectorAll('.tipo-especifico').forEach(campo => campo.classList.add('d-none'));
                if (tipo) {
                    document.querySelector(`.${tipo}-campo`).classList.remove('d-none');
                }
            },

            abrirModalNovoContato: function() {
                document.getElementById('contatoForm').reset();
                document.getElementById('contatoForm').classList.remove('was-validated');
                document.getElementById('cod_contato').value = '';
                document.getElementById('contatoModalLabel').textContent = 'Novo Contato';
                document.getElementById('btnExcluir').style.display = 'none';
                this.toggleCamposEspecificos();
                this.contatoModal.show();
            },

            buscarCEP: function(cep) {
                this.logInfo(`Buscando CEP: ${cep}`);
                const camposEndereco = ['dsc_logradouro', 'dsc_bairro', 'dsc_cidade', 'dsc_estado'];
                
                // Resetar os campos de endereço antes de iniciar a busca
                camposEndereco.forEach(campo => {
                    const elemento = document.getElementById(campo);
                    elemento.value = '';
                    elemento.classList.remove('is-invalid'); // Remove qualquer validação de erro anterior
                });

                // Validar o comprimento do CEP
                if (cep.length !== 8) {
                    this.showToast('error', 'O CEP deve ter 8 dígitos.');
                    return;
                }

                // Fazer a requisição à API com um timeout para evitar travamentos
                fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`, { 
                    signal: AbortSignal.timeout(5000) // Timeout de 5 segundos
                })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 404) throw new Error('CEP não encontrado.');
                            throw new Error(`Erro ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.logInfo('Dados do CEP recebidos:', data);
                        // Verificar se os dados necessários estão presentes
                        if (!data.street || !data.neighborhood || !data.city || !data.state) {
                            throw new Error('Dados de endereço incompletos.');
                        }
                        // Preencher os campos com os dados retornados
                        document.getElementById('dsc_logradouro').value = data.street;
                        document.getElementById('dsc_bairro').value = data.neighborhood;
                        document.getElementById('dsc_cidade').value = data.city;
                        document.getElementById('dsc_estado').value = data.state;
                    })
                    .catch(error => {
                        this.logError('Erro ao buscar CEP:', error);
                        // Exibir mensagem de erro e garantir que os campos permaneçam limpos
                        this.showToast('error', error.message || 'Erro ao buscar o CEP. Verifique se o CEP está correto.');
                        camposEndereco.forEach(campo => {
                            const elemento = document.getElementById(campo);
                            elemento.value = ''; // Garante que os campos fiquem vazios
                            elemento.classList.add('is-invalid'); // Marca os campos como inválidos
                        });
                    });
            },

            applyMask: function(input, mask) {
                input.addEventListener('input', () => {
                    let value = input.value.replace(/\D/g, '');
                    if (mask === 'cep') {
                        if (value.length > 5) value = `${value.slice(0, 5)}-${value.slice(5, 8)}`;
                    } else if (mask === 'telefone') {
                        if (value.length > 2) value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}${value.length > 7 ? '-' + value.slice(7, 11) : ''}`;
                    }
                    input.value = value;
                });
            },

            resetForm: function() {
                this.logInfo('Resetando formulário');
                const form = document.getElementById('contatoForm');
                form.classList.remove('was-validated');
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                document.getElementById('cod_contato').value = '';
                document.getElementById('dsc_tipo_contato').value = '';
                document.getElementById('txt_nome').value = '';
                document.getElementById('num_telefone').value = '';
                document.getElementById('dsc_email').value = '';
                document.getElementById('num_cep').value = '';
                document.getElementById('dsc_logradouro').value = '';
                document.getElementById('dsc_bairro').value = '';
                document.getElementById('dsc_cidade').value = '';
                document.getElementById('dsc_estado').value = '';
                document.getElementById('txt_observacoes').value = '';
                document.getElementById('dsc_prefeitura').value = '';
                document.getElementById('dsc_camara_municipal').value = '';
                document.getElementById('dsc_orgao_publico').value = '';
                document.getElementById('dsc_identificador_eleitor').value = '';

                document.getElementById('contatoModalLabel').textContent = 'Novo Contato';
                document.getElementById('btnExcluir').style.display = 'none';
                document.querySelectorAll('.tipo-especifico').forEach(el => el.classList.add('d-none'));
            },

            validarFormulario: function() {
                this.logInfo('Validando formulário');
                const form = document.getElementById('contatoForm');
                form.classList.remove('was-validated');
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                const tipoContato = document.getElementById('dsc_tipo_contato').value;
                if (!tipoContato) {
                    document.getElementById('dsc_tipo_contato').classList.add('is-invalid');
                    this.showToast('error', 'Selecione um tipo de contato');
                    return false;
                }

                let campoEspecificoValido = true;
                switch (tipoContato) {
                    case 'eleitor':
                        const campoEleitor = document.getElementById('dsc_identificador_eleitor');
                        if (!campoEleitor.value.trim()) {
                            campoEleitor.classList.add('is-invalid');
                            this.showToast('error', 'Preencha a identificação do eleitor');
                            campoEspecificoValido = false;
                        }
                        break;
                    case 'prefeitura':
                        const campoPrefeitura = document.getElementById('dsc_prefeitura');
                        if (!campoPrefeitura.value.trim()) {
                            campoPrefeitura.classList.add('is-invalid');
                            this.showToast('error', 'Preencha o nome da prefeitura');
                            campoEspecificoValido = false;
                        }
                        break;
                    case 'camara_municipal':
                        const campoCamara = document.getElementById('dsc_camara_municipal');
                        if (!campoCamara.value.trim()) {
                            campoCamara.classList.add('is-invalid');
                            this.showToast('error', 'Preencha o nome da câmara municipal');
                            campoEspecificoValido = false;
                        }
                        break;
                    case 'orgao_publico':
                        const campoOrgao = document.getElementById('dsc_orgao_publico');
                        if (!campoOrgao.value.trim()) {
                            campoOrgao.classList.add('is-invalid');
                            this.showToast('error', 'Preencha o nome do órgão público');
                            campoEspecificoValido = false;
                        }
                        break;
                }
                if (!campoEspecificoValido) return false;

                const camposObrigatorios = ['txt_nome', 'num_telefone', 'dsc_email', 'num_cep', 'dsc_logradouro', 'dsc_bairro', 'dsc_cidade', 'dsc_estado'];
                let valido = true;

                camposObrigatorios.forEach(campo => {
                    const elemento = document.getElementById(campo);
                    if (!elemento.value.trim()) {
                        elemento.classList.add('is-invalid');
                        valido = false;
                    }
                });

                const email = document.getElementById('dsc_email').value;
                if (email && !(/^[^\s@]+@[^\s@]+\.[^\s@]+$/).test(email)) {
                    document.getElementById('dsc_email').classList.add('is-invalid');
                    this.showToast('error', 'Email inválido');
                    valido = false;
                }

                const cep = document.getElementById('num_cep').value;
                if (cep && !(/^\d{5}-\d{3}$/).test(cep)) {
                    document.getElementById('num_cep').classList.add('is-invalid');
                    this.showToast('error', 'CEP inválido. Use o formato XXXXX-XXX');
                    valido = false;
                }

                const telefone = document.getElementById('num_telefone').value;
                if (telefone && !(/^\(\d{2}\)\s\d{5}-\d{4}$/).test(telefone)) {
                    document.getElementById('num_telefone').classList.add('is-invalid');
                    this.showToast('error', 'Telefone inválido. Use o formato (XX) XXXXX-XXXX');
                    valido = false;
                }

                if (!valido) {
                    this.showToast('error', 'Preencha todos os campos obrigatórios corretamente');
                }

                this.logInfo(`Validação concluída. Resultado: ${valido && campoEspecificoValido}`);
                return valido && campoEspecificoValido;
            },

            atualizarCamposPorTipo: function() {
                const tipoContato = document.getElementById('dsc_tipo_contato').value;
                this.logInfo(`Atualizando campos para tipo: ${tipoContato}`);

                document.querySelectorAll('.tipo-especifico').forEach(el => {
                    el.classList.add('d-none');
                    const input = el.querySelector('input');
                    if (input) input.required = false;
                });

                if (tipoContato) {
                    const campoEspecifico = document.querySelector('.' + tipoContato + '-campo');
                    if (campoEspecifico) {
                        campoEspecifico.classList.remove('d-none');
                        const input = campoEspecifico.querySelector('input');
                        if (input) input.required = true;
                    }
                }
            },

            limparCamposCondicionais: function() {
                const camposCondicionais = ['dsc_identificador_eleitor', 'dsc_prefeitura', 'dsc_camara_municipal', 'dsc_orgao_publico'];
                camposCondicionais.forEach(campo => {
                    const elemento = document.getElementById(campo);
                    if (elemento) {
                        elemento.value = '';
                        elemento.classList.remove('is-invalid');
                    }
                });
            },

            aplicarFiltros: function() {
                const tipoContato = document.getElementById('filtroTipoContato').value;
                const nome = document.getElementById('filtroNome').value.toLowerCase();
                const email = document.getElementById('filtroEmail').value.toLowerCase();

                this.logInfo('Aplicando filtros:', { tipoContato, nome, email });

                $.fn.dataTable.ext.search.pop();
                $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
                    const rowData = this.tabelaContatos.row(dataIndex).data();
                    const passaTipo = !tipoContato || rowData.dsc_tipo_contato === tipoContato;
                    const passaNome = !nome || (rowData.txt_nome && rowData.txt_nome.toLowerCase().includes(nome));
                    const passaEmail = !email || (rowData.dsc_email && rowData.dsc_email.toLowerCase().includes(email));
                    return passaTipo && passaNome && passaEmail;
                });

                this.tabelaContatos.draw();
            },

            limparFiltros: function() {
                this.logInfo('Limpando filtros');
                document.getElementById('filtroTipoContato').value = '';
                document.getElementById('filtroNome').value = '';
                document.getElementById('filtroEmail').value = '';
                $.fn.dataTable.ext.search.pop();
                this.tabelaContatos.search('').columns().search('').draw();
            },

            abrirModalEdicao: async function(codContato) {
                this.logInfo(`Abrindo modal para edição do contato ${codContato}`);
                this.resetForm();
                document.getElementById('contatoModalLabel').textContent = 'Editar Contato';
                document.getElementById('btnExcluir').style.display = 'block';

                if (!codContato) {
                    this.logError('ID do contato não fornecido');
                    this.showToast('error', 'ID do contato não fornecido.');
                    return;
                }

                try {
                    const response = await fetch(`${this.routes.obter}?cod_contato=${encodeURIComponent(codContato)}`, {
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
                            const errorDetails = errorData.errors ? Object.values(errorData.errors)[0][0] : null;
                            this.logError('Erro 422 ao obter contato:', errorData);
                            throw new Error(errorDetails || errorMessage);
                        }
                        throw new Error(`Erro ${response.status}: ${response.statusText}`);
                    }

                    const data = await response.json();
                    this.logInfo('Resposta recebida:', data);

                    if (data.status === 'success') {
                        const contato = data.data;
                        this.logInfo('Preenchendo formulário com dados do contato:', contato);

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
                        document.getElementById('txt_observacoes').value = contato.txt_observacoes || '';

                        if (contato.dsc_tipo_contato === 'prefeitura') {
                            document.getElementById('dsc_prefeitura').value = contato.dsc_prefeitura || '';
                        } else if (contato.dsc_tipo_contato === 'camara_municipal') {
                            document.getElementById('dsc_camara_municipal').value = contato.dsc_camara_municipal || '';
                        } else if (contato.dsc_tipo_contato === 'orgao_publico') {
                            document.getElementById('dsc_orgao_publico').value = contato.dsc_orgao_publico || '';
                        } else if (contato.dsc_tipo_contato === 'eleitor') {
                            document.getElementById('dsc_identificador_eleitor').value = contato.dsc_identificador_eleitor || '';
                        }

                        this.atualizarCamposPorTipo();
                        this.contatoModal.show();
                    } else {
                        this.showToast('error', data.message || 'Erro ao obter dados do contato');
                    }
                } catch (error) {
                    this.logError('Erro ao obter contato:', error);
                    this.showToast('error', error.message || 'Erro ao obter dados do contato.');
                }
            },

            salvarContato: function() {
                const form = document.getElementById('contatoForm');
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                const codContato = document.getElementById('cod_contato').value;
                const isEdicao = codContato !== '';
                const method = isEdicao ? 'PUT' : 'POST';
                const url = isEdicao ? '{{ route('contatos.atualizar') }}' : '{{ route('contatos.salvar') }}';

                const formData = {
                    cod_contato: codContato,
                    dsc_tipo_contato: document.getElementById('dsc_tipo_contato').value,
                    txt_nome: document.getElementById('txt_nome').value,
                    num_telefone: document.getElementById('num_telefone').value,
                    dsc_email: document.getElementById('dsc_email').value,
                    num_cep: document.getElementById('num_cep').value,
                    dsc_logradouro: document.getElementById('dsc_logradouro').value,
                    dsc_bairro: document.getElementById('dsc_bairro').value,
                    dsc_cidade: document.getElementById('dsc_cidade').value,
                    dsc_estado: document.getElementById('dsc_estado').value,
                    txt_observacoes: document.getElementById('txt_observacoes').value,
                    dsc_prefeitura: document.getElementById('dsc_prefeitura').value || '',
                    dsc_camara_municipal: document.getElementById('dsc_camara_municipal').value || '',
                    dsc_orgao_publico: document.getElementById('dsc_orgao_publico').value || '',
                    dsc_identificador_eleitor: document.getElementById('dsc_identificador_eleitor').value || '',
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: (response) => {
                        if (response.status === 'success') {
                            toastr.success(response.message || 'Contato salvo com sucesso');
                            this.contatoModal.hide();
                            this.carregarContatos();
                        } else {
                            toastr.error(response.message || 'Erro ao salvar o contato');
                        }
                    },
                    error: (xhr) => {
                        console.log('Erro na requisição:', xhr);
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                toastr.error(`${field}: ${errors[field][0]}`);
                            }
                        } else {
                            toastr.error('Erro ao processar a requisição: ' + xhr.statusText);
                        }
                    }
                });
            },

            carregarContatos: function() {
                $.ajax({
                    url: '{{ route('contatos.listar') }}',
                    method: 'GET',
                    success: (response) => {
                        if (response.status === 'success') {
                            this.atualizarTabela(response.data);
                        }
                    },
                    error: (xhr) => {
                        toastr.error('Erro ao carregar contatos: ' + xhr.statusText);
                    }
                });
            },

            atualizarTabela: function(contatos) {
                const tbody = document.querySelector('#tabelaContatos tbody');
                tbody.innerHTML = '';
                contatos.forEach(contato => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${contato.txt_nome}</td>
                        <td>${contato.dsc_tipo_contato}</td>
                        <td>${contato.dsc_email}</td>
                        <td>${contato.num_telefone}</td>
                        <td>${contato.dsc_cidade}</td>
                        <td>${contato.dsc_estado}</td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-editar-contato" data-cod-contato="${contato.cod_contato}">Editar</button>
                        </td>
                    `;
                    tbody.appendChild(tr);

                    tr.querySelector('.btn-editar-contato').addEventListener('click', () => {
                        this.editarContato(contato.cod_contato);
                    });
                });
            },

            editarContato: function(codContato) {
                $.ajax({
                    url: '{{ route('contatos.obter') }}',
                    method: 'GET',
                    data: {
                        cod_contato: codContato
                    },
                    success: (response) => {
                        if (response.status === 'success') {
                            this.preencherFormulario(response.data);
                            document.getElementById('contatoModalLabel').textContent = 'Editar Contato';
                            document.getElementById('btnExcluir').style.display = 'block';
                            this.contatoModal.show();
                        } else {
                            toastr.error(response.message || 'Erro ao obter contato');
                        }
                    },
                    error: (xhr) => {
                        toastr.error('Erro ao obter contato: ' + xhr.statusText);
                    }
                });
            },

            preencherFormulario: function(contato) {
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
                document.getElementById('txt_observacoes').value = contato.txt_observacoes || '';
                document.getElementById('dsc_prefeitura').value = contato.dsc_prefeitura || '';
                document.getElementById('dsc_camara_municipal').value = contato.dsc_camara_municipal || '';
                document.getElementById('dsc_orgao_publico').value = contato.dsc_orgao_publico || '';
                document.getElementById('dsc_identificador_eleitor').value = contato.dsc_identificador_eleitor || '';
                this.toggleCamposEspecificos();
            },

            excluirContato: function() {
                const codContato = document.getElementById('cod_contato').value;
                if (!codContato) {
                    toastr.error('ID do contato não encontrado');
                    return;
                }

                if (confirm('Tem certeza que deseja excluir este contato?')) {
                    $.ajax({
                        url: '{{ route('contatos.excluir') }}',
                        method: 'DELETE',
                        data: {
                            cod_contato: codContato,
                            _token: '{{ csrf_token() }}'
                        },
                        success: (response) => {
                            if (response.status === 'success') {
                                toastr.success(response.message || 'Contato excluído com sucesso');
                                this.contatoModal.hide();
                                this.carregarContatos();
                            } else {
                                toastr.error(response.message || 'Erro ao excluir contato');
                            }
                        },
                        error: (xhr) => {
                            toastr.error('Erro ao excluir contato: ' + xhr.statusText);
                        }
                    });
                }
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            ModuloContatos.init();
        });
    })();
</script>