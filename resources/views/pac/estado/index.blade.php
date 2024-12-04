@if (!empty($pac))
    <div class="row pl-1">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 table-responsive">

            <table id="tablePac" class="table table-sm mt-3 dt-responsive table-striped" cellspacing="0"
                style="width: 100% !Important;">

                <thead>
                    <tr>
                        <th class="">
                            Código
                        </th>
                        <th class="">
                            Nome do Empreendimento
                        </th>
                        <th class="">
                            Eixo
                        </th>
                        <th class="">
                            Subeixo
                        </th>
                        <th class="text-left">
                            UF
                        </th>
                        <th>
                            Início do empreendimento
                        </th>
                        <th>
                            Previsão de conclusão
                        </th>
                        <th>
                            Código e Plano Orçamentário
                        </th>
                        <th>
                            Fase
                        </th>
                        <th class="text-right">
                            % de execução física
                        </th>
                        <th>
                            Paralisado
                        </th>
                        <th class="text-right">
                            Valor empenhado da LOA 2023
                        </th>
                        <th class="text-right">
                            Valor pago/repassado total (LOA+RAP)
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $contTse = 1;
                    @endphp
                    @foreach ($pac as $key => $value)
                        <tr>
                            <td class="font-numero" style="">
                                {!! $value->cod_pac !!}
                            </td>
                            <td class="" style="">
                                {!! $value->nom_empreendimento_divulgacao !!}
                            </td>
                            <td class="" style="">
                                {!! $value->dsc_eixo !!}
                            </td>
                            <td class="" style="">
                                {!! $value->dsc_subeixo !!}
                            </td>
                            <td class="" style="">
                                {!! $value->sgl_uf !!}
                            </td>
                            <td class="font-numero" style="">
                                {!! formatarDataComCarbonParaBR($value->dte_inicio_empreendimento) !!}
                            </td>
                            <td class="font-numero" style="">
                                {!! formatarDataComCarbonParaBR($value->dte_previsao_conclusao_empreendimento) !!}
                            </td>
                            <td class="" style="">
                                @php
                                    $codPlanoOrcamentario = null;
                                    $codPlanoOrcamentario .= $value->cod_acao_orcamentaria;
                                    isset($value->cod_plano_orcamentario) && !is_null($value->cod_plano_orcamentario) && $value->cod_plano_orcamentario != '' ? ($codPlanoOrcamentario .= ' - ' . $value->cod_plano_orcamentario) : '';
                                @endphp
                                {!! $codPlanoOrcamentario !!}
                            </td>
                            <td class="" style="">
                                {!! $value->dsc_fase !!}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->prc_execucao_fisica) !!}
                            </td>
                            <td class="" style="">
                                {!! $value->bln_paralisado !!}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->vlr_ogu_empenhado_loa_2023) !!}
                            </td>
                            <td class="text-right font-numero" style="">
                                {!! converteValor('MYSQL', 'PTBR', $value->vlr_ogu_pago_repassado_total_loa_mais_rap) !!}
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
                    var table = $('#tablePac').DataTable({
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

                        fixedHeader: {
                            header: true,
                            headerOffset: $('#header').outerHeight() - 12
                        },
                        "order": [
                            [1, "asc"]
                        ],
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
