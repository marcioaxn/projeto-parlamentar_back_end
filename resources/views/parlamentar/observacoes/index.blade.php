<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3" id="divObservacoes">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2 mb-3 collapse" id="collapseFormNovaObservacao">

            <div class="card border-primary">

                <div class="card-body border-primary">

                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 mb-4 text-left">
                            <label for="observacao_cod_assundo" class="form-label">Assunto da observação</label>

                            {!! Form::select('cod_assunto', $observacao_cod_assunto_pluck, null, [
                                'class' => 'form-control text-dark',
                                'style' => 'cursor: pointer; width: 100% !Important;',
                                'id' => 'observacao_cod_assundo',
                                'autocomplete' => 'off',
                                'placeholder' => 'Selecione ou digite um novo assunto',
                                'required' => 'required',
                            ]) !!}
                            <div id="" class="form-text textoPequeno text-secondary">
                                Se o tópico desejado não estiver listado, você pode simplesmente
                                digitá-lo e
                                ao
                                finalizar confirme selecionando-o.
                            </div>

                            <script>
                                $(document).ready(function() {
                                    // Inicialize o Select2
                                    var select = $('#observacao_cod_assundo').select2({
                                        tags: true, // Permite a adição de tags personalizadas
                                        tokenSeparators: [','], // Define o separador de tags
                                        createTag: function(params) {
                                            return {
                                                id: params.term,
                                                text: params.term,
                                                newTag: true // Marca a tag como nova
                                            };
                                        },
                                        templateResult: function(data) {
                                            if (data.newTag) {
                                                return $('<span class="new-tag">' + data.text + '</span>');
                                                alert('Ajax');

                                            }
                                            return data.text;
                                        }
                                    });

                                    // Intercepta a abertura do dropdown do Select2 para permitir edição da tag
                                    select.on('select2:open', function() {
                                        $(".new-tag").each(function() {
                                            var $this = $(this);
                                            $this.replaceWith($('<option>', {
                                                value: $this.text(),
                                                text: $this.text(),
                                                selected: true
                                            }));
                                        });
                                    });
                                });
                            </script>

                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 mb-4 text-left">
                            <label for="observacao_cod_assundo" class="form-label">Texto da observação</label>

                            {!! Form::textarea('txt_observacao_parlamentar', null, [
                                'class' => 'form-control text-dark',
                                'id' => 'observacao_txt_observacao_parlamentar',
                                'placeholder' => 'Digite a observação',
                                'rows' => 2,
                                'cols' => 50,
                                'required' => 'required',
                            ]) !!}

                        </div>

                    </div>
                </div>
                <div class="card-footer border-primary bg-light text-right">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse"
                        data-bs-target="#collapseFormNovaObservacao" aria-expanded="false"
                        aria-controls="collapseFormNovaObservacao">
                        Cancelar</button>
                    <button class="btn btn-primary"
                        onclick="javascript: gravar_observacao($('#observacao_cod_assundo').val(), $('#observacao_txt_observacao_parlamentar').val(),'{!! $cod_parlamentar !!}','','');">
                        Salvar observação
                    </button>
                </div>
            </div>

        </div>
    </div>

    @php
        $assuntos = [];
        foreach ($getParlamentar->observacoes as $key => $value) {
            if (!in_array($value->assunto->dsc_assunto, $assuntos)) {
                array_push($assuntos, $value->assunto->dsc_assunto);
            }
        }

        sort($assuntos);
    @endphp
    @if ($getParlamentar->observacoes->count() > 0)
        <div class="row">

            @php
                $contObservacao = 1;
            @endphp

            @foreach ($assuntos as $assunto)
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mb-2">
                    <ul class="list-group">
                        <li class="list-group-item <?php isset($getParlamentar) && $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print 'bg-camara-sub-titulo-modal' : print 'bg-senado-sub-titulo-modal'; ?> p-1 pl-3" style="border: 0px solid #e5e5e5;">
                            <span class="font-numero">{{ $contObservacao }}</span>. {!! $assunto !!}
                        </li>

                        @foreach ($getParlamentar->observacoes as $key => $value)
                            @if ($assunto === $value->assunto->dsc_assunto)
                                <li class="list-group-item" style="border: 1px solid #e5e5e5;">
                                    {!! $value->txt_observacao_parlamentar !!}
                                    @if (Session::get('permissao') === '0000100')
                                        <span data-bs-toggle="modal"
                                            data-bs-target="#modalEditarObservacao{!! $value->cod_observacao_parlamentar !!}"
                                            class="d-print-none">
                                            <i class="fas fa-edit text-primary d-print-none" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Editar observação"
                                                style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                        </span>
                                        <!-- Modal -->
                                        <div class="modal fade" id="modalEditarObservacao{!! $value->cod_observacao_parlamentar !!}"
                                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                            data-bs-backdrop="static" data-bs-keyboard="false"
                                            style="padding-top: 150px!Important;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header"
                                                        style="background: linear-gradient(135deg,#898989 0%,#acacac 100%);color: white;">
                                                        <p class="modal-title text-white"
                                                            style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                            Editar observação</p>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">

                                                            <div
                                                                class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-4 text-left">
                                                                <label for="observacao_cod_assundo"
                                                                    class="form-label">Assunto da observação</label>

                                                                {!! Form::select('cod_assunto', $observacao_cod_assunto_pluck, $value->cod_assunto, [
                                                                    'class' => 'form-control text-dark',
                                                                    'style' => 'cursor: pointer; width: 100% !Important;',
                                                                    'id' => 'observacao_cod_assundo' . $value->cod_observacao_parlamentar,
                                                                    'autocomplete' => 'off',
                                                                    'placeholder' => 'Selecione ou digite um novo assunto',
                                                                    'required' => 'required',
                                                                ]) !!}
                                                                <div id=""
                                                                    class="form-text textoPequeno text-secondary">
                                                                    Se o tópico desejado não estiver listado, você pode
                                                                    simplesmente
                                                                    digitá-lo e
                                                                    ao
                                                                    finalizar confirme selecionando-o.
                                                                </div>

                                                                <script>
                                                                    $(document).ready(function() {
                                                                        // Inicialize o Select2
                                                                        var select = $('#observacao_cod_assundo{!! $value->cod_observacao_parlamentar !!}').select2({
                                                                            dropdownParent: $("#modalEditarObservacao{!! $value->cod_observacao_parlamentar !!}"),
                                                                            tags: true, // Permite a adição de tags personalizadas
                                                                            tokenSeparators: [','], // Define o separador de tags
                                                                            createTag: function(params) {
                                                                                return {
                                                                                    id: params.term,
                                                                                    text: params.term,
                                                                                    newTag: true // Marca a tag como nova
                                                                                };
                                                                            },
                                                                            templateResult: function(data) {
                                                                                if (data.newTag) {
                                                                                    return $('<span class="new-tag">' + data.text + '</span>');
                                                                                    alert('Ajax');

                                                                                }
                                                                                return data.text;
                                                                            }
                                                                        });

                                                                        // Intercepta a abertura do dropdown do Select2 para permitir edição da tag
                                                                        select.on('select2:open', function() {
                                                                            $(".new-tag").each(function() {
                                                                                var $this = $(this);
                                                                                $this.replaceWith($('<option>', {
                                                                                    value: $this.text(),
                                                                                    text: $this.text(),
                                                                                    selected: true
                                                                                }));
                                                                            });
                                                                        });
                                                                    });
                                                                </script>

                                                            </div>

                                                            <div
                                                                class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-4 text-left">
                                                                <label for="observacao_cod_assundo"
                                                                    class="form-label">Texto
                                                                    da observação</label>

                                                                {!! Form::textarea('txt_observacao_parlamentar', $value->txt_observacao_parlamentar, [
                                                                    'class' => 'form-control text-dark',
                                                                    'id' => 'observacao_txt_observacao_parlamentar' . $value->cod_observacao_parlamentar,
                                                                    'placeholder' => 'Digite a observação',
                                                                    'rows' => 2,
                                                                    'cols' => 50,
                                                                    'required' => 'required',
                                                                ]) !!}

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="button" class="btn btn-primary"
                                                            onclick="javascript: gravar_observacao($('#observacao_cod_assundo{!! $value->cod_observacao_parlamentar !!}').val(), $('#observacao_txt_observacao_parlamentar{!! $value->cod_observacao_parlamentar !!}').val(),'{!! $cod_parlamentar !!}', '{!! $value->cod_observacao_parlamentar !!}', '');"
                                                            data-bs-dismiss="modal">Alterar</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            // Início da parte de exclusão da observação
                                        @endphp
                                        <span data-bs-toggle="modal"
                                            data-bs-target="#modalExcluirObservacao{!! $value->cod_observacao_parlamentar !!}"
                                            class="d-print-none">
                                            <i class="fas fa-trash-alt text-danger" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Excluir observação"
                                                style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                        </span>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalExcluirObservacao{!! $value->cod_observacao_parlamentar !!}"
                                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                            data-bs-backdrop="static" data-bs-keyboard="false"
                                            style="padding-top: 150px!Important;">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header"
                                                        style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                        <p class="modal-title text-white"
                                                            style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                            Excluir observação</p>
                                                    </div>
                                                    <div class="modal-body">

                                                        <p>
                                                            Observação: <span
                                                                class="textoNormalTabela">{!! $value->txt_observacao_parlamentar !!}</span>
                                                        </p>

                                                        <p>
                                                            Assunto: <span
                                                                class="textoNormalTabela">{!! $value->assunto->dsc_assunto !!}</span>
                                                        </p>

                                                        <p class="">
                                                            Deseja realmente excluir esta observação?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="javascript: gravar_observacao($('#observacao_cod_assundo{!! $value->cod_observacao_parlamentar !!}').val(), $('#observacao_txt_observacao_parlamentar{!! $value->cod_observacao_parlamentar !!}').val(),'{!! $cod_parlamentar !!}', '{!! $value->cod_observacao_parlamentar !!}', 'Sim');"
                                                            data-bs-dismiss="modal">Sim,
                                                            excluir</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            // Fim da parte de exclusão da observação
                                        @endphp
                                    @endif
                                </li>
                            @endif
                        @endforeach

                    </ul>
                </div>
                @php
                    $contObservacao++;
                @endphp
            @endforeach

        </div>
    @else
        <p>Não há observação cadastrada</p>
    @endif
