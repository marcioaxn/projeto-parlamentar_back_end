@extends('layouts.app')

@section('content')
    @php
        isset($temaSelecionado) && !is_null($temaSelecionado) && $temaSelecionado != ''
            ? ($temaSelecionado = $temaSelecionado)
            : ($temaSelecionado = null);

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
                        <span id="breadcrumbs-current">Parlamentares</span>
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

        {{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3 d-print-none">

            {!! Form::open(['method' => 'post', 'url' => route('parlamentar')]) !!}
            <div class="card border border-light" style="">
                <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseFiltro"
                    aria-expanded="true" aria-controls="collapseFiltro"
                    style="cursor: pointer; padding: 0.3rem !Important; font-size: 0.8rem !Important; padding-left: 8px !Important; background-color: #f1f2f4 !Important; color: #000000 !Important;">
                    <i class="fas fa-filter text-info"></i> <span style="color: #0A58CA!Important;"> Filtrar por Parlamentar
                </div>
                <div class="card-body bg-white collapse <?php $cod_parlamentar ? print '' : print 'show'; ?>" id="collapseFiltro">

                    <div class="row" style="">

                        <div class="col-12 col-sm-12 col-md-6 mb-3 pt-3">
                            <label for="cod_parlamentar">Deputados Federais e Senadores</label>
                            {!! Form::select('cod_parlamentar', $getParlamentares, $cod_parlamentar, [
                                'class' => 'form-control',
                                'placeholder' => 'Pesquisar',
                                'id' => 'cod_parlamentar',
                                'onchange' => 'window.location.href = "' . url('parlamentar') . '/" + this.value;',
                                'style' => 'width: 99% !Important; ',
                            ]) !!}

                            <div id="" class="form-text pt-1 pl-3 textoPequeno text-secondary"><strong>
                                    <span class="font-numero">{{ formatarNumeroInteiro(count($getParlamentares)) }}</span>
                                    parlamentares</strong>
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

                        <div class="col-12 col-sm-12 col-md-6 pt-3"">
                            <label for="cod_parlamentar_estadual">Deputados Estaduais e Distritais</label>
                            {!! Form::select('cod_parlamentar_estadual', $getParlamentaresEstaduais, $cod_parlamentar_estadual, [
                                'class' => 'form-control',
                                'placeholder' => 'Pesquisar',
                                'id' => 'cod_parlamentar_estadual',
                                'onchange' => 'window.location.href = "' . url('parlamentar') . '/" + this.value;',
                                'style' => 'width: 99% !Important; ',
                            ]) !!}

                            <div id="" class="form-text pt-1 pl-3 textoPequeno text-secondary"><strong>
                                    <span
                                        class="font-numero">{{ formatarNumeroInteiro(count($getParlamentaresEstaduais)) }}</span>
                                    parlamentares estaduais e distritais</strong>
                            </div>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#cod_parlamentar_estadual').select2();
                                    $(document).on("select2:open", () => {
                                        document.querySelector('.select2-container--open .select2-search__field').focus();
                                    });
                                });
                            </script>

                        </div>

                    </div>
                </div>
            </div>

            {!! Form::close() !!}

        </div> --}}

        @php
            /* Início da parte dos dados do parlamentar */
        @endphp
        @if (isset($cod_parlamentar) && !is_null($cod_parlamentar) && $cod_parlamentar != '')
            @if (!$parlamentarEstadualSelecionado)
                @include('parlamentar.deputados-federais-e-senadores')
            @else
                @include('parlamentar.deputados-estaduais-e-distritais')
            @endif


            @php
                /* Início do loop dos temas */
                $contTema = 1;
            @endphp
            @if (Session::get('permissao') === '0100000' || Session::get('permissao') === '0001000')
                @php
                    foreach (array_keys($temas, 'Observações', true) as $key) {
                        unset($temas[$key]);
                    }
                    foreach (array_keys($temas, 'Atendimento', true) as $key) {
                        unset($temas[$key]);
                    }
                    foreach (array_keys($temas, 'TSE', true) as $key) {
                        unset($temas[$key]);
                    }
                @endphp
            @endif

            @if ($parlamentarEstadualSelecionado)
                @php
                    foreach (array_keys($temas, 'Carteira de Investimento', true) as $key) {
                        unset($temas[$key]);
                    }
                @endphp
            @endif

            @foreach ($temas as $tema)
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-0" id="accordionTemas">

                    <div class="card sticky-top mt-2">
                        <div class="card-body cardTemas shadow-sm" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $contTema }}" aria-expanded="true"
                            aria-controls="collapse{{ $contTema }}" onclick="scrollToCard(this, {{ $contTema }});"
                            id="divTema{!! $tema !!}">
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
