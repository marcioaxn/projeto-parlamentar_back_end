<div class="modal fade" id="modalAdicionarSaldoEmpenhado{{ $acaoOrcamentaria }}" tabindex="-1"
    aria-labelledby="modalAdicionarSaldoEmpenhadoLabel" aria-hidden="true" style="padding-top: 100px!Important;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#257ab3 0%, #6dbbef 100%);color: white;">
                <p class="modal-title text-white" id="modalAdicionarSaldoEmpenhadoLabel">Cadastrar
                    Saldo Empenhado para {{ $acaoOrcamentaria }}</p>
            </div>
            <div class="modal-body">

                <div class="row">

                    {!! Form::hidden('dsc_tipo_item_orcamentario_financeiro', 'Saldo Empenhado', [
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

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-4">

                        <label for="vlr_dinheiro" class="form-label">Valor</label>

                        {!! Form::text('vlr_dinheiro', null, [
                            'class' => 'form-control text-dark text-right mascara-dinheiro-soma font-numero',
                            'id' => 'vlr_dinheiro_' . $acaoOrcamentaria,
                            'autocomplete' => 'off',
                        ]) !!}

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">

                        <label for="txt_observacao" class="form-label">Observação</label>

                        {!! Form::textarea('txt_observacao', null, [
                            'class' => 'form-control text-dark',
                            'id' => 'txt_observacao_' . $acaoOrcamentaria,
                            'rows' => 2,
                            'cols' => 50,
                        ]) !!}

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
