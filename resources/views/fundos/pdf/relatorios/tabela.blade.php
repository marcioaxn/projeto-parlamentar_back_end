@php

    $fco = false;

    $totalFcoNumAuantidade = 0;
    $totalFcoVlrSaldoCarteira = 0;
    $totalFcoVlrSaldoAtraso = 0;
    $totalFcoVlrContratado = 0;
    $totalFcoVlrDesembolsado = 0;

    $fne = false;

    $totalFneNumAuantidade = 0;
    $totalFneVlrSaldoCarteira = 0;
    $totalFneVlrSaldoAtraso = 0;
    $totalFneVlrContratado = 0;
    $totalFneVlrDesembolsado = 0;

    $fno = false;

    $totalFnoNumAuantidade = 0;
    $totalFnoVlrSaldoCarteira = 0;
    $totalFnoVlrSaldoAtraso = 0;
    $totalFnoVlrContratado = 0;
    $totalFnoVlrDesembolsado = 0;

    $carteira = false;

    $totalCarteiraNumAuantidade = 0;
    $totalCarteiraVlrSaldoCarteira = 0;
    $totalCarteiraVlrSaldoAtraso = 0;
    $totalCarteiraVlrContratado = 0;
    $totalCarteiraVlrDesembolsado = 0;

    $contratacoes = false;

    $totalContratacoesNumAuantidade = 0;
    $totalContratacoesVlrSaldoCarteira = 0;
    $totalContratacoesVlrSaldoAtraso = 0;
    $totalContratacoesVlrContratado = 0;
    $totalContratacoesVlrDesembolsado = 0;

    $desembolso = false;

    $totalDesembolsoNumAuantidade = 0;
    $totalDesembolsoVlrSaldoCarteira = 0;
    $totalDesembolsoVlrSaldoAtraso = 0;
    $totalDesembolsoVlrContratado = 0;
    $totalDesembolsoVlrDesembolsado = 0;

    foreach ($getTabFundosConsolidadaFinanciamentoFinalidade as $value) {
        // Início do cálculo em relação ao FCO
        if (verificaPalavra($value->dsc_tipo_fundo, 'FCO') || verificaPalavra($value->dsc_tipo_fundo, 'fco')) {
            $fco = true;

            $totalFcoNumAuantidade += $value->num_quantidade_contratos;
            $totalFcoVlrSaldoCarteira += $value->vlr_saldo_carteira;
            $totalFcoVlrSaldoAtraso += $value->vlr_saldo_atraso;
            $totalFcoVlrContratado += $value->vlr_contratado;
            $totalFcoVlrDesembolsado += $value->vlr_desembolsado;
        }
        // Fim do cálculo em relação ao FCO

        // Início do cálculo em relação ao FNE
        if (verificaPalavra($value->dsc_tipo_fundo, 'FNE') || verificaPalavra($value->dsc_tipo_fundo, 'fne')) {
            $fne = true;

            $totalFneNumAuantidade += $value->num_quantidade_contratos;
            $totalFneVlrSaldoCarteira += $value->vlr_saldo_carteira;
            $totalFneVlrSaldoAtraso += $value->vlr_saldo_atraso;
            $totalFneVlrContratado += $value->vlr_contratado;
            $totalFneVlrDesembolsado += $value->vlr_desembolsado;
        }
        // Fim do cálculo em relação ao FNE

        // Início do cálculo em relação ao FNO
        if (verificaPalavra($value->dsc_tipo_fundo, 'FNO') || verificaPalavra($value->dsc_tipo_fundo, 'fno')) {
            $fno = true;

            $totalFnoNumAuantidade += $value->num_quantidade_contratos;
            $totalFnoVlrSaldoCarteira += $value->vlr_saldo_carteira;
            $totalFnoVlrSaldoAtraso += $value->vlr_saldo_atraso;
            $totalFnoVlrContratado += $value->vlr_contratado;
            $totalFnoVlrDesembolsado += $value->vlr_desembolsado;
        }
        // Fim do cálculo em relação ao FNO

        // Início do cálculo em relação ao CARTEIRA
        if (verificaPalavra($value->dsc_tipo_fundo, 'CARTEIRA') || verificaPalavra($value->dsc_tipo_fundo, 'carteira')) {
            $carteira = true;

            $totalCarteiraNumAuantidade += $value->num_quantidade_contratos;
            $totalCarteiraVlrSaldoCarteira += $value->vlr_saldo_carteira;
            $totalCarteiraVlrSaldoAtraso += $value->vlr_saldo_atraso;
            $totalCarteiraVlrContratado += $value->vlr_contratado;
            $totalCarteiraVlrDesembolsado += $value->vlr_desembolsado;
        }
        // Fim do cálculo em relação ao CARTEIRA

        // Início do cálculo em relação ao CONTRATACOES
        if (
            verificaPalavra($value->dsc_tipo_fundo, 'CONTRATACOES') ||
            verificaPalavra($value->dsc_tipo_fundo, 'contratacoes') ||
            verificaPalavra($value->dsc_tipo_fundo, 'contratações')
        ) {
            $contratacoes = true;

            $totalContratacoesNumAuantidade += $value->num_quantidade_contratos;
            $totalContratacoesVlrSaldoCarteira += $value->vlr_saldo_carteira;
            $totalContratacoesVlrSaldoAtraso += $value->vlr_saldo_atraso;
            $totalContratacoesVlrContratado += $value->vlr_contratado;
            $totalContratacoesVlrDesembolsado += $value->vlr_desembolsado;
        }
        // Fim do cálculo em relação ao CONTRATACOES

        // Início do cálculo em relação ao DESEMBOLSOS
        if (
            verificaPalavra($value->dsc_tipo_fundo, 'DESEMBOLSOS') ||
            verificaPalavra($value->dsc_tipo_fundo, 'DESEMBOLSO') ||
            verificaPalavra($value->dsc_tipo_fundo, 'desembolsos') ||
            verificaPalavra($value->dsc_tipo_fundo, 'desembolso')
        ) {
            $desembolso = true;

            $totalDesembolsoNumAuantidade += $value->num_quantidade_contratos;
            $totalDesembolsoVlrSaldoCarteira += $value->vlr_saldo_carteira;
            $totalDesembolsoVlrSaldoAtraso += $value->vlr_saldo_atraso;
            $totalDesembolsoVlrContratado += $value->vlr_contratado;
            $totalDesembolsoVlrDesembolsado += $value->vlr_desembolsado;
        }
        // Fim do cálculo em relação ao DESEMBOLSOS
    }
