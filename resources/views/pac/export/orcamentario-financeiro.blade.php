<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo PAC - {{ date('d-m-Y') }}</title>

</head>

<body>

    <table style="font-family: Calibri, Arial;">
        <thead>
            <tr>
                <th rowspan="2"
                    style="background-color: #002060; color: #FFFFFF; width: 156px; height: 75px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>AÇÃO ORÇAMENTÁRIA</strong>
                </th>
                <th rowspan="2"
                    style="background-color: #002060; color: #FFFFFF; width: 100px; height: 75px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>ID PAC</strong>
                </th>
                <th rowspan="2"
                    style="background-color: #002060; color: #FFFFFF; width: 100px; height: 75px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>RESPONSÁVEL</strong>
                </th>
                <th rowspan="2"
                    style="background-color: #002060; color: #FFFFFF; width: 300px; height: 75px; text-align: left; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>EMPREENDIMENTO PAC</strong>
                </th>
                <th rowspan="2"
                    style="background-color: #c27ba0; color: #FFFFFF; width: 179px; height: 75px; text-align: center; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>CRÉDITO DISPONÍVEL (NÃO EMPENHADO)</strong>
                </th>
                <th rowspan="2"
                    style="background-color: #002060; color: #FFFFFF; width: 179px; height: 75px; text-align: center; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>SALDO EMPENHADO</strong>
                </th>
                <th rowspan="2"
                    style="background-color: #3C7D22; color: #FFFFFF; width: 179px; height: 75px; text-align: center; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>SUPLEMENTAÇÃO ORÇAMENTÁRIA ({{ $ano }})</strong>
                </th>
                <th rowspan="2"
                    style="background-color: #FF0000; color: #FFFFFF; width: 179px; height: 75px; text-align: center; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                    valign="center">
                    <strong>NECESSIDADE FINANCEIRA ({{ $ano }})</strong>
                </th>

                <!-- Gerando os meses dinamicamente -->
                @for ($mes = date('m'); $mes <= 12; $mes++)
                    <th colspan="2"
                        style="background-color: #7030A0; color: #FFFFFF; width: 358px; height: 75px; text-align: center; font-size: 11px; border: 1px solid #696969; word-wrap:break-word;"
                        valign="center">
                        <strong>{{ mesNumeralParaExtenso($mes) }} {{ $ano }}</strong>
                    </th>
                @endfor
            </tr>

            <tr>
                @for ($mes = date('m'); $mes <= 12; $mes++)
                    <th style="background-color: #7030A0; color: #FFFFFF; width: 179px; height: 21px; text-align: center; font-size: 11px; border: 1px solid #696969;"
                        valign="center">
                        <strong>RP 2</strong>
                    </th>
                    <th style="background-color: #7030A0; color: #FFFFFF; width: 179px; height: 21px; text-align: center; font-size: 11px; border: 1px solid #696969;"
                        valign="center">
                        <strong>RP 3</strong>
                    </th>
                @endfor
            </tr>
        </thead>

        <tbody>

            @php
                $totais = array_fill_keys(
                    array_filter($matrizColunas, function ($col) {
                        return strpos($col, 'vlr') === 0;
                    }),
                    0,
                );
            @endphp

            @foreach ($getResumoPorAnoOrcamentarioFinanceiro as $value)
                <tr>
                    @foreach ($matrizColunas as $coluna)
                        @php
                            $isValorColumn = strpos($coluna, 'vlr') === 0;

                            $isRP = strpos($coluna, 'rp') === strlen($coluna) - 2;

                            $formatValue = function ($val) {
                                return $val;
                            };
                        @endphp

                        @if ($isValorColumn && !$isRP)
                            <td style="border: 1px dotted gray; height: 37px; text-align: right; font-weight: bold;"
                                valign="center">
                                {{ $formatValue($value->$coluna) }}</td>
                            @php $totais[$coluna] += $value->$coluna ?? 0; @endphp
                        @elseif ($isValorColumn && $isRP)
                            <td style="border: 1px dotted gray; text-align: right;" valign="center">
                                {{ $formatValue($value->$coluna) }}
                            </td>
                            @php $totais[$coluna] += $value->$coluna ?? 0; @endphp
                        @else
                            <td style="border: 1px dotted gray; text-align: left; word-wrap:break-word;"
                                valign="center">{{ $value->$coluna }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach

            <!-- Linha de total -->
            <tr>
                <td colspan="4"
                    style="background-color: #fffae0; color: #0c0a2e; height: 75px; text-align: left; font-size: 12px; font-weight: bold; border: 1px dotted gray;"
                    valign="center">
                    TOTAL</td>
                @foreach ($matrizColunas as $coluna)
                    @if (strpos($coluna, 'vlr') === 0)
                        <td style="background-color: #fffae0; color: #0c0a2e; height: 75px; text-align: right; font-size: 12px; font-weight: bold; border: 1px dotted gray;"
                            valign="center">
                            {{ $totais[$coluna] }}</td>
                    @endif
                @endforeach
            </tr>
        </tbody>
    </table>


</body>

</html>
