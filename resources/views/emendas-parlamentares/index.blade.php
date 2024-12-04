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
                    <a href="{!! url('parlamentar') !!}">
                        <span id="breadcrumbs-current">Emendas Parlamentares</span>
                    </a>
                </span>

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

            </div>

        </div>

    </div>

    <!-- Início apresentação dos cards de entrada -->
    <div class="row" id="informacao">
        <div class="col-xs-12 col-sm-12 col-md-12"
            style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;">
            <i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando...
        </div>
    </div>

    <div class="row" id="div1" style="display: none;">



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
