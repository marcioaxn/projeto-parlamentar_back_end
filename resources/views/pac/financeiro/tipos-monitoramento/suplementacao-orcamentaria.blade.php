<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 font-numero mb-2">
    <div class="rounded p-2 mb-2" style="background-color: #338114; color: #FFFFFF;">

        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9" style="font-size: 0.9rem!Important;">
                3. SUPLEMENTAÇÃO ORÇAMENTÁRIA NECESSÁRIA
            </div>

            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 col-xxl-3 text-right">

                <span class="" data-bs-toggle="modal"
                    data-bs-target="#modalAdicionarItensOrcamentariosFinanceiros{{ $acaoOrcamentaria }}"
                    style="cursor: pointer;"
                    onclick="javascript: atualizarModal('Suplementação Orçamentária Necessária', '{{ $acaoOrcamentaria }}')"><i
                        class="fas fa-plus-circle"></i></span>

            </div>
        </div>

    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Ano</th>
                    <th class="text-right" style="background-color: #eaf2e7;">Valor</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($novoPac->evolucaoSuplementacaoOrcamentaria as $valueItem)
                    @if ($valueItem->cod_acao_orcamentaria === $acaoOrcamentaria)
                        @php
                            $table = null;
                            $item = null;
                            $codItem = null;
                            $dscTipoItemOrcamentarioFinanceiro = null;
                            $vlrItem = null;
                            $txtObservacao = null;

                            // Verifica se a PK do resultado existe e se é diferente de vazio e nulo,
                            // para preencher as variáveis.
                            if (
                                isset($valueItem->cod_evolucao_credito_disponivel) &&
                                !empty($valueItem->cod_evolucao_credito_disponivel)
                            ) {
                                $table = 'tab_evolucao_credito_disponivel';
                                $item = 'cd';
                                $codItem = $valueItem->cod_evolucao_credito_disponivel;
                                $dscTipoItemOrcamentarioFinanceiro = 'Crédito Disponível (Não Empenhado)';
                                $vlrItem = $valueItem->vlr_credito_disponivel;
                                $txtObservacao = $valueItem->txt_observacao_credito_disponivel;
                            }

                            if (
                                isset($valueItem->cod_evolucao_saldo_empenhado) &&
                                !empty($valueItem->cod_evolucao_saldo_empenhado)
                            ) {
                                $table = 'tab_evolucao_saldo_empenhado';
                                $item = 'se';
                                $codItem = $valueItem->cod_evolucao_saldo_empenhado;
                                $dscTipoItemOrcamentarioFinanceiro = 'Saldo Empenhado';
                                $vlrItem = $valueItem->vlr_saldo_empenhado;
                                $txtObservacao = $valueItem->txt_observacao_saldo_empenhado;
                            }

                            if (
                                isset($valueItem->cod_evolucao_suplementacao_orcamentaria) &&
                                !empty($valueItem->cod_evolucao_suplementacao_orcamentaria)
                            ) {
                                $table = 'tab_evolucao_suplementacao_orcamentaria';
                                $item = 'so';
                                $codItem = $valueItem->cod_evolucao_suplementacao_orcamentaria;
                                $dscTipoItemOrcamentarioFinanceiro = 'Suplementação Orçamentária Necessária';
                                $vlrItem = $valueItem->vlr_suplementacao_orcamentaria;
                                $txtObservacao = $valueItem->txt_observacao_suplementacao_orcamentaria;
                            }
                        @endphp

                        <tr>
                            <th class="text-left p-1">
                                <div class="row">
                                    <div class="col-2 text-center">
                                        <i class="fas fa-edit text-success" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem) . $item }}"
                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                        {{-- Início da modal para edição --}}
                                        @include('pac.financeiro.modal.editar')
                                        {{-- Fim da modal para edição --}}
                                    </div>
                                    <div class="col-2 text-center">
                                        <i class="fas fa-trash text-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalExcluirItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem) . $item }}"
                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                        {{-- Início da modal para excluir --}}
                                        @include('pac.financeiro.modal.excluir')
                                        {{-- Fim da modal para excluir --}}
                                    </div>
                                    <div class="col-2 text-center">
                                        <i class="fas fa-eye pointer text-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalLogOrcamentarioFinanceiro{{ substituirPipePorHifen($codItem) }}"></i>
                                        </label>
                                        {!! app(App\Http\Controllers\TabNovoPacController::class)->modalTabelaLogOrcamentarioFinanceiro(
                                            substituirPipePorHifen($codItem),
                                            $valueItem->auditoria->count() . ' ação(ões) realizada(s)',
                                            $valueItem->auditoria,
                                        ) !!}
                                    </div>
                                    <div class="col-5 text-right">
                                        {{ $valueItem->num_ano }}
                                    </div>
                                </div>
                            </th>
                            <th class="text-right p-1" style="font-size: 0.95rem!Important; background-color: #eaf2e7;">
                                {{ converteValor('MYSQL', 'PTBR', $vlrItem) }}
                            </th>
                            <th class="text-justify p-1" style="width: 51%!Important;">
                                {{ $txtObservacao }}
                            </th>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