</div>

<script>
    function gravar_observacao(cod_assunto, txt_observacao_parlamentar, cod_parlamentar, cod_observacao_parlamentar,
        excluir) {

        @auth

        @php
            // Início das verificações dos parâmetros recebidos após o cliente clicar em Salvar
        @endphp

        var texto_erro = '';

        if (cod_observacao_parlamentar === null || cod_observacao_parlamentar === '') {

            var newItem = $('#observacao_cod_assundo').val();
            if (newItem === null || newItem === '') {

                texto_erro = 'O campo Assunto da observação é de preenchimento obrigatório.<br />';

            }

        } else {

            var newItem = $('#observacao_cod_assundo' + cod_observacao_parlamentar).val();
            if (newItem === null || newItem === '') {

                texto_erro = 'O campo Assunto da observação é de preenchimento obrigatório.<br />';

            }

        }

        if (txt_observacao_parlamentar == '') {

            texto_erro = texto_erro + 'O campo Texto da observação é de preenchimento obrigatório.<br />';

        }

        if (texto_erro != '') {


            $("#divTextoModalMensagemErro").empty();

            $("#divTextoModalMensagemErro").append('<strong>Incluir nova observação</strong><br /><br />' + texto_erro);

            var minhaModal = new bootstrap.Modal(document.getElementById('modalMensagemErro'));
            minhaModal.show();

            return false;

        } else {

            var texto_sucesso = '';

            if (cod_observacao_parlamentar === null || cod_observacao_parlamentar === '') {
                var parametros = '/' + cod_assunto + '/' + txt_observacao_parlamentar + '/' + cod_parlamentar;
            } else {

                if (excluir === null || excluir === '') {
                    var parametros = '/' + cod_assunto + '/' + txt_observacao_parlamentar + '/' + cod_parlamentar +
                        '/' + cod_observacao_parlamentar;
                } else {
                    var parametros = '/' + cod_assunto + '/' + txt_observacao_parlamentar + '/' + cod_parlamentar +
                        '/' + cod_observacao_parlamentar + '/' + excluir;
                }

            }

            $.get('<?php print url('gravar-observacao-parlamentar'); ?>' + parametros,
                function(data) {

                    if (cod_observacao_parlamentar === null || cod_observacao_parlamentar === '') {
                        var minhaModal = new bootstrap.Modal(document.getElementById('modalMensagemSucesso'));
                        minhaModal.show();
                        texto_sucesso = 'Observação ( ' + txt_observacao_parlamentar +
                            ' ) foi gravada com sucesso!';
                        $("#divTextoModalMensagemSucesso").empty();

                        $("#divTextoModalMensagemSucesso").append(
                            '<strong>Incluir nova observação</strong><br /><br />' +
                            texto_sucesso);
                    } else {

                        if (excluir === null || excluir === '') {
                            var minhaModal = new bootstrap.Modal(document.getElementById('modalMensagemSucesso'));
                            minhaModal.show();
                            texto_sucesso = 'Observação ( ' + txt_observacao_parlamentar +
                                ' ) foi gravada com sucesso!';
                            $("#divTextoModalMensagemSucesso").empty();

                            $("#divTextoModalMensagemSucesso").append(
                                '<strong>Incluir nova observação</strong><br /><br />' +
                                texto_sucesso);
                        } else {
                            var minhaModal = new bootstrap.Modal(document.getElementById('modalMensagemSucesso'));
                            minhaModal.show();
                            texto_sucesso = 'Observação ( ' + txt_observacao_parlamentar +
                                ' ) foi excluída com sucesso!';
                            $("#divTextoModalMensagemSucesso").empty();

                            $("#divTextoModalMensagemSucesso").append(
                                '<strong>Incluir nova observação</strong><br /><br />' +
                                texto_sucesso);
                        }

                    }

                    $("#divObservacoes").empty();

                    $("#divObservacoes").append(
                        '<div class="col-xs-12 col-sm-12 col-md-12" style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;"><i class="fa fa - circle - notch fa - spin text - primary "></i><span class="sr - only "></span> Carregando...</div>'
                    );

                    $("#divObservacoes").empty();

                    $("#divObservacoes").append(data);

                });

        }

        @php
            // Fim das verificações dos parâmetros recebidos após o cliente clicar em Salvar
        @endphp

        @php
            // Início das ações necessárias após o cliente clicar em Salvar
        @endphp

        if (cod_observacao_parlamentar === null || cod_observacao_parlamentar === '') {

            $('#observacao_cod_assundo').val('');
            $('#observacao_txt_observacao_parlamentar').val('');

            var element = document.getElementById("collapseFormNovaObservacao");
            var myCollapse = new bootstrap.Collapse(element);

        }

        @php
            // Fim das ações necessárias após o cliente clicar em Salvar
        @endphp
    @else
        alert('Por gentileza, é necessário efetuar um novo login (acesso) ao sistema.');
    @endauth

    }
</script>