@endphp

@if ($filtros)
    <br />
    <div id="nomeRelatorio">
        Filtro(s) utilizado(s)
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            @foreach ($filtros as $key => $value)
                <td>
                    {!! nomeCampoTabVisMdrNormalizado($key) . ': ' !!}

                    @php
                        $filtrosUtilizados = null;
                        foreach ($value as $filtro) {
                            $filtrosUtilizados .= $filtro . '; ';
                        }
                        $filtrosUtilizados = trim($filtrosUtilizados, '; ');
                    @endphp

                    <span style="font-weight: bold;">{!! $filtrosUtilizados !!}</span>

                </td>
            @endforeach
        </tr>
    </table>
    <br />
    <br />
@endif



{{-- Início da tabela resumo pelo Fundo --}}
<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="width: 45%; text-align:left; border: none; margin: 0px; padding: 0px;">
            <div id="nomeRelatorio">
                Resumo por localidade do Fundo
            </div>

            <table class="table" style="width: 98%;">

                <thead>
                    <tr>
                        <th style="width: 8%; text-align: left;">
                            Fundo
                        </th>
                        <th style="width: 7%; text-align: right;">
                            N. de Operações
                        </th>
                        <th style="width: 12%; text-align: right;">
                            Valor do Saldo da Carteira
                        </th>
                        <th style="width: 12%; text-align: right;">
                            Valor do Saldo em Atraso
                        </th>
                        <th style="width: 12%; text-align: right;">
                            Valor Contratado
                        </th>
                        <th style="width: 12%; text-align: right;">
                            Valor Desembolsado
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @if ($fco)
                        <tr>
                            <td>
                                FCO
                            </td>
                            <td style="text-align: right;">
                                {!! formatarNumeroInteiro($totalFcoNumAuantidade) !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFcoVlrSaldoCarteira) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFcoVlrSaldoAtraso) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFcoVlrContratado) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFcoVlrDesembolsado) ?? 0 !!}
                            </td>
                        </tr>
                    @endif

                    @if ($fne)
                        <tr>
                            <td>
                                FNE
                            </td>
                            <td style="text-align: right;">
                                {!! formatarNumeroInteiro($totalFneNumAuantidade) !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFneVlrSaldoCarteira) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFneVlrSaldoAtraso) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFneVlrContratado) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFneVlrDesembolsado) ?? 0 !!}
                            </td>
                        </tr>
                    @endif

                    @if ($fno)
                        <tr>
                            <td>
                                FNO
                            </td>
                            <td style="text-align: right;">
                                {!! formatarNumeroInteiro($totalFnoNumAuantidade) !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFnoVlrSaldoCarteira) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFnoVlrSaldoAtraso) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFnoVlrContratado) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalFnoVlrDesembolsado) ?? 0 !!}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! formatarNumeroInteiro($totalFcoNumAuantidade + $totalFneNumAuantidade + $totalFnoNumAuantidade) !!}
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! prettify_numbers($totalFcoVlrSaldoCarteira + $totalFneVlrSaldoCarteira + $totalFnoVlrSaldoCarteira) ?? 0 !!}
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! prettify_numbers($totalFcoVlrSaldoAtraso + $totalFneVlrSaldoAtraso + $totalFnoVlrSaldoAtraso) ?? 0 !!}
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! prettify_numbers($totalFcoVlrContratado + $totalFneVlrContratado + $totalFnoVlrContratado) ?? 0 !!}
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! prettify_numbers($totalFcoVlrDesembolsado + $totalFneVlrDesembolsado + $totalFnoVlrDesembolsado) ?? 0 !!}
                        </td>
                    </tr>

                </tbody>

            </table>
        </td>
        <td style="width: 45%; text-align:left; border: none; margin: 0px; padding: 0px;">
            <div id="nomeRelatorio">
                Resumo por tipo de Fundo
            </div>

            <table class="table" style="width: 98%;">

                <thead>
                    <tr>
                        <th style="width: 8%; text-align: left;">
                            Tipo de Fundo
                        </th>
                        <th style="width: 7%; text-align: right;">
                            N. de Operações
                        </th>
                        <th style="width: 12%; text-align: right;">
                            Valor do Saldo da Carteira
                        </th>
                        <th style="width: 12%; text-align: right;">
                            Valor do Saldo em Atraso
                        </th>
                        <th style="width: 12%; text-align: right;">
                            Valor Contratado
                        </th>
                        <th style="width: 12%; text-align: right;">
                            Valor Desembolsado
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @if ($carteira)
                        <tr>
                            <td>
                                CARTEIRA
                            </td>
                            <td style="text-align: right;">
                                {!! formatarNumeroInteiro($totalCarteiraNumAuantidade) !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalCarteiraVlrSaldoCarteira) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalCarteiraVlrSaldoAtraso) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalCarteiraVlrContratado) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalCarteiraVlrDesembolsado) ?? 0 !!}
                            </td>
                        </tr>
                    @endif

                    @if ($contratacoes)
                        <tr>
                            <td>
                                CONTRATAÇÕES
                            </td>
                            <td style="text-align: right;">
                                {!! formatarNumeroInteiro($totalContratacoesNumAuantidade) !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalContratacoesVlrSaldoCarteira) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalContratacoesVlrSaldoAtraso) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalContratacoesVlrContratado) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalContratacoesVlrDesembolsado) ?? 0 !!}
                            </td>
                        </tr>
                    @endif

                    @if ($desembolso)
                        <tr>
                            <td>
                                DESEMBOLSOS
                            </td>
                            <td style="text-align: right;">
                                {!! formatarNumeroInteiro($totalDesembolsoNumAuantidade) !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalDesembolsoVlrSaldoCarteira) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalDesembolsoVlrSaldoAtraso) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalDesembolsoVlrContratado) ?? 0 !!}
                            </td>
                            <td style="text-align: right;">
                                {!! prettify_numbers($totalDesembolsoVlrDesembolsado) ?? 0 !!}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! formatarNumeroInteiro(
                                $totalCarteiraNumAuantidade + $totalContratacoesNumAuantidade + $totalDesembolsoNumAuantidade,
                            ) !!}
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! prettify_numbers(
                                $totalCarteiraVlrSaldoCarteira + $totalContratacoesVlrSaldoCarteira + $totalDesembolsoVlrSaldoCarteira,
                            ) ?? 0 !!}
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! prettify_numbers(
                                $totalCarteiraVlrSaldoAtraso + $totalContratacoesVlrSaldoAtraso + $totalDesembolsoVlrSaldoAtraso,
                            ) ?? 0 !!}
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! prettify_numbers(
                                $totalCarteiraVlrContratado + $totalContratacoesVlrContratado + $totalDesembolsoVlrContratado,
                            ) ?? 0 !!}
                        </td>
                        <td style="font-weight: bold; text-align: right;">
                            {!! prettify_numbers(
                                $totalCarteiraVlrDesembolsado + $totalContratacoesVlrDesembolsado + $totalDesembolsoVlrDesembolsado,
                            ) ?? 0 !!}
                        </td>
                    </tr>

                </tbody>

            </table>
        </td>
    </tr>
