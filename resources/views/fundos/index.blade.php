@extends('layouts.app')

@section('content')
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
                    <span id="breadcrumbs-current">Fundos Regionais</span>
                </span>

            </div>
        </nav>
        <div class="cover-richtext-tile tile-content mb-0 p-0 pt-2">

            <hr class="mt-0 mb-0">

            <h1 class="tituloSemUpper mb-0 p-0 pt-1 pl-2">Fundos Constitucionais de Financiamento (FNO, FNE e FCO)</h1>

        </div>
    </div>
    <!-- Fim breadcrumbs -->

    <div class="row">

        {!! Form::open([
            'class' => 'p-0',
            'method' => 'post',
            'url' => route('fundos'),
        ]) !!}

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-4">

            <div class="card shadow-sm">
                <div class="card-header cardTemas p-1 pt-2 pl-3 pb-2">
                    Filtros
                </div>
                <div class="card-body bg-white pt-2 pb-2">

                    <div class="row m-0 p-0">



                        @foreach ($variaveisConsulta as $variavelConsulta)
                            @if ($variavelConsulta === 'dsc_linha_financiamento' || $variavelConsulta === 'dsc_finalidade_operacao')
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mt-1 mb-1">
                                @else
                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 mt-1 mb-1">
                            @endif

                            <div class="row">

                                <div class="col-12 mb-2">

                                    <label for="sgl_uf" class="form-label">{!! nomeCampoTabVisMdrNormalizado($variavelConsulta) !!}</label>
                                    {!! Form::select($variavelConsulta . '[]', ${$variavelConsulta . 's'}, ${$variavelConsulta}, [
                                        'class' => 'form-control text-dark multiple_select2',
                                        'multiple' => true,
                                        'style' => 'cursor: pointer; width: 100% !important;',
                                        'id' => $variavelConsulta,
                                        'onchange' => 'javascript: this.form.submit();',
                                    ]) !!}

                                </div>

                            </div>

                    </div>
                    @endforeach

                    <script type="text/javascript">
                        $('.multiple_select2').select2();
                    </script>



                </div>

            </div>

            <div class="card-footer bg-white text-right">

                <a href="javascript:void(0);" class="mt-1 mb-1"
                    onclick="document.getElementById('gerar_relatorio_pdf').value = 'sim'; this.closest('form').submit(); document.getElementById('gerar_relatorio_pdf').value = '';"
                    style="font-size: 0.8rem!important;">
                    <i class="fas fa-file-pdf" style="font-size: 1rem!important;"></i>Gerar <strong>PDF</strong></span>
                </a>


            </div>

        </div>

    </div>

    {!! Form::hidden('gerar_relatorio_pdf', null, ['id' => 'gerar_relatorio_pdf']) !!}

    {!! Form::close() !!}

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-4">

        <div class="card shadow-sm">
            <div class="card-header cardTemas p-1 pt-2 pl-3 pb-2">
                Relatório
            </div>
            <div class="card-body bg-white pt-2 pb-2">

                <style>
                    #nomeRelatorio {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                        font-size: 13px;
                        padding-top: 4px;
                        padding-bottom: 4px;
                        font-weight: bold;
                    }

                    #filtros {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                        font-size: 12px;
                        padding-top: 4px;
                        padding-bottom: 4px;
                    }

                    th {
                        border-top: 1px solid rgb(165, 164, 164);
                        border-bottom: 1px solid rgb(165, 164, 164);
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                        font-size: 11px;
                    }

                    td {
                        border-bottom: 1px solid rgb(165, 164, 164);
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                        font-size: 11px;
                    }

                    p {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                        font-size: 12px;
                    }
                </style>

                @include('fundos.pdf.relatorios.tabela')

                <script type="text/javascript" charset="utf-8">
                    $(document).ready(function() {
                        var table = $('#tableRelatorio').DataTable({
                            "language": {
                                "url": "{{ asset('Portuguese-Brasil.json') }}",
                                "decimal": ",",
                                "thousands": "."
                            },
                            "paging": true,
                            "lengthMenu": [
                                [5, 10, 25, 50, 100, -1],
                                ["5 ", "10 ", "25 ", "50 ", "100 ", "Todos "]
                            ],
                            responsive: true,
                            "autoWidth": true,
                            fixedHeader: {
                                header: true,
                                headerOffset: $('#header').outerHeight() - 12
                            },
                            "order": [
                                [0, "asc"]
                            ],
                            scrollx: true,
                            scrollCollapse: false,
                        });
                    });
                </script>

            </div>

        </div>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-4">

        <div class="card shadow-sm">
            <div class="card-header cardTemas p-1 pt-2 pl-3 pb-2">
                Painel <a class="d-print-none"
                    href="https://app.powerbi.com/view?r=eyJrIjoiYzQ2MzcxODktZWQ0Mi00MjVlLTkyNjMtZmVhMzNlODY4NmU0IiwidCI6Ijk2MTFlY2UxLTM0MTQtNGMzNS1hM2YwLTdkMTAwNDI5MGNkNiJ9"
                    target="_blank"><i class="fas fa-link"></i><span style="font-size: 0.6rem!Important;">fonte</span></a>
            </div>
            <div class="card-body bg-white pt-2 pb-2">
                <iframe
                    src="https://app.powerbi.com/view?r=eyJrIjoiYzQ2MzcxODktZWQ0Mi00MjVlLTkyNjMtZmVhMzNlODY4NmU0IiwidCI6Ijk2MTFlY2UxLTM0MTQtNGMzNS1hM2YwLTdkMTAwNDI5MGNkNiJ9"
                    frameborder="0" allowFullScreen="true" style="width: 100%!Important; height: 79vh!Important;"></iframe>
            </div>

        </div>

    </div>

    </div>
@endsection
