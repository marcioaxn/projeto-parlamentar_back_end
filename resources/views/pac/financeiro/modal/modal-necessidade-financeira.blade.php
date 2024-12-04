<div class="modal fade" id="modalAdicionarNecessidadeFinanceira{{ $acaoOrcamentaria }}" tabindex="-1"
    aria-labelledby="modalAdicionarNecessidadeFinanceiraLabel" aria-hidden="true" style="padding-top: 100px!Important;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#610774 0%,#7d0c94 100%);color: white;">
                <p class="modal-title text-white" id="modalAdicionarNecessidadeFinanceiraLabel">Cadastrar
                    Necessidade Financeira para {{ $acaoOrcamentaria }}</p>
            </div>
            <div class="modal-body">

                <div class="row">

                    {!! Form::hidden('dsc_tipo_item_orcamentario_financeiro', 'Necessidade Financeira', [
                        'id' => 'dsc_tipo_item_orcamentario_financeiro_' . $acaoOrcamentaria,
                    ]) !!}

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-4">

                        <label for="num_ano" class="form-label">Exercício Financeiro (Ano)</label>

                        {!! Form::select('num_ano', $anos, date('Y'), [
                            'class' => 'form-control text-dark',
                            'style' => 'cursor: pointer; width: 100% !Important;',
                            'id' => 'num_ano_' . $acaoOrcamentaria,
                            'autocomplete' => 'off',
                            'placeholder' => 'Selecione',
                        ]) !!}

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 col-xl-2 col-xxl-2 mb-4">

                        <label for="num_mes" class="form-label">Mês</label>

                        {!! Form::select('num_mes', $meses, null, [
                            'class' => 'form-control text-dark',
                            'style' => 'cursor: pointer; width: 100% !Important;',
                            'id' => 'num_mes_' . $acaoOrcamentaria,
                            'autocomplete' => 'off',
                            'placeholder' => 'Selecione',
                        ]) !!}

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-4">

                        <label for="num_rp" class="form-label">Resultado Primário RP</label>

                        {!! Form::select('num_rp', $rps, null, [
                            'class' => 'form-control text-dark',
                            'style' => 'cursor: pointer; width: 100% !Important;',
                            'id' => 'num_rp_' . $acaoOrcamentaria,
                            'autocomplete' => 'off',
                            'placeholder' => 'Selecione',
                        ]) !!}

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4 mb-4">

                        <label for="vlr_dinheiro" class="form-label">Valor</label>

                        {!! Form::text('vlr_dinheiro', null, [
                            'class' => 'form-control text-dark text-right mascara-dinheiro-soma font-numero',
                            'id' => 'vlr_dinheiro_' . $acaoOrcamentaria,
                            'autocomplete' => 'off',
                        ]) !!}

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">

                        <label for="txt_observacao" class="form-label">Observação</label>

                        {!! Form::textarea('txt_observacao', null, [
                            'class' => 'form-control text-dark',
                            'id' => 'txt_observacao_' . $acaoOrcamentaria,
                            'rows' => 2,
                            'cols' => 50,
                        ]) !!}

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">

                        <p class="text-bold" style="font-size: 0.8rem!Important;">Legenda:</p>

                        <table class="table table-sm">
                            <tbody>
                                <thead>
                                    <tr>
                                        <th style="font-size: 0.8rem!Important;">Resultado Primário RP</th>
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
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="submitButton" class="btn btn-primary btn-sm" data-bs-dismiss="modal"
                    onclick="javascript: gravarOrcamentarioFinanceiro('{{ $acaoOrcamentaria }}', ''); location.reload();">Salvar</button>
            </div>
        </div>
    </div>
</div>
