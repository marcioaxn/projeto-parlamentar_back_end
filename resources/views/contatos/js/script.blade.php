<script>
    // JavaScript para gerenciar o módulo de contatos
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa as máscaras para os campos
        $('#num_telefone').mask('(00) 00000-0000');
        $('#num_cep').mask('00000-000');

        // Carrega os contatos ao iniciar a página
        loadContatos();

        // Evento para verificar o CEP quando digitado
        $('#num_cep').on('input', function() {
            const cep = $(this).val().replace('-', '');
            if (cep.length === 9) { // Considerando o hífen (8 dígitos + hífen)
                buscarCep(cep.replace('-', ''));
            }
        });

        // Evento para mostrar campos específicos com base no tipo de contato
        $('#dsc_tipo_contato').on('change', function() {
            const tipoContato = $(this).val();
            mostrarCampoTipoContato(tipoContato);
        });
    });

    // Função para carregar a lista de contatos
    function loadContatos(filtros = {}) {
        $.ajax({
            url: route('contatos.listar'),
            type: 'GET',
            data: filtros,
            beforeSend: function() {
                $('#contatos-list').html('<tr><td colspan="6" class="text-center">Carregando...</td></tr>');
            },
            success: function(response) {
                $('#contatos-list').empty();

                if (response.data.length === 0) {
                    $('#contatos-list').html(
                        '<tr><td colspan="6" class="text-center">Nenhum contato encontrado</td></tr>');
                    return;
                }

                $.each(response.data, function(index, contato) {
                    const tipoLabel = getTipoLabel(contato.dsc_tipo_contato);

                    $('#contatos-list').append(`
                <tr>
                    <td>${contato.txt_nome}</td>
                    <td>${tipoLabel}</td>
                    <td>${contato.num_telefone}</td>
                    <td>${contato.dsc_email}</td>
                    <td>${contato.dsc_cidade}/${contato.dsc_estado}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="openEditModal('${contato.cod_contato}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="openDeleteModal('${contato.cod_contato}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
                });
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.message :
                    'Ocorreu um erro ao carregar os contatos';
                toastr.error(errorMsg);
            }
        });
    }

    // Função para filtrar contatos
    function filtrarContatos() {
        const filtros = {
            tipo: $('#filtro_tipo').val(),
            nome: $('#filtro_nome').val(),
            email: $('#filtro_email').val()
        };

        loadContatos(filtros);
    }

    // Função para limpar filtros
    function limparFiltros() {
        $('#filtro_tipo').val('');
        $('#filtro_nome').val('');
        $('#filtro_email').val('');

        loadContatos();
    }

    // Função para buscar CEP via API
    function buscarCep(cep) {
        fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('CEP não encontrado ou inválido');
                }
                return response.json();
            })
            .then(data => {
                $('#dsc_logradouro').val(data.street || '');
                $('#dsc_bairro').val(data.neighborhood || '');
                $('#dsc_cidade').val(data.city || '');
                $('#dsc_estado').val(data.state || '');
            })
            .catch(error => {
                // Limpa os campos
                $('#dsc_logradouro').val('');
                $('#dsc_bairro').val('');
                $('#dsc_cidade').val('');
                $('#dsc_estado').val('');

                toastr.error('CEP não encontrado ou inválido.');
            });
    }

    // Função para mostrar campos específicos do tipo de contato
    function mostrarCampoTipoContato(tipo) {
        // Esconde todos os campos específicos
        $('.campo-tipo').hide();

        // Remove a obrigatoriedade de todos os campos específicos
        $('#dsc_prefeitura').prop('required', false);
        $('#dsc_camara_municipal').prop('required', false);
        $('#dsc_orgao_publico').prop('required', false);
        $('#dsc_identificador_eleitor').prop('required', false);

        // Mostra o campo específico conforme o tipo selecionado
        if (tipo) {
            $(`#campo-${tipo}`).show();
            $(`#dsc_${tipo === 'eleitor' ? 'identificador_eleitor' : tipo}`).prop('required', true);
        }
    }

    // Função para abrir o modal de criação
    function openCreateModal() {
        // Limpa o formulário
        $('#contatoForm')[0].reset();
        $('#cod_contato').val('');

        // Esconde todos os campos específicos
        $('.campo-tipo').hide();

        // Atualiza o título do modal
        $('#contatoModalLabel').text('Novo Contato');

        // Abre o modal
        $('#contatoModal').modal('show');
    }

    // Função para abrir o modal de edição
    function openEditModal(codContato) {
        // Limpa o formulário
        $('#contatoForm')[0].reset();

        // Carrega os dados do contato
        $.ajax({
            url: route('contatos.obter', {
                id: codContato
            }),
            type: 'GET',
            success: function(response) {
                const contato = response.data;

                // Preenche os campos do formulário
                $('#cod_contato').val(contato.cod_contato);
                $('#dsc_tipo_contato').val(contato.dsc_tipo_contato);
                $('#txt_nome').val(contato.txt_nome);
                $('#num_telefone').val(contato.num_telefone);
                $('#dsc_email').val(contato.dsc_email);
                $('#num_cep').val(contato.num_cep);
                $('#dsc_logradouro').val(contato.dsc_logradouro);
                $('#dsc_bairro').val(contato.dsc_bairro);
                $('#dsc_cidade').val(contato.dsc_cidade);
                $('#dsc_estado').val(contato.dsc_estado);
                $('#txt_observacoes').val(contato.txt_observacoes);

                // Mostra o campo específico conforme o tipo
                mostrarCampoTipoContato(contato.dsc_tipo_contato);

                // Preenche o campo específico
                if (contato.dsc_tipo_contato === 'prefeitura') {
                    $('#dsc_prefeitura').val(contato.dsc_prefeitura);
                } else if (contato.dsc_tipo_contato === 'camara_municipal') {
                    $('#dsc_camara_municipal').val(contato.dsc_camara_municipal);
                } else if (contato.dsc_tipo_contato === 'orgao_publico') {
                    $('#dsc_orgao_publico').val(contato.dsc_orgao_publico);
                } else if (contato.dsc_tipo_contato === 'eleitor') {
                    $('#dsc_identificador_eleitor').val(contato.dsc_identificador_eleitor);
                }

                // Atualiza o título do modal
                $('#contatoModalLabel').text('Editar Contato');

                // Abre o modal
                $('#contatoModal').modal('show');
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.message :
                    'Ocorreu um erro ao carregar o contato';
                toastr.error(errorMsg);
            }
        });
    }

    // Função para abrir o modal de exclusão
    function openDeleteModal(codContato) {
        $('#delete_cod_contato').val(codContato);
        $('#deleteModal').modal('show');
    }

    // Função para salvar o contato (criar ou atualizar)
    function saveContato() {
        // Valida o formulário
        if (!validarFormulario()) {
            return;
        }

        const codContato = $('#cod_contato').val();
        const isUpdate = codContato !== '';

        // Prepara os dados
        const formData = {
            dsc_tipo_contato: $('#dsc_tipo_contato').val(),
            txt_nome: $('#txt_nome').val(),
            num_telefone: $('#num_telefone').val(),
            dsc_email: $('#dsc_email').val(),
            num_cep: $('#num_cep').val(),
            dsc_logradouro: $('#dsc_logradouro').val(),
            dsc_bairro: $('#dsc_bairro').val(),
            dsc_cidade: $('#dsc_cidade').val(),
            dsc_estado: $('#dsc_estado').val(),
            txt_observacoes: $('#txt_observacoes').val()
        };

        // Adiciona o campo específico conforme o tipo
        const tipoContato = $('#dsc_tipo_contato').val();
        if (tipoContato === 'prefeitura') {
            formData.dsc_prefeitura = $('#dsc_prefeitura').val();
        } else if (tipoContato === 'camara_municipal') {
            formData.dsc_camara_municipal = $('#dsc_camara_municipal').val();
        } else if (tipoContato === 'orgao_publico') {
            formData.dsc_orgao_publico = $('#dsc_orgao_publico').val();
        } else if (tipoContato === 'eleitor') {
            formData.dsc_identificador_eleitor = $('#dsc_identificador_eleitor').val();
        }

        // Configura a requisição baseada na operação (criar ou atualizar)
        let url, method;
        if (isUpdate) {
            url = route('contatos.atualizar', {
                id: codContato
            });
            method = 'PUT';
        } else {
            url = route('contatos.salvar');
            method = 'POST';
        }

        // Envia a requisição
        $.ajax({
            url: url,
            type: method,
            data: formData,
            beforeSend: function() {
                $('#saveButton').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Salvando...');
            },
            success: function(response) {
                toastr.success(response.message);
                $('#contatoModal').modal('hide');
                loadContatos();
            },
            error: function(xhr) {
                const data = xhr.responseJSON;

                if (data && data.errors) {
                    // Mostra os erros de validação
                    $.each(data.errors, function(field, errors) {
                        toastr.error(`${field}: ${errors[0]}`);
                    });
                } else {
                    toastr.error(data ? data.message : 'Ocorreu um erro ao salvar o contato');
                }
            },
            complete: function() {
                $('#saveButton').prop('disabled', false).text('Salvar');
            }
        });
    }

    // Função para excluir o contato
    function deleteContato() {
        const codContato = $('#delete_cod_contato').val();

        $.ajax({
            url: route('contatos.excluir', {
                id: codContato
            }),
            type: 'DELETE',
            beforeSend: function() {
                $('#deleteModal .btn-danger').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Excluindo...');
            },
            success: function(response) {
                toastr.success(response.message);
                $('#deleteModal').modal('hide');
                loadContatos();
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.message :
                    'Ocorreu um erro ao excluir o contato';
                toastr.error(errorMsg);
            },
            complete: function() {
                $('#deleteModal .btn-danger').prop('disabled', false).text('Excluir');
            }
        });
    }

    // Função para validar o formulário
    function validarFormulario() {
        // Verifica campos obrigatórios
        if (!$('#dsc_tipo_contato').val()) {
            toastr.error('O tipo de contato é obrigatório');
            return false;
        }

        if (!$('#txt_nome').val()) {
            toastr.error('O nome é obrigatório');
            return false;
        }

        if (!$('#num_telefone').val()) {
            toastr.error('O telefone é obrigatório');
            return false;
        }

        if (!$('#dsc_email').val()) {
            toastr.error('O email é obrigatório');
            return false;
        }

        // Valida email
        const email = $('#dsc_email').val();
        if (email && !validateEmail(email)) {
            toastr.error('Email inválido');
            return false;
        }

        // Verifica o campo específico conforme o tipo
        const tipoContato = $('#dsc_tipo_contato').val();
        if (tipoContato === 'prefeitura' && !$('#dsc_prefeitura').val()) {
            toastr.error('O nome da prefeitura é obrigatório');
            return false;
        } else if (tipoContato === 'camara_municipal' && !$('#dsc_camara_municipal').val()) {
            toastr.error('O nome da câmara municipal é obrigatório');
            return false;
        } else if (tipoContato === 'orgao_publico' && !$('#dsc_orgao_publico').val()) {
            toastr.error('O nome do órgão público é obrigatório');
            return false;
        } else if (tipoContato === 'eleitor' && !$('#dsc_identificador_eleitor').val()) {
            toastr.error('O identificador do eleitor é obrigatório');
            return false;
        }

        return true;
    }

    // Função para validar email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Função para obter o label do tipo de contato
    function getTipoLabel(tipo) {
        const tipos = {
            'prefeitura': 'Prefeitura',
            'camara_municipal': 'Câmara Municipal',
            'orgao_publico': 'Órgão Público',
            'eleitor': 'Eleitor'
        };

        return tipos[tipo] || tipo;
    }
</script>
