<div class="card sticky-top border border-0 mt-0 mb-0 pt-0 pb-0">
    <div class="card-body cardTemas shadow-sm pl-2" style="cursor: default!Important;">
        Resumo da Carteira ativa por Área de Investimento <a class="d-print-none"
            href="https://formulariopainel.mdr.gov.br/aplicativo/{{ $sgl_uf . '/' . $getMunicipio->nom_municipio_sem_formatacao }}"
            target="_blank"><i class="fas fa-link"></i><span style="font-size: 0.6rem!Important;">fonte</span></a>
    </div>
</div>

<div class="row mt-1 pl-1">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-0 pt-0 mb-0 pb-0" style="width: 95% !Important;">

        <div id="chart" class="mt-3" style="height: 229px !Important; width: 95% !Important;"></div>

        @php
            $categories = null;
            $quantidade_emp = 0;
            $somaValorInvestimento = 0;
            $somaValorPago = 0;
        @endphp

        @foreach ($resumoAreaInvestimento as $areaInvestimento => $valores)
            @foreach ($valores as $nomeCampoInvestimento => $valueCampoInvestimento)
                @if ($nomeCampoInvestimento === 'quantidade_emp')
                    @php
                        $quantidade_emp = formatarNumeroInteiro($valueCampoInvestimento);
                    @endphp
                @elseif ($nomeCampoInvestimento === 'soma_valor_investimento')
                    @php
                        $somaValorInvestimento .= $valueCampoInvestimento . ',';
                    @endphp
                @else
                    @php
                        $somaValorPago .= $valueCampoInvestimento . ',';
                    @endphp
                @endif
            @endforeach
            @php
                $categories .= "'" . $areaInvestimento . ' (' . $quantidade_emp . ")',";
            @endphp
        @endforeach
        @php
            $categories = trim($categories, ',');
            $somaValorInvestimento = trim($somaValorInvestimento, ',');
            $somaValorPago = trim($somaValorPago, ',');
        @endphp

        <script>
            var options = {
                series: [{
                        name: 'Valor de Investimento',
                        group: 'budget',
                        data: [{{ $somaValorInvestimento }}]
                    },
                    {
                        name: 'Valor Pago',
                        group: 'budget',
                        data: [{{ $somaValorPago }}]
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 212,
                    stacked: false,
                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val) => {
                        return prettifyNumbers(val)
                    },
                    offsetX: 1,
                    offsetY: -19,
                    style: {
                        fontSize: '10px',
                        colors: ["#304758"]
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 5,
                        columnWidth: '57px',
                        dataLabels: {
                            position: 'top', // top, center, bottom
                        },
                    }
                },
                xaxis: {
                    categories: [
                        {!! $categories !!}
                    ]
                },
                fill: {
                    opacity: 1
                },
                colors: ['#008FFB', '#80c7fd', '#80f1cb', '#00E396'],
                yaxis: {
                    labels: {
                        formatter: (val) => {
                            return prettifyNumbers(val)
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        </script>

    </div>

    <script>
        function prettifyNumbers(number = '0', decimals = 2, intOnly = false) {
            number = String(number);
            let symbol = null;

            // Yotta: 1000000000000000000000000
            if (Number(number) > 99999999999999999999999) {
                number = (Number(number) / 1000000000000000000000000).toFixed(decimals);
                symbol = 'Y';
            }
            // Zetta: 1000000000000000000000
            else if (Number(number) > 999999999999999999999) {
                number = (Number(number) / 1000000000000000000000).toFixed(decimals);
                symbol = 'Z';
            }
            // Exa : 1000000000000000000
            else if (Number(number) > 999999999999999999) {
                number = (Number(number) / 1000000000000000000).toFixed(decimals);
                symbol = 'E';
            }
            // Peta : 1000000000000000
            else if (Number(number) > 999999999999999) {
                number = (Number(number) / 1000000000000000).toFixed(decimals);
                symbol = 'P';
            }
            // Tera : 1000000000000
            else if (Number(number) > 999999999999) {
                number = (Number(number) / 1000000000000).toFixed(decimals);
                symbol = 'T';
            }
            // Giga : 1000000000
            else if (Number(number) > 999999999) {
                number = (Number(number) / 1000000000).toFixed(decimals);

                const firstNumber = Number(number.split('.')[0]);
                symbol = firstNumber > 1 ? ' Bilhões' : ' Bilhão';
            }
            // Mega : 1000000
            else if (Number(number) > 999999) {
                number = (Number(number) / 1000000).toFixed(decimals);

                const firstNumber = Number(number.split('.')[0]);
                symbol = firstNumber > 1 ? ' Milhões' : ' Milhão';
            }
            // Kilo : 1000
            else if (Number(number) > 999) {
                number = (Number(number) / 1000).toFixed(decimals);
                symbol = ' Mil';
            } else {
                number = 0;
                symbol = ' ';
            }

            // Retorna apenas o número inteiro
            if (intOnly) {
                return parseInt(number) + symbol;
            }

            // Retorna o número e o símbolo
            return number + symbol;
        }
    </script>

</div>
