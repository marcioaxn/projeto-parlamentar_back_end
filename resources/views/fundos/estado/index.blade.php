@if (!empty($fundosResumo))
    <div class="row pl-1">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 table-responsive">

            <table id="tableFundos" class="table table-sm mt-3 dt-responsive table-striped" cellspacing="0"
                style="width: 100% !Important;">

                <thead>
                    <tr>
                        <th class="">
                            Município
                        </th>
                        <th class="">
                            Fundo
                        </th>
                        <th class="">
                            Tipo
                        </th>
                        <th class="">
                            Setor
                        </th>
                        <th class="text-left">
                            Programa
                        </th>
                        <th>
                            Porte
                        </th>
                        <th>
                            Ano
                        </th>
                        <th>
                            Quantidade de Operações
                        </th>
                        <th class="text-right">
                            Valor do Saldo da Carteira
                        </th>
                        <th class="text-right">
                            Valor do Saldo em Atraso
                        </th>
                        <th class="text-right">
                            Valor Contratado
                        </th>
                        <th class="text-right">
                            Valor Desembolsado
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $contFundo = 1;
                    @endphp
                    @foreach ($fundosResumo as $key => $value)
                        <tr>
                            <td class="" style="">
                                {{ $value->nom_municipio }}
                            </td>
                            <td class="" style="">
                                {{ $value->nom_fundo }}
                            </td>
                            <td class="" style="">
                                {{ $value->nom_tipo_fundo }}
                            </td>
                            <td class="" style="">
                                {{ $value->dsc_setor }}
                            </td>
                            <td class="font-numero" style="">
                                {{ $value->dsc_programa }}
                            </td>
                            <td class="" style="">
                                {{ $value->dsc_porte }}
                            </td>
                            <td class="" style="">
                                {{ $value->num_ano }}
                            </td>
                            <td class="text-right font-numero" style="">
                                {{ formatarNumeroInteiro($value->num_quantidade_contratos) }}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->vlr_saldo_carteira) !!}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->vlr_saldo_atraso) !!}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->vlr_contratado) !!}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->vlr_desembolsado) !!}
                            </td>
                        </tr>
                        @php
                            $contFundo++;
                        @endphp
                    @endforeach

                </tbody>

            </table>

            <script type="text/javascript" charset="utf-8">
                $(document).ready(function() {
                    var table = $('#tableFundos').DataTable({
                        "language": {
                            "url": "{{ asset('Portuguese-Brasil.json') }}",
                            "decimal": ",",
                            "thousands": "."
                        },
                        "paging": true,
                        "lengthMenu": [
                            [5, 10, 25, 50, 100, -1],
                            ["5 ", "10 ", "25 ", "50 ", "100 ", "Todos "]
                        ],
                        responsive: true,
                        "autoWidth": true,
                        dom: 'Plfrtip', // Adicionado 'P' para incluir o seletor de quantidade de itens por página

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
                        dom: 'Blfrtip',
                        buttons: [{
                                extend: 'excelHtml5',
                                text: 'Excel',
                                visibility: true,
                                title: ''
                            },
                            {
                                extend: 'csv',
                                text: 'CSV',
                                charset: "UTF-8",
                                bom: true,
                                fieldSeparator: ';',
                                visibility: true,
                                title: ''
                            },
                            {
                                extend: 'colvis',
                                text: 'Colunas Visíveis',
                                visibility: true
                            }
                        ]
                    });
                });
            </script>

        </div>

    </div>
@endif
