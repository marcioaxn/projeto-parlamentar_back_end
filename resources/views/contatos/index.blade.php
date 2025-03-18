@extends('layouts.app')

@section('content')
    <!-- Toastr CSS e JS -->
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    
    <div class="container">
        <h1>Contatos</h1>

        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-md-4">
                <select class="form-select" id="filtro-tipo">
                    <option value="">Todos os Tipos</option>
                    <option value="prefeitura">Prefeitura</option>
                    <option value="camara_municipal">Câmara Municipal</option>
                    <option value="orgao_publico">Órgão Público</option>
                    <option value="eleitor">Eleitor</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="filtro-nome" placeholder="Buscar por nome">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalContato">Adicionar
                    Contato</button>
            </div>
        </div>

        <!-- Tabela -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="lista-contatos"></tbody>
        </table>

        <!-- Paginação -->
        <div id="paginacao" class="mt-3"></div>

        <!-- Modal para Adicionar/Editar -->
        <div class="modal fade" id="modalContato" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl"> <!-- Mantendo modal-xl para mais espaço -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Adicionar Contato</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-contato">
                            @csrf
                            <input type="hidden" id="cod_contato" name="cod_contato">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="dsc_tipo_contato" class="form-label">Tipo <span class="text-danger">*</span></label>
                                        <select class="form-select" id="dsc_tipo_contato" name="dsc_tipo_contato" required
                                            onchange="mostrarCamposEspecificos()">
                                            <option value="" selected >Selecione um tipo</option>
                                            <option value="prefeitura">Prefeitura</option>
                                            <option value="camara_municipal">Câmara Municipal</option>
                                            <option value="orgao_publico">Órgão Público</option>
                                            <option value="eleitor">Eleitor</option>
                                        </select>
                                    </div>
                                    <!-- Campo específico para o tipo selecionado (dinâmico) -->
                                    <div id="campo_especifico" class="mb-3"></div>
                                    <div class="mb-3">
                                        <label for="txt_nome" class="form-label">Nome <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="txt_nome" name="txt_nome" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="num_telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="num_telefone" name="num_telefone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="dsc_email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="dsc_email" name="dsc_email">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="num_cep" class="form-label">CEP</label>
                                            <input type="text" class="form-control" id="num_cep" name="num_cep"
                                                maxlength="9"
                                                oninput="formatarCep(this); buscarEnderecoPorCep(this.value.replace('-', ''))">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="dsc_logradouro" class="form-label">Logradouro</label>
                                            <input type="text" class="form-control" id="dsc_logradouro"
                                                name="dsc_logradouro" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="dsc_bairro" class="form-label">Bairro</label>
                                            <input type="text" class="form-control" id="dsc_bairro" name="dsc_bairro"
                                                readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="dsc_cidade" class="form-label">Cidade <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="dsc_cidade" name="dsc_cidade"
                                                readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="dsc_estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="dsc_estado" name="dsc_estado"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_observacoes" class="form-label">Observações</label>
                                        <textarea class="form-control" id="txt_observacoes" name="txt_observacoes"
                                            placeholder="Adicione observações sobre o contato"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="salvar-contato">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showToast(message, isError = false) {
            // Configurações do toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000"
            };
            
            if (isError) {
                toastr.error(message, 'Erro');
            } else {
                toastr.success(message, 'Sucesso');
            }
        }

        function listarContatos() {
            const tipo = document.getElementById('filtro-tipo')?.value || '';
            const nome = document.getElementById('filtro-nome')?.value || '';
            fetch('{{ route('contatos.index') }}?' + new URLSearchParams({
                    dsc_tipo_contato: tipo,
                    txt_nome: nome
                }), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('lista-contatos');
                    if (tbody) {
                        tbody.innerHTML = '';
                        data.data.forEach(contato => {
                            tbody.innerHTML += `
                    <tr>
                        <td>${contato.txt_nome}</td>
                        <td>${contato.dsc_tipo_contato}</td>
                        <td>${contato.num_telefone || '-'}</td>
                        <td>${contato.dsc_email || '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning text-white" onclick="editarContato('${contato.cod_contato}')">Editar</button>
                            <button class="btn btn-sm btn-danger text-white" onclick="excluirContato('${contato.cod_contato}')">Excluir</button>
                        </td>
                    </tr>`;
                        });

                        // Gerar paginação com Bootstrap, garantindo renderização correta
                        const paginacao = document.getElementById('paginacao');
                        if (paginacao) {
                            let paginationHtml = `
                    <nav aria-label="Page navigation">
                        <ul class="pagination">`;

                            // Botão "Anterior"
                            paginationHtml += `
                    <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="mudarPagina(${data.current_page - 1}); return false;">Anterior</a>
                    </li>`;

                            // Números das páginas
                            for (let i = 1; i <= data.last_page; i++) {
                                paginationHtml += `
                    <li class="page-item ${data.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="mudarPagina(${i}); return false;">${i}</a>
                    </li>`;
                            }

                            // Botão "Próxima"
                            paginationHtml += `
                    <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="mudarPagina(${data.current_page + 1}); return false;">Próxima</a>
                    </li>`;

                            paginationHtml += `
                        </ul>
                    </nav>`;

                            paginacao.innerHTML = paginationHtml;
                        } else {
                            console.error('Elemento "paginacao" não encontrado no DOM.');
                        }
                    } else {
                        console.error('Elemento "lista-contatos" não encontrado no DOM.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar contatos:', error);
                    showToast('Erro ao carregar os contatos. Tente novamente.', true);
                });
        }

        // Filtros dinâmicos
        document.getElementById('filtro-tipo').addEventListener('change', listarContatos);
        document.getElementById('filtro-nome').addEventListener('keyup', listarContatos);

        // Função para mudar de página
        function mudarPagina(pagina) {
            const tipo = document.getElementById('filtro-tipo')?.value || '';
            const nome = document.getElementById('filtro-nome')?.value || '';
            fetch('{{ route('contatos.index') }}?' + new URLSearchParams({
                    dsc_tipo_contato: tipo,
                    txt_nome: nome,
                    page: pagina
                }), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('lista-contatos');
                    if (tbody) {
                        tbody.innerHTML = '';
                        data.data.forEach(contato => {
                            tbody.innerHTML += `
                    <tr>
                        <td>${contato.txt_nome}</td>
                        <td>${contato.dsc_tipo_contato}</td>
                        <td>${contato.num_telefone || '-'}</td>
                        <td>${contato.dsc_email || '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning text-white" onclick="editarContato('${contato.cod_contato}')">Editar</button>
                            <button class="btn btn-sm btn-danger text-white" onclick="excluirContato('${contato.cod_contato}')">Excluir</button>
                        </td>
                    </tr>`;
                        });

                        // Gerar paginação com Bootstrap, garantindo renderização correta
                        const paginacao = document.getElementById('paginacao');
                        if (paginacao) {
                            let paginationHtml = `
                    <nav aria-label="Page navigation">
                        <ul class="pagination">`;

                            // Botão "Anterior"
                            paginationHtml += `
                    <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="mudarPagina(${data.current_page - 1}); return false;">Anterior</a>
                    </li>`;

                            // Números das páginas
                            for (let i = 1; i <= data.last_page; i++) {
                                paginationHtml += `
                    <li class="page-item ${data.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="mudarPagina(${i}); return false;">${i}</a>
                    </li>`;
                            }

                            // Botão "Próxima"
                            paginationHtml += `
                    <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="mudarPagina(${data.current_page + 1}); return false;">Próxima</a>
                    </li>`;

                            paginationHtml += `
                        </ul>
                    </nav>`;

                            paginacao.innerHTML = paginationHtml;
                        } else {
                            console.error('Elemento "paginacao" não encontrado no DOM.');
                        }
                    } else {
                        console.error('Elemento "lista-contatos" não encontrado no DOM.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar contatos:', error);
                    showToast('Erro ao carregar os contatos. Tente novamente.', true);
                });
        }

        // Mostrar campos específicos baseados no tipo
        function mostrarCamposEspecificos() {
            const tipo = document.getElementById('dsc_tipo_contato')?.value || '';
            const campoEspecifico = document.getElementById('campo_especifico');
            if (campoEspecifico) {
                campoEspecifico.innerHTML = ''; // Limpa o conteúdo anterior

                if (tipo === 'prefeitura') {
                    campoEspecifico.innerHTML = `
                <div class="mb-3">
                    <label for="dsc_prefeitura" class="form-label">Nome da Prefeitura <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="dsc_prefeitura" name="dsc_prefeitura">
                </div>`;
                } else if (tipo === 'camara_municipal') {
                    campoEspecifico.innerHTML = `
                <div class="mb-3">
                    <label for="dsc_camara_municipal" class="form-label">Nome da Câmara Municipal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="dsc_camara_municipal" name="dsc_camara_municipal">
                </div>`;
                } else if (tipo === 'orgao_publico') {
                    campoEspecifico.innerHTML = `
                <div class="mb-3">
                    <label for="dsc_orgao_publico" class="form-label">Nome do Órgão Público <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="dsc_orgao_publico" name="dsc_orgao_publico">
                </div>`;
                } else if (tipo === 'eleitor') {
                    campoEspecifico.innerHTML = `
                <div class="mb-3">
                    <label for="dsc_identificador_eleitor" class="form-label">Identificador do Eleitor</label>
                    <input type="text" class="form-control" id="dsc_identificador_eleitor" name="dsc_identificador_eleitor" placeholder="Ex.: Nome completo ou código único">
                </div>`;
                }
            } else {
                console.error('Elemento "campo_especifico" não encontrado no DOM.');
            }
        }

        // Buscar endereço por CEP (corrigido para aceitar o CEP formatado com traço)
        function buscarEnderecoPorCep(cep) {
            cep = cep.replace(/\D/g, ''); // Remove qualquer caractere não numérico (como o traço)
            if (cep.length !== 8) {
                showToast('CEP inválido. Use 8 dígitos.', true);
                document.getElementById('dsc_logradouro').value = '';
                document.getElementById('dsc_bairro').value = '';
                document.getElementById('dsc_cidade').value = '';
                document.getElementById('dsc_estado').value = '';
                return;
            }

            fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showToast('CEP não encontrado ou inválido.', true);
                        document.getElementById('dsc_logradouro').value = '';
                        document.getElementById('dsc_bairro').value = '';
                        document.getElementById('dsc_cidade').value = '';
                        document.getElementById('dsc_estado').value = '';
                    } else {
                        document.getElementById('dsc_logradouro').value = data.street || '';
                        document.getElementById('dsc_bairro').value = data.neighborhood || '';
                        document.getElementById('dsc_cidade').value = data.city || '';
                        document.getElementById('dsc_estado').value = data.state || '';
                        showToast('Endereço preenchido com sucesso!', false);
                    }
                })
                .catch(error => {
                    showToast('Erro ao buscar o endereço. Tente novamente.', true);
                    document.getElementById('dsc_logradouro').value = '';
                    document.getElementById('dsc_bairro').value = '';
                    document.getElementById('dsc_cidade').value = '';
                    document.getElementById('dsc_estado').value = '';
                });
        }

        // Formatar CEP com máscara (permitindo 9 caracteres com traço, corrigido)
        function formatarCep(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.replace(/^(\d{5})(\d{0,3})/, '$1-$2');
            }
            input.value = value.substring(0, 9); // Limita a 8 dígitos + hífen
        }

        // Salvar contato (ajustado para depuração e envio correto)
        document.getElementById('salvar-contato').addEventListener('click', () => {
            const tipo = document.getElementById('dsc_tipo_contato')?.value || '';
            const identificador = document.getElementById('dsc_identificador_eleitor')?.value;

            if (tipo === 'eleitor' && !identificador) {
                showToast('O identificador do eleitor é obrigatório.', true);
                return;
            }

            const cod_contato = document.getElementById('cod_contato')?.value || '';
            const url = cod_contato ? '{{ route('contatos.update', '') }}/' + cod_contato :
                '{{ route('contatos.store') }}';

            const formContato = document.getElementById('form-contato');
            if (formContato) {
                // Debug: Verificar se o formulário está sendo encontrado
                console.log('Formulário encontrado:', formContato);
                console.log('Campos do formulário:', [...formContato.elements].map(el => el.id + ': ' + el.value));

                const data = new FormData(formContato);
                // Debug: Logar os dados antes de enviar
                console.log([...data.entries()]);
                data.append('_token', '{{ csrf_token() }}');

                fetch(url, {
                        method: 'POST',
                        body: data,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na resposta do servidor: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        showToast(data.message);
                        listarContatos();
                        bootstrap.Modal.getInstance(document.getElementById('modalContato')).hide();
                        formContato.reset();
                        document.getElementById('cod_contato').value = '';
                        document.getElementById('modalLabel').textContent = 'Adicionar Contato';
                        document.getElementById('dsc_prefeitura').value = '';
                        document.getElementById('dsc_camara_municipal').value = '';
                        document.getElementById('dsc_orgao_publico').value = '';
                        document.getElementById('dsc_identificador_eleitor').value = '';
                        document.getElementById('num_cep').value = '';
                        document.getElementById('dsc_logradouro').value = '';
                        document.getElementById('dsc_bairro').value = '';
                        document.getElementById('dsc_cidade').value = '';
                        document.getElementById('dsc_estado').value = '';
                        document.getElementById('txt_observacoes').value = '';
                    })
                    .catch(error => {
                        console.error('Erro ao salvar contato:', error);
                        showToast('Erro ao salvar contato. Tente novamente.', true);
                    });
            } else {
                console.error('Formulário "form-contato" não encontrado no DOM.');
            }
        });

        // Editar contato
        function editarContato(cod_contato) {
            fetch('{{ route('contatos.show', '') }}/' + cod_contato, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(contato => {
                    document.getElementById('cod_contato').value = contato.cod_contato;
                    document.getElementById('dsc_tipo_contato').value = contato.dsc_tipo_contato;
                    document.getElementById('txt_nome').value = contato.txt_nome;
                    document.getElementById('num_telefone').value = contato.num_telefone || '';
                    document.getElementById('dsc_email').value = contato.dsc_email || '';
                    document.getElementById('num_cep').value = contato.num_cep ? formatarCepDisplay(contato.num_cep) :
                        '';
                    document.getElementById('dsc_logradouro').value = contato.dsc_logradouro || '';
                    document.getElementById('dsc_bairro').value = contato.dsc_bairro || '';
                    document.getElementById('dsc_cidade').value = contato.dsc_cidade || '';
                    document.getElementById('dsc_estado').value = contato.dsc_estado || '';
                    document.getElementById('txt_observacoes').value = contato.txt_observacoes || '';

                    mostrarCamposEspecificos();
                    if (contato.dsc_tipo_contato === 'prefeitura') {
                        document.getElementById('dsc_prefeitura').value = contato.dsc_prefeitura || '';
                    } else if (contato.dsc_tipo_contato === 'camara_municipal') {
                        document.getElementById('dsc_camara_municipal').value = contato.dsc_camara_municipal || '';
                    } else if (contato.dsc_tipo_contato === 'orgao_publico') {
                        document.getElementById('dsc_orgao_publico').value = contato.dsc_orgao_publico || '';
                    } else if (contato.dsc_tipo_contato === 'eleitor') {
                        document.getElementById('dsc_identificador_eleitor').value = contato
                            .dsc_identificador_eleitor || '';
                    }

                    document.getElementById('modalLabel').textContent = 'Editar Contato';
                    new bootstrap.Modal(document.getElementById('modalContato')).show();
                });
        }

        // Função auxiliar para formatar CEP ao exibir no campo (editar)
        function formatarCepDisplay(cep) {
            return cep.replace(/^(\d{5})(\d{3})$/, '$1-$2');
        }

        // Excluir contato
        function excluirContato(cod_contato) {
            if (confirm('Deseja realmente excluir este contato?')) {
                fetch('{{ route('contatos.destroy', '') }}/' + cod_contato, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showToast(data.message);
                        listarContatos();
                    })
                    .catch(error => showToast('Erro ao excluir contato', true));
            }
        }

        listarContatos(); // Carrega inicialmente
    </script>
@endsection