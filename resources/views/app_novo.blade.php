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

    <div class="row row-cols-1 row-cols-md-3 justify-content-center g-4">

        @foreach ($cards as $key => $card)
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="..." class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                                additional content. This content is a little bit longer.</p>
                            <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

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
