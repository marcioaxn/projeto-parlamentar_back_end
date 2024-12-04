@php
    $numMes = $numMes ?? null;
    $numRp = $numRp ?? null;
@endphp

<div class="modal fade"
    id="modalEditarItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . $codItem . $item }}"
    tabindex="-1" aria-labelledby="modalEditarItensOrcamentariosFinanceirosLabel" aria-hidden="true"
    style="padding-top: 100px!Important;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#16537e 0%,#6fa8dc 100%);color: white;">
                <p class="modal-title text-white" id="modalEditarItensOrcamentariosFinanceirosLabel">
                    Editar
                    Orçamento/Financeiro para
                    {{ $acaoOrcamentaria }}</p>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4 mb-4">

                        <label for="dsc_tipo_item_orcamentario_financeiro" class="form-label">Tipo</label>

                        {!! Form::select(
                            'dsc_tipo_item_orcamentario_financeiro',
                            $tiposItemOrcamentarioFinanceiro,
                            $dscTipoItemOrcamentarioFinanceiro,
                            [
                                'class' => 'form-control text-dark',
                                'style' => 'cursor: pointer; width: 100% !Important;',
                                'id' => 'dsc_tipo_item_orcamentario_financeiro_' . $acaoOrcamentaria . $codItem,
                                'autocomplete' => 'off',
                                'placeholder' => 'Selecione',
                                'disabled' => 'disabled',
                                'onchange' => "javascript: hiddenViseble(this.value, '$acaoOrcamentaria');",
                            ],
                        ) !!}

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-4">

                        <label for="num_ano" class="form-label">Exercício
                            Financeiro (Ano)</label>

                        {!! Form::select('num_ano', $anos, $valueItem->num_ano, [
                            'class' => 'form-control text-dark',
                            'style' => 'cursor: pointer; width: 100% !Important;',
                            'id' => 'num_ano_' . $acaoOrcamentaria . $codItem,
                            'autocomplete' => 'off',
                            'placeholder' => 'Selecione',
                        ]) !!}

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 col-xl-2 col-xxl-2 mb-4">

                        <label for="num_mes" class="form-label">Mês</label>

                        @if ($item != 'nf')
                            {!! Form::select('num_mes', $meses, null, [
                                'class' => 'form-control text-dark',
                                'style' => 'cursor: pointer; width: 100% !Important;',
                                'id' => 'num_mes_' . $acaoOrcamentaria . $codItem,
                                'autocomplete' => 'off',
                                'placeholder' => 'Selecione',
                                'disabled' => 'disabled',
                            ]) !!}
                        @else
                            {!! Form::select('num_mes', $meses, $numMes, [
                                'class' => 'form-control text-dark',
                                'style' => 'cursor: pointer; width: 100% !Important;',
                                'id' => 'num_mes_' . $acaoOrcamentaria . $codItem,
                                'autocomplete' => 'off',
                                'placeholder' => 'Selecione',
                            ]) !!}
                        @endif

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-4">

                        <label for="num_rp" class="form-label">Resultado
                            Primário RP</label>

                        @if ($item != 'nf')
                            {!! Form::select('num_rp', $rps, null, [
                                'class' => 'form-control text-dark',
                                'style' => 'cursor: pointer; width: 100% !Important;',
                                'id' => 'num_rp_' . $acaoOrcamentaria . $codItem,
                                'autocomplete' => 'off',
                                'placeholder' => 'Selecione',
                                'disabled' => 'disabled',
                            ]) !!}
                        @else
                            {!! Form::select('num_rp', $rps, $numRp, [
                                'class' => 'form-control text-dark',
                                'style' => 'cursor: pointer; width: 100% !Important;',
                                'id' => 'num_rp_' . $acaoOrcamentaria . $codItem,
                                'autocomplete' => 'off',
                                'placeholder' => 'Selecione',
                            ]) !!}
                        @endif



                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4 mb-4">

                        <label for="vlr_dinheiro" class="form-label">Valor</label>

                        {!! Form::text('vlr_dinheiro', converteValor('MYSQL', 'PTBR', $vlrItem), [
                            'class' => 'form-control text-dark text-right mascara-dinheiro-soma font-numero',
                            'id' => 'vlr_dinheiro_' . $acaoOrcamentaria . $codItem,
                            'autocomplete' => 'off',
                        ]) !!}

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 col-xl-8 col-xxl-8 mb-4">

                        <label for="txt_observacao" class="form-label">Observação</label>

                        {!! Form::textarea('txt_observacao', $txtObservacao, [
                            'class' => 'form-control text-dark',
                            'id' => 'txt_observacao_' . $acaoOrcamentaria . $codItem,
                            'rows' => 2,
                            'cols' => 50,
                        ]) !!}

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-2">

                        <p class="text-bold" style="font-size: 0.8rem!Important;">
                            Observação:</p>

                        <p style="font-size: 0.8rem!Important;">
                            Ao selecionar o Tipo <span class="text-bold">Necessidade
                                Financeira</span> será
                            necessário o
                            preenchimento dos campos de
                            <span class="text-bold">Mês</span>
                            e do <span class="text-bold">Resultado
                                Primário RP</span>.
                        </p>

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">

                        <p class="text-bold" style="font-size: 0.8rem!Important;">
                            Legenda:</p>

                        <table class="table table-sm">
                            <tbody>
                                <thead>
                                    <tr>
                                        <th style="font-size: 0.8rem!Important;">
                                            Resultado
                                            Primário RP
                                        </th>
                                    </tr>
                                </thead>
                                @foreach ($rpsLegenda as $rpLegenda)
                                    <tr>
                                        <th class="text-muted" style="font-size: 0.8rem!Important;">
                                            {{ $rpLegenda->dsc_resultado_primario }}
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>

            </div>
            <div class="modal-footer">
                {!! Form::hidden('cod_iten', $codItem, [
                    'id' => 'cod_item_' . $acaoOrcamentaria . $codItem,
                ]) !!}
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="submitButton" class="btn btn-primary btn-sm" data-bs-dismiss="modal"
                    onclick="javascript: gravarOrcamentarioFinanceiro('{{ $acaoOrcamentaria }}', '{{ $codItem }}');">Editar</button>
            </div>
        </div>
    </div>
</div>
