@php
    $giniReferencia = '0,489';
    $ishReferencia = '3';
@endphp

<div class="row mb-0 pb-0">

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-0 pb-0">

        <table class="table table-borderless m-0 p-0">

            <tr>

                <th class="borda_table_indicadores" style="width: 55%">
                    População <small class="text-muted">IBGE
                        {{ $getIndicadoresMunicipio->populacao ? $getIndicadoresMunicipio->populacao->num_ano : '-' }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    <?php $getIndicadoresMunicipio->populacao && isset($getIndicadoresMunicipio->populacao->vlr_populacao) && $getIndicadoresMunicipio->populacao->vlr_populacao != '' ? print formatarNumeroInteiro($getIndicadoresMunicipio->populacao->vlr_populacao) . ' <small class="text-muted">pessoas</small>' : print 'Sem informação'; ?>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores" style="width: 50%">
                    PIB per Capita <small class="text-muted">IBGE
                        {{ $getIndicadoresMunicipio->pibPerCapita ? $getIndicadoresMunicipio->pibPerCapita->num_ano : '-' }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    <?php $getIndicadoresMunicipio->pibPerCapita && isset($getIndicadoresMunicipio->pibPerCapita->vlr_pib_per_capita) && $getIndicadoresMunicipio->pibPerCapita->vlr_pib_per_capita != '' ? print 'R$ ' . detectarConverteValor($getIndicadoresMunicipio->pibPerCapita->vlr_pib_per_capita) : print 'Sem informação'; ?>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores">
                    Receitas municipais realizadas <small class="text-muted">IBGE
                        {{ $getIndicadoresMunicipio->receitaDespesa ? $getIndicadoresMunicipio->receitaDespesa->num_ano : '-' }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    R$ <?php $getIndicadoresMunicipio->receitaDespesa && isset($getIndicadoresMunicipio->receitaDespesa->vlr_receita_orcamentaria_realizada) && $getIndicadoresMunicipio->receitaDespesa->vlr_receita_orcamentaria_realizada != '' ? print prettify_numbers($getIndicadoresMunicipio->receitaDespesa->vlr_receita_orcamentaria_realizada * 1000) : print '-'; ?>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores">
                    Despesas municipais empenhadas <small class="text-muted">IBGE
                        {{ $getIndicadoresMunicipio->receitaDespesa ? $getIndicadoresMunicipio->receitaDespesa->num_ano : '-' }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    R$ <?php $getIndicadoresMunicipio->receitaDespesa && isset($getIndicadoresMunicipio->receitaDespesa->vlr_despesa_orcamentaria_empenhada) && $getIndicadoresMunicipio->receitaDespesa->vlr_despesa_orcamentaria_empenhada != '' ? print prettify_numbers($getIndicadoresMunicipio->receitaDespesa->vlr_despesa_orcamentaria_empenhada * 1000) : print '-'; ?>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores">
                    Rural/Urbana <small class="text-muted">IBGE 2016</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    <?php isset($getMunicipio->tipologiaruralurbana) && $getMunicipio->tipologiaruralurbana != '' ? print $getMunicipio->tipologiaruralurbana : print '-'; ?>
                    <?php isset($getMunicipio->tipologiaruralurbana) && $getMunicipio->tipologiaruralurbana != '' && $getMunicipio->tipologiaruralurbana == 'Urbano' ? print '/ ' . $getMunicipio->hierarquiaurbana : print ''; ?>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores" style="width: 50%">
                    É área de atuação da
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    {{ isset($fazParteDaAreaDeAtuacaoVinculadas) && !is_null($fazParteDaAreaDeAtuacaoVinculadas) && $fazParteDaAreaDeAtuacaoVinculadas != '' ? $fazParteDaAreaDeAtuacaoVinculadas : '-' }}
                </th>

            </tr>

        </table>

    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-0 pt-0">

        <table class="table table-borderless m-0 p-0">

            @if ($getPisf)
                <tr>

                    <th colspan="2" class="borda_table_indicadores">

                        @php

                            $complementoPisf = null;

                            if (isset($getPisf->nom_bacia) && !is_null($getPisf->nom_bacia) && $getPisf->nom_bacia != '') {
                                $complementoPisf .= $getPisf->nom_bacia . ' - ';
                            }
                            if (isset($getPisf->nom_eixo_principal) && !is_null($getPisf->nom_eixo_principal) && $getPisf->nom_eixo_principal != '') {
                                $complementoPisf .= $getPisf->nom_eixo_principal . ' - ';
                            }
                            $complementoPisf = trim($complementoPisf, ' - ');
                        @endphp

                        <i class="fas fa-water text-primary"></i> Beneficiado pelo <span class="text-bold">PISF</span>.
                        {!! $complementoPisf !!} <a class="d-print-none"
                            href="https://www.gov.br/mdr/pt-br/assuntos/seguranca-hidrica/projeto-sao-francisco"
                            target="_blank"><i class="fas fa-link"></i><span
                                style="font-size: 0.6rem!Important;">fonte</span></a>

                    </th>

                </tr>
            @endif

            <tr>

                @php
                    $idhBrasil = 0.727;
                @endphp

                <th class="borda_table_indicadores" style="width: 50%">
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
                        data-bs-content="Índice de Desenvolvimento Humano Municipal" style="cursor: help;">IDHM</span>
                    <small class="text-muted">IBGE
                        {{ $getIndicadoresMunicipio->idh ? $getIndicadoresMunicipio->idh->num_ano : '-' }} ( Brasil
                        <span class="text-bold">{{ $idhBrasil }}</span> )</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    @php
                        // $getIndicadoresMunicipio->idh->vlr_idh = str_replace('.', ',', $getIndicadoresMunicipio->idh->vlr_idh);
                    @endphp
                    @if (
                        $getIndicadoresMunicipio->idh &&
                            !is_null($getIndicadoresMunicipio->idh->vlr_idh) &&
                            $getIndicadoresMunicipio->idh->vlr_idh < $idhBrasil)
                        <i class="fas fa-arrow-circle-down text-danger" style="font-size: 0.8rem !Important;"></i>
                        <?php isset($getIndicadoresMunicipio->idh->vlr_idh) && $getIndicadoresMunicipio->idh->vlr_idh != '' ? print $getIndicadoresMunicipio->idh->vlr_idh . '<small class="text-muted">&nbsp;&nbsp;menor que o IDH Brasil</small>' : print '-'; ?>
                    @elseif (
                        $getIndicadoresMunicipio->idh &&
                            !is_null($getIndicadoresMunicipio->idh->vlr_idh) &&
                            $getIndicadoresMunicipio->idh->vlr_idh > $idhBrasil)
                        <i class="fas fa-arrow-circle-up text-success" style="font-size: 0.8rem !Important;"></i>
                        <?php isset($getIndicadoresMunicipio->idh->vlr_idh) && $getIndicadoresMunicipio->idh->vlr_idh != '' ? print $getIndicadoresMunicipio->idh->vlr_idh . '<small class="text-muted">&nbsp;&nbsp;maior que o IDH Brasil</small>' : print '-'; ?>
                    @elseif (
                        $getIndicadoresMunicipio->idh &&
                            !is_null($getIndicadoresMunicipio->idh->vlr_idh) &&
                            ($getIndicadoresMunicipio->idh->vlr_idh = $idhBrasil))
                        <?php isset($getIndicadoresMunicipio->idh->vlr_idh) && $getIndicadoresMunicipio->idh->vlr_idh != '' ? print $getIndicadoresMunicipio->idh->vlr_idh . '<small class="text-muted">&nbsp;&nbsp;igual ao IDH Brasil</small>' : print '-'; ?>
                    @else
                    @endif
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores" style="width: 50%">
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
                        data-bs-content="Índice da Política Nacional de Desenvolvimento Regional"
                        style="cursor: help;">Classificação segundo a PNDR</span>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    {!! $getIndicadoresMunicipio->idc_pndr !!}
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores" style="width: 50%">
                    Integra a área prioritária da PNDR?
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    @php
                        $municipioPrioritario = null;
                        if ($getIndicadoresMunicipio->bln_semiarido) {
                            $municipioPrioritario .= 'Semiárido e ';
                        }

                        if ($getIndicadoresMunicipio->bln_fronteira) {
                            $municipioPrioritario .= 'Fronteiriço e ';
                        }

                        if ($getIndicadoresMunicipio->bln_ride) {
                            $municipioPrioritario .= 'RIDE e ';
                        }

                        $municipioPrioritario = trim($municipioPrioritario, ' e ');

                        if (!is_null($municipioPrioritario) && $municipioPrioritario != '') {
                            $municipioPrioritario = 'Sim - ' . $municipioPrioritario;
                        } else {
                            $municipioPrioritario = 'Não';
                        }
                    @endphp
                    {{ $municipioPrioritario }}
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores" style="width: 50%">
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
                        data-bs-content="Gini é a medida estatística que avalia a desigualdade de distribuição de renda ou riqueza em uma população. Ele varia de 0 a 1, onde 0 representa igualdade perfeita (todos têm a mesma renda ou riqueza) e 1 representa desigualdade total (uma pessoa possui toda a renda ou riqueza, enquanto as outras não possuem nada)"
                        style="cursor: help;">Gini</span> <small class="text-muted">IPEA 2010</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">

                    <span data-bs-toggle="popover" data-bs-trigger="hover focus"
                        data-bs-content="Gini menor, maior ou igual a {{ $giniReferencia }} conforme a Portaria do MIDR nº 1.825/2023"
                        style="cursor: help;">
                        @if (!is_null($getIndicadoresMunicipio->idc_gini) && $getIndicadoresMunicipio->idc_gini <= $giniReferencia)
                            <i class="fas fa-arrow-circle-down text-danger" style="font-size: 0.8rem !Important;"></i>
                            {!! $getIndicadoresMunicipio->idc_gini !!} <small class="text-muted">Gini menor ou igual a
                                {{ $giniReferencia }} em 2023</small>
                        @elseif (!is_null($getIndicadoresMunicipio->idc_gini) && $getIndicadoresMunicipio->idc_gini > $giniReferencia)
                            <i class="fas fa-arrow-circle-up text-success" style="font-size: 0.8rem !Important;"></i>
                            {!! $getIndicadoresMunicipio->idc_gini !!} <small class="text-muted">Gini maior do que
                                {{ $giniReferencia }} em 2023</small>
                        @else
                            -
                        @endif
                    </span>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores" style="width: 50%">
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
                        data-bs-content="Índice de Segurança Hídrica, na dimensão humana (Garantia de água para abastecimento e Cobertura da rede de abastecimento)"
                        style="cursor: help;">ISH</span> <small class="text-muted">ANA 2017</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">

                    <span data-bs-toggle="popover" data-bs-trigger="hover focus"
                        data-bs-content="ISH menor, maior ou igual a {{ $ishReferencia }} conforme a Portaria do MIDR nº 1.825/2023"
                        style="cursor: help;">
                        @if (!is_null($getIndicadoresMunicipio->idc_ihu_cs_ish) && $getIndicadoresMunicipio->idc_ihu_cs_ish <= $ishReferencia)
                            <i class="fas fa-arrow-circle-down text-danger" style="font-size: 0.8rem !Important;"></i>
                            {!! $getIndicadoresMunicipio->idc_ihu_cs_ish !!} <small class="text-muted">ISH menor ou igual a
                                {{ $ishReferencia }} em 2023</small>
                        @elseif (!is_null($getIndicadoresMunicipio->idc_ihu_cs_ish) && $getIndicadoresMunicipio->idc_ihu_cs_ish > $ishReferencia)
                            <i class="fas fa-arrow-circle-up text-success" style="font-size: 0.8rem !Important;"></i>
                            {!! $getIndicadoresMunicipio->idc_ihu_cs_ish !!} <small class="text-muted">ISH maior do que
                                {{ $ishReferencia }} em 2023</small>
                        @else
                            -
                        @endif
                    </span>

                </th>

            </tr>

        </table>

    </div>

</div>
