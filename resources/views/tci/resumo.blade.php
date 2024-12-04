<div class="card sticky-top border border-0">
    <div class="card-body cardTemas shadow-sm pl-2" style="cursor: default!Important;">
        <i class="fas fa-search-dollar text-info"></i> Resumo da Carteira ativa por Área de Investimento <a
            class="d-print-none"
            href="https://formulariopainel.mdr.gov.br/aplicativo/{{ $sgl_uf . '/' . $getMunicipio->nom_municipio_sem_formatacao }}"
            target="_blank"><i class="fas fa-link"></i><span style="font-size: 0.6rem!Important;">fonte</span></a>
    </div>
</div>

<div class="row mt-1 pl-1">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-0 pt-0 mb-0 pb-0">

        <table class="table table-borderless">
            @if (count($resumoTciMunicipio) > 0)
                <thead class="">
                    <tr>
                        <th class="borda_table_indicadores text-bold" style="font-size: 0.8rem !Important;">
                            Área de Investimento
                        </th>
                        <th class="borda_table_indicadores text-bold text-right" style="font-size: 0.8rem !Important;">
                            Valor de Repasse
                        </th>
                        <th class="borda_table_indicadores text-bold text-right" style="font-size: 0.8rem !Important;">
                            Valor Pago
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @php
                        $quantidadeTotal = 0;
                        $vlrRepasseTotal = 0;
                        $vlrPagoTotal = 0;
                    @endphp

                    @foreach ($arrayAreasInvestimento as $areasInvestimento)
                        @php
                            $mostrarTrZerada = true;
                        @endphp
                        @foreach ($resumoTciMunicipio as $tci)
                            @if (!is_null($tci->num_empreendimentos) && $tci->dsc_area_investimento === $areasInvestimento)
                                <tr class="" style="">
                                    @php
                                        // Início do cálculo dos totais
                                        $quantidadeTotal = $quantidadeTotal + $tci->num_empreendimentos;
                                        $vlrRepasseTotal = $vlrRepasseTotal + $tci->vlr_repasse;

                                        if ($tci->dsc_area_investimento != 'Segurança Hídrica') {
                                            $vlrPagoTotal = $vlrPagoTotal + $tci->vlr_pago;
                                        } else {
                                            $vlrPagoTotal = $vlrPagoTotal + $tci->vlr_pago_conta;
                                        }
                                        // Fim do cálculo dos totais
                                        $mostrarTrZerada = false;
                                    @endphp


                                    <th class="borda_table_indicadores text-bold" style="width: 45%">
                                        {{ $tci->dsc_area_investimento === 'Desenvolvimento Regional e Urbano' ? 'Desenv. Regional e Urbano' : $tci->dsc_area_investimento }}
                                        <span class=" font-numero text-primary">({{ $tci->num_empreendimentos }})</span>
                                    </th>

                                    <th class="borda_table_indicadores font-numero text-right pr-2">
                                        {!! prettify_numbers($tci->vlr_repasse) !!}
                                    </th>

                                    <th class="borda_table_indicadores font-numero text-right pr-2">
                                        {!! prettify_numbers($tci->vlr_pago) !!}
                                    </th>
                                </tr>
                            @endif
                        @endforeach

                        @if ($mostrarTrZerada)
                            <tr class="d-print-none" style="">

                                <th class="borda_table_indicadores text-bold font-numero-zerado" style="width: 45%">
                                    {{ $areasInvestimento === 'Desenvolvimento Regional e Urbano' ? 'Desenv. Regional e Urbano' : $areasInvestimento }}
                                    <span class=" font-numero text-primary">(0)</span>
                                </th>

                                <th class="borda_table_indicadores font-numero text-right pr-2 font-numero-zerado">
                                    {!! prettify_numbers(0) !!}
                                </th>

                                <th class="borda_table_indicadores font-numero text-right pr-2 font-numero-zerado">
                                    {!! prettify_numbers(0) !!}
                                </th>

                            </tr>
                        @endif
                    @endforeach

                    <tr class="" style="background-color: #fffdf9!Important;">

                        <th class="borda_table_indicadores text-bold" style="width: 45%">
                            Total
                            <span class=" font-numero text-primary">({{ $quantidadeTotal }})</span>
                        </th>

                        <th class="borda_table_indicadores font-numero text-right pr-2 text-bold">
                            {!! prettify_numbers($vlrRepasseTotal) !!}
                        </th>

                        <th class="borda_table_indicadores font-numero text-right pr-2 text-bold">
                            {!! prettify_numbers($vlrPagoTotal) !!}
                        </th>

                    </tr>

                </tbody>
            @else
                <tr>
                    <th class="text-danger">
                        Não há investimento <span class="text-bold">ativo</span> do MIDR para esse município
                    </th>
                </tr>
            @endif

        </table>

    </div>

</div>
