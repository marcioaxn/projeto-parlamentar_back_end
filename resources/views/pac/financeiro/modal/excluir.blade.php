<div class="modal fade"
    id="modalExcluirItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem) . $item }}"
    tabindex="-1" aria-labelledby="modalExcluirItensOrcamentariosFinanceirosLabel" aria-hidden="true"
    style="padding-top: 100px!Important;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#690404 0%,#a20404 100%);color: white;">
                <p class="modal-title text-white" id="modalExcluirItensOrcamentariosFinanceirosLabel">
                    Excluir
                    Orçamento/Financeiro para
                    {{ $acaoOrcamentaria }}</p>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 text-left text-danger text-bold mb-2"
                        style="font-size: 1rem!Important;7">
                        Você selecionou este item para excluir.
                    </div>

                    <hr>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4 text-left mb-1">

                        <label for="dsc_tipo_item_orcamentario_financeiro" class="form-label">Tipo</label>

                        <p>Crédito Disponível (Não Empenhado)</p>

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 text-left mb-1">

                        <label for="num_ano" class="form-label">Exercício
                            Financeiro (Ano)</label>

                        <p>{{ $valueItem->num_ano }}</p>

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 col-xl-2 col-xxl-2 text-left mb-1">

                        <label for="num_mes" class="form-label">Mês</label>

                        <p>{{ $valueItem->num_mes }}</p>

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 text-left mb-1">

                        <label for="num_rp" class="form-label">Resultado
                            Primário RP</label>

                        <p>{{ $valueItem->num_rp }}</p>

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4 text-left mb-1">

                        <label for="vlr_dinheiro" class="form-label">Valor</label>

                        <p>{{ converteValor('MYSQL', 'PTBR', $vlrItem) }}</p>

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 col-xl-8 col-xxl-8 text-left mb-1">

                        <label for="txt_observacao" class="form-label">Observação</label>

                        <p>{{ $txtObservacao }}</p>

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 text-left text-danger text-bold mb-2"
                        style="font-size: 1rem!Important;7">
                        Quer realmente excluir?
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="submitButton" class="btn btn-danger btn-sm" data-bs-dismiss="modal"
                    onclick="javascript: excluirOrcamentarioFinanceiro('{{ $table }}', '{{ $codItem }}'); location.reload();">Sim,
                    excluir</button>
            </div>
        </div>
    </div>
</div>
