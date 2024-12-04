@extends('layouts.app')

@section('content')
    @if (Session::get('permissao') === '0000100')
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
        <!-- Início breadcrumbs -->
        <div id="portal-breadcrumbs-wrapper" class="m-0 pl-0 mb-3 d-print-none">
            <nav id="breadcrumbs" aria-label="Histórico de navegação (Breadcrumbs)">
                <div class="content">
                    <span class="sr-only">Você está aqui:</span>
                    <span class="home">
                        <a href="{!! url('/') !!}">
                            <span class="fas fa-home" aria-hidden="true"></span>
                            <span class="sr-only">Página Inicial</span>
                        </a>
                    </span>

                    <span class="pl-1 pr-1">></span>

                    <span dir="ltr" id="breadcrumbs-2">
                        <a href="{!! url('/') !!}">
                            <span id="breadcrumbs-current">Principal</span>
                        </a>
                    </span>

                    <span class="pl-1 pr-1">></span>

                    <span dir="ltr" id="breadcrumbs-2">
                        <a href="{!! url('parlamentar') !!}">
                            <span id="breadcrumbs-current">Consulta Parlamentar</span>
                        </a>
                    </span>

                    <span class="pl-1 pr-1">></span>

                    @if ($cod_parlamentar)
                        <span dir="ltr" id="breadcrumbs-2">
                            <a href="{!! route('parlamentar', [$cod_parlamentar, 'Atendimento']) !!}"> <span
                                    id="breadcrumbs-current">{{ $getParlamentar->nom_parlamentar }}</span>
                            </a>
                        </span>

                        <span class="pl-1 pr-1">></span>

                        <span dir="ltr" id="breadcrumbs-2">
                            <span id="breadcrumbs-current">Editar Atendimento</span>
                        </span>
                    @else
                        <span dir="ltr" id="breadcrumbs-2">
                            <span id="breadcrumbs-current">Editar Atendimento</span>
                        </span>
                    @endif

                </div>
            </nav>
        </div>
        <!-- Fim breadcrumbs -->

        <!-- Início da apresentação da div processando -->
        @include('processando', [
            'processando_mensagem' => 'processando dados da edição do atendimento parlamentar',
        ])
        <!-- Fim da apresentação da div processando -->

        <!-- Início da apresentação do conteúdo da página -->

        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </symbol>
            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
            </symbol>
            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </symbol>
        </svg>

        <div class="row" id="divContent" style="display: none;">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2 " id="collapseFormNovoAtendimento">

                <div class="card border-secondary">

                    <div class="card-body border-secondary">

                        <form id="formEditarDetalhesAtendimento{{ $codAtendimento }}" class="" method="post">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left collapse"
                                    id="divColRetorno_atendimento">
                                    <div class="alert alert-success alert-dismissible d-flex align-items-center mb-3 fade show"
                                        role="alert">
                                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                            aria-label="Success:">
                                            <use xlink:href="#check-circle-fill" />
                                        </svg>
                                        <div>
                                            O sistema gravou com sucesso a alteração feita na parde de detalhes do
                                            atendimento.
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">
                                    <label for="" class="form-label tituloItens">Detalhes do atendimento <i
                                            class="fas fa-eye pointer text-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalLog{{ 'DetalheAtendimento' }}"></i> </label>
                                    {!! app(App\Http\Controllers\TabAtendimentosController::class)->modalTabelaLog(
                                        'DetalheAtendimento',
                                        $atendimento->auditoria->count() . ' ação(ões) realizada(s) no(a) Detalhes do atendimento',
                                        $atendimento->auditoria,
                                    ) !!}
                                </div>

                                {!! Form::hidden('cod_atendimento', $codAtendimento, ['id' => 'cod_atendimento']) !!}

                                @foreach ($estruturaTableAtendimento as $table)
                                    @php
                                        $columnName = $table->column_name;
                                        $data_type = $table->data_type;
                                        $ordinalPosition = $table->ordinal_position;
                                    @endphp

                                    @if ($ordinalPosition != 1)
                                        @if (!in_array($columnName, $colunasEscondidas))
                                            <div id="div_{!! $columnName !!}"
                                                class="col-xs-12 col-sm-12 col-md-6 col-lg-3 mb-4 text-left"
                                                style="<?php in_array($columnName, $colunasDisplayNone) && $atendimento->bln_representante != 'Sim' ? print 'display: none;' : print 'display: block;'; ?>">
                                                <label for="{!! $columnName !!}"
                                                    class="form-label">{!! nomeCampoNormalizadoTabAtendimento($columnName) !!}</label>

                                                @if ($data_type === 'uuid')
                                                    @if ($columnName === 'cod_assunto')
                                                        {!! Form::select($columnName, ${$columnName . '_pluck'}, $atendimento->$columnName, [
                                                            'class' => 'form-control text-dark',
                                                            'style' => 'cursor: pointer; width: 100% !Important;',
                                                            'id' => $columnName,
                                                            'autocomplete' => 'off',
                                                            'placeholder' => 'Selecione',
                                                            'required' => 'required',
                                                        ]) !!}
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
                                                        {!! Form::select($columnName, ${$columnName . '_pluck'}, $atendimento->$columnName, [
                                                            'class' => 'form-control text-dark',
                                                            'style' => 'cursor: pointer; width: 100% !Important;',
                                                            'id' => $columnName,
                                                            'autocomplete' => 'off',
                                                            'placeholder' => 'Selecione',
                                                            'required' => 'required',
                                                        ]) !!}

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
                                                    {!! Form::date($columnName, $atendimento->$columnName, [
                                                        'class' => 'form-control text-dark text-right font-numero date',
                                                        'id' => $columnName,
                                                        'style' => 'cursor: pointer',
                                                        'autocomplete' => 'off',
                                                        'required' => 'required',
                                                    ]) !!}
                                                @endif

                                                @if ($data_type === 'character varying')
                                                    @if ($columnName === 'bln_representante')
                                                        {!! Form::select($columnName, ['Sim' => 'Sim', 'Não' => 'Não'], $atendimento->$columnName, [
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
                                                        {!! Form::text($columnName, $atendimento->$columnName, [
                                                            'class' => 'form-control text-dark',
                                                            'id' => $columnName,
                                                            'placeholder' => 'Digite o(a) ' . nomeCampoNormalizadoTabAtendimento($columnName),
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                    @endif
                                                @endif

                                                @if ($data_type === 'text')
                                                    {!! Form::textarea($columnName, $atendimento->$columnName, [
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
                                                {!! Form::hidden($columnName, $valueDefaultCodInterlocutor) !!}
                                            @endif
                                            @if ($columnName === 'nom_interlocutor')
                                                {!! Form::hidden($columnName, $nom_parlamentar) !!}
                                            @endif
                                        @endif
                                    @endif
                                @endforeach

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 mb-4 mt-4 pt-2 text-left">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-save text-white"></i> Confirmar alteração
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalConfirmarExclusaoAtendimento_{{ $codAtendimento }}">
                                        <i class="fas fa-trash-alt"></i> Excluir atendimento
                                    </button>

                                    @php
                                        // Início da modal de confirmação de exclusão do convidado
                                    @endphp

                                    <div class="modal" id="modalConfirmarExclusaoAtendimento_{{ $codAtendimento }}"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                        data-bs-backdrop="static" data-bs-keyboard="false"
                                        style="padding-top: 150px!Important;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header"
                                                    style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                    <p class="modal-title text-white"
                                                        style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                        Excluir Atendimento</p>
                                                </div>
                                                <div class="modal-body">

                                                    <p>
                                                        Deseja realmente excluir este atendimento?
                                                    </p>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        data-bs-dismiss="modal">Fechar</button>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="javascript: $('#formExcluirAtendimento').submit();"
                                                        data-bs-dismiss="modal">Sim, quero excluir!</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        // Fim da modal de confirmação de exclusão do convidado
                                    @endphp
                                </div>

                                {!! Form::hidden('cod_parlamentar', $cod_parlamentar) !!}

                            </div>

                        </form>

                        {!! Form::open([
                            'method' => 'delete',
                            'id' => 'formExcluirAtendimento',
                            'url' => route('atendimento.delete'),
                        ]) !!}

                        {!! Form::hidden('cod_parlamentar', $cod_parlamentar) !!}
                        {!! Form::hidden('cod_atendimento', $codAtendimento) !!}

                        {!! Form::close() !!}

                        <script>
                            $(document).ready(function() {
                                $('#formEditarDetalhesAtendimento{{ $codAtendimento }}').submit(function(event) {

                                    event.preventDefault();
                                    var formData = new FormData(this);

                                    $.ajax({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                        },
                                        url: "{{ url('atendimento/update') }}",
                                        data: formData,
                                        type: 'post',
                                        async: false,
                                        processData: false,
                                        contentType: false,
                                        success: function(response) {

                                            setTimeout(function() {
                                                $("#divColRetorno_atendimento").fadeIn("slow");
                                            }, 100);

                                            setTimeout(function() {
                                                $("#divColRetorno_atendimento").fadeOut("slow");
                                            }, 3900);

                                        }
                                    });

                                });
                            });
                        </script>

                        <div class="row">

                            @php
                                /* Início da parte para Incluir a parte dos Convidados */
                            @endphp

                            @include('atendimento.editar-convidado')

                            @php
                                /* Fim da parte para Incluir a parte dos Convidados */
                            @endphp

                            @php
                                /* Início da parte para Incluir a parte das Demandas */
                            @endphp

                            @include('atendimento.editar-demanda')

                            @php
                                /* Fim da parte para Incluir a parte das Demandas */
                            @endphp

                            @php
                                /* Início da parte para Incluir a parte dos Anexos */
                            @endphp

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">
                                <hr>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left collapse"
                                id="divColRetorno_incluir_anexo">
                                <div class="alert alert-success alert-dismissible d-flex align-items-center mb-3 fade show"
                                    role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                        aria-label="Success:">
                                        <use xlink:href="#check-circle-fill" />
                                    </svg>
                                    <div>
                                        O sistema gravou com sucesso o novo anexo.
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left collapse"
                                id="divColRetorno_excluir_anexo">
                                <div class="alert alert-success alert-dismissible d-flex align-items-center mb-3 fade show"
                                    role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                        aria-label="Success:">
                                        <use xlink:href="#check-circle-fill" />
                                    </svg>
                                    <div>
                                        O sistema excluiu com sucesso o anexo.
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left">
                                <label for="" class="form-label tituloItens">Anexos em PDF <i
                                        class="fas fa-file-pdf text-danger"></i> <i
                                        class="fas fa-eye pointer text-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalLog{{ 'Arquivos' }}"></i> </label>
                                {!! app(App\Http\Controllers\TabAtendimentosController::class)->modalTabelaLog(
                                    'Arquivos',
                                    $auditoriaCompletaArquivos->count() . ' ação(ões) realizada(s) na parte dos Anexos',
                                    $auditoriaCompletaArquivos,
                                ) !!}

                                <form id="formAIncluirArquivo{{ $codAtendimento }}" class="form-horizontal file-upload"
                                    method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row form-group multiple-form-group input-group pt-2">

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left text-bold">
                                            Incluir
                                            Anexo
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

                                            <label for="" class="form-label">Assunto do arquivo</label>

                                            {!! Form::text('txt_assunto', null, [
                                                'class' => 'form-control',
                                                'rows' => 1,
                                                'id' => 'txt_assunto_novo',
                                                'placeholder' => 'Digite o assunto do teor do arquivo',
                                                'required' => 'required',
                                            ]) !!}

                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-left">

                                            <label for="" class="form-label">Arquivo</label>

                                            {!! Form::file('arquivo', [
                                                'class' => 'anexos',
                                                'id' => 'arquivoInput_novo',
                                            ]) !!}

                                        </div>

                                        <div id="buttonsFileAddRemove"
                                            class="col-xs-12 col-sm-6 col-md-4 col-lg-2 mb-4 text-left mt-4 tp-4">
                                            <button type="submit" class="btn btn-success btn-sm" id="upload_csv">Incluir
                                                Arquivo</button>
                                        </div>

                                        {!! Form::hidden('cod_atendimento', $codAtendimento, ['id' => 'cod_atendimento']) !!}

                                    </div>

                                    <div id="erros"></div>
                                </form>

                                <script>
                                    $(document).ready(function() {
                                        $('#formAIncluirArquivo{{ $codAtendimento }}').submit(function(event) {

                                            event.preventDefault();
                                            var formData = new FormData(this);

                                            var arquivoInput = document.getElementById('arquivoInput_novo');

                                            if (arquivoInput.files.length > 0) {
                                                $.ajax({
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                                    },
                                                    url: "{{ url('incluirArquivoAjax') }}",
                                                    data: formData,
                                                    type: 'post',
                                                    async: false,
                                                    processData: false,
                                                    contentType: false,
                                                    success: function(response) {

                                                        $("#txt_assunto_novo").val('');
                                                        $("#arquivoInput_novo").val(null);

                                                        $("#divColRetorno_incluir_anexo").fadeIn("slow");

                                                        setTimeout(function() {
                                                            $("#divColRetorno_incluir_anexo").fadeOut("slow");
                                                        }, 3900);

                                                        $("#divAnexos").empty();
                                                        $("#divAnexos").append(response);

                                                    }
                                                });
                                            } else {
                                                alert("É necessário selecionar um arquivo PDF.");
                                                return false;
                                            }



                                        });
                                    });
                                </script>

                                <div class="row">

                                    <div id="divAnexos" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">

                                        <div class="row">

                                            @php
                                                $contArquivo = 1;
                                            @endphp

                                            @foreach ($atendimento->arquivos as $arquivo)
                                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-2 text-left">

                                                    <a href="{!! asset($arquivo->nom_arquivo) !!}" target="_blank">
                                                        <span class="font-numero">{{ $contArquivo }}</span>.
                                                        {!! $arquivo->txt_assunto !!}
                                                    </a>
                                                    <i class="fas fa-trash-alt text-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalConfirmarExclusaoArquivo_{{ $arquivo->cod_arquivo }}"
                                                        style="cursor: pointer;"></i>

                                                    @php
                                                        // Início da modal de confirmação de exclusão do arquivo
                                                    @endphp

                                                    <div class="modal"
                                                        id="modalConfirmarExclusaoArquivo_{{ $arquivo->cod_arquivo }}"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true" data-bs-backdrop="static"
                                                        data-bs-keyboard="false" style="padding-top: 150px!Important;">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                                    <p class="modal-title text-white"
                                                                        style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                                        Excluir Anexo</p>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <p>
                                                                        Deseja realmente excluir este anexo deste
                                                                        assunto?
                                                                    </p>

                                                                    <p>
                                                                        <span
                                                                            class="text-bold">{{ $arquivo->txt_assunto }}</span>
                                                                    </p>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-secondary btn-sm"
                                                                        data-bs-dismiss="modal">Fechar</button>
                                                                    <button class="btn btn-danger btn-sm"
                                                                        onclick="javascript: excluir_arquivo('{{ $arquivo->cod_arquivo }}', '{{ $codAtendimento }}');"
                                                                        data-bs-dismiss="modal">Sim, quero
                                                                        excluir!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @php
                                                        // Fim da modal de confirmação de exclusão do arquivo
                                                    @endphp

                                                </div>

                                                @php
                                                    $contArquivo++;
                                                @endphp
                                            @endforeach

                                        </div>

                                    </div>

                                </div>

                            </div>

                            @php
                                /* Fim da parte para Incluir a parte dos Anexos */
                            @endphp

                        </div>

                    </div>
                </div>

                <script type="text/javascript">
                    $(document).ready(function() {

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


            </div>

            @php
                // Início do javascript para gravar a edição
            @endphp

            <script>
                function atualizar_input_select(item_id, novo_valor, chave_primaria, tema) {

                    novo_valor = novo_valor.replace(/\//g, ">>barra<<");
                    novo_valor = novo_valor.replace(/,/g, ">>virgula<<");

                    $.get('{!! url('atendimento-ajax-gravar-alteracao-select') !!}' + '/' + item_id + '/' + encodeURIComponent(novo_valor) + '/' + chave_primaria,
                        function(
                            data) {

                            setTimeout(function() {
                                $("#divColRetorno_" + tema).fadeIn("slow");
                            }, 100);

                            setTimeout(function() {
                                $("#divColRetorno_" + tema).fadeOut("slow");
                            }, 3900);

                        });

                }

                function atualizar_input_text(item_id, novo_valor, chave_primaria, tema) {

                    novo_valor = novo_valor.replace(/\//g, ">>barra<<");
                    novo_valor = novo_valor.replace(/,/g, ">>virgula<<");

                    $.get('{!! url('atendimento-ajax-gravar-alteracao-select') !!}' + '/' + item_id + '/' + encodeURIComponent(novo_valor) + '/' + chave_primaria,
                        function(
                            data) {

                            $("#divColRetorno_" + tema).fadeIn("slow");

                            setTimeout(function() {
                                $("#divColRetorno_" + tema).fadeOut("slow");
                            }, 3900);

                        });

                }

                function salvar_convidados(cod_interlocutor_convidado_novo, nom_convidado_novo, cod_atendimento) {

                    var texto_alerta = '';

                    if (cod_interlocutor_convidado_novo === null || cod_interlocutor_convidado_novo === undefined ||
                        cod_interlocutor_convidado_novo === '') {

                        texto_alerta = texto_alerta +
                            'Selecionar o <span class="text-bold">Cargo do(a) convidado(a)</span>;<br />';
                        document.getElementById("cod_interlocutor_convidado_novo").focus();

                    }

                    if (nom_convidado_novo === null || nom_convidado_novo === undefined ||
                        nom_convidado_novo === '') {

                        texto_alerta = texto_alerta +
                            'Digitar o <span class="text-bold">Nome convidado(a)</span>;<br />';
                        document.getElementById("nom_convidado_novo").focus();

                    }

                    if (texto_alerta != '') {

                        $("#pTextoModal").empty();
                        $("#pTextoModal").append(
                            '<i class="fas fa-exclamation-triangle text-danger"></i> <span class="text-bold">Para incluir um novo convidado você deve:</span> <br /><br />' +
                            texto_alerta);

                        var modalLocalAlerta = new bootstrap.Modal(document.getElementById('modalLocalAlerta'));
                        modalLocalAlerta.show();

                    } else {

                        $.get('{!! url('atendimento/incluir/convidado') !!}' + '/' + cod_interlocutor_convidado_novo + '/' + nom_convidado_novo + '/' +
                            cod_atendimento,
                            function(
                                data) {

                                document.getElementById("cod_interlocutor_convidado_novo").value = '';
                                document.getElementById("nom_convidado_novo").value = '';

                                $("#divColRetorno_incluir_convidado").fadeIn("slow");

                                setTimeout(function() {
                                    $("#divColRetorno_incluir_convidado").fadeOut("slow");
                                }, 3900);

                                setTimeout(function() {
                                    $("#divConvidados").empty();
                                    $("#divConvidados").append(
                                        "<i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando..."
                                    );
                                }, 100);

                                setTimeout(function() {
                                    $("#divConvidados").empty();
                                    $("#divConvidados").append(data);
                                }, 900);

                            });

                    }

                }

                function excluir_convidado(cod_convidado, cod_atendimento) {

                    $.get('{!! url('atendimento/excluir/convidado') !!}' + '/' + cod_convidado + '/' + cod_atendimento,
                        function(
                            data) {

                            $("#divColRetorno_excluir_convidado").fadeIn("slow");

                            setTimeout(function() {
                                $("#divColRetorno_excluir_convidado").fadeOut("slow");
                            }, 3900);

                            setTimeout(function() {
                                $("#divConvidados").empty();
                                $("#divConvidados").append(
                                    "<i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando..."
                                );
                            }, 100);

                            setTimeout(function() {
                                $("#divConvidados").empty();
                                $("#divConvidados").append(data);
                            }, 900);

                        });

                }

                function salvar_demanda(dsc_demanda_nova, codigoUnidade_nova, dte_prazo_nova, cod_status_demanda_nova,
                    cod_atendimento) {

                    var texto_alerta = '';

                    if (dsc_demanda_nova === null || dsc_demanda_nova === undefined ||
                        dsc_demanda_nova === '') {

                        texto_alerta = texto_alerta +
                            'Digitar a <span class="text-bold">Descrição da demanda</span>;<br />';
                        document.getElementById("dsc_demanda_nova").focus();

                    }

                    if (codigoUnidade_nova === null || codigoUnidade_nova === undefined ||
                        codigoUnidade_nova === '') {

                        texto_alerta = texto_alerta +
                            'Selecionar a <span class="text-bold">Área Responsável pela demanda</span>;<br />';
                        document.getElementById("codigoUnidade_nova").focus();

                    }

                    if (dte_prazo_nova === null || dte_prazo_nova === undefined ||
                        dte_prazo_nova === '') {

                        texto_alerta = texto_alerta +
                            'Selecionar ou digitar o <span class="text-bold">Prazo estimado de conclusão da demanda</span>;<br />';
                        document.getElementById("dte_prazo_nova").focus();

                    }

                    if (cod_status_demanda_nova === null || cod_status_demanda_nova === undefined ||
                        cod_status_demanda_nova === '') {

                        texto_alerta = texto_alerta +
                            'Selecionar o <span class="text-bold">Status da demanda</span>;<br />';
                        document.getElementById("cod_status_demanda_nova").focus();

                    }

                    if (texto_alerta != '') {

                        $("#pTextoModal").empty();
                        $("#pTextoModal").append(
                            '<i class="fas fa-exclamation-triangle text-danger"></i> <span class="text-bold">Para incluir uma nova demanda recebida você deve:</span> <br /><br />' +
                            texto_alerta);

                        var modalLocalAlerta = new bootstrap.Modal(document.getElementById('modalLocalAlerta'));
                        modalLocalAlerta.show();

                    } else {

                        $.get('{!! url('atendimento/incluir/demanda') !!}' + '/' + dsc_demanda_nova + '/' + codigoUnidade_nova + '/' +
                            dte_prazo_nova + '/' + cod_status_demanda_nova + '/' + cod_atendimento,
                            function(
                                data) {

                                document.getElementById("dsc_demanda_nova").value = '';
                                document.getElementById("codigoUnidade_nova").value = '';
                                document.getElementById("dte_prazo_nova").value = '';
                                document.getElementById("cod_status_demanda_nova").value = '';

                                $("#divColRetorno_incluir_demandas").fadeIn("slow");

                                setTimeout(function() {
                                    $("#divColRetorno_incluir_demandas").fadeOut("slow");
                                }, 3900);

                                setTimeout(function() {
                                    $("#divDemandas").empty();
                                    $("#divDemandas").append(
                                        "<i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando..."
                                    );
                                }, 100);

                                setTimeout(function() {
                                    $("#divDemandas").empty();
                                    $("#divDemandas").append(data);
                                }, 900);

                            });

                    }

                }

                function excluir_demanda(cod_demanda_atendimento, cod_atendimento) {

                    $.get('{!! url('atendimento/excluir/demanda') !!}' + '/' + cod_demanda_atendimento + '/' + cod_atendimento,
                        function(
                            data) {

                            $("#divColRetorno_excluir_demandas").fadeIn("slow");

                            setTimeout(function() {
                                $("#divColRetorno_excluir_demandas").fadeOut("slow");
                            }, 3900);

                            setTimeout(function() {
                                $("#divDemandas").empty();
                                $("#divDemandas").append(
                                    "<i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando..."
                                );
                            }, 100);

                            setTimeout(function() {
                                $("#divDemandas").empty();
                                $("#divDemandas").append(data);
                            }, 900);

                        });

                }

                function excluir_arquivo(cod_arquivo, cod_atendimento) {

                    $.get('{!! url('atendimento/excluir/arquivo') !!}' + '/' + cod_arquivo + '/' + cod_atendimento,
                        function(
                            data) {

                            $("#divColRetorno_excluir_anexo").fadeIn("slow");

                            setTimeout(function() {
                                $("#divColRetorno_excluir_anexo").fadeOut("slow");
                            }, 3900);

                            setTimeout(function() {
                                $("#divAnexos").empty();
                                $("#divAnexos").append(data);
                            }, 9);

                        });

                }
            </script>

            @php
                // Fim do javascript para gravar a edição
            @endphp

            <div class="modal" id="modalLocalAlerta" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"
                style="padding-top: 150px!Important;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header"
                            style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                            <p class="modal-title text-white"
                                style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                ALGO DEU ERRADO!</p>
                        </div>
                        <div class="modal-body">

                            <span id="pTextoModal" class="pt-2"></span>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- Fim da apresentação do conteúdo da página -->

        <!-- Início funções javascript -->
        <script>
            setTimeout(function() {
                $("#divContent").fadeIn("slow");
            }, 700);

            setTimeout(function() {
                $("#divProcessando").fadeOut("slow");
            }, 300);
        </script>
        <!-- Fim funções javascript -->
    @endif
@endsection
