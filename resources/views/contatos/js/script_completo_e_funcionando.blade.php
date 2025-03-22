
<script>
    const ContatosModule = (function() {
        // Variáveis privadas
        let tabelaContatos = null;
        let modalContato = null;
        let isInitialized = false;

        // Configuração das rotas
        const routes = {
            listar: '{{ route('contatos.listar') }}',
            obter: '{{ route('contatos.obter') }}',
            salvar: '{{ route('contatos.salvar') }}',
            atualizar: '{{ route('contatos.atualizar') }}',
            excluir: '{{ route('contatos.excluir') }}'
        };

        // Funções de log privadas
        const logInfo = (message, data) => console.log(`[Contatos] INFO: ${message}`, data || '');
        const logError = (message, error) => console.error(`[Contatos] ERRO: ${message}`, error || '');

        // Carrega as dependências necessárias (incluindo Toastr)
        const carregarDependencias = () => {
            return new Promise((resolve, reject) => {
                // Verifica se jQuery já está disponível
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
                        toastrScript.src =
                            'https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js';
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
        };

        const showToast = (type, message) => {
            logInfo(`Exibindo toast: ${type} - ${message}`);
            if (type === 'success') {
                toastr.success(message);
            } else {
                toastr.error(message);
            }
        };

        const resetForm = () => {
            logInfo('Resetando formulário');
            const form = document.getElementById('contatoForm');
            form.classList.remove('was-validated');
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Limpar os campos manualmente em vez de usar form.reset()
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
        };

        const validarFormulario = () => {
            logInfo('Validando formulário');
            const form = document.getElementById('contatoForm');
            form.classList.remove('was-validated');
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            const tipoContato = document.getElementById('dsc_tipo_contato').value;
            if (!tipoContato) {
                document.getElementById('dsc_tipo_contato').classList.add('is-invalid');
                showToast('error', 'Selecione um tipo de contato');
                return false;
            }

            let campoEspecificoValido = true;
            switch (tipoContato) {
                case 'eleitor':
                    const campoEleitor = document.getElementById('dsc_identificador_eleitor');
                    if (!campoEleitor.value.trim()) {
                        campoEleitor.classList.add('is-invalid');
                        showToast('error', 'Preencha a identificação do eleitor');
                        campoEspecificoValido = false;
                    }
                    break;
                case 'prefeitura':
                    const campoPrefeitura = document.getElementById('dsc_prefeitura');
                    if (!campoPrefeitura.value.trim()) {
                        campoPrefeitura.classList.add('is-invalid');
                        showToast('error', 'Preencha o nome da prefeitura');
                        campoEspecificoValido = false;
                    }
                    break;
                case 'camara_municipal':
                    const campoCamara = document.getElementById('dsc_camara_municipal');
                    if (!campoCamara.value.trim()) {
                        campoCamara.classList.add('is-invalid');
                        showToast('error', 'Preencha o nome da câmara municipal');
                        campoEspecificoValido = false;
                    }
                    break;
                case 'orgao_publico':
                    const campoOrgao = document.getElementById('dsc_orgao_publico');
                    if (!campoOrgao.value.trim()) {
                        campoOrgao.classList.add('is-invalid');
                        showToast('error', 'Preencha o nome do órgão público');
                        campoEspecificoValido = false;
                    }
                    break;
            }
            if (!campoEspecificoValido) return false;

            const camposObrigatorios = ['txt_nome', 'num_telefone', 'dsc_email', 'num_cep',
                'dsc_logradouro', 'dsc_bairro', 'dsc_cidade', 'dsc_estado'
            ];
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
                showToast('error', 'Email inválido');
                valido = false;
            }

            const cep = document.getElementById('num_cep').value;
            if (cep && !(/^\d{5}-\d{3}$/).test(cep)) {
                document.getElementById('num_cep').classList.add('is-invalid');
                showToast('error', 'CEP inválido. Use o formato XXXXX-XXX');
                valido = false;
            }

            const telefone = document.getElementById('num_telefone').value;
            if (telefone && !(/^\(\d{2}\)\s\d{5}-\d{4}$/).test(telefone)) {
                document.getElementById('num_telefone').classList.add('is-invalid');
                showToast('error', 'Telefone inválido. Use o formato (XX) XXXXX-XXXX');
                valido = false;
            }

            if (!valido) {
                showToast('error', 'Preencha todos os campos obrigatórios corretamente');
            }

            logInfo(`Validação concluída. Resultado: ${valido && campoEspecificoValido}`);
            return valido && campoEspecificoValido;
        };

        document.getElementById('contatoForm').addEventListener('submit', (event) => {
            event.preventDefault();
            event.stopPropagation();
            if (validarFormulario()) {
                salvarContato();
            }
        });

        const atualizarCamposPorTipo = () => {
            const tipoContato = document.getElementById('dsc_tipo_contato').value;
            logInfo(`Atualizando campos para tipo: ${tipoContato}`);

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
        };

        const buscarCEP = (cep) => {
            logInfo(`Buscando CEP: ${cep}`);
            const camposEndereco = ['dsc_logradouro', 'dsc_bairro', 'dsc_cidade', 'dsc_estado'];
            camposEndereco.forEach(campo => document.getElementById(campo).value = '');

            if (cep.length !== 8) {
                showToast('error', 'O CEP deve ter 8 dígitos.');
                return;
            }

            fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`)
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 404) throw new Error('CEP não encontrado.');
                        throw new Error(`Erro ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    logInfo('Dados do CEP recebidos:', data);
                    if (!data.street || !data.neighborhood || !data.city || !data.state) {
                        throw new Error('Dados de endereço incompletos.');
                    }
                    document.getElementById('dsc_logradouro').value = data.street;
                    document.getElementById('dsc_bairro').value = data.neighborhood;
                    document.getElementById('dsc_cidade').value = data.city;
                    document.getElementById('dsc_estado').value = data.state;
                })
                .catch(error => {
                    logError('Erro ao buscar CEP:', error);
                    showToast('error', error.message ||
                        'Erro ao buscar o CEP. Verifique se o CEP está correto.');
                });
        };

        const limparCamposCondicionais = () => {
            const camposCondicionais = [
                'dsc_identificador_eleitor',
                'dsc_prefeitura',
                'dsc_camara_municipal',
                'dsc_orgao_publico'
            ];
            camposCondicionais.forEach(campo => {
                const elemento = document.getElementById(campo);
                if (elemento) {
                    elemento.value = '';
                    elemento.classList.remove('is-invalid');
                }
            });
        };

        document.getElementById('dsc_tipo_contato').addEventListener('change', (event) => {
            limparCamposCondicionais();
            atualizarCamposPorTipo();
        });

        const carregarContatos = async () => {
            logInfo('Carregando contatos');
            try {
                const response = await fetch(routes.listar, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    if (response.status === 403 || response.status === 401) {
                        throw new Error('Acesso negado. Verifique suas permissões.');
                    }
                    throw new Error(`Erro ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                logInfo('Resposta recebida:', data);

                if (data.status === 'success') {
                    const contatosFormatados = data.data.map(contato => {
                        const tiposFormatados = {
                            'prefeitura': 'Prefeitura',
                            'camara_municipal': 'Câmara Municipal',
                            'orgao_publico': 'Órgão Público',
                            'eleitor': 'Eleitor'
                        };
                        contato.dsc_tipo_contato_formatado = tiposFormatados[contato
                            .dsc_tipo_contato] || contato.dsc_tipo_contato;
                        return contato;
                    });
                    tabelaContatos.clear().rows.add(contatosFormatados).draw();
                    logInfo(`${contatosFormatados.length} contatos carregados`);
                } else {
                    showToast('error', data.message || 'Erro ao carregar contatos');
                }
            } catch (error) {
                logError('Erro ao carregar contatos:', error);
                showToast('error', error.message ||
                    'Erro ao carregar contatos. Verifique sua conexão ou a configuração do servidor.'
                );
            }
        };

        const aplicarFiltros = () => {
            const tipoContato = document.getElementById('filtroTipoContato').value;
            const nome = document.getElementById('filtroNome').value.toLowerCase();
            const email = document.getElementById('filtroEmail').value.toLowerCase();

            logInfo('Aplicando filtros:', {
                tipoContato,
                nome,
                email
            });

            $.fn.dataTable.ext.search.pop();
            $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
                const rowData = tabelaContatos.row(dataIndex).data();
                const passaTipo = !tipoContato || rowData.dsc_tipo_contato === tipoContato;
                const passaNome = !nome || (rowData.txt_nome && rowData.txt_nome.toLowerCase()
                    .includes(nome));
                const passaEmail = !email || (rowData.dsc_email && rowData.dsc_email.toLowerCase()
                    .includes(email));
                return passaTipo && passaNome && passaEmail;
            });

            tabelaContatos.draw();
        };

        const limparFiltros = () => {
            logInfo('Limpando filtros');
            document.getElementById('filtroTipoContato').value = '';
            document.getElementById('filtroNome').value = '';
            document.getElementById('filtroEmail').value = '';
            $.fn.dataTable.ext.search.pop();
            tabelaContatos.search('').columns().search('').draw();
        };

        // Função ajustada para abrir modal de edição
        const abrirModalEdicao = async (codContato) => {
            logInfo(`Abrindo modal para edição do contato ${codContato}`);
            resetForm();
            document.getElementById('contatoModalLabel').textContent = 'Editar Contato';
            document.getElementById('btnExcluir').style.display = 'block';

            // Verifica se o cod_contato é válido
            if (!codContato) {
                logError('ID do contato não fornecido');
                showToast('error', 'ID do contato não fornecido.');
                return;
            }

            try {
                const response = await fetch(
                    `${routes.obter}?cod_contato=${encodeURIComponent(codContato)}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                if (!response.ok) {
                    // Tenta capturar detalhes do erro 422
                    if (response.status === 422) {
                        const errorData = await response.json();
                        const errorMessage = errorData.message || 'Erro de validação no servidor.';
                        const errorDetails = errorData.errors ? Object.values(errorData.errors)[0][0] :
                            null;
                        logError('Erro 422 ao obter contato:', errorData);
                        throw new Error(errorDetails || errorMessage);
                    }
                    throw new Error(`Erro ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                logInfo('Resposta recebida:', data);

                if (data.status === 'success') {
                    const contato = data.data;
                    logInfo('Preenchendo formulário com dados do contato:', contato);

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
                        document.getElementById('dsc_camara_municipal').value = contato
                            .dsc_camara_municipal || '';
                    } else if (contato.dsc_tipo_contato === 'orgao_publico') {
                        document.getElementById('dsc_orgao_publico').value = contato
                            .dsc_orgao_publico || '';
                    } else if (contato.dsc_tipo_contato === 'eleitor') {
                        document.getElementById('dsc_identificador_eleitor').value = contato
                            .dsc_identificador_eleitor || '';
                    }

                    atualizarCamposPorTipo();
                    const modal = new bootstrap.Modal(document.getElementById('contatoModal'));
                    modal.show();
                } else {
                    showToast('error', data.message || 'Erro ao obter dados do contato');
                }
            } catch (error) {
                logError('Erro ao obter contato:', error);
                showToast('error', error.message || 'Erro ao obter dados do contato.');
            }
        };

        const salvarContato = async () => {
            logInfo('Tentando salvar contato');
            if (!validarFormulario()) {
                logInfo('Validação falhou');
                return;
            }

            const tipoContato = document.getElementById('dsc_tipo_contato').value;
            const codContato = document.getElementById('cod_contato').value;
            const isEdicao = codContato !== '';

            // Log dos valores diretamente dos elementos do DOM para depuração
            const domValues = {
                cod_contato: document.getElementById('cod_contato').value,
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
                dsc_prefeitura: document.getElementById('dsc_prefeitura')?.value || '',
                dsc_camara_municipal: document.getElementById('dsc_camara_municipal')?.value || '',
                dsc_orgao_publico: document.getElementById('dsc_orgao_publico')?.value || '',
                dsc_identificador_eleitor: document.getElementById('dsc_identificador_eleitor')
                    ?.value || ''
            };
            logInfo('Valores diretamente do DOM antes do envio:', domValues);

            const formData = new FormData();
            formData.append('cod_contato', codContato);
            formData.append('dsc_tipo_contato', tipoContato);
            formData.append('txt_nome', document.getElementById('txt_nome').value);
            formData.append('num_telefone', document.getElementById('num_telefone').value);
            formData.append('dsc_email', document.getElementById('dsc_email').value);
            formData.append('num_cep', document.getElementById('num_cep').value);
            formData.append('dsc_logradouro', document.getElementById('dsc_logradouro').value);
            formData.append('dsc_bairro', document.getElementById('dsc_bairro').value);
            formData.append('dsc_cidade', document.getElementById('dsc_cidade').value);
            formData.append('dsc_estado', document.getElementById('dsc_estado').value);
            formData.append('txt_observacoes', document.getElementById('txt_observacoes').value);

            formData.append('dsc_prefeitura', tipoContato === 'prefeitura' ? (document.getElementById(
                'dsc_prefeitura')?.value || '') : '');
            formData.append('dsc_camara_municipal', tipoContato === 'camara_municipal' ? (document
                .getElementById('dsc_camara_municipal')?.value || '') : '');
            formData.append('dsc_orgao_publico', tipoContato === 'orgao_publico' ? (document
                .getElementById('dsc_orgao_publico')?.value || '') : '');
            formData.append('dsc_identificador_eleitor', tipoContato === 'eleitor' ? (document
                .getElementById('dsc_identificador_eleitor')?.value || '') : '');

            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                'content') || '');

            const debugData = {};
            for (let [key, value] of formData.entries()) {
                debugData[key] = value;
            }
            logInfo('Dados enviados para o servidor:', debugData);

            const url = isEdicao ? routes.atualizar : routes.salvar;
            const method = isEdicao ? 'POST' : 'POST';

            try {
                document.getElementById('btnSalvar').disabled = true;
                document.getElementById('btnSalvar').innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                if (!response.ok) {
                    if (response.status === 422) {
                        const errors = await response.json();
                        let errorMessage = 'Erro de validação nos dados enviados:\n';
                        for (const [field, messages] of Object.entries(errors.errors)) {
                            messages.forEach(message => {
                                errorMessage += `- ${field}: ${message}\n`;
                            });
                        }
                        throw new Error(errorMessage);
                    }
                    const errorText = await response.text();
                    logError('Resposta do servidor:', errorText);
                    throw new Error(`Erro ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                logInfo('Resposta recebida:', data);

                if (data.status === 'success') {
                    showToast('success', data.message || 'Contato salvo com sucesso');
                    carregarContatos();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('contatoModal'));
                    modal.hide();
                } else {
                    showToast('error', data.message || 'Erro ao salvar o contato');
                }
            } catch (error) {
                logError('Erro ao salvar contato:', error);
                showToast('error', error.message || 'Erro ao salvar o contato.');
            } finally {
                document.getElementById('btnSalvar').disabled = false;
                document.getElementById('btnSalvar').innerHTML = 'Salvar';
            }
        };

        const excluirContato = async () => {
            const codContato = document.getElementById('cod_contato').value;
            if (!codContato) {
                showToast('error', 'ID do contato não encontrado');
                return;
            }

            if (!confirm('Tem certeza que deseja excluir este contato?')) return;

            logInfo(`Excluindo contato ${codContato}`);
            const formData = new FormData();
            formData.append('cod_contato', codContato);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                'content') || '');

            try {
                document.getElementById('btnExcluir').disabled = true;
                document.getElementById('btnExcluir').innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...';

                const response = await fetch(routes.excluir, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                if (!response.ok) throw new Error(`Erro ${response.status}: ${response.statusText}`);
                const data = await response.json();
                logInfo('Resposta recebida:', data);

                if (data.status === 'success') {
                    showToast('success', data.message || 'Contato excluído com sucesso');
                    carregarContatos();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('contatoModal'));
                    modal.hide();
                } else {
                    showToast('error', data.message || 'Erro ao excluir o contato');
                }
            } catch (error) {
                logError('Erro ao excluir contato:', error);
                showToast('error', error.message || 'Erro ao excluir o contato.');
            } finally {
                document.getElementById('btnExcluir').disabled = false;
                document.getElementById('btnExcluir').innerHTML = 'Excluir';
            }
        };

        const init = () => {
            if (isInitialized) {
                logInfo('Módulo já inicializado');
                return;
            }

            logInfo('Inicializando módulo de contatos');
            carregarDependencias().then(() => {
                try {
                    tabelaContatos = $('#tabelaContatos').DataTable({
                        language: {
                            url: "{{ asset('js/i18n/pt-BR.json') }}"
                        },
                        pageLength: 10,
                        responsive: true,
                        order: [
                            [0, 'asc']
                        ],
                        columns: [{
                                data: 'txt_nome'
                            },
                            {
                                data: 'dsc_tipo_contato_formatado'
                            },
                            {
                                data: 'dsc_email'
                            },
                            {
                                data: 'num_telefone'
                            },
                            {
                                data: 'dsc_cidade'
                            },
                            {
                                data: 'dsc_estado'
                            },
                            {
                                data: null,
                                orderable: false,
                                render: (data, type, row) =>
                                    `<button class="btn btn-sm btn-info btn-editar" data-id="${row.cod_contato}"><i class="fas fa-edit"></i> Editar</button>`
                            }
                        ]
                    });

                    modalContato = new bootstrap.Modal(document.getElementById('contatoModal'));

                    const applyMask = (input, mask) => {
                        input.addEventListener('input', () => {
                            let value = input.value.replace(/\D/g, '');
                            if (mask === 'cep') {
                                if (value.length > 5) value =
                                    `${value.slice(0, 5)}-${value.slice(5, 8)}`;
                            } else if (mask === 'telefone') {
                                if (value.length > 2) value =
                                    `(${value.slice(0, 2)}) ${value.slice(2, 7)}${value.length > 7 ? '-' + value.slice(7, 11) : ''}`;
                            }
                            input.value = value;
                        });
                    };

                    applyMask(document.getElementById('num_cep'), 'cep');
                    applyMask(document.getElementById('num_telefone'), 'telefone');

                    document.getElementById('dsc_tipo_contato').addEventListener('change',
                        atualizarCamposPorTipo);
                    document.getElementById('num_cep').addEventListener('input', function() {
                        const cep = this.value.replace(/\D/g, '');
                        if (cep.length === 8) buscarCEP(cep);
                    });
                    document.getElementById('btnSalvar').addEventListener('click', salvarContato);
                    document.getElementById('btnExcluir').addEventListener('click', excluirContato);
                    document.getElementById('filtroTipoContato').addEventListener('change',
                        aplicarFiltros);
                    document.getElementById('filtroNome').addEventListener('input', aplicarFiltros);
                    document.getElementById('filtroEmail').addEventListener('input',
                        aplicarFiltros);
                    document.getElementById('btnLimparFiltros').addEventListener('click',
                        limparFiltros);
                    $('#tabelaContatos').on('click', '.btn-editar', function() {
                        const codContato = $(this).data('id');
                        abrirModalEdicao(codContato);
                    });
                    document.getElementById('contatoModal').addEventListener('hidden.bs.modal',
                        resetForm);
                    document.getElementById('contatoForm').addEventListener('submit', (event) => {
                        event.preventDefault();
                        event.stopPropagation();
                        salvarContato();
                    });

                    carregarContatos();
                    isInitialized = true;
                    logInfo('Módulo inicializado com sucesso');
                } catch (error) {
                    logError('Erro ao inicializar módulo:', error);
                }
            }).catch(error => {
                logError('Erro ao carregar dependências:', error);
            });
        };

        return {
            init,
            carregarContatos,
            abrirModalNovo: () => {
                resetForm();
                const modal = new bootstrap.Modal(document.getElementById('contatoModal'));
                modal.show();
            }
        };
    })();

    document.addEventListener('DOMContentLoaded', () => {
        console.log('[Contatos] Documento carregado, inicializando módulo');
        setTimeout(() => ContatosModule.init(), 100);
    });
</script>
