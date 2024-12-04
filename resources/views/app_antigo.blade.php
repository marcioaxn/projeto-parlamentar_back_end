@extends('layouts.app')

@section('content')
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
            'Relatórios' => [
                'img' => 'img/relatorios_02.png',
                'content' => 'Acesse relatórios com foco no parlamentar',
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
    <div class="row" id="informacao">
        <div class="col-xs-12 col-sm-12 col-md-12"
            style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-size: 14px;">
            <i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando...
        </div>
    </div>

    <div class="row pt-2" id="div1" style="font-size: 12px !Important; display: none;">

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

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-4">

            <div class="row row-cols-1 row-cols-md-3 justify-content-center g-3">

                @foreach ($cards as $key => $card)
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 mt-3 mb-3 d-flex justify-content-center">
                        <div class="card shadow-none h-100 hover" style="width: 17rem !Important;">
                            <img src="{{ asset($card['img']) }}" class="card-img-top" alt="Congresso Nacional"
                                style="min-height: 190px !Important; height: 190px !Important;">

                            <div class="card-body">

                                <h5 class="card-title text-dark m-0 p-0" style="">{!! $key !!}</h5>

                                <p class="card-text text-dark text-justify pt-3"
                                    style="height: 8rem !Important; font-size: 0.9rem !Important;">{!! $card['content'] !!}
                                </p>

                            </div>

                            <div class="card-footer bg-light text-right">
                                <a href="{!! route($card['link']) !!}"
                                    class="btn btn-outline-secondary btn-sm stretched-link">Consultar</a>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

        </div>

    </div>
    <!-- Fim apresentação dos cards de entrada -->

    <!-- Início funções javascript -->
    <script>
        setTimeout(function() {
            $("#div1").fadeIn("slow");
        }, 721);

        setTimeout(function() {
            $("#informacao").fadeOut("slow");
        }, 267);

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
