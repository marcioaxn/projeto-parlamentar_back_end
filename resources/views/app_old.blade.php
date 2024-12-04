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
                    <span id="breadcrumbs-current">Principal</span>
                </span>

            </div>
        </nav>
        <div class="cover-richtext-tile tile-content pt-2">

            <hr class="mt-0 mb-0">

            <h1 class="tituloSemUpper pt-1 pl-2">Projeto Visão 360°</h1>

        </div>
    </div>
    <!-- Fim breadcrumbs -->

    @php
        $cards = [
            'Parlamentar' => [
                'img' => 'img/congresso.png',
                'content' =>
                    'Consultar informações parlamentares por meio dos dados extraídos diariamente da Câmara dos Deputados e do Senado Federal',
                'link' => 'parlamentar',
            ],
            'Estados e Municípios' => [
                'img' => 'img/bi.png',
                'content' =>
                    'Consultar os dados da carteira de investimentos do MIDR agregado aos principais indicadores sociais e econômicos de cada estado/ município',
                'link' => 'uf-municipio',
            ],
            'Fundos Constitucionais de Financiamento' => [
                'img' => 'img/fundos_01.png',
                'content' => 'Consultar os dados relativos aos Fundos Constitucionais de Financiamento',
                'link' => 'fundos',
            ],
            'Novo PAC' => [
                'img' => 'img/novo_pac_05.png',
                'content' => 'Gerir e consultar os dados relativos ao Novo PAC',
                'link' => 'novo-pac',
            ],
            'Relatórios' => [
                'img' => 'img/relatorios_02.png',
                'content' => 'Acessar relatórios com foco no parlamentar',
                'link' => 'relatorios',
            ],
            'Atendimentos' => [
                'img' => 'img/atendimento_01.png',
                'content' => 'Consultar os atendimentos realizados pelas autoridades no MIDR aos entes políticos',
                'link' => 'atendimentos',
            ],
        ];
    @endphp
    <!-- Início apresentação dos cards de entrada -->
    <div class="row pt-2" id="div1" style="font-size: 12px !Important;">

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

        <div
            class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-4 d-flex align-items-center justify-content-center d-none d-sm-block">

            <div class="row row-cols-1 justify-content-center g-4">

                @foreach ($cards as $key => $card)
                    <div class="card shadow-none hover ml-3 mr-3 mb-2 pb-4 d-none d-sm-block"
                        style="width: 30rem !Important; min-height: 11rem !Important; height: 11rem !Important; max-height: 11rem !Important; font-size: 0.9rem !Important; border-color: #e1e1e1;">
                        <div class="row mt-0 pt-0 g-0">
                            <div class="col-5 m-0 p-0">
                                <a href="{!! route($card['link']) !!}" class="text-dark stretched-link">
                                    <img src="{{ asset($card['img']) }}" class="img-fluid rounded-start" alt="..."
                                        style="width: 14rem !Important; min-height: 175px !Important; height: 175px !Important;">
                                </a>
                            </div>
                            <div class="col-7 mt-0 pt-0 pl-0">
                                <h4 class="card-title mt-0 mr-0 mb-1 pt-2 pl-2 pr-1" style="font-size: 1.1rem !Important;">

                                    <a href="{!! route($card['link']) !!}" class="text-dark stretched-link">
                                        {!! $key !!}
                                    </a>

                                </h4>

                                <hr class="mt-0 pt-0 pl-1">

                                <p class="card-text text-dark text-justify pt-0 pl-2"
                                    style="height: 8rem !Important; font-size: 0.9rem !Important;">{!! $card['content'] !!}
                                </p>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-4 d-flex align-items-center justify-content-center">

            <ol class="list-group list-group-numbered d-block d-sm-none" style="width: 90%!Important;">
                @foreach ($cards as $key => $card)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <a href="{!! route($card['link']) !!}" class="text-dark stretched-link">
                            <div class="ms-2 me-auto" style="font-size: 1rem !Important;">
                                <div class="fw-bold">{!! $key !!}</div>
                                {!! $card['content'] !!}
                                <br />
                                <span class="text-primary text-bold">Acessar</span>
                                <br />
                                <br />
                            </div>
                        </a>
                    </li>
                @endforeach
            </ol>

        </div>

    </div>
    <!-- Fim apresentação dos cards de entrada -->

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
