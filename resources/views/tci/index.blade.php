@if (!empty($tci))
    <div class="row pl-1">

        @if (isset($getParlamentar) && $getParlamentar)
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">
                <div for="" class="<?php isset($getParlamentar) && $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print 'bg-camara-sub-titulo-modal' : print 'bg-senado-sub-titulo-modal'; ?> rounded mt-3 pt-1 pb-1 pl-2">Empreendimentos ativos da
                    Carteira de Investimento</div>
            </div>
        @else
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">
                <div for="" class="bg-geral-sub-titulo-modal rounded mt-3 pt-1 pb-1 pl-2">Empreendimentos ativos
                    da
                    Carteira de Investimento</div>
            </div>
        @endif



        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 table-responsive">

            <table id="tableTci" class="table table-sm mt-3 dt-responsive table-striped" cellspacing="0"
                style="width: 100% !Important;">

                <thead>
                    <tr>
                        <th class="">
                            Código
                        </th>
                        <th class="">
                            Emenda Parlamentar
                        </th>
                        <th class="">
                            Área de investimento
                        </th>
                        <th class="">
                            Unidade responsáavel
                        </th>
                        <th class="text-left">
                            UF/Município
                        </th>
                        <th>
                            Data da assinatura do contrato
                        </th>
                        <th>
                            Situação do contrato
                        </th>
                        <th>
                            Situação obra
                        </th>
                        <th class="text-right">
                            Valor de investimento
                        </th>
                        <th class="text-right">
                            Valor de repasse
                        </th>
                        <th class="text-right">
                            Valor pago
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $contTse = 1;
                    @endphp
                    @foreach ($tci as $key => $value)
                        @php
                            isset($value->municipio) && !is_null($value->municipio) && $value->municipio != '' ? ($municipioUrl = tirarAcentuacao(passarTextoParaMaiusculo($value->municipio))) : ($municipioUrl = '');
                            isset($value->municipio) && !is_null($value->municipio) && $value->municipio != '' ? ($municipio = $value->nom_municipio_ajustado) : ($municipio = '');
                        @endphp

                        <tr>
                            <td class="font-numero" style="">
                                <a href="https://formulariopainel.mdr.gov.br/app/instrumentos/{!! $value->cod_mdr !!}/{!! $getParlamentar->sgl_uf_representante !!}"
                                    target="_blank">
                                    {!! $value->cod_mdr !!}
                                </a>
                            </td>
                            <td class="" style="">
                                {!! $value->nom_parlamentar !!}
                            </td>
                            <td class="" style="">
                                {!! $value->dsc_area_investimento !!}
                            </td>
                            <td class="" style="">
                                {!! $value->sgl_unidade_responsavel_agrupada !!}
                            </td>
                            <td class="" style="">
                                @php
                                    if (isset($value->municipio) && !is_null($value->municipio) && $value->municipio != '') {
                                        $partesMunicipio = explode(',', $value->municipio);
                                    } else {
                                        $partesMunicipio = [];
                                    }
                                @endphp
                                @if (isset($value->municipio) && !is_null($value->municipio) && $value->municipio != '' && count($partesMunicipio) == 1)
                                    <a href="{!! route('uf-municipio', [$value->uf, $municipioUrl]) !!}" target="_blank">
                                        {!! $value->uf . '/' . $municipio !!}
                                    </a>
                                @elseif (isset($value->municipio) && !is_null($value->municipio) && $value->municipio != '' && count($partesMunicipio) != 1)
                                    @if (count($partesMunicipio) > 1)
                                        @foreach ($partesMunicipio as $nomMunicipio)
                                            {!! $nomMunicipio . '<br />' !!}
                                        @endforeach
                                    @else
                                        {!! $municipio !!}
                                    @endif
                                @else
                                    {{ '-' }}
                                @endif

                            </td>
                            <td class="font-numero" style="">
                                {!! formatarDataComCarbonParaBR($value->dte_assinatura_contrato_ajustado) !!}
                            </td>
                            <td class="" style="">
                                {!! $value->dsc_situacao_contrato_mdr !!}
                            </td>
                            <td class="" style="">
                                {!! $value->dsc_situacao_obra !!}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->vlr_investimento_ajustado) !!}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->vlr_repasse) !!}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->vlr_pago) !!}
                            </td>
                        </tr>
                        @php
                            $contTse++;
                        @endphp
                    @endforeach

                </tbody>

            </table>

            <script type="text/javascript" charset="utf-8">
                $(document).ready(function() {
                    var table = $('#tableTci').DataTable({
                        "language": {
                            "url": "{{ asset('Portuguese-Brasil.json') }}",
                            "decimal": ",",
                            "thousands": "."
                        },
                        "paging": true,
                        "lengthMenu": [
                            [-1, 5, 10, 25, 50, 100],
                            ["Todos ", "5 ", "10 ", "25 ", "50 ", "100 "]
                        ],
                        responsive: true,
                        "autoWidth": true,
                        dom: 'Plfrtip', // Adicionado 'P' para incluir o seletor de quantidade de itens por página

                        @if (isset($getParlamentar) && $getParlamentar)
                            searchPanes: {
                                layout: 'columns-1',
                                columns: [1],
                                preSelect: [{
                                    column: 1,
                                    rows: ['{{ $getParlamentar->nom_parlamentar_sem_formatacao }}']
                                }]
                            },
                        @endif

                        @if (isset($sglUfNomMunicipio) && !is_null($sglUfNomMunicipio) && $sglUfNomMunicipio != '')
                            searchPanes: {
                                layout: 'columns-4',
                                columns: [4],
                                preSelect: [{
                                    column: 4,
                                    rows: ['{{ $sglUfNomMunicipio }}']
                                }]
                            },
                        @endif


                        fixedHeader: {
                            header: true,
                            headerOffset: $('#header').outerHeight() - 12
                        },
                        "order": [
                            [5, "asc"]
                        ],
                        initComplete: function(settings, json) {
                            $('.dtsp-panesContainer button.dtsp-showAll').click();

                            $('.dtsp-panesContainer').hide();

                            var toggleButton = $(
                                '<div class="text-right"><button class="btn btn-outline-secondary btn-sm mb-3 ">Visualizar Filtro</button></div>'
                            ).click(function() {
                                $('.dtsp-panesContainer').toggle();
                            });

                            $(table.table().container()).prepend(toggleButton);
                        },
                        scrollx: true,
                        scrollCollapse: false,
                    });
                });
            </script>

        </div>

    </div>
@endif
