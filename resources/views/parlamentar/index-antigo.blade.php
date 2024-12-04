@extends('layouts.app')

@section('content')
    @php
        isset($temaSelecionado) && !is_null($temaSelecionado) && $temaSelecionado != '' ? ($temaSelecionado = $temaSelecionado) : ($temaSelecionado = null);

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
                    <a href="{!! url('parlamentar') !!}">
                        <span id="breadcrumbs-current">Parlamentar</span>
                    </a>
                </span>

                @if ($getParlamentar)
                    <span class="pl-1 pr-1">></span>

                    <span dir="ltr" id="breadcrumbs-2">
                        <span id="breadcrumbs-current">
                            {{ $getParlamentar->nom_parlamentar }}</span>
                    </span>
                @endif

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->

    <!-- Início apresentação dos cards de entrada -->
    <div class="row" id="informacao">
        <div class="col-xs-12 col-sm-12 col-md-12"
            style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;">
            <i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando...
        </div>
    </div>

    <div class="row" id="div1" style="display: none;">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3 d-print-none">

            {!! Form::open(['method' => 'post', 'url' => route('parlamentar')]) !!}
            <div class="card" style="">
                <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseFiltro"
                    aria-expanded="true" aria-controls="collapseFiltro"
                    style="cursor: pointer; padding: 6px !Important; padding-left: 17px !Important; background-color: #efefef !Important; color: #000000 !Important;">
                    <i class="fas fa-filter text-secondary"></i> Filtrar por Nome Parlamentar - UF de Representação - Cargo
                    - Partido - liderança de partido
                </div>
                <div class="card-body collapse <?php $cod_parlamentar ? print '' : print 'show'; ?>" id="collapseFiltro">

                    <div class="row" style="">
                        <div class="col-12 col-sm-12 col-md-12">
                            {!! Form::select('cod_parlamentar', $getParlamentares, $cod_parlamentar, [
                                'class' => 'form-control',
                                'placeholder' => 'Pesquisar',
                                'id' => 'cod_parlamentar',
                                'onchange' => 'window.location.href = "' . url('parlamentar') . '/" + this.value;',
                                'style' => 'width: 99% !Important; ',
                            ]) !!}

                            <div id="" class="form-text pt-1 pl-3 textoPequeno text-secondary"><strong>
                                    <span class="font-numero">{{ count($getParlamentares) }}</span> parlamentares</strong>
                            </div>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#cod_parlamentar').select2();
                                    $(document).on("select2:open", () => {
                                        document.querySelector('.select2-container--open .select2-search__field').focus();
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        {!! Form::close() !!}
        @php
            /* Início da parte dos dados do parlamentar */
        @endphp
        @if (isset($cod_parlamentar) && !is_null($cod_parlamentar) && $cod_parlamentar != '')
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">

                <div class="card shadow-sm">
                    <div class="card-header <?php $getParlamentar->dsc_casa == 'Câmara dos Deputados' ? print 'bg-camara' : print 'bg-senado'; ?> p-1 pl-3">
                        {!! $getParlamentar->dsc_tratamento . ' - ' . $getParlamentar->nom_parlamentar !!}
                    </div>
                    <div class="card-body pt-2 pb-2">

                        <div class="row">

                            <div
                                class="col-xs-12 col-sm-4 col-md-4 col-lg-3 col-xl-2 pt-4 d-flex align-items-center justify-content-center">

                                <figure class="figure">
                                    <img src="<?php $getParlamentar->dsc_casa == 'Câmara dos Deputados' ? print asset('storage/fotos/deputados/' . $cod_parlamentar . '.jpg') : print asset('storage/fotos/senadores/' . $cod_parlamentar . '.jpg'); ?>" class="figure-img img-fluid shadow-sm rounded"
                                        style="min-width: 225px !Important; width: 245px !Important; max-width: 245px !Important; min-height: 314px !Important; height: 314px !Important; max-height: 314px !Important;">
                                </figure>

                            </div>

                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-4 pt-3 ">

                                <div class="row">

                                    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 text-justify border-bottom divParlamentar"
                                        style="padding-bottom: 0.4rem !Important;">
                                        <span class="textoNormalTabela">Dados gerais</span>
                                    </div>

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">Nome:</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-4 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center border-bottom text-justify divParlamentar">

                                        <a href="{!! $getParlamentar->lnk_parlamentar !!}" target="_blank" style="text-decoration: none;">
                                            <span class="textoNormalTabela">{!! primeiraLetraMaiuscula($getParlamentar->nom_parlamentar_sem_formatacao) !!}</span>
                                        </a>

                                    </div>

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">Nome civil:</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-4 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center border-bottom text-justify divParlamentar">

                                        <span class="textoNormalTabela">{!! primeiraLetraMaiuscula($getParlamentar->nom_parlamentar_completo) !!}</span>

                                    </div>

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">Situação:</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                        <span class="textoNormalTabela">{!! $getParlamentar->dsc_participacao . ' -' !!} <?php $getParlamentar->dsc_situacao != 'Exercício' ? print '<span class="text-danger">' . $getParlamentar->dsc_situacao . '</span>' : print $getParlamentar->dsc_situacao; ?></span>

                                    </div>

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">Legislatura:</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-4 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center border-bottom text-justify divParlamentar">

                                        @php
                                            // Início de recuperar a legislatura dos Deputados Federais
                                            $legislaturas = null;
                                            if ($getParlamentar->dsc_casa === 'Câmara dos Deputados') {
                                                if ($getParlamentar->legislaturasDeputado->count() > 0) {
                                                    foreach ($getParlamentar->legislaturasDeputado as $legislatura) {
                                                        $legislaturas .= $legislatura->legislatura . '/';
                                                    }

                                                    $legislaturas = trim($legislaturas, '/');
                                                }
                                            }
                                            // Fim de recuperar a legislatura dos Deputados Federais

                                            // Início de recuperar a legislatura dos Deputados Federais
                                            if ($getParlamentar->dsc_casa === 'Senado Federal') {
                                                if ($getParlamentar->legislaturasSenado->count() > 0) {
                                                    foreach ($getParlamentar->legislaturasSenado as $legislatura) {
                                                        $legislaturas .= $legislatura->legislatura . '/';
                                                    }

                                                    $legislaturas = trim($legislaturas, '/');
                                                }
                                            }
                                            // Fim de recuperar a legislatura dos Deputados Federais
                                        @endphp

                                        <span class="textoNormalTabela font-numero">{!! $legislaturas !!}</span>

                                    </div>

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">Formação <span
                                                class="text-small text-muted">(TSE)</span> :</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                        <span class="textoNormalTabela">{!! $getParlamentar->resumo ? primeiraLetraMaiuscula($getParlamentar->resumo->ds_grau_instrucao) : '-' !!}</span>

                                    </div>

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">Última ocupação <span
                                                class="text-small text-muted">(TSE)</span> :</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                        <span class="textoNormalTabela">{!! $getParlamentar->resumo ? primeiraLetraMaiuscula($getParlamentar->resumo->ds_ocupacao) : '-' !!}</span>

                                    </div>

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">Aniversário:</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                        <span class="textoNormalTabela font-numero">
                                            {!! formatarDataComCarbonParaBR($getParlamentar->dte_nascimento) !!} &nbsp;&nbsp;<span
                                                style="font-weight: normal !Important; font-size: 0.7rem !Important;">{!! retornaTextoTirandoParteDoTexto(formatarDataComCarbonForHumans($getParlamentar->dte_nascimento), 'há ') !!}</span>
                                        </span>

                                    </div>

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">Cidade natal:</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                        <span
                                            class="textoNormalTabela">{!! primeiraLetraMaiuscula($getParlamentar->nom_municipio_nascimento) !!}/{!! $getParlamentar->sgl_uf_nascimento !!}</span>

                                    </div>

                                    @if ($bln_acesso_inrestrito == 1)
                                        <div
                                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                                            <span class="textoTituloTabela">Celular:</span>
                                            <span data-bs-toggle="modal" data-bs-target="#modalNovoCelular">
                                                <i class="fas fa-plus-circle text-success d-print-none"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Incluir novo número de celular"
                                                    style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                            </span>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalNovoCelular" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true"
                                                data-bs-backdrop="static" data-bs-keyboard="false"
                                                style="padding-top: 150px!Important;">
                                                <div class="modal-dialog  modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header"
                                                            style="background: linear-gradient(135deg,#898989 0%,#acacac 100%);color: white;">
                                                            <p class="modal-title text-white"
                                                                style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                                Cadastrar número de celular</p>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input id="num_celular" type="text"
                                                                class="form-control font-numero @error('num_celular') is-invalid @enderror"
                                                                name="num_celular" value="" required
                                                                autocomplete="num_celular" autofocus
                                                                placeholder="Número do celular com DDD">

                                                            <div id=""
                                                                class="form-text pl-3 textoPequeno text-primary font-numero">
                                                                Ex.: (61)
                                                                98888-9999</div>

                                                            <script type="text/javascript">
                                                                $('#num_celular').mask('(00) 00000-0000');
                                                            </script>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="button" class="btn btn-primary"
                                                                onclick="javascript: gravar_celular($('#num_celular').val(),'{!! $cod_parlamentar !!}');">Salvar</button>
                                                        </div>

                                                        <script>
                                                            function gravar_celular(num_celular, cod_parlamentar) {

                                                                $('#modalNovoCelular').modal('toggle');

                                                                @auth
                                                                $.get('<?php print url('gravar-celular-parlamentar'); ?>' + '/' + num_celular + '/' + cod_parlamentar, function(data) {
                                                                    $("#divCelular").empty();

                                                                    $("#divCelular").append(
                                                                        '<div class="col-xs-12 col-sm-12 col-md-12" style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;"><i class="fa fa - circle - notch fa - spin text - primary "></i><span class="sr - only "></span> Carregando...</div>'
                                                                    );

                                                                    $("#divCelular").empty();

                                                                    $("#divCelular").append(data);

                                                                    $("#num_celular").val('');

                                                                });
                                                            @else
                                                                alert('Por gentileza, é necessário efetuar um novo login (acesso) ao sistema.');
                                                            @endauth

                                                            }
                                                        </script>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 col-lg-8 m-0 p-0 pt-1 pb-1 d-flex align-items-center text-justify border-bottom divParlamentar"
                                            id="divCelular">

                                            <div class="row pl-4" style="width: 100%!Important;">

                                                @if ($getParlamentar->celulares->count() > 0)
                                                    <?php $contCelular = 1; ?>
                                                    @foreach ($getParlamentar->celulares as $celular)
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-<?php $getParlamentar->celulares->count() <= 1 ? print '12' : print '6'; ?> col-xl-<?php $getParlamentar->celulares->count() <= 1 ? print '12' : print '6'; ?> m-0 p-0"
                                                            style="margin-left: -3px!Important; padding-left: -3px!Important;">
                                                            <span class="textoNormalTabela font-numero">
                                                                {!! applyMask($celular->num_celular, '(##) #####-####') !!}
                                                            </span>
                                                            &nbsp;
                                                            <span class="m-0 p-0">
                                                                <span data-bs-toggle="modal"
                                                                    data-bs-target="#modalEditarCelular{!! $celular->cod_celular !!}"
                                                                    class="d-print-none m-0 p-0">
                                                                    <i class="fas fa-edit text-primary d-print-none"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="Editar número de celular"
                                                                        style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                                                </span>

                                                                <!-- Modal -->
                                                                <div class="modal fade"
                                                                    id="modalEditarCelular{!! $celular->cod_celular !!}"
                                                                    tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                    aria-hidden="true" data-bs-backdrop="static"
                                                                    data-bs-keyboard="false"
                                                                    style="padding-top: 150px!Important;">
                                                                    <div class="modal-dialog  modal-sm">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header"
                                                                                style="background: linear-gradient(135deg,#898989 0%,#acacac 100%);color: white;">
                                                                                <p class="modal-title text-white"
                                                                                    style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                                                    Editar número de celular</p>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <input
                                                                                    id="num_celular{!! $celular->cod_celular !!}"
                                                                                    type="text"
                                                                                    class="form-control font-numero @error('num_celular') is-invalid @enderror"
                                                                                    name="num_celular"
                                                                                    value="{!! applyMask($celular->num_celular, '(##) #####-####') !!}"
                                                                                    required autocomplete="num_celular"
                                                                                    autofocus
                                                                                    placeholder="Número do celular com DDD">

                                                                                <div id=""
                                                                                    class="form-text pl-3 textoPequeno text-primary font-numero">
                                                                                    Ex.: {!! applyMask($celular->num_celular, '(##) #####-####') !!}</div>

                                                                                <script type="text/javascript">
                                                                                    $('#num_celular{!! $celular->cod_celular !!}').mask('(00) 00000-0000');
                                                                                </script>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                                <button type="button"
                                                                                    class="btn btn-primary"
                                                                                    onclick="javascript: editar_celular('{!! $celular->cod_celular !!}',$('#num_celular{!! $celular->cod_celular !!}').val(),'{!! $cod_parlamentar !!}');">Alterar</button>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </span>
                                                            <span class="m-0 p-0">
                                                                <span data-bs-toggle="modal"
                                                                    data-bs-target="#modalExcluirCelular{!! $celular->cod_celular !!}"
                                                                    class="d-print-none m-0 p-0">
                                                                    <i class="fas fa-trash-alt text-danger"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="Excluir número de celular"
                                                                        style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                                                </span>

                                                                <!-- Modal -->
                                                                <div class="modal fade"
                                                                    id="modalExcluirCelular{!! $celular->cod_celular !!}"
                                                                    tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                    aria-hidden="true" data-bs-backdrop="static"
                                                                    data-bs-keyboard="false"
                                                                    style="padding-top: 150px!Important;">
                                                                    <div class="modal-dialog  modal-sm">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header"
                                                                                style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                                                <p class="modal-title text-white"
                                                                                    style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                                                    Excluir número de celular</p>
                                                                            </div>
                                                                            <div class="modal-body">

                                                                                <p>
                                                                                    Número: <span
                                                                                        class="font-numero">{!! applyMask($celular->num_celular, '(##) #####-####') !!}</span>
                                                                                </p>

                                                                                <p class="">
                                                                                    Deseja realmente excluir este número de
                                                                                    celular?
                                                                                </p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                                <button type="button"
                                                                                    class="btn btn-danger"
                                                                                    onclick="javascript: excluir_celular('{!! $celular->cod_celular !!}',$('#num_celular{!! $celular->cod_celular !!}').val(),'{!! $cod_parlamentar !!}');">Sim,
                                                                                    excluir</button>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </span>
                                                            <?php $contCelular++; ?>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    {{ '-' }}
                                                @endif
                                            </div>

                                        </div>

                                        <script>
                                            function editar_celular(cod_celular, num_celular, cod_parlamentar) {

                                                $('#modalEditarCelular' + cod_celular).modal('toggle');

                                                @auth
                                                $.get('<?php print url('gravar-celular-parlamentar'); ?>' + '/' + num_celular + '/' + cod_parlamentar + '/' + cod_celular, function(data) {
                                                    $("#divCelular").empty();

                                                    $("#divCelular").append(
                                                        '<div class="col-xs-12 col-sm-12 col-md-12" style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;"><i class="fa fa - circle - notch fa - spin text - primary "></i><span class="sr - only "></span> Carregando...</div>'
                                                    );

                                                    $("#divCelular").empty();

                                                    $("#divCelular").append(data);

                                                });
                                            @else
                                                alert('Por gentileza, é necessário efetuar um novo login (acesso) ao sistema.');
                                            @endauth

                                            }

                                            function excluir_celular(cod_celular, num_celular, cod_parlamentar) {

                                                $('#modalExcluirCelular' + cod_celular).modal('toggle');

                                                @auth
                                                $.get('<?php print url('gravar-celular-parlamentar'); ?>' + '/' + num_celular + '/' + cod_parlamentar + '/' + cod_celular + '/Sim', function(
                                                    data) {
                                                    $("#divCelular").empty();

                                                    $("#divCelular").append(
                                                        '<div class="col-xs-12 col-sm-12 col-md-12" style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;"><i class="fa fa - circle - notch fa - spin text - primary "></i><span class="sr - only "></span> Carregando...</div>'
                                                    );

                                                    $("#divCelular").empty();

                                                    $("#divCelular").append(data);

                                                });
                                            @else
                                                alert('Por gentileza, é necessário efetuar um novo login (acesso) ao sistema.');
                                            @endauth

                                            }
                                        </script>
                                    @endif

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">Telefone:</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                        <span class="textoNormalTabela font-numero">
                                            @php
                                                if ($getParlamentar->dsc_casa === 'Câmara dos Deputados') {
                                                    print $getParlamentar->num_telefone;
                                                } else {
                                                    print applyMask($getParlamentar->num_telefone, '####-####');
                                                }
                                            @endphp
                                        </span>

                                    </div>

                                    <div
                                        class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                                        <span class="textoTituloTabela">E-mail:</span>
                                    </div>

                                    <div
                                        class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                        <span class="textoNormalTabela">{!! strtolower(limpaStringSemTirarHifem($getParlamentar->dsc_email)) !!}</span>

                                    </div>

                                </div>

                            </div>

                            <div class="col-xs-12 col-sm-7 col-md-8 col-lg-3 col-xl-4 pt-3"
                                style="padding-top: 1.15rem!Important;">

                                <div class="conditional-div pl-0 pl-sm-2">

                                    <div class="row">

                                        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 text-justify border-bottom divParlamentar"
                                            style="padding-top: 0.1rem !Important; padding-bottom: 0.3rem !Important;">
                                            <span class="textoNormalTabela">Lideranças, Cargos e comissões</span>
                                        </div>

                                        @if ($getParlamentar->dsc_casa === 'Câmara dos Deputados')

                                            @if ($getParlamentar->cargosMesaDiretora)
                                                <p class="textoCargosEComissoes pt-2 pl-0">
                                                    @php
                                                        $getParlamentar->cargosMesaDiretora->titulo === 'Presidente' ? ($getParlamentar->cargosMesaDiretora->titulo = 'Presidente da Câmara dos Deputados') : ($getParlamentar->cargosMesaDiretora->titulo = $getParlamentar->cargosMesaDiretora->titulo . ' da MESA DIRETORA');
                                                    @endphp
                                                    <span
                                                        class="text-bold"><?php $getParlamentar->cargosMesaDiretora->titulo === 'Presidente da Câmara dos Deputados' ? print '<i class="fas fa-medal text-success"></i> ' : ''; ?>{!! mb_strtoupper($getParlamentar->cargosMesaDiretora->titulo, 'UTF-8') !!}</span>
                                                </p>
                                            @endif

                                            @if ($getParlamentar->liderancaDeputados->count() > 0)
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-1">

                                                    @php
                                                        $contLideranca = 1;
                                                    @endphp

                                                    @foreach ($getParlamentar->liderancaDeputados as $lideranca)
                                                        <p class="textoCargosEComissoes">
                                                            {!! '<span class="font-numero">' . $contLideranca . '</span>. ' . $lideranca->titulo !!} do(a)
                                                            {{ $lideranca->tipo }} <?php $lideranca->nome != $lideranca->tipo ? print ' ' . $lideranca->nome : ''; ?> desde
                                                            <span
                                                                class="font-numero">{{ formatarDataComCarbonParaBR($lideranca->dataInicio) }}</span>
                                                        </p>

                                                        @php
                                                            $contLideranca++;
                                                        @endphp
                                                    @endforeach

                                                </div>
                                            @endif

                                            @if ($getParlamentar->comissoesDeputados->count() > 0)
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-1">

                                                    @foreach ($getParlamentar->comissoesDeputados as $comissao)
                                                        <div class="badge bg-camara-badge" data-bs-toggle="popover"
                                                            data-bs-trigger="hover focus"
                                                            data-bs-content="{!! $comissao->siglaOrgao !!} - {{ $comissao->nomePublicacao }}"
                                                            data-bs-placement="auto" style="cursor: help;">
                                                            <?php $comissao->siglaOrgao === 'CINDRE' ? print '<i class="fas fa-exclamation-triangle" style="font-size: 0.8rem !Important; color: #EDBE18;"></i> ' : ''; ?>{!! $comissao->siglaOrgao !!}</div>
                                                    @endforeach

                                                </div>
                                            @endif
                                        @endif

                                        @if ($getParlamentar->dsc_casa === 'Senado Federal')

                                            @if ($getParlamentar->cargosMesaDiretoraSenado)
                                                <p class="textoCargosEComissoes mb-1 pt-2 pl-0 pb-0">
                                                    @php
                                                        $getParlamentar->cargosMesaDiretoraSenado->Cargo === 'PRESIDENTE' ? ($getParlamentar->cargosMesaDiretoraSenado->Cargo = 'Presidente do Senado Federal') : ($getParlamentar->cargosMesaDiretoraSenado->Cargo = $getParlamentar->cargosMesaDiretoraSenado->Cargo . ' da MESA DO SENADO');
                                                    @endphp
                                                    <span
                                                        class="text-bold"><?php $getParlamentar->cargosMesaDiretoraSenado->Cargo === 'Presidente do Senado Federal' ? print '<i class="fas fa-medal text-primary"></i> ' : ''; ?>{!! mb_strtoupper($getParlamentar->cargosMesaDiretoraSenado->Cargo, 'UTF-8') !!}</span>
                                                </p>
                                            @endif

                                            @if ($getParlamentar->liderancaSenadores->count() > 0 || $getParlamentar->cargosSenadores)
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-1">

                                                    @php
                                                        $contLideranca = 1;
                                                    @endphp

                                                    @foreach ($getParlamentar->liderancaSenadores as $lideranca)
                                                        <p class="textoCargosEComissoes mb-1 pb-0">

                                                            @if (isset($lideranca->SiglaPartido) && !is_null($lideranca->SiglaPartido) && $lideranca->SiglaPartido != '')
                                                                {!! '<span class="font-numero">' .
                                                                    $contLideranca .
                                                                    '</span>. ' .
                                                                    retornaTextoTirandoParteDoTexto($lideranca->DescricaoTipoLideranca, ' do Senado Federal') !!}
                                                                do
                                                                <span
                                                                    style="font-size: 1rem !Important;">{!! $lideranca->SiglaPartido !!}</span>
                                                                no {{ $lideranca->SiglaCasaLideranca }} desde
                                                                <span
                                                                    class="font-numero">{{ formatarDataComCarbonParaBR($lideranca->DataDesignacao) }}</span>
                                                            @else
                                                                {!! '<span class="font-numero">' . $contLideranca . '</span>. ' . $lideranca->UnidadeLideranca !!}
                                                                <?php isset($lideranca->NomeBloco) && !is_null($lideranca->NomeBloco) && $lideranca->NomeBloco != '' ? print 'do ' . $lideranca->NomeBloco : ''; ?>
                                                                desde
                                                                <span
                                                                    class="font-numero">{{ formatarDataComCarbonParaBR($lideranca->DataDesignacao) }}</span>
                                                            @endif
                                                        </p>

                                                        @php
                                                            $contLideranca++;
                                                        @endphp
                                                    @endforeach

                                                    @php
                                                        $contCargo = $contLideranca;
                                                    @endphp

                                                    @foreach ($getParlamentar->cargosSenadores as $cargo)
                                                        @if (!is_null($cargo->colegiadoAtivo))
                                                            <p class="textoCargosEComissoes mb-1 pb-0">
                                                                {!! '<span class="font-numero">' .
                                                                    $contCargo .
                                                                    '</span>. ' .
                                                                    primeiraLetraMaiuscula($cargo->DescricaoCargo) .
                                                                    ' do(a) ' .
                                                                    $cargo->SiglaComissao .
                                                                    ' desde <span class="font-numero">' .
                                                                    formatarDataComCarbonParaBR($cargo->DataInicio) .
                                                                    '</span>' !!}
                                                            </p>

                                                            @php
                                                                $contCargo++;
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                </div>
                                            @endif

                                            @if ($getParlamentar->comissoesSenadores->count() > 0)
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-1">

                                                    @foreach ($getParlamentar->comissoesSenadores as $comissao)
                                                        @if (substr($comissao->SiglaComissao, 0, 1) === 'C')
                                                            <a href="https://legis.senado.leg.br/comissoes/comissao?codcol={!! $comissao->CodigoComissao !!}"
                                                                target="_blank">
                                                                <div class="badge bg-senado-badge"
                                                                    data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                                    data-bs-content="{!! $comissao->SiglaCasaComissao !!} - {!! $comissao->SiglaComissao !!} - {{ $comissao->NomeComissao }}"
                                                                    data-bs-placement="auto" style="cursor: help;"><i
                                                                        class="fas fa-link text-warning"
                                                                        style="font-size: 0.6rem !Important;"></i>
                                                                    {!! $comissao->SiglaComissao !!}</div>
                                                            </a>
                                                        @else
                                                            <div class="badge bg-senado-badge" data-bs-toggle="popover"
                                                                data-bs-trigger="hover focus"
                                                                data-bs-content="{!! $comissao->SiglaCasaComissao !!} - {!! $comissao->SiglaComissao !!} - {{ $comissao->NomeComissao }}"
                                                                data-bs-placement="auto" style="cursor: help;">
                                                                {!! $comissao->SiglaComissao !!}</div>
                                                        @endif
                                                    @endforeach

                                                </div>
                                            @endif


                                        @endif

                                    </div>

                                </div>

                            </div>

                            <div class="col-xs-12 col-sm-5 col-md-4 col-lg-2 col-xl-2 pt-3 r-xl-4 pr-xl-4"
                                style="padding-top: 1.15rem!Important;">

                                <div class="conditional-div pl-0 pl-sm-2 pr-xl-1">
                                    <div class="row">

                                        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 pr-0 text-justify border-bottom divParlamentar"
                                            style="padding-top: 0.1rem !Important; padding-bottom: 0.3rem !Important;">
                                            <span class="textoNormalTabela">Dados do TSE</span>
                                        </div>

                                        <div
                                            class="col-4 col-xs-4 col-sm-5 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                            <span class="textoTituloTabela">Partido/UF:</span>
                                        </div>

                                        <div
                                            class="col-8 col-xs-8 col-sm-7 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                            <span class="textoNormalTabela">{!! $getParlamentar->sgl_partido !!} /
                                                {!! $getParlamentar->sgl_uf_representante !!}</span>

                                        </div>

                                        <div
                                            class="col-4 col-xs-4 col-sm-5 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                            <span class="textoTituloTabela">Ano eleição:</span>
                                        </div>

                                        <div
                                            class="col-8 col-xs-8 col-sm-7 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                            <span class="textoNormalTabela font-numero">
                                                @php
                                                    isset($getParlamentar->num_ano_eleicao) && !is_null($getParlamentar->num_ano_eleicao) && $getParlamentar->num_ano_eleicao != '' ? print $getParlamentar->num_ano_eleicao : print '-';
                                                @endphp
                                            </span>

                                        </div>

                                        <div
                                            class="col-4 col-xs-4 col-sm-5 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                            <span class="textoTituloTabela">Reeleito:</span>
                                        </div>

                                        <div
                                            class="col-8 col-xs-8 col-sm-7 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                            <span class="textoNormalTabela">
                                                @php
                                                    isset($getParlamentar->dsc_reeleito) && !is_null($getParlamentar->dsc_reeleito) && $getParlamentar->dsc_reeleito != '' ? print $getParlamentar->dsc_reeleito : print '-';
                                                @endphp
                                            </span>

                                        </div>

                                        <div
                                            class="col-4 col-xs-4 col-sm-5 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                            <span class="textoTituloTabela">TSE votos:</span>
                                        </div>

                                        <div
                                            class="col-8 col-xs-8 col-sm-7 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                            <span class="textoNormalTabela font-numero">
                                                @php
                                                    isset($getParlamentar->num_total_votos) && !is_null($getParlamentar->num_total_votos) && $getParlamentar->num_total_votos != '' ? print formatarNumeroInteiro($getParlamentar->num_total_votos) : print '-';
                                                @endphp
                                            </span>

                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    @if ($dteAtualizacaoCD !== null && $dteAtualizacaoSF !== null)
                        <div class="card-footer <?php $getParlamentar->dsc_casa == 'Câmara dos Deputados' ? print 'bg-camara-footer' : print 'bg-senado-footer'; ?> pt-2 pb-2">
                            <span class="textoTituloTabela">Dados atualizados em
                            </span><span class="textoNormalTabela font-numero"><?php $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print formatarTimeStampComCarbonParaBR($dteAtualizacaoCD) . ', ' . formatarDataComCarbonForHumans($dteAtualizacaoCD) : print formatarTimeStampComCarbonParaBR($dteAtualizacaoSF) . ', ' . formatarDataComCarbonForHumans($dteAtualizacaoSF); ?></span>. <span
                                class="textoTituloTabela"> Fonte:</span> <span
                                class="textoNormalTabela"><?php $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print $getParlamentar->dsc_casa : print $getParlamentar->dsc_casa; ?></span>
                        </div>
                    @endif
                </div>

            </div>

            @php
                /* Início do loop dos temas */
                $contTema = 1;
            @endphp
            @if ($bln_acesso_inrestrito != 1)
                @php
                    foreach (array_keys($temas, 'Observações', true) as $key) {
                        unset($temas[$key]);
                    }
                @endphp
            @endif

            @foreach ($temas as $tema)
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-0" id="accordionTemas">

                    <div class="card sticky-top mt-2">
                        <div class="card-body cardTemas shadow-sm" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $contTema }}" aria-expanded="true"
                            aria-controls="collapse{{ $contTema }}"
                            onclick="scrollToCard(this, {{ $contTema }});" id="divTema{!! $tema !!}">
                            {!! '<span class="font-numero">' . $contTema . '</span>. ' . $tema !!}
                            <?php
                            if ($tema === 'Observações') {
                                // echo '<span class="text-primary font-numero" style="font-size: 0.9rem !Important;">(' . $getParlamentar->observacoes->count() . ')</span>';
                            }
                            if ($tema === 'Atendimento') {
                                echo '<span class="text-primary font-numero" style="font-size: 0.9rem !Important;">(' . $atendimentos->count() . ')</span>';
                            }
                            ?>
                        </div>
                    </div>

                    <div id="collapse{{ $contTema }}" class="accordion-collapse collapse <?php $temaSelecionado === 'Atendimento' && $tema === 'Atendimento' ? print 'show' : ''; ?>"
                        aria-labelledby="heading{{ $contTema }}">

                        @php
                            /* Início da parte do Atendimento */
                        @endphp
                        @if ($tema === 'Observações')
                            <div class="row">
                                @include('parlamentar.observacoes.form-nova-observacao')
                                @include('parlamentar.observacoes.index')
                            </div>
                        @elseif ($tema === 'Atendimento')
                            <div class="row">

                                @php
                                    /* Início da parte para Incluir um novo Atendimento */
                                @endphp

                                @include('atendimento.form-novo-atendimento')

                                @php
                                    /* Fim da parte para Incluir um novo Atendimento */
                                @endphp

                                @include('atendimento.atendimentos')

                            </div>
                        @elseif ($tema === 'TSE')
                            @include('tse.index')
                        @elseif ($tema === 'Carteira de Investimento')
                            @include('tci.index')
                        @else
                            {!! $tema !!}
                        @endif
                        @php
                            /* Fim da parte do Atendimento */
                        @endphp

                    </div>

                </div>
                @php
                    /* Incremento do contador $contTema */
                    $contTema++;
                @endphp
            @endforeach.
            <script>
                function scrollToCard(cardElement, cardIndex) {
                    $('html, body').animate({
                        scrollTop: $(cardElement).offset().top - 112 // Adjust the value as needed
                    }, 'slow');
                }
            </script>
            @php
                /* Fim do loop dos temas */
            @endphp

        @endif
        @php
            /* Fim da parte dos dados do parlamentar */
        @endphp

    </div>
    <!-- Fim apresentação dos cards de entrada -->

    <!-- Início funções javascript -->
    <script>
        setTimeout(function() {
            $("#div1").fadeIn("slow");
        }, 700);

        setTimeout(function() {
            $("#informacao").fadeOut("slow");
        }, 300);
    </script>
    <!-- Fim funções javascript -->
@endsection
