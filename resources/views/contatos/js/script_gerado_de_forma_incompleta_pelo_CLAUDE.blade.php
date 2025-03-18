<script>
    // Encapsular o código para evitar conflitos
    (function() {
        // Captura de erros globais
        window.onerror = function(message, source, lineno, colno, error) {
            console.error('Erro global detectado:', message, 'na linha', lineno, 'coluna', colno, error);
        };

        // Definir as rotas
        const routes = {
            listar: '{{ route('contatos.listar') }}',
            obter: '{{ route('contatos.obter') }}',
            salvar: '{{ route('contatos.salvar') }}',
            atualizar: '{{ route('contatos.atualizar') }}',
            excluir: '{{ route('contatos.excluir') }}'
        };

        // Variáveis globais
        let tabelaContatos = null;
        let toastSuccess = null;
        let toastError = null;

        // Função de inicialização
        function initElements() {
            try {
                // Inicializar toasts
                toastSuccess = new bootstrap.Toast(document.getElementById('toastSuccess'));
                toastError = new bootstrap.Toast(document.getElementById('toastError'));

                // Inicializar DataTable
                tabelaContatos = $('#tabelaContatos').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json"
                    },
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
                            render: function(data, type, row) {
                                return '<button class="btn btn-sm btn-info btn-editar" data-id="' +
                                    row.cod_contato +
                                    '"><i class="fas fa-edit"></i> Editar</button>';
                            }
                        }
                    ]
                });

                // Carregar contatos
                carregarContatos();

                // Aplicar máscaras
                if ($.fn.mask) {
                    $('#num_cep').mask('00000-000');
                    $('#num_telefone').mask('(00) 00000-0000');
                }

                // Eventos
                document.getElementById('dsc_tipo_contato').addEventListener('change', atualizarCamposPorTipo);
                document.getElementById('num_cep').addEventListener('blur', function() {
                    const cep = this.value.replace(/\D/g, '');
                    if (cep.length === 8) {
                        buscarCEP(cep);
                    }
                });

                // Corrigir para não processar submit default
                const contatoForm = document.getElementById('contatoForm');
                contatoForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    salvarContato();
                });

                // Corrigir evento de salvar
                document.getElementById('btnSalvar').addEventListener('click', function(event) {
                    event.preventDefault();
                    salvarContato();
                });

                document.getElementById('btnExcluir').addEventListener('click', excluirContato);
                document.getElementById('filtroTipoContato').addEventListener('change', aplicarFiltros);
                document.getElementById('filtroNome').addEventListener('input', aplicarFiltros);
                document.getElementById('filtroEmail').addEventListener('input', aplicarFiltros);
                document.getElementById('btnLimparFiltros').addEventListener('click', limparFiltros);

                // Corrigindo botão novo contato
                document.getElementById('btnNovoContato').addEventListener('click', function() {
                    $('#contatoModal').modal('show');
                });

                // Delegação de eventos para botões de edição
                $(document).on('click', '.btn-editar', function() {
                    const codContato = $(this).data('id');
                    abrirModalEdicao(codContato);
                });

                // Listener para o evento de abertura do modal
                const contatoModal = document.getElementById('contatoModal');
                contatoModal.addEventListener('show.bs.modal', function() {
                    document.getElementById('contatoForm').reset();
                    document.getElementById('contatoForm').classList.remove('was-validated');
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove(
                        'is-invalid'));
                    document.getElementById('cod_contato').value = '';
                    document.getElementById('contatoModalLabel').textContent = 'Novo Contato';
                    document.getElementById('btnExcluir').style.display = 'none';
                    document.querySelectorAll('.tipo-especifico').forEach(el => el.classList.add('d-none'));
                });
            } catch (e) {
                console.error('Erro em initElements:', e);
            }
        }

        function validarFormulario() {
            const form = document.getElementById('contatoForm');
            form.classList.add('was-validated');
            const tipoContato = document.getElementById('dsc_tipo_contato').value;

            if (!tipoContato) {
                document.getElementById('dsc_tipo_contato').classList.add('is-invalid');
                showToast('error', 'Selecione um tipo de contato');
                return false;
            }

            // Validação do campo específico baseado no tipo
            if (tipoContato === 'eleitor') {
                const campoEleitor = document.getElementById('dsc_identificador_eleitor');
                if (!campoEleitor.value) {
                    campoEleitor.classList.add('is-invalid');
                    showToast('error', 'Preencha a identificação do eleitor');
                    return false;
                }
            } else {
                const campoEspecifico = document.getElementById('dsc_' + tipoContato);
                if (campoEspecifico && !campoEspecifico.value) {
                    campoEspecifico.classList.add('is-invalid');
                    showToast('error', 'Preencha o campo ' + campoEspecifico.previousElementSibling.textContent
                        .trim());
                    return false;
                }
            }

            const camposObrigatorios = ['txt_nome', 'num_telefone', 'dsc_email', 'num_cep', 'dsc_logradouro',
                'dsc_bairro', 'dsc_cidade', 'dsc_estado'
            ];
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
            fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erro ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
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
            $.ajax({
                url: routes.listar,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const contatosFormatados = response.data.map(contato => {
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

            tabelaContatos.column(0).search(nome, true, false);
            tabelaContatos.column(2).search(email, true, false);

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const rowData = tabelaContatos.row(dataIndex).data();
                return !tipoContato || rowData.dsc_tipo_contato === tipoContato;
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
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.getElementById('contatoModalLabel').textContent = 'Editar Contato';
            document.getElementById('btnExcluir').style.display = 'block';

            $.ajax({
                url: routes.obter,
                method: 'GET',
                data: {
                    cod_contato: codContato
                },
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

                        // Campos específicos por tipo
                        if (contato.dsc_tipo_contato === 'prefeitura') {
                            document.getElementById('dsc_prefeitura').value = contato.dsc_prefeitura;
                        } else if (contato.dsc_tipo_contato === 'camara_municipal') {
                            document.getElementById('dsc_camara_municipal').value = contato
                                .dsc_camara_municipal;
                        } else if (contato.dsc_tipo_contato === 'orgao_publico') {
                            document.getElementById('dsc_orgao_publico').value = contato
                                .dsc_orgao_publico;
                        } else if (contato.dsc_tipo_contato === 'eleitor') {
                            document.getElementById('dsc_identificador_eleitor').value = contato
                                .dsc_identificador_eleitor;
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

            // Criar objeto FormData para envio
            const formData = new FormData(document.getElementById('contatoForm'));

            // Adicionando token CSRF
            formData.append('_token', '{{ csrf_token() }}');

            // Criar objeto FormData para envio
            const formData = new FormData(document.getElementById('contatoForm'));

            // Adicionando token CSRF
            formData.append('_token', '{{ csrf_token() }}');

            // Verificar se é uma edição ou um novo contato
            const isEdicao = document.getElementById('cod_contato').value !== '';
            const url = isEdicao ? routes.atualizar : routes.salvar;
            const method = isEdicao ? 'PUT' : 'POST';

            // Converter FormData para objeto regular para AJAX
            const formDataObj = {};
            for (const [key, value] of formData.entries()) {
                formDataObj[key] = value;
            }

            // Adicionar campos específicos do tipo de contato
            if (tipoContato === 'prefeitura') {
                formDataObj.dsc_prefeitura = document.getElementById('dsc_prefeitura').value;
            } else if (tipoContato === 'camara_municipal') {
                formDataObj.dsc_camara_municipal = document.getElementById('dsc_camara_municipal').value;
            } else if (tipoContato === 'orgao_publico') {
                formDataObj.dsc_orgao_publico = document.getElementById('dsc_orgao_publico').value;
            } else if (tipoContato === 'eleitor') {
                formDataObj.dsc_identificador_eleitor = document.getElementById('dsc_identificador_eleitor').value;
            }

            console.log('Enviando dados:', formDataObj);
            console.log('URL:', url);
            console.log('Método:', method);

            // Enviar requisição AJAX
            $.ajax({
                url: url,
                method: method,
                data: formDataObj,
                dataType: 'json',
                success: function(response) {
                    console.log('Resposta recebida:', response);
                    if (response.status === 'success') {
                        showToast('success', response.message || 'Contato salvo com sucesso');
                        carregarContatos();
                        $('#contatoModal').modal('hide');
                    } else {
                        showToast('error', response.message || 'Erro ao salvar o contato');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao salvar contato:', xhr, status, error);
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
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
                        showToast('success', response.message || 'Contato excluído com sucesso');
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
            toast._element.querySelector('.toast-body').textContent = message;
            toast.show();
        }

        // Inicializar quando o DOM estiver pronto
        $(document).ready(function() {
            console.log('Documento pronto, chamando initElements');
            try {
                initElements();

                // Corrigindo o evento do botão Novo Contato
                document.getElementById('btnNovoContato').addEventListener('click', function() {
                    $('#contatoModal').modal('show');
                });
            } catch (e) {
                console.error('Erro ao chamar initElements:', e);
            }
        });

    })();
</script>
