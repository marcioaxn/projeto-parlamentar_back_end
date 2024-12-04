<script>
    function hiddenViseble(dsc_tipo_item_orcamentario_financeiro_id, cod_acao_orcamentaria) {

        // Seleciona o elemento pelo ID
        var mesesSelectElement = document.getElementById('num_mes_' + cod_acao_orcamentaria);
        var rpsSelectElement = document.getElementById('num_rp_' + cod_acao_orcamentaria);

        // Verifica o dsc_tipo_item_orcamentario_financeiro_id passado
        if (dsc_tipo_item_orcamentario_financeiro_id === 'Necessidade Financeira') {
            // Remove o atributo 'disabled' se o dsc_tipo_item_orcamentario_financeiro_id for 'Necessidade Financeira'
            mesesSelectElement.removeAttribute('disabled');
            rpsSelectElement.removeAttribute('disabled');

            mesesSelectElement.value = '';
            rpsSelectElement.value = '';
        } else {
            // Adiciona o atributo 'disabled' se o dsc_tipo_item_orcamentario_financeiro_id for diferente
            mesesSelectElement.setAttribute('disabled', 'disabled');
            rpsSelectElement.setAttribute('disabled', 'disabled');

            mesesSelectElement.value = '';
            rpsSelectElement.value = '';
        }
    }

    function atualizarModal(tema, acaoOrcamentaria) {

        var textHeader =
            'Cadastrar <span class="text-bold">' + tema + '</span> para a Ação Orçamentária <span class="text-bold">' +
            acaoOrcamentaria + '</span>';

        $("#modalAdicionarItensOrcamentariosFinanceirosLabel" + acaoOrcamentaria).empty();
        $("#modalAdicionarItensOrcamentariosFinanceirosLabel" + acaoOrcamentaria).append(textHeader);

        $('#dsc_tipo_item_orcamentario_financeiro_' + acaoOrcamentaria).val('');
        $('#dsc_tipo_item_orcamentario_financeiro_' + acaoOrcamentaria).val(tema);

        if (tema == 'Necessidade Financeira') {
            document.getElementById('divNumMes' + acaoOrcamentaria).style.display = 'block';
            document.getElementById('divNumRp' + acaoOrcamentaria).style.display = 'block';
            document.getElementById('divLegendaRp' + acaoOrcamentaria).style.display = 'block';
        }

    }

    function gravarOrcamentarioFinanceiro(acao_orcamentaria, cod_item) {
        event.preventDefault(); // Evita o envio padrão do formulário

        // Captura os valores dos campos do formulário
        let data = {
            cod_item: cod_item,
            cod_pac: $('#cod_pac').val(),
            cod_acao_orcamentaria: acao_orcamentaria,
            dsc_tipo_item_orcamentario_financeiro: $('#dsc_tipo_item_orcamentario_financeiro_' +
                    acao_orcamentaria + cod_item)
                .val(),
            num_ano: $('#num_ano_' + acao_orcamentaria + cod_item).val(),
            num_mes: $('#num_mes_' + acao_orcamentaria + cod_item).val(),
            num_rp: $('#num_rp_' + acao_orcamentaria + cod_item).val(),
            vlr_dinheiro: $('#vlr_dinheiro_' + acao_orcamentaria + cod_item).val(),
            txt_observacao: $('#txt_observacao_' + acao_orcamentaria + cod_item)
                .val(),
        };

        $.ajax({
            url: "{{ url('novo-pac/orcamentario-financeiro/store') }}", // URL para a qual a requisição será enviada
            type: "POST", // Tipo de requisição
            data: data, // Dados enviados na requisição
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content') // Adiciona o token CSRF para segurança
            },
            success: function(response) {
                // Executado em caso de sucesso
                // alert('Gravado com sucesso.');
                location.reload();
                // Realize qualquer outra ação de acordo com sua necessidade
            },
            error: function(xhr, status, error) {
                // Tratamento de erro
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Erro - ' + errorMessage);
            }
        });
    }

    function excluirOrcamentarioFinanceiro(nom_tabela, cod_item) {

        let data = {
            table: nom_tabela,
            cod_item: cod_item,
        };

        $.ajax({
            url: "{{ url('novo-pac/orcamentario-financeiro/destroy') }}",
            type: "delete",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content')
            },
            success: function(response) {
                // alert('Excluído com sucesso.');
                location.reload();
            },
            error: function(xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Erro - ' + errorMessage);
            }
        });
    }
</script>
