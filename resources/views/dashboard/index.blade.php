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
                    <span id="breadcrumbs-current">Dashboard</span>
                </span>

            </div>
        </nav>
        <div class="cover-richtext-tile tile-content mb-0 p-0 pt-2">

            <hr class="mt-0 mb-0">

            <h1 class="tituloSemUpper mb-0 p-0 pt-1 pl-2">Dashboard</h1>

        </div>
    </div>
    <!-- Fim breadcrumbs -->

    <div class="container-fluid m-0 p-0">

        <style>
            .card {
                transition: box-shadow 0.3s ease;
            }

            .shadow-sm {
                box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
            }

            .shadow {
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
            }
        </style>

        <div class="pt-4 d-flex align-items-center justify-content-center d-none d-sm-block">

            <div class="row row-cols-1 row-cols-md-3 justify-content-center g-3">

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-3">

                    <div class="card shadow-none hover ml-3 mr-3 mb-2 pb-4 d-none d-sm-block"
                        style="min-height: 11rem !Important; height: 11rem !Important; max-height: 11rem !Important; font-size: 0.9rem !Important; border-color: #e1e1e1;">

                        <div class="row mt-0 pt-0 g-0">
                            <div class="col-4 m-0 p-0">
                                <a href="{{ route('planos.index') }}" class="text-dark stretched-link">
                                    <img src="{{ asset('img/planos.png') }}" class="img-fluid rounded-start" alt="..."
                                        style="min-width: 10rem !Important; width: 14rem !Important; min-height: 175px !Important; height: 175px !Important;">
                                </a>
                            </div>
                            <div class="col-7 mt-0 pt-0 pl-0">
                                <h4 class="card-title mt-0 mr-0 mb-1 pt-2 pl-2 pr-1" style="font-size: 1.1rem !Important;">

                                    <a href="{{ route('planos.index') }}" class="text-dark stretched-link">
                                        1. Planos
                                    </a>

                                </h4>

                                <hr class="mt-0 pt-0 pl-1">

                                <p class="card-text text-dark text-justify pt-0 pl-2"
                                    style="height: 8rem !Important; font-size: 0.9rem !Important;">
                                    Administrar e gerenciar os planos de assinatura.
                                </p>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-3">

                    <div class="card shadow-none hover ml-3 mr-3 mb-2 pb-4 d-none d-sm-block"
                        style="min-height: 11rem !Important; height: 11rem !Important; max-height: 11rem !Important; font-size: 0.9rem !Important; border-color: #e1e1e1;">

                        <div class="row mt-0 pt-0 g-0">
                            <div class="col-4 m-0 p-0">
                                <a href="{{ route('gabinetes.index') }}" class="text-dark stretched-link">
                                    <img src="{{ asset('img/gabinetes.png') }}" class="img-fluid rounded-start"
                                        alt="..."
                                        style="min-width: 10rem !Important; width: 14rem !Important; min-height: 175px !Important; height: 175px !Important;">
                                </a>
                            </div>
                            <div class="col-7 mt-0 pt-0 pl-0">
                                <h4 class="card-title mt-0 mr-0 mb-1 pt-2 pl-2 pr-1" style="font-size: 1.1rem !Important;">

                                    <a href="{{ route('gabinetes.index') }}" class="text-dark stretched-link">
                                        2. Gabinetes
                                    </a>

                                </h4>

                                <hr class="mt-0 pt-0 pl-1">

                                <p class="card-text text-dark text-justify pt-0 pl-2"
                                    style="height: 8rem !Important; font-size: 0.9rem !Important;">
                                    Administrar e gerenciar Gabinetes.
                                </p>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-3">

                    <div class="card shadow-none hover ml-3 mr-3 mb-2 pb-4 d-none d-sm-block"
                        style="min-height: 11rem !Important; height: 11rem !Important; max-height: 11rem !Important; font-size: 0.9rem !Important; border-color: #e1e1e1;">

                        <div class="row mt-0 pt-0 g-0">
                            <div class="col-4 m-0 p-0">
                                <a href="{{ route('contratos.index') }}" class="text-dark stretched-link">
                                    <img src="{{ asset('img/contratos.png') }}" class="img-fluid rounded-start"
                                        alt="..."
                                        style="min-width: 10rem !Important; width: 14rem !Important; min-height: 175px !Important; height: 175px !Important;">
                                </a>
                            </div>
                            <div class="col-7 mt-0 pt-0 pl-0">
                                <h4 class="card-title mt-0 mr-0 mb-1 pt-2 pl-2 pr-1" style="font-size: 1.1rem !Important;">

                                    <a href="{{ route('contratos.index') }}" class="text-dark stretched-link">
                                        3. Contratos
                                    </a>

                                </h4>

                                <hr class="mt-0 pt-0 pl-1">

                                <p class="card-text text-dark text-justify pt-0 pl-2"
                                    style="height: 8rem !Important; font-size: 0.9rem !Important;">
                                    Administrar e gerenciar os COntratos.
                                </p>

                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Início funções javascript -->
    <script>
        // Seleciona todos os elementos com a classe 'hover'
        var cards = document.querySelectorAll(".hover");

        // Itera sobre cada elemento selecionado
        cards.forEach(function(card) {
            // Adiciona um ouvinte de eventos para o evento 'mouseenter'
            card.addEventListener("mouseenter", function(event) {
                // Remove a classe 'shadow-sm' e adiciona a classe 'shadow'
                card.classList.remove("shadow-none");
                card.classList.add("shadow");
            }, false);

            // Adiciona um ouvinte de eventos para o evento 'mouseleave'
            card.addEventListener("mouseleave", function(event) {
                // Remove a classe 'shadow' e adiciona a classe 'shadow-sm'
                card.classList.remove("shadow");
                card.classList.add("shadow-none");
            }, false);
        });
    </script>
    <!-- Fim funções javascript -->
@endsection