</table>

{{-- Fim da tabela resumo pelo Fundo --}}
<br />
<div id="nomeRelatorio">
    Relatório consolidado por linha de financiamento e finalidade da operação
</div>
<table class="table" id="tableRelatorio" style="width: 100%; border-collapse: collapse;">

    <thead>
        <tr>
            @if ($columnsCount == 9)
                <th style="text-align: left;">
                    UF
                </th>
            @endif
            <th style="text-align: left;">
                Tipo
            </th>
            <th style="text-align: left;">
                Linha de financiamento
            </th>
            <th style="text-align: left;">
                Finalidade da operação
            </th>
            <th style="text-align: right;">
                N. de Operações
            </th>
            <th style="width: 12%; text-align: right;">
                Valor do Saldo da Carteira
            </th>
            <th style="width: 12%; text-align: right;">
                Valor do Saldo em Atraso
            </th>
            <th style="width: 12%; text-align: right;">
                Valor Contratado
            </th>
            <th style="width: 12%; text-align: right;">
                Valor Desembolsado
            </th>
        </tr>
    </thead>

    <tbody>

        @foreach ($getTabFundosConsolidadaFinanciamentoFinalidade as $value)
            <tr>
                @if ($columnsCount == 9)
                    <td>
                        {!! $value->sgl_uf !!}
                    </td>
                @endif
                <td>
                    {!! $value->dsc_tipo_fundo !!}
                </td>
                <td>
                    {!! $value->dsc_linha_financiamento !!}
                </td>
                <td>
                    {!! $value->dsc_finalidade_operacao !!}
                </td>
                <td style="text-align: right;">
                    {!! formatarNumeroInteiro($value->num_quantidade_contratos) !!}
                </td>
                <td style="text-align: right;">
                    {!! prettify_numbers($value->vlr_saldo_carteira) ?? 0 !!}
                </td>
                <td style="text-align: right;">
                    {!! prettify_numbers($value->vlr_saldo_atraso) ?? 0 !!}
                </td>
                <td style="text-align: right;">
                    {!! prettify_numbers($value->vlr_contratado) ?? 0 !!}
                </td>
                <td style="text-align: right;">
                    {!! prettify_numbers($value->vlr_desembolsado) ?? 0 !!}
                </td>
            </tr>
        @endforeach

    </tbody>

</table>
