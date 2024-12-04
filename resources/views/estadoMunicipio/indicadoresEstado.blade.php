@php
    $giniReferencia = '0,489';
    $ishReferencia = '3';
@endphp

<div class="row mb-0 pb-0">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-0 pb-0">

        <table class="table table-borderless m-0 p-0">

            <tr>

                <th class="borda_table_indicadores" style="width: 59%">
                    População <small class="text-muted">IBGE
                        {{ $indicadoresEstado->populacao ? $indicadoresEstado->populacao->num_ano : '-' }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    <?php $indicadoresEstado->populacao && isset($indicadoresEstado->populacao->vlr_populacao) && $indicadoresEstado->populacao->vlr_populacao != '' ? print formatarNumeroInteiro($indicadoresEstado->populacao->vlr_populacao) . ' <small class="text-muted">pessoas</small>' : print 'Sem informação'; ?>
                </th>

            </tr>

            <tr>

                @php
                    if ($indicadoresEstado->densidadeDemografica) {
                        $indicadoresEstado->densidadeDemografica->vlr_densidade_demografica = str_replace(
                            '.',
                            ',',
                            $indicadoresEstado->densidadeDemografica->vlr_densidade_demografica,
                        );
                    }
                @endphp

                <th class="borda_table_indicadores">
                    Densidade demográfica <small class="text-muted">IBGE
                        {{ $indicadoresEstado->densidadeDemografica ? $indicadoresEstado->densidadeDemografica->num_ano : '-' }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    <?php $indicadoresEstado->densidadeDemografica && isset($indicadoresEstado->densidadeDemografica->vlr_densidade_demografica) && $indicadoresEstado->densidadeDemografica->vlr_densidade_demografica != '' ? print $indicadoresEstado->densidadeDemografica->vlr_densidade_demografica . ' <small class="text-muted">habitante por km&#178;</small>' : print '-'; ?>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores">
                    Rendimento nominal mensal domiciliar per capita <small class="text-muted">IBGE
                        {{ $indicadoresEstado->rendimentoDomiciliarPerCapita->num_ano }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    <?php $indicadoresEstado->rendimentoDomiciliarPerCapita && isset($indicadoresEstado->rendimentoDomiciliarPerCapita->vlr_rnmdpc) && $indicadoresEstado->rendimentoDomiciliarPerCapita->vlr_rnmdpc != '' ? print 'R$ ' . detectarConverteValor($indicadoresEstado->rendimentoDomiciliarPerCapita->vlr_rnmdpc) : print '-'; ?>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores">
                    Receitas estaduais realizadas <small class="text-muted">IBGE
                        {{ $indicadoresEstado->receitaDespesa ? $indicadoresEstado->receitaDespesa->num_ano : '-' }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    R$ <?php $indicadoresEstado->receitaDespesa && isset($indicadoresEstado->receitaDespesa->vlr_receita_orcamentaria_realizada) && $indicadoresEstado->receitaDespesa->vlr_receita_orcamentaria_realizada != '' ? print prettify_numbers($indicadoresEstado->receitaDespesa->vlr_receita_orcamentaria_realizada * 1000) : print '-'; ?>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores">
                    Despesas estaduais empenhadas <small class="text-muted">IBGE
                        {{ $indicadoresEstado->receitaDespesa ? $indicadoresEstado->receitaDespesa->num_ano : '-' }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    R$ <?php $indicadoresEstado->receitaDespesa && isset($indicadoresEstado->receitaDespesa->vlr_despesa_orcamentaria_empenhada) && $indicadoresEstado->receitaDespesa->vlr_despesa_orcamentaria_empenhada != '' ? print prettify_numbers($indicadoresEstado->receitaDespesa->vlr_despesa_orcamentaria_empenhada * 1000) : print '-'; ?>
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores" style="width: 50%">
                    Área de atuação da SUDAM, SUDENE OU SUDECO?
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    {{ isset($fazParteDaAreaDeAtuacaoVinculadas) && !is_null($fazParteDaAreaDeAtuacaoVinculadas) && $fazParteDaAreaDeAtuacaoVinculadas != '' ? 'Sim - ' . $fazParteDaAreaDeAtuacaoVinculadas : 'Não' }}
                </th>

            </tr>

            <tr>

                @php
                    $idhBrasil = 0.727;
                @endphp

                <th class="borda_table_indicadores" style="width: 50%">
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
                        data-bs-content="Índice de Desenvolvimento Humano Municipal" style="cursor: help;">IDHM</span>
                    <small class="text-muted">IBGE
                        {{ $indicadoresEstado->idh ? $indicadoresEstado->idh->num_ano : '-' }} ( Brasil
                        <span class="text-bold">{{ $idhBrasil }}</span> )</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    @php
                        // $indicadoresEstado->idh->vlr_idh = str_replace('.', ',', $indicadoresEstado->idh->vlr_idh);
                    @endphp
                    @if (
                        $indicadoresEstado->idh &&
                            !is_null($indicadoresEstado->idh->vlr_idh) &&
                            $indicadoresEstado->idh->vlr_idh < $idhBrasil)
                        <i class="fas fa-arrow-circle-down text-danger" style="font-size: 0.8rem !Important;"></i>
                        <?php isset($indicadoresEstado->idh->vlr_idh) && $indicadoresEstado->idh->vlr_idh != '' ? print $indicadoresEstado->idh->vlr_idh . '<small class="text-muted">&nbsp;&nbsp;menor que o IDH Brasil</small>' : print '-'; ?>
                    @elseif (
                        $indicadoresEstado->idh &&
                            !is_null($indicadoresEstado->idh->vlr_idh) &&
                            $indicadoresEstado->idh->vlr_idh > $idhBrasil)
                        <i class="fas fa-arrow-circle-up text-success" style="font-size: 0.8rem !Important;"></i>
                        <?php isset($indicadoresEstado->idh->vlr_idh) && $indicadoresEstado->idh->vlr_idh != '' ? print $indicadoresEstado->idh->vlr_idh . '<small class="text-muted">&nbsp;&nbsp;maior que o IDH Brasil</small>' : print '-'; ?>
                    @elseif (
                        $indicadoresEstado->idh &&
                            !is_null($indicadoresEstado->idh->vlr_idh) &&
                            ($indicadoresEstado->idh->vlr_idh = $idhBrasil))
                        <?php isset($indicadoresEstado->idh->vlr_idh) && $indicadoresEstado->idh->vlr_idh != '' ? print $indicadoresEstado->idh->vlr_idh . '<small class="text-muted">&nbsp;&nbsp;igual ao IDH Brasil</small>' : print '-'; ?>
                    @else
                    @endif
                </th>

            </tr>

            <tr>

                <th class="borda_table_indicadores" style="width: 51%">
                    Estado integra a área prioritária da PNDR?
                </th>

                <th class="borda_table_indicadores text-bold font-numero">
                    @php
                        $municipioPrioritario = null;
                        if ($indicadoresEstado->bln_semiarido) {
                            $municipioPrioritario .= 'Semiárido e ';
                        }

                        if ($indicadoresEstado->bln_fronteira) {
                            $municipioPrioritario .= 'Fronteiriço e ';
                        }

                        if ($indicadoresEstado->bln_ride) {
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
                        style="cursor: help;">Gini</span> <small class="text-muted">IPEA
                        {{ $indicadoresEstado->gini ? $indicadoresEstado->gini->num_ano : '-' }}</small>
                </th>

                <th class="borda_table_indicadores text-bold font-numero">

                    <span data-bs-toggle="popover" data-bs-trigger="hover focus"
                        data-bs-content="Gini menor, maior ou igual a {{ $giniReferencia }} conforme a Portaria do MIDR nº 1.825/2023"
                        style="cursor: help;">
                        @if (
                            $indicadoresEstado->gini &&
                                !is_null($indicadoresEstado->gini->vlr_gini) &&
                                $indicadoresEstado->gini->vlr_gini <= $giniReferencia)
                            <i class="fas fa-arrow-circle-down text-danger" style="font-size: 0.8rem !Important;"></i>
                            {!! $indicadoresEstado->gini->vlr_gini !!} <small class="text-muted">Gini menor ou igual a
                                {{ $giniReferencia }} em 2023</small>
                        @elseif (
                            $indicadoresEstado->gini &&
                                !is_null($indicadoresEstado->gini->vlr_gini) &&
                                $indicadoresEstado->gini->vlr_gini > $giniReferencia)
                            <i class="fas fa-arrow-circle-up text-success" style="font-size: 0.8rem !Important;"></i>
                            {!! $indicadoresEstado->gini->vlr_gini !!} <small class="text-muted">Gini maior do que
                                {{ $giniReferencia }} em 2023</small>
                        @else
                            -
                        @endif
                    </span>
                </th>

            </tr>

            @if ($indicadoresEstado->bln_pisf)
                <tr>

                    <th colspan="2" class="borda_table_indicadores">

                        <i class="fas fa-water text-primary"></i> Estado beneficiado pelo <span
                            class="text-bold">PISF</span>. <a class="d-print-none"
                            href="https://www.gov.br/mdr/pt-br/assuntos/seguranca-hidrica/projeto-sao-francisco"
                            target="_blank"><i class="fas fa-link"></i><span
                                style="font-size: 0.6rem!Important;">fonte</span></a>

                    </th>

                </tr>
            @endif

            @if ($novosPac)
                <tr>

                    <th colspan="2" class="borda_table_indicadores">

                        <img src="data:image/png;base64,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"
            alt="" style="height: 25px!Important;"> Estado com <span class="text-bold font-numero">{{ $novosPac->count() }}</span> Empreendimento(s) do Novo PAC

                    </th>

                </tr>
            @endif

        </table>

    </div>

</div>
