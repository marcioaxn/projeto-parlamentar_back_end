<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mt-1 mb-3 text-left">

    <div class="row mt-1 pl-1">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
            <div class="bg-light shadow-sm rounded pt-2 pb-1 pb-1 pl-1" style="font-weight: 600 !Important;">
                <i class="fas fa-map-marker-alt text-warning" style="color: #FF8C00!Important;"></i>
                {!! $sgl_uf !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            @if ($indicadoresEstado)
                @php
                    // Início da parte dos indicadores e do mapa

                    $fazParteDaAreaDeAtuacaoVinculadas = null;

                    if ($indicadoresEstado->bln_sudam) {
                        $fazParteDaAreaDeAtuacaoVinculadas .= 'SUDAM e ';
                    }

                    if ($indicadoresEstado->bln_sudene) {
                        $fazParteDaAreaDeAtuacaoVinculadas .= 'SUDENE e ';
                    }

                    if ($indicadoresEstado->bln_sudeco) {
                        $fazParteDaAreaDeAtuacaoVinculadas .= 'SUDECO e ';
                    }

                    $fazParteDaAreaDeAtuacaoVinculadas = trim($fazParteDaAreaDeAtuacaoVinculadas, ' e ');
                @endphp

                @include('estadoMunicipio.indicadoresEstado')

                @php
                    // Fim da partde dos indicadores e do mapa
                @endphp
            @endif
        </div>

    </div>

</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 h-100 mb-3 pt-2 d-print-none">
    <div id="map" class="rounded" style="width: 99.6% !Important; height: 455px !Important;">
    </div>

    <script>
        function initMap() {
            const myLatLng = {
                lat: {{ $indicadoresEstado->latitude }},
                lng: {{ $indicadoresEstado->longitude }}
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 6,
                center: myLatLng,
                mapTypeId: google.maps.MapTypeId.TERRAIN,
            });

            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "MIDR - Visão 360",
            });
        }

        window.initMap = initMap;
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0WnHexT_I1s-cI_YOTp-BOWFFQInRrz4&callback=initMap" async
        defer></script>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 h-100 mb-3 pt-2">

    @include('tci.estado.resumo')

</div>

@if (is_null($fundoResumo))
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 h-100 mb-3 pt-2">

        @include('rotasDeIntegracao.estado.rota')

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 h-100 mb-3 pt-2">

        @include('sedec.estado.index')

    </div>
@else
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 h-100 mb-3 pt-2">

        @include('fundos.card.estado.resumo')

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 h-100 mb-3 pt-2">

        @include('sedec.estado.index')

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 h-100 mb-3 pt-2">

        @include('rotasDeIntegracao.estado.rota')

    </div>
@endif

@if (Session::get('permissao') === '0100000' || Session::get('permissao') === '0001000')
    @php
        foreach (array_keys($temas, 'TSE', true) as $key) {
            unset($temas[$key]);
        }
    @endphp
@endif

@php
    /* Início do loop dos temas */
    $contTema = 1;
@endphp

@foreach ($temas as $tema)
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2 mb-1" id="accordionTemas">

        <div class="card sticky-top border border-0 mt-0 mb-0 pt-0 pb-0">
            <div class="card-body cardTemas shadow-sm" data-bs-toggle="collapse"
                data-bs-target="#collapse{{ $contTema }}" aria-expanded="true"
                aria-controls="collapse{{ $contTema }}"
                onclick="scrollToCard(this, {{ $contTema }}); <?php $tema === 'TSE' ? print '$(\'#thCountDF\').click(); $(\'#thCountDF\').click();' : ''; ?>"
                id="divTema{!! $tema !!}">
                {!! $tema !!}
                @if ($tema === 'Empreendimentos do novo PAC')
                    <a class="d-print-none"
                        href="https://www.in.gov.br/en/web/dou/-/resolucao-cgpac-n-1-de-19-de-dezembro-de-2023-532316160"
                        target="_blank"><i class="fas fa-link"></i><span
                            style="font-size: 0.6rem!Important;">fonte</span></a>
                @endif
            </div>
        </div>

        <div id="collapse{{ $contTema }}" class="accordion-collapse collapse "
            aria-labelledby="heading{{ $contTema }}">

            @if ($tema === 'TSE')
                @include('tse.estado.index')
            @elseif ($tema === 'Carteira de Investimento')
                @include('tci.estado.index')
            @elseif ($tema === 'Empreendimentos do novo PAC')
                @include('pac.estado.index')
            @elseif ($tema === 'Fundos de Desenvolvimento Regional')
                @include('fundos.estado.index')
            @else
                {!! $tema !!}
            @endif

        </div>

    </div>
    @php
        /* Incremento do contador $contTema */
        $contTema++;
    @endphp
