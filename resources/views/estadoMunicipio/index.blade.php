@extends('layouts.app')

@section('content')
    @php

        isset($selecaoTemaAnterior) && !is_null($selecaoTemaAnterior) && $selecaoTemaAnterior != '' ? ($selecaoTemaAnterior = $selecaoTemaAnterior) : ($selecaoTemaAnterior = null);

        if ($getMunicipio) {
            $blnMunicipio = true;
            $nomMunicipioSemFormatacao = ' - <span class="text-bold">' . $getMunicipio->nom_municipio_sem_formatacao . '</span>';
        } else {
            $blnMunicipio = false;
            $nomMunicipioSemFormatacao = null;
        }

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

                @if (isset($sgl_uf) && !is_null($sgl_uf) && $sgl_uf != '' && is_null($cod_nom_municipio))
                    <span dir="ltr" id="breadcrumbs-2">
                        <a href="{!! route('uf-municipio') !!}">
                            <span id="breadcrumbs-current">Estado/Município</span>
                        </a>
                    </span>

                    <span class="pl-1 pr-1">></span>

                    <span dir="ltr" id="breadcrumbs-2">
                        <span id="breadcrumbs-current">{{ $sgl_uf }}</span>
                    </span>
                @elseif (isset($sgl_uf) &&
                        !is_null($sgl_uf) &&
                        $sgl_uf != '' &&
                        isset($cod_nom_municipio) &&
                        !is_null($cod_nom_municipio) &&
                        $cod_nom_municipio != '')
                    <span dir="ltr" id="breadcrumbs-2">
                        <a href="{!! route('uf-municipio') !!}">
                            <span id="breadcrumbs-current">Estado/Município</span>
                        </a>
                    </span>

                    <span class="pl-1 pr-1">></span>

                    <span dir="ltr" id="breadcrumbs-2">
                        <a href="{!! route('uf-municipio', [$sgl_uf]) !!}">
                            <span id="breadcrumbs-current">{!! $sgl_uf !!}</span>
                        </a>
                    </span>

                    <span class="pl-1 pr-1">></span>

                    <span dir="ltr" id="breadcrumbs-2">
                        <span id="breadcrumbs-current">{!! $getMunicipio->nom_municipio_sem_formatacao !!}</span>
                    </span>
                @else
                    <span dir="ltr" id="breadcrumbs-2">
                        <span id="breadcrumbs-current">Estado/Município</span>
                    </span>
                @endif

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->

    <div class="row" id="" style="display: block;">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 d-print-none">

            <div class="card border border-light" style="">
                <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseFiltro"
                    aria-expanded="true" aria-controls="collapseFiltro"
                    style="cursor: pointer; padding: 0.3rem !Important; font-size: 0.8rem !Important; padding-left: 8px !Important; background-color: #f1f2f4 !Important; color: #000000 !Important;">
                    <i class="fas fa-filter text-info"></i> <span style="color: #0A58CA!Important;">Filtrar por UF ou
                        Município</span>
                </div>
                <div class="card-body border border-light rounded bg-white collapse <?php isset($cod_nom_municipio) && !is_null($cod_nom_municipio) && $cod_nom_municipio != '' ? '' : print 'show'; ?> mt-0 mb-0 pt-0 pb-2"
                    id="collapseFiltro">

                    <div class="row" style="">
                        @php
                            // Início da parte da pesquisa por UF
                        @endphp
                        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 mt-2 pt-1">
                            @if (isset($sgl_uf) && !is_null($sgl_uf) && $sgl_uf != '' ? $sgl_uf : null)
                                {!! Form::select(
                                    'sgl_uf',
                                    $sgl_uf_select,
                                    isset($sgl_uf) && !is_null($sgl_uf) && $sgl_uf != '' ? $sgl_uf : null,
                                    [
                                        'class' => 'form-control',
                                        'id' => 'sgl_uf',
                                        'onchange' => "javascript: alterarUrl(this.value,'');",
                                        'required' => 'required',
                                        'style' => 'width: 99% !Important; ',
                                    ],
                                ) !!}
                            @else
                                {!! Form::select(
                                    'sgl_uf',
                                    $sgl_uf_select,
                                    isset($sgl_uf) && !is_null($sgl_uf) && $sgl_uf != '' ? $sgl_uf : null,
                                    [
                                        'class' => 'form-control',
                                        'id' => 'sgl_uf',
                                        'onchange' => "javascript: alterarUrl(this.value,'');",
                                        'required' => 'required',
                                        'placeholder' => 'Selecione',
                                        'style' => 'width: 99% !Important; ',
                                    ],
                                ) !!}
                            @endif

                            <div id="" class="form-text pt-1 pl-3 textoPequeno text-secondary"><strong>Pesquisar
                                    por:</strong> UF - Unidade da Federação</div>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#sgl_uf').select2();
                                    $(document).on("select2:open", () => {
                                        document.querySelector(".select2-container--open .select2-search__field").focus()
                                    });
                                });
                            </script>

                        </div>
                        @php
                            // Fim da parte da pesquisa por UF
                        @endphp

                        @php
                            // Início da parte da pesquisa por Município
                        @endphp
                        @php
                            if (is_numeric($cod_nom_municipio)) {
                            }
                        @endphp
                        <div class="col-xs-12 col-sm-7 col-md-8 col-lg-8 mt-2 pt-1">
                            {!! Form::select(
                                'cod_nom_municipio',
                                $nom_municipio_select,
                                isset($cod_nom_municipio) && !is_null($cod_nom_municipio) && $cod_nom_municipio != '' ? $cod_nom_municipio : null,
                                [
                                    'class' => 'form-control',
                                    'id' => 'cod_nom_municipio',
                                    'onchange' => "javascript: alterarUrl('',this.value);",
                                    'placeholder' => 'Selecione',
                                    'required' => 'required',
                                    'style' => 'width: 99% !Important; ',
                                ],
                            ) !!}

                            <div id="" class="form-text pt-1 pl-3 textoPequeno text-secondary"><strong>Pesquisar
                                    por:</strong> <span
                                    class="font-numero text-bold">{{ count($nom_municipio_select) }}</span> Município(s)
                            </div>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#cod_nom_municipio').select2();
                                    $(document).on("select2:open", () => {
                                        document.querySelector(".select2-container--open .select2-search__field").focus()
                                    });
                                });
                            </script>

                        </div>
                        @php
                            // Fim da parte da pesquisa por Município
                        @endphp
                    </div>
                </div>
            </div>

        </div>

        <script>
            function alterarUrl(uf, municipio) {

                // Obtém a parte inicial da URL atual (antes de "consulta-uf-municipio")
                var baseUrl = window.location.href.split('/uf-municipio')[0];

                if (municipio == '') {
                    if (uf != '') {

                        var newUrl = baseUrl + '/uf-municipio/' + uf;

                        // Atualiza a URL da página
                        window.location.href = newUrl;

                    }
                } else {
                    var uf = $('#sgl_uf').val();
                    var newUrl = baseUrl + '/uf-municipio/' + uf + '/' + municipio;

                    // Atualiza a URL da página
                    window.location.href = newUrl;
                }

            }
        </script>

    </div>

    <div class="row" id="informacao">
        <div class="col-xs-12 col-sm-12 col-md-12"
            style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;">
            <i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando...
        </div>
    </div>

    <div class="row" id="div1" style="display: none;">

        @if (isset($sgl_uf) && !is_null($sgl_uf) && $sgl_uf != '' && is_null($cod_nom_municipio))
            @include('estadoMunicipio.estado')
        @elseif (isset($sgl_uf) &&
                !is_null($sgl_uf) &&
                $sgl_uf != '' &&
                isset($cod_nom_municipio) &&
                !is_null($cod_nom_municipio) &&
                $cod_nom_municipio != '')
            @include('estadoMunicipio.municipio')
        @else
            {{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3 d-print-none">

                <div class="alert alert-primary" role="alert">
                    É necessário que uma UF ou um município estejam selecionados para que as informações sejam visualizadas.
                </div>

            </div> --}}
            @include('estadoMunicipio.brasil')
        @endif

    </div>
    <!-- Fim apresentação dos cards de entrada -->

    <!-- Início funções javascript -->
    <script>
        setTimeout(function() {
            $("#div1").fadeIn("slow");
        }, 400);

        setTimeout(function() {
            $("#informacao").fadeOut("slow");
        }, 100);
    </script>
    <!-- Fim funções javascript -->
@endsection
