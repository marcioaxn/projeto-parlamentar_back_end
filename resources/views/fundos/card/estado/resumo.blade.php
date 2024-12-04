<div class="card sticky-top border border-0 mt-0 mb-0 pt-0 pb-0">
    <div class="card-body cardTemas shadow-sm pl-2" style="cursor: default!Important;">
        Resumo dos Fundos Constitucionais de Financiamento <a class="d-print-none"
            href="https://www.gov.br/mdr/pt-br/assuntos/fundos-regionais-e-incentivos-fiscais/fundos-de-desenvolvimento-regional"
            target="_blank"><i class="fas fa-link"></i><span style="font-size: 0.6rem!Important;">fonte</span></a>
    </div>
</div>

<div class="row mt-1 pl-1">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-0 pt-0 mb-0 pb-0">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-0 pt-2 mb-0 pb-0">

                <table class="table table-sm table-borderless">

                    <thead>
                        <tr>
                            <th colspan="4" class="borda_table_indicadores text-bold bg-light"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Carteira
                            </th>
                        </tr>
                        <tr>
                            <th class="borda_table_indicadores text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Ano
                            </th>
                            <th class="borda_table_indicadores text-right text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Número de Operações
                            </th>
                            <th class="borda_table_indicadores text-right text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Saldo
                            </th>
                            <th class="borda_table_indicadores text-right text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                % de inadiplência
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <th class="borda_table_indicadores text-bold" style="width: 8%">
                                2023
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="width: 32.65%; font-size: 0.8rem !Important;">
                                {!! formatarNumeroInteiro($fundoResumo->num_quantidade_carteira_2023) !!}
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="width: 32.65%; font-size: 0.8rem !Important;">
                                {!! prettify_numbers($fundoResumo->vlr_saldo_carteira_2023) !!}
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="width: 32.65%; font-size: 0.8rem !Important;">
                                {!! converteValor('MYSQL', 'PTBR', $fundoResumo->prc_inadiplencia_2023) . '%' !!}
                            </th>
                        </tr>

                        <tr>
                            <th class="borda_table_indicadores text-bold">
                                2024
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="font-size: 0.8rem !Important;">

                                {!! formatarNumeroInteiro($fundoResumo->num_quantidade_carteira_2024) !!}

                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="font-size: 0.8rem !Important;">
                                {!! prettify_numbers($fundoResumo->vlr_saldo_carteira_2024) !!}
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="font-size: 0.8rem !Important;">
                                {!! converteValor('MYSQL', 'PTBR', $fundoResumo->prc_inadiplencia_2024) . '%' !!}
                            </th>
                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 table-responsive mt-0 pt-2 mb-0 pb-0">

                <table class="table table-sm table-borderless">

                    <thead>
                        <tr>
                            <th colspan="4" class="borda_table_indicadores text-bold bg-light"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Contratações
                            </th>
                        </tr>
                        <tr>
                            <th class="borda_table_indicadores text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Ano
                            </th>
                            <th class="borda_table_indicadores text-right text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Número de Operações
                            </th>
                            <th class="borda_table_indicadores text-right text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Valor Contratado
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <th class="borda_table_indicadores text-bold" style="width: 8%">
                                2023
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="width: 32.65%; font-size: 0.8rem !Important;">
                                {!! formatarNumeroInteiro($fundoResumo->num_quantidade_contratos_2023) !!}
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="width: 32.65%; font-size: 0.8rem !Important;">
                                {!! prettify_numbers($fundoResumo->vlr_contratado_2023) !!}
                            </th>
                        </tr>

                        <tr>
                            <th class="borda_table_indicadores text-bold" style="width: 8%">
                                2024
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="font-size: 0.8rem !Important;">
                                {!! formatarNumeroInteiro($fundoResumo->num_quantidade_contratos_2024) !!}
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="font-size: 0.8rem !Important;">
                                {!! prettify_numbers($fundoResumo->vlr_contratado_2024) !!}
                            </th>
                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 table-responsive mt-0 pt-2 mb-0 pb-0">

                <table class="table table-sm table-borderless">

                    <thead>
                        <tr>
                            <th colspan="4" class="borda_table_indicadores text-bold bg-light"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Desembolsos
                            </th>
                        </tr>
                        <tr>
                            <th class="borda_table_indicadores text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Ano
                            </th>
                            <th class="borda_table_indicadores text-right text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Número de Operações
                            </th>
                            <th class="borda_table_indicadores text-right text-bold"
                                style="font-size: 0.8rem !Important; vertical-align: middle!Important;">
                                Valor Desembolsado
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <th class="borda_table_indicadores text-bold" style="width: 8%">
                                2023
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="width: 32.65%; font-size: 0.8rem !Important;">
                                {!! formatarNumeroInteiro($fundoResumo->num_quantidade_desembolso_2023) !!}
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="width: 32.65%; font-size: 0.8rem !Important;">
                                {!! prettify_numbers($fundoResumo->vlr_desembolsado_2023) !!}
                            </th>
                        </tr>

                        <tr>
                            <th class="borda_table_indicadores text-bold" style="width: 8%">
                                2024
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="font-size: 0.8rem !Important;">
                                {!! formatarNumeroInteiro($fundoResumo->num_quantidade_desembolso_2024) !!}
                            </th>
                            <th class="borda_table_indicadores text-right font-numero"
                                style="font-size: 0.8rem !Important;">
                                {!! prettify_numbers($fundoResumo->vlr_desembolsado_2024) !!}
                            </th>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
