@php
    if ($getParlamentar) {
        $getParlamentar->dsc_casa == 'Câmara dos Deputados'
            ? ($valueDefaultCodInterlocutor = 'b06ae9a4-385b-4a50-8181-e30d630af5a4')
            : ($valueDefaultCodInterlocutor = 'b06ae9a4-385b-4a50-8181-e30d630af5b4');
        $colunasEscondidas = $colunasEscondidas;
        $nom_parlamentar = $getParlamentar->nom_parlamentar;
    } else {
        $valueDefaultCodInterlocutor = null;
        $colunasEscondidas = [];
        $nom_parlamentar = null;
    }
    $colunasDisplayNone = ['nom_representante', 'dsc_cargo_representante'];
@endphp

@if (Session::get('permissao') === '0000100')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-1 text-left d-print-none" id="divBtnIncluirNovoAtendimento">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse"
            data-bs-target="#collapseFormNovoAtendimento" aria-expanded="false"
            aria-controls="collapseFormNovoAtendimento"><i class="fas fa-plus-circle"></i>
            Incluir
            novo atendimento</button>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2 collapse" id="collapseFormNovoAtendimento">

        {!! Form::open([
            'method' => 'post',
            'url' => route('atendimento.store'),
            'files' => true,
            'enctype' => 'multipart/form-data',
        ]) !!}
        <div class="card border-primary">

            <div class="card-body border-primary">

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">
                        <label for="" class="form-label tituloItens">Detalhes do atendimento</label>
                    </div>


                    @foreach ($estruturaTableAtendimento as $table)
                        @php
                            $columnName = $table->column_name;
                            $data_type = $table->data_type;
                            $ordinalPosition = $table->ordinal_position;
                        @endphp

                        @if ($ordinalPosition != 1)
                            @if (!in_array($columnName, $colunasEscondidas))
                                <div id="div_{!! $columnName !!}"
                                    class="col-xs-12 col-sm-12 col-md-6 col-lg-4 mb-4 text-left"
                                    style="<?php in_array($columnName, $colunasDisplayNone) ? print 'display: none;' : print 'display: block;'; ?>">
                                    <label for="{!! $columnName !!}"
                                        class="form-label">{!! nomeCampoNormalizadoTabAtendimento($columnName) !!}</label>

                                    @if ($data_type === 'uuid')
                                        @if ($columnName === 'cod_assunto')
                                            {!! Form::select(
                                                'atendimento[' . $columnName . ']',
                                                ${$columnName . '_pluck'},
                                                $columnName === 'cod_interlocutor' ? $valueDefaultCodInterlocutor : null,
                                                [
                                                    'class' => 'form-control text-dark',
                                                    'style' => 'cursor: pointer; width: 100% !Important;',
                                                    'id' => $columnName,
                                                    'autocomplete' => 'off',
                                                    'placeholder' => 'Selecione',
                                                    'required' => 'required',
                                                ],
                                            ) !!}
                                            <div id="" class="form-text textoPequeno text-secondary">
                                                Se o tópico desejado não estiver listado, você pode simplesmente
                                                digitá-lo e
                                                ao
                                                finalizar confirme selecionado-o.
                                            </div>

                                            <script>
                                                $(document).ready(function() {
                                                    // Inicialize o Select2
                                                    var select = $('#{!! $columnName !!}').select2({
                                                        tags: true, // Permite a adição de tags personalizadas
                                                        tokenSeparators: [';'], // Define o separador de tags
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
                                        @else
                                            {!! Form::select(
                                                'atendimento[' . $columnName . ']',
                                                ${$columnName . '_pluck'},
                                                $columnName === 'cod_interlocutor' ? $valueDefaultCodInterlocutor : null,
                                                [
                                                    'class' => 'form-control text-dark',
                                                    'style' => 'cursor: pointer; width: 100% !Important;',
                                                    'id' => $columnName,
                                                    'autocomplete' => 'off',
                                                    'placeholder' => 'Selecione',
                                                    'required' => 'required',
                                                ],
                                            ) !!}

                                            <script type="text/javascript">
                                                $(document).ready(function() {
                                                    $('#{!! $columnName !!}').select2();
                                                    $(document).on("select2:open", () => {
                                                        document.querySelector(".select2-container--open .select2-search__field").focus()
                                                    });
                                                });
                                            </script>
                                        @endif
                                    @endif

                                    @if ($data_type === 'date')
                                        {!! Form::date('atendimento[' . $columnName . ']', date('Y-m-d'), [
                                            'class' => 'form-control text-dark text-right font-numero date',
                                            'id' => $columnName,
                                            'style' => 'cursor: pointer',
                                            'autocomplete' => 'off',
                                            'required' => 'required',
                                        ]) !!}
                                    @endif

                                    @if ($data_type === 'character varying')
                                        @if ($columnName === 'bln_representante')
                                            {!! Form::select('atendimento[' . $columnName . ']', ['Sim' => 'Sim', 'Não' => 'Não'], null, [
                                                'class' => 'form-control text-dark',
                                                'style' => 'cursor: pointer; width: 100% !Important;',
                                                'id' => $columnName,
                                                'autocomplete' => 'off',
                                                'placeholder' => 'Selecione',
                                                'required' => 'required',
                                                'onchange' =>
                                                    "javascript: if(this.value == 'Sim') { document.getElementById('div_nom_representante').style.display = 'block'; document.getElementById('div_dsc_cargo_representante').style.display = 'block'; } else { document.getElementById('div_nom_representante').style.display = 'none'; document.getElementById('nom_representante').value = ''; document.getElementById('div_dsc_cargo_representante').style.display = 'none'; document.getElementById('dsc_cargo_representante').value = ''; }",
                                            ]) !!}
                                        @else
                                            {!! Form::text('atendimento[' . $columnName . ']', null, [
                                                'class' => 'form-control text-dark',
                                                'id' => $columnName,
                                                'placeholder' => 'Digite o(a) ' . nomeCampoNormalizadoTabAtendimento($columnName),
                                                'autocomplete' => 'off',
                                            ]) !!}
                                        @endif
                                    @endif

                                    @if ($data_type === 'text')
                                        {!! Form::textarea('atendimento[' . $columnName . ']', null, [
                                            'class' => 'form-control text-dark text-uppercase',
                                            'id' => $columnName,
                                            'placeholder' => 'Digite os(as) ' . nomeCampoNormalizadoTabAtendimento($columnName),
                                            'rows' => 2,
                                            'cols' => 50,
                                        ]) !!}
                                    @endif
                                </div>
                            @else
                                @if ($columnName === 'cod_interlocutor')
                                    {!! Form::hidden('atendimento[' . $columnName . ']', $valueDefaultCodInterlocutor) !!}
                                @endif
                                @if ($columnName === 'nom_interlocutor')
                                    {!! Form::hidden('atendimento[' . $columnName . ']', $nom_parlamentar) !!}
                                @endif
                            @endif
                        @endif
                    @endforeach

                    {!! Form::hidden('atendimento[cod_parlamentar]', $cod_parlamentar) !!}

                    @php
                        /* Início da parte para Incluir a parte dos Convidados */
                    @endphp

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">
                        <hr>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left">
                        <label for="" class="form-label tituloItens">Convidados</label>

                        @include('atendimento.convidados')

                    </div>

                    @php
                        /* Fim da parte para Incluir a parte dos Convidados */
                    @endphp

                    @php
                        /* Início da parte para Incluir a parte das Demandas */
                    @endphp

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">
                        <hr>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left">
                        <label for="" class="form-label tituloItens">Demandas recebidas</label>

                        @include('atendimento.demandas')

                    </div>

                    @php
                        /* Fim da parte para Incluir a parte das Demandas */
                    @endphp

                    @php
                        /* Início da parte para Incluir a parte dos Anexos */
                    @endphp

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">
                        <hr>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left">
                        <label for="" class="form-label tituloItens">Anexos em PDF <i
                                class="fas fa-file-pdf text-danger"></i></label>

                        @include('atendimento.anexos')

                    </div>

                    @php
                        /* Fim da parte para Incluir a parte dos Anexos */
                    @endphp

                </div>


            </div>
            <div class="card-footer border-primary bg-light text-right">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse"
                    data-bs-target="#collapseFormNovoAtendimento" aria-expanded="false"
                    aria-controls="collapseFormNovoAtendimento"
                    onclick="javascript: $('html, body').animate({scrollTop: 0 + ($('#app').position().top) + 475}, 'slow');">
                    Cancelar</button>
                <button class="btn btn-primary">Salvar atendimento</button>
            </div>
        </div>



        <script type="text/javascript">
            $(document).ready(function() {

                // Ao alterar qualquer campo dentro do grupo
                $(".multiple-form-group").on("change", "input, textarea, select", function() {
                    var group = $(this).closest(".multiple-form-group");

                    // Verificar se algum campo no grupo foi preenchido
                    var isAnyFieldFilled = group.find("input, textarea, select").filter(function() {
                        return $(this).val() !== "";
                    }).length > 0;

                    // Tornar todos os campos obrigatórios se algum campo estiver preenchido
                    group.find("input, textarea, select").prop("required", isAnyFieldFilled);
                });

                var addFormGroup = function(event) {
                    event.preventDefault();

                    //document.getElementById('btn-remove-inicial').style.display = 'none';

                    var $formGroup = $(this).closest('.form-group');
                    var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
                    var $formGroupClone = $formGroup.clone();

                    // Remove the Remove button from the cloned group
                    $formGroupClone.find('.btn-remove').remove();

                    // Create and append the Remove button
                    var $removeButton = $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger btn-remove',
                        text: '-'
                    });
                    $formGroupClone.find('.btn-add').after($removeButton);

                    $(this)
                        .toggleClass('btn-success btn-add btn-danger btn-remove')
                        .html('–');

                    $(this)
                        .toggleClass('btn-danger btn-remove btn-success btn-add')
                        .html('+');

                    $formGroupClone.find('input').val('');
                    $formGroupClone.find('textarea').val('');
                    $formGroupClone.find('select').val('');
                    $formGroupClone.find('date').val('');
                    $formGroupClone.find('file').val('');
                    //$formGroupClone.find('.concept').text('Phone');

                    // Adicione o atributo 'required' a todos os campos do grupo clonado
                    $formGroupClone.find('input, textarea, select, date, file').attr('required', 'required');

                    $formGroupClone.insertAfter($formGroup);

                    var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
                    if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
                        $lastFormGroupLast.find('.btn-add').attr('disabled', false);
                    }
                };

                var removeFormGroup = function(event) {
                    event.preventDefault();

                    var $formGroup = $(this).closest('.form-group');
                    var $multipleFormGroup = $formGroup.closest('.multiple-form-group');

                    var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
                    if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
                        $lastFormGroupLast.find('.btn-add').attr('disabled', true);
                    }

                    $formGroup.remove();
                };



                var countFormGroup = function($form) {
                    return $form.find('.form-group').length;
                };

                $(document).on('click', '.btn-add', addFormGroup);
                $(document).on('click', '.btn-remove', removeFormGroup);

                const opcaoSelect = document.getElementById('bln_representante');

                opcaoSelect.addEventListener('change', function() {
                    if (opcaoSelect.value == 'Sim') {
                        const campoDependenteInput1 = document.getElementById('nom_representante');
                        const campoDependenteInput2 = document.getElementById('dsc_cargo_representante');
                        campoDependenteInput1.setAttribute('required', 'required');
                        campoDependenteInput2.setAttribute('required', 'required');
                    } else {
                        const campoDependenteInput1 = document.getElementById('nom_representante');
                        const campoDependenteInput2 = document.getElementById('dsc_cargo_representante');
                        campoDependenteInput1.removeAttribute('required');
                        campoDependenteInput2.removeAttribute('required');
                    }
                });

                function validarArquivos(files) {
                    // Validação dos arquivos selecionados
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        if (file.size > 30000000) {
                            $('#erros').append('<p>O arquivo ' + file.name +
                                ' excede o tamanho máximo de 10MB.</p>');
                        }
                        if (file.type !== 'application/pdf') {
                            $('#erros').append(
                                '<div class="alert alert-danger alert-dismissible fade show bg-danger text-white" role="alert"><div class="row"><div class="col-xs-10 col-sm-10 col-md-10 col-lg-10"><strong>Aquivo diferente do tipo PDF</strong></div><div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-right"><i class="fas fa-times-circle" data-bs-dismiss="alert" aria-label="Close"></i></div><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">&nbsp;</div><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><p class="text-white">O arquivo ' +
                                file.name +
                                ' não é do tipo PDF.</p><p class="text-white">O sistema só gravará se for do tipo PDF. Este tipo será desconsiderado.</p></div></div></div>'
                            );
                        }
                    }
                }

                $(document).on('change', "input[type=file]", function() {
                    $('#erros').empty();
                    validarArquivos($(this)[0].files);
                });

            });
        </script>

        {!! Form::close() !!}


    </div>
@endif