@endforeach
<script>
    function scrollToCard(cardElement, cardIndex) {
        $('html, body').animate({
            scrollTop: $(cardElement).offset().top - 91 // Adjust the value as needed
        }, 'slow');
    }
</script>
@php
    /* Fim do loop dos temas */
@endphp

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-0 pt-2 mb-3 pb-3">
    <p class="p-legenda m-0 p-0 mt-1 mb-1">
        Legenda:
    </p>
    <ul style="list-style-type: circle!Important;">
        <li class="p-legenda m-0 p-0 mt-1 mb-1">
            <span class="text-bold">IDHM</span>: <span class="text-muted">O Índice de Desenvolvimento Humano
                Municipal (IDHM) é um número que varia entre 0,000 e 1,000. Quanto mais próximo de 1,000, maior
                o desenvolvimento humano de uma localidade. Elaboração: PNUD, Ipea e FJP.</span>
        </li>
        <li class="p-legenda m-0 p-0 mt-1 mb-1">
            <span class="text-bold">PNDR</span>: <span class="text-muted">Política Nacional de Desenvolvimento
                Regional, clique <a
                    href="https://www.gov.br/mdr/pt-br/assuntos/desenvolvimento-regional/copy_of_NotaTcnica522017PropostadeatualizaodaTipologiaSubregional.pdf"
                    target="_blank">aqui</a> para acessar mais detalhes.</span>
        </li>
        <li class="p-legenda m-0 p-0 mt-1 mb-1">
            <span class="text-bold">Área prioritária do PNDR</span>: <span class="text-muted">São considerados
                prioritários os municípios que compõem o semiárido ou são fronteiriços ou compõem a RIDE.</span>
        </li>
        <li class="p-legenda m-0 p-0 mt-1 mb-1">
            <span class="text-bold">Gini</span>: <span class="text-muted">Índice de Gini da renda domiciliar
                per
                capita segundo Município. Quando o índice tem valor igual a um (1), existe perfeita
                desigualdade. Quando ele tem valor igual a zero (0), tem-se perfeita igualdade. Clique <a
                    href="http://tabnet.datasus.gov.br/tabdata/livroidb/idb2010/b09.pdf" target="_blank">aqui</a>
                para acessar mais detalhes.</span>
        </li>
        <li class="p-legenda m-0 p-0 mt-1 mb-1">
            <span class="text-bold">ISH</span>: <span class="text-muted">Índice de Segurança Hídrica, na
                dimensão humana (Garantia de água para abastecimento e Cobertura da rede de abastecimento),
                clique <a href="https://pnsh.ana.gov.br/seguranca" target="_blank">aqui</a> para acessar mais
                detalhes.</span>
        </li>
        <li class="p-legenda m-0 p-0 mt-1 mb-1">
            <span class="text-bold">Rotas de Integração Nacional</span><span class="text-muted">, clique <a
                    href="https://www.gov.br/mdr/pt-br/assuntos/desenvolvimento-regional/rotas-de-integracao-nacional">aqui</a>
                para acessar mais detalhes.</span>
        </li>
        <li class="p-legenda m-0 p-0 mt-1 mb-1">
            <span class="text-bold">Carteira ativa</span>: <span class="text-muted">São os empreendimentos que
                não foram cancelados e nem concluídos.</span>
        </li>
        <li class="p-legenda m-0 p-0 mt-1 mb-1">
            <span class="text-bold">PISF</span>: <span class="text-muted">Projeto de Integração do Rio São
                Francisco com as Bacias do Nordeste Setentrional.</span>
        </li>
    </ul>
</div>
