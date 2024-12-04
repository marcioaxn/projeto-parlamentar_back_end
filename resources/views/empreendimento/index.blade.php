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
                    <a href="{!! url('/') !!}">
                        <span id="breadcrumbs-current">Principal</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                <span dir="ltr" id="breadcrumbs-2">
                    <span id="breadcrumbs-current">Consulta Empreendimento</span>
                </span>

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->

    <div class="row" id="informacao">
        <div class="col-xs-12 col-sm-12 col-md-12"
            style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;">
            <i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando...
        </div>
    </div>

    @php
        // Início da <div> que receberá o conteúdo dinaâmico da página
    @endphp

    <div class="row" id="div1" style="display: none;">

        @if ($empreendimento)
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 text-left">
                <div for="" class="bg-geral-titulo-modal rounded pt-1 pb-1 pl-2">
                    {!! $empreendimento->tipo_instrumento . ' - ' . $empreendimento->dsc_area_investimento !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 6px; font-size: 3px;">
                &nbsp;
            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <ul class="list-group">
                            <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 bg-light text-black"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important;">
                                        <b>Unidade responsável</b>
                                    </div>
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important; padding-right: 0.4rem !Important;">
                                        {!! $empreendimento->sgl_unidade_responsavel_agrupada !!}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 6px; font-size: 3px;">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <ul class="list-group">
                            <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 bg-light text-black"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important;">
                                        <b>UF/Município</b>
                                    </div>
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important; padding-right: 0.4rem !Important;">
                                        {!! $empreendimento->uf !!}/{!! $empreendimento->municipio !!}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 6px; font-size: 3px;">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <ul class="list-group">
                            <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 bg-light text-black"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important;">
                                        <b>Situação contrato</b>
                                    </div>
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important; padding-right: 0.4rem !Important;">
                                        {!! $empreendimento->dsc_situacao_contrato_mdr !!}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 6px; font-size: 3px;">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <ul class="list-group">
                            <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 bg-light text-black"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important;">
                                        <b>Impositivo</b>
                                    </div>
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important; padding-right: 0.4rem !Important;">
                                        {{ $empreendimento->bln_emenda }}
                                        @if ($empreendimento->bln_emenda === 'SIM')
                                            {{ 'informar nome do autor' }}
                                        @endif
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 6px; font-size: 3px;">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <ul class="list-group">
                            <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 bg-light text-black"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important;">
                                        <b>Valor investimento / repasse</b>
                                    </div>
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important; padding-right: 0.4rem !Important;">
                                        R$ {!! converteValor('MYSQL', 'PTBR', $empreendimento->vlr_investimento_ajustado) !!} / R$ {!! converteValor('MYSQL', 'PTBR', $empreendimento->vlr_repasse) !!}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 6px; font-size: 3px;">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <ul class="list-group">
                            <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 bg-light text-black"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important;">
                                        <b>Valor empenhado / pago</b>
                                    </div>
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important; padding-right: 0.4rem !Important;">
                                        R$ {!! converteValor('MYSQL', 'PTBR', $empreendimento->vlr_empenhado_ajustado) !!} / R$ {!! converteValor('MYSQL', 'PTBR', $empreendimento->vlr_pago_ajustado) !!}

                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 6px; font-size: 3px;">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <ul class="list-group">
                            <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 bg-light text-black"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important;">
                                        <b>Percentual financeiro</b>
                                    </div>
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important; padding-right: 0.4rem !Important;">
                                        {!! $empreendimento->prc_execucao !!}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 6px; font-size: 3px;">
                        &nbsp;
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-print-none">
                        <div id="map" style="width: 99.6% !Important; height: 303px !Important;"></div>
                    </div>
                    @if ($getIndicadoresMunicipio)
                        <script>
                            function initMap() {
                                const myLatLng = {
                                    lat: {{ $getTabCities->latitude }},
                                    lng: {{ $getTabCities->longitude }}
                                };
                                const map = new google.maps.Map(document.getElementById("map"), {
                                    zoom: 14,
                                    center: myLatLng,
                                    mapTypeId: google.maps.MapTypeId.HYBRID,
                                });

                                new google.maps.Marker({
                                    position: myLatLng,
                                    map,
                                    title: "Município do Empreendimento",
                                });
                            }

                            window.initMap = initMap;
                        </script>
                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqbrfrVv89EdiGsEBXhDdsFRKlHNmNvv8&callback=initMap" async
                            defer></script>
                    @endif

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 9px; font-size: 3px;">
                        &nbsp;
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <ul class="list-group" style="margin: 0px !Important; padding: 0px !Important;">
                                    <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                        <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 bg-light text-black"
                                                style="margin: 0px !Important; padding: 3px !Important; padding-left: 9px !Important;">
                                                <b>Habitantes</b> <small class="text-muted">IBGE 2022</small>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-right"
                                                style="margin: 0px !Important; padding: 3px !Important; padding-left: 9px !Important; padding-right: 17px !Important;">
                                                @if ($getIndicadoresMunicipio)
                                                    <?php isset($getIndicadoresMunicipio) && $getIndicadoresMunicipio != '' ? print formatarNumeroInteiro($getIndicadoresMunicipio->num_populacao_2022) . ' <small class="text-muted">em 2016</small>' : print 'Sem informação'; ?>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                style="height: 6px; font-size: 3px;">
                                &nbsp;
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <ul class="list-group" style="margin: 0px !Important; padding: 0px !Important;">
                                    <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                        <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 bg-light text-black"
                                                style="margin: 0px !Important; padding: 3px !Important; padding-left: 9px !Important;">
                                                <b>PIB</b> <small class="text-muted">IBGE 2020</small>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-right"
                                                style="margin: 0px !Important; padding: 3px !Important; padding-left: 9px !Important; padding-right: 17px !Important;">
                                                @if ($getIndicadoresMunicipio)
                                                    <?php isset($getIndicadoresMunicipio->num_pib_per_capita_2020) && $getIndicadoresMunicipio->num_pib_per_capita_2020 != '' ? print 'R$ ' . detectarConverteValor($getIndicadoresMunicipio->num_pib_per_capita_2020) : print '-'; ?>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                style="height: 6px; font-size: 3px;">
                                &nbsp;
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <ul class="list-group" style="margin: 0px !Important; padding: 0px !Important;">
                                    <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                        <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 bg-light text-black"
                                                style="margin: 0px !Important; padding: 3px !Important; padding-left: 9px !Important;">
                                                <b>Receitas realizadas</b> <small class="text-muted">IBGE 2017</small>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-right"
                                                style="margin: 0px !Important; padding: 3px !Important; padding-left: 9px !Important; padding-right: 17px !Important;">
                                                <?php isset($getIndicadoresMunicipio->num_receitas_realizadas_por_1000_em_2017) && $getIndicadoresMunicipio->num_receitas_realizadas_por_1000_em_2017 != '' ? print prettify_numbers($getIndicadoresMunicipio->num_receitas_realizadas_por_1000_em_2017 * 1000) : print '-'; ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"
                                style="height: 6px; font-size: 3px;">
                                &nbsp;
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <ul class="list-group" style="margin: 0px !Important; padding: 0px !Important;">
                                    <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                        <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 bg-light text-black"
                                                style="margin: 0px !Important; padding: 3px !Important; padding-left: 9px !Important;">
                                                <b>Despesas empenhadas</b> <small class="text-muted">IBGE 2017</small>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-right"
                                                style="margin: 0px !Important; padding: 3px !Important; padding-left: 9px !Important; padding-right: 17px !Important;">
                                                <?php isset($getIndicadoresMunicipio->num_despesas_empenhadas_por_1000_em_2017) && $getIndicadoresMunicipio->num_despesas_empenhadas_por_1000_em_2017 != '' ? print prettify_numbers($getIndicadoresMunicipio->num_despesas_empenhadas_por_1000_em_2017 * 1000) : print '-'; ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 1px; font-size: 3px;">
                &nbsp;
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-2">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <ul class="list-group">
                            <li class="list-group-item" style="margin: 0px !Important; padding: 0px !Important;">
                                <div class="row" style="margin: 0px !Important; padding: 0px !Important;">
                                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1 bg-light text-black"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important;">
                                        <b>Objeto proposta</b>
                                    </div>
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9"
                                        style="margin: 0px !Important; padding: 0.4rem !Important; font-size: 0.8rem !Important; padding-right: 0.4rem !Important;">
                                        <?php isset($empreendimento) && $empreendimento != '' ? print $empreendimento->txt_empreendimento : print '-'; ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="alert alert-danger" role="alert">
                    <strong>Ops!</strong> É possível que este código do empreendimento, <strong
                        class="font-numero">{{ $cod_mdr }},</strong> seja inválido, pois não o encontramos na nossa
                    base de
                    dados
                    atualizada.
                </div>
            </div>
        @endif

    </div>

    @php
        // Fim da <div> que receberá o conteúdo dinaâmico da página
    @endphp

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
