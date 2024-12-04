@extends('layouts.app')

@section('content')
    @php
        isset($selecaoTemaAnterior) && !is_null($selecaoTemaAnterior) && $selecaoTemaAnterior != '' ? ($selecaoTemaAnterior = $selecaoTemaAnterior) : ($selecaoTemaAnterior = null);

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

                @if ($cod_parlamentar)
                    <span dir="ltr" id="breadcrumbs-2">
                        <a href="{!! route('parlamentar', [$cod_parlamentar, 'Atendimento']) !!}">
                            <span id="breadcrumbs-current">{{ $nomParlamentar }}</span>
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
    @include('processando')
    <!-- Fim da apresentação da div processando -->

    <!-- Início da apresentação do conteúdo da página -->

    <div class="row" id="divContent" style="display: none;">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3 d-print-none">

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
@endsection
