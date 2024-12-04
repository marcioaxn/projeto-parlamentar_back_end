@if (isset($sgl_uf) &&
        !is_null($sgl_uf) &&
        $sgl_uf != '' &&
        isset($cod_nom_municipio) &&
        !is_null($cod_nom_municipio) &&
        $cod_nom_municipio != '' &&
        !is_null($getIndicadoresMunicipio))
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9 col-xl-8 mt-1 mb-3 text-left">
        <div class="row mt-1 pl-1">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                <div class="bg-light shadow-sm rounded pt-2 pb-1 pb-1 pl-1" style="font-weight: 600 !Important;">
                    <i class="fas fa-map-marker-alt text-warning" style="color: #FF8C00!Important;"></i>
                    {!! $sgl_uf . $nomMunicipioSemFormatacao !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                @if ($getIndicadoresMunicipio)
                    @php
                        // Início da parte dos indicadores e do mapa

                        $fazParteDaAreaDeAtuacaoVinculadas = null;

                        if ($getIndicadoresMunicipio->bln_sudam) {
                            $fazParteDaAreaDeAtuacaoVinculadas .= 'SUDAM e ';
                        }

                        if ($getIndicadoresMunicipio->bln_sudene) {
                            $fazParteDaAreaDeAtuacaoVinculadas .= 'SUDENE e ';
                        }

                        if ($getIndicadoresMunicipio->bln_sudeco) {
                            $fazParteDaAreaDeAtuacaoVinculadas .= 'SUDECO e ';
                        }

                        $fazParteDaAreaDeAtuacaoVinculadas = trim($fazParteDaAreaDeAtuacaoVinculadas, ' e ');
                    @endphp

                    @include('estadoMunicipio.indicadoresMunicipio')

                    @php
                        // Fim da partde dos indicadores e do mapa
                    @endphp
                @endif
            </div>

        </div>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 col-xl-4 h-100 mb-3 pt-2 d-print-none">
        <div id="map" class="rounded" style="width: 99.6% !Important; height: 265px !Important;">
        </div>

        <script>
            function initMap() {
                const myLatLng = {
                    lat: {{ $getTabCities->latitude }},
                    lng: {{ $getTabCities->longitude }}
                };
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 13,
                    center: myLatLng,
                    mapTypeId: google.maps.MapTypeId.HYBRID,
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

    @if ($novosPac)
        @php
            /* Início do resumo da carteira de investimento */
        @endphp

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-2 mb-1">
            @include('pac.municipio.index')
        </div>

        @php
            /* Fim do resumo da carteira de investimento */
        @endphp
    @endif

    @if (is_null($fundoResumoMunicipio))
        @php
            /* Início do resumo da carteira de investimento */
        @endphp

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 mt-2 mb-1">
            @include('tci.resumo')
        </div>

        @php
            /* Fim do resumo da carteira de investimento */
        @endphp

        @php
            /* Início do resumo da SEDEC */
        @endphp

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 mt-2 mb-1">
            @include('sedec.resumo')
        </div>

        @php
            /* Fim do resumo da SEDEC */
        @endphp

        @php
            /* Início das rotas de integração nacional */
        @endphp

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mt-2 mb-1 <?php !is_array($rotas) && $rotas->count() > 0 ? '' : print 'd-print-none'; ?>">
            @include('rotasDeIntegracao.municipio.rota')
        </div>

        @php
            /* Fim das rotas de integração nacional */
        @endphp
    @else
        @php
            /* Início do resumo da carteira de investimento */
        @endphp

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-2 mb-1">
            @include('tci.resumo')
        </div>

        @php
            /* Fim do resumo da carteira de investimento */
        @endphp

        @php
            /* Início do resumo dos Fundos de Desenvolvimento Regional */
        @endphp

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-2 mb-1">
            @include('fundos.card.municipio.resumo')
        </div>

        @php
            /* Fim do resumo dos Fundos de Desenvolvimento Regional */
        @endphp

        @php
            /* Início do resumo da SEDEC */
        @endphp

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-2 mb-1">
            @include('sedec.resumo')
        </div>

        @php
            /* Fim do resumo da SEDEC */
        @endphp

        @php
            /* Início das rotas de integração nacional */
        @endphp

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mt-2 mb-1 <?php !is_array($rotas) && $rotas->count() > 0 ? '' : print 'd-print-none'; ?>">
            @include('rotasDeIntegracao.municipio.rota')
        </div>

        @php
            /* Fim das rotas de integração nacional */
        @endphp
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
        @if ($tema != 'Empreendimentos do novo PAC')
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2 mb-1" id="accordionTemas">

                <div class="card sticky-top border border-0 mt-0 mb-0 pt-0 pb-0">
                    <div class="card-body cardTemas shadow-sm" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $contTema }}" aria-expanded="true"
                        aria-controls="collapse{{ $contTema }}"
                        onclick="scrollToCard(this, {{ $contTema }}); <?php $tema === 'TSE' ? print '$(\'#thCountDF\').click(); $(\'#thCountDF\').click();' : ''; ?>"
                        id="divTema{!! $tema !!}">
                        {!! $tema !!}
                        @if ($tema === 'Novo PAC')
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
                        @include('tse.municipio.index')
                    @elseif ($tema === 'Carteira de Investimento')
                        @include('tci.municipio.index')
                    @elseif ($tema === 'Empreendimentos do novo PAC')
                        {{ '' }}
                    @elseif ($tema === 'Fundos de Desenvolvimento Regional')
                        @include('fundos.municipio.index')
                    @else
                        {!! $tema !!}
                    @endif

                </div>

            </div>
            @php
                /* Incremento do contador $contTema */
                $contTema++;
            @endphp
        @endif
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
@endif
