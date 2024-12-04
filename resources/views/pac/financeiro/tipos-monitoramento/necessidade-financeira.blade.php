<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 font-numero mb-2">
    <div class="rounded p-2 mb-2" style="background-color: #610774; color: #FFFFFF;">

        <div class="row">

            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9" style="font-size: 0.9rem!Important;">
                4. NECESSIDADE FINANCEIRA
            </div>

            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 col-xxl-3 text-right">

                <span class="" data-bs-toggle="modal"
                    data-bs-target="#modalAdicionarItensOrcamentariosFinanceiros{{ $acaoOrcamentaria }}"
                    style="cursor: pointer;"
                    onclick="javascript: atualizarModal('Necessidade Financeira', '{{ $acaoOrcamentaria }}')"><i
                        class="fas fa-plus-circle"></i></span>

            </div>

        </div>

    </div>

    @php
        $matrizAcoesOrcamentariasComNecessidadeFinanceira = [];

        foreach ($evolucaoFinanceira as $ano => $resultMeses) {
            foreach ($resultMeses as $keyMes => $valueCollection) {
                foreach ($valueCollection as $value) {
                    // Se o código da ação orçamentária não estiver na matriz, inicialize o índice com um array vazio
                    if (
                        !array_key_exists(
                            $value->cod_acao_orcamentaria,
                            $matrizAcoesOrcamentariasComNecessidadeFinanceira,
                        )
                    ) {
                        $matrizAcoesOrcamentariasComNecessidadeFinanceira[$value->cod_acao_orcamentaria] = [];
                    }

                    // Adiciona o mês ao array da ação orçamentária correspondente, se ainda não estiver lá
                    if (
                        !in_array(
                            $keyMes,
                            $matrizAcoesOrcamentariasComNecessidadeFinanceira[$value->cod_acao_orcamentaria],
                        )
                    ) {
                        $matrizAcoesOrcamentariasComNecessidadeFinanceira[$value->cod_acao_orcamentaria][] = $keyMes;
                    }
                }
            }
        }
        // dd($matrizAcoesOrcamentariasComNecessidadeFinanceira);
    @endphp

    @if (array_key_exists($acaoOrcamentaria, $matrizAcoesOrcamentariasComNecessidadeFinanceira))
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">Mês/Ano</th>
                        <th colspan="4" class="" style="width: 80%!Important;">
                            Valor/Observação por RP</th>
                        <th rowspan="2" class="align-middle text-right" style="background-color: #efe6f1;">Total</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="">RP 2</th>
                        <th colspan="2" style="">RP 3</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($evolucaoFinanceira as $ano => $resultMeses)
                        @php
                            $totalAnualRp2 = 0;
                            $totalAnualRp3 = 0;
                            $totalAnualGeral = 0;

                            $subtotalRp2 = 0;
                            $subtotalRp3 = 0;
                            $subtotalGeral = 0;

                            $anoVigente = now()->year;
                            $mesAtual = now()->month;
                            $subtotalStartMonth = date('m');
                        @endphp

                        @foreach ($resultMeses as $mes => $dados)
                            @if ($ano == $anoVigente && $mes >= date('n'))
                                @php
                                    $item = 'nf';
                                    $dscTipoItemOrcamentarioFinanceiro = 'Necessidade Financeira';

                                    $codItem2 = null;
                                    $vlrItem2 = null; // Garantir que a variável seja inicializada
                                    $txtObservacao2 = '';

                                    $countAuditoria2 = null;
                                    $auditoria2 = null;

                                    $codItem3 = null;
                                    $vlrItem3 = null; // Garantir que a variável seja inicializada
                                    $txtObservacao3 = '';

                                    $countAuditoria3 = null;
                                    $auditoria3 = null;

                                    $valorRp2 = null;
                                    $observacaoRp2 = '';
                                    $valorRp3 = null;
                                    $observacaoRp3 = '';
                                    $totalMes = 0;
                                @endphp

                                @foreach ($dados as $valueItem)
                                    @if ($valueItem->cod_acao_orcamentaria === $acaoOrcamentaria)
                                        @if ($valueItem['num_rp'] == 2)
                                            @php
                                                $codItem2 = $valueItem->cod_evolucao_financeira;
                                                $vlrItem2 = $valueItem->vlr_financeiro;
                                                $txtObservacao2 = $valueItem->txt_observacao_financeira;

                                                $countAuditoria2 = $valueItem->auditoria->count();
                                                $auditoria2 = $valueItem->auditoria;

                                                $valorRp2 += $valueItem['vlr_financeiro'];
                                                $observacaoRp2 = $valueItem['txt_observacao_financeira'] ?? '';
                                            @endphp
                                        @elseif ($valueItem['num_rp'] == 3)
                                            @php
                                                $codItem3 = $valueItem->cod_evolucao_financeira;
                                                $vlrItem3 = $valueItem->vlr_financeiro;
                                                $txtObservacao3 = $valueItem->txt_observacao_financeira;

                                                $countAuditoria3 = $valueItem->auditoria->count();
                                                $auditoria3 = $valueItem->auditoria;

                                                $valorRp3 += $valueItem['vlr_financeiro'];
                                                $observacaoRp3 = $valueItem['txt_observacao_financeira'] ?? '';
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach

                                @php
                                    $totalMes = $valorRp2 + $valorRp3;
                                    $totalAnualRp2 += $valorRp2;
                                    $totalAnualRp3 += $valorRp3;
                                    $totalAnualGeral += $totalMes;

                                    if ($ano == $anoVigente && $mes >= $subtotalStartMonth) {
                                        $subtotalRp2 += $valorRp2;
                                        $subtotalRp3 += $valorRp3;
                                        $subtotalGeral += $totalMes;
                                    }
                                @endphp

                                @if (in_array($mes, $matrizAcoesOrcamentariasComNecessidadeFinanceira[$acaoOrcamentaria]))
                                    <tr>
                                        <th class="align-middle p-1" style="width: 14%!Important;">
                                            {{ mesNumeralParaExtensoCurto($mes) . '/' . $ano }}
                                        </th>
                                        <th class="text-right align-middle p-1"
                                            style="font-size: 0.95rem!Important; width: 12%!Important;">
                                            @if (isset($valorRp2) && !empty($valorRp2))
                                                <div class="row">
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-edit text-success" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditarItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem2) . $item }}"
                                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                                        {{-- Início da modal para edição --}}
                                                        @include('pac.financeiro.modal.editar', [
                                                            'acaoOrcamentaria' => $acaoOrcamentaria,
                                                            'codItem' => $codItem2,
                                                            'item' => $item,
                                                            'dscTipoItemOrcamentarioFinanceiro' => $dscTipoItemOrcamentarioFinanceiro,
                                                            'vlrItem' => $vlrItem2,
                                                            'txtObservacao' => $txtObservacao2,
                                                            'numAno' => $ano,
                                                            'numMes' => $mes,
                                                            'numRp' => 2,
                                                        ])
                                                        {{-- Fim da modal para edição --}}

                                                    </div>
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-trash text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalExcluirItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem2) . $item }}"
                                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                                        {{-- Início da modal para excluir --}}
                                                        @include('pac.financeiro.modal.excluir', [
                                                            'table' => 'tab_evolucao_financeira',
                                                            'acaoOrcamentaria' => $acaoOrcamentaria,
                                                            'codItem' => $codItem2,
                                                            'item' => $item,
                                                            'dscTipoItemOrcamentarioFinanceiro' => $dscTipoItemOrcamentarioFinanceiro,
                                                            'vlrItem' => $vlrItem2,
                                                            'txtObservacao' => $txtObservacao2,
                                                            'numAno' => $ano,
                                                            'numMes' => $mes,
                                                            'numRp' => 2,
                                                        ])
                                                        {{-- Fim da modal para excluir --}}

                                                    </div>
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-eye pointer text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalLogOrcamentarioFinanceiro{{ substituirPipePorHifen($codItem2) }}"></i>
                                                        </label>
                                                        {!! app(App\Http\Controllers\TabNovoPacController::class)->modalTabelaLogOrcamentarioFinanceiro(
                                                            substituirPipePorHifen($codItem2),
                                                            $countAuditoria2 . ' ação(ões) realizada(s)',
                                                            $auditoria2,
                                                        ) !!}
                                                    </div>
                                                    <div class="col-8 text-right pr-0">
                                                        {{ converteValor('MYSQL', 'PTBR', $valorRp2) }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-2 text-center">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-2 text-center">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-8 text-muted text-right">
                                                        -
                                                    </div>
                                                </div>
                                            @endif
                                        </th>
                                        <th class="text-muted align-middle p-1" style="width: 15%!Important;">
                                            {{ $observacaoRp2 }}
                                        </th>
                                        <th class="text-right align-middle p-1"
                                            style="font-size: 0.95rem!Important; width: 12%!Important;">

                                            @if (isset($vlrItem3) && !empty($vlrItem3))
                                                <div class="row">
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-edit text-success" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditarItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem3) . $item }}"
                                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                                        {{-- Início da modal para edição --}}
                                                        @include('pac.financeiro.modal.editar', [
                                                            'acaoOrcamentaria' => $acaoOrcamentaria,
                                                            'codItem' => $codItem3,
                                                            'item' => $item,
                                                            'dscTipoItemOrcamentarioFinanceiro' => $dscTipoItemOrcamentarioFinanceiro,
                                                            'vlrItem' => $vlrItem3,
                                                            'txtObservacao' => $txtObservacao3,
                                                            'numAno' => $ano,
                                                            'numMes' => $mes,
                                                            'numRp' => 3,
                                                        ])
                                                        {{-- Fim da modal para edição --}}
                                                    </div>
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-trash text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalExcluirItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem3) . $item }}"
                                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                                        {{-- Início da modal para excluir --}}
                                                        @include('pac.financeiro.modal.excluir', [
                                                            'table' => 'tab_evolucao_financeira',
                                                            'acaoOrcamentaria' => $acaoOrcamentaria,
                                                            'codItem' => $codItem3,
                                                            'item' => $item,
                                                            'dscTipoItemOrcamentarioFinanceiro' => $dscTipoItemOrcamentarioFinanceiro,
                                                            'vlrItem' => $vlrItem3,
                                                            'txtObservacao' => $txtObservacao3,
                                                            'numAno' => $ano,
                                                            'numMes' => $mes,
                                                            'numRp' => 3,
                                                        ])
                                                        {{-- Fim da modal para excluir --}}


                                                    </div>
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-eye pointer text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalLogOrcamentarioFinanceiro{{ substituirPipePorHifen($codItem3) }}"></i>
                                                        </label>
                                                        {!! app(App\Http\Controllers\TabNovoPacController::class)->modalTabelaLogOrcamentarioFinanceiro(
                                                            substituirPipePorHifen($codItem3),
                                                            $countAuditoria3 . ' ação(ões) realizada(s)',
                                                            $auditoria3,
                                                        ) !!}
                                                    </div>
                                                    <div class="col-8 text-right pr-0">
                                                        {{ converteValor('MYSQL', 'PTBR', $valorRp3) }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-2 text-center">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-2 text-center">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-8 text-muted text-right">
                                                        -
                                                    </div>
                                                </div>
                                            @endif
                                        </th>
                                        <th class="text-muted align-middle p-1" style="width: 15%!Important;">
                                            {{ $observacaoRp3 }}
                                        </th>
                                        <th class="text-right align-middle p-1"
                                            style="font-size: 0.95rem!Important; background-color: #efe6f1; width: 9%!Important;">
                                            {{ converteValor('MYSQL', 'PTBR', $totalMes) }}
                                        </th>
                                    </tr>
                                @endif
                            @elseif($ano != $anoVigente)
                                @php
                                    $item = 'nf';
                                    $dscTipoItemOrcamentarioFinanceiro = 'Necessidade Financeira';

                                    $codItem2 = null;
                                    $vlrItem2 = null; // Garantir que a variável seja inicializada
                                    $txtObservacao2 = '';

                                    $countAuditoria2 = null;
                                    $auditoria2 = null;

                                    $codItem3 = null;
                                    $vlrItem3 = null; // Garantir que a variável seja inicializada
                                    $txtObservacao3 = '';

                                    $countAuditoria3 = null;
                                    $auditoria3 = null;

                                    $valorRp2 = null;
                                    $observacaoRp2 = '';
                                    $valorRp3 = null;
                                    $observacaoRp3 = '';
                                    $totalMes = 0;
                                @endphp

                                @foreach ($dados as $valueItem)
                                    @if ($valueItem->cod_acao_orcamentaria === $acaoOrcamentaria)
                                        @if ($valueItem['num_rp'] == 2)
                                            @php
                                                $codItem2 = $valueItem->cod_evolucao_financeira;
                                                $vlrItem2 = $valueItem->vlr_financeiro;
                                                $txtObservacao2 = $valueItem->txt_observacao_financeira;

                                                $countAuditoria2 = $valueItem->auditoria->count();
                                                $auditoria2 = $valueItem->auditoria;

                                                $valorRp2 += $valueItem['vlr_financeiro'];
                                                $observacaoRp2 = $valueItem['txt_observacao_financeira'] ?? '';
                                            @endphp
                                        @elseif ($valueItem['num_rp'] == 3)
                                            @php
                                                $codItem3 = $valueItem->cod_evolucao_financeira;
                                                $vlrItem3 = $valueItem->vlr_financeiro;
                                                $txtObservacao3 = $valueItem->txt_observacao_financeira;

                                                $countAuditoria3 = $valueItem->auditoria->count();
                                                $auditoria3 = $valueItem->auditoria;

                                                $valorRp3 += $valueItem['vlr_financeiro'];
                                                $observacaoRp3 = $valueItem['txt_observacao_financeira'] ?? '';
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach

                                @php
                                    $totalMes = $valorRp2 + $valorRp3;
                                    $totalAnualRp2 += $valorRp2;
                                    $totalAnualRp3 += $valorRp3;
                                    $totalAnualGeral += $totalMes;

                                    if ($ano == $anoVigente && $mes >= $subtotalStartMonth) {
                                        $subtotalRp2 += $valorRp2;
                                        $subtotalRp3 += $valorRp3;
                                        $subtotalGeral += $totalMes;
                                    }
                                @endphp

                                @if (in_array($mes, $matrizAcoesOrcamentariasComNecessidadeFinanceira[$acaoOrcamentaria]))
                                    <tr>
                                        <th class="align-middle p-1" style="width: 14%!Important;">
                                            {{ mesNumeralParaExtensoCurto($mes) . '/' . $ano }}
                                        </th>
                                        <th class="text-right align-middle p-1"
                                            style="font-size: 0.95rem!Important; width: 12%!Important;">
                                            @if (isset($valorRp2) && !empty($valorRp2))
                                                <div class="row">
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-edit text-success" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditarItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem2) . $item }}"
                                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                                        {{-- Início da modal para edição --}}
                                                        @include('pac.financeiro.modal.editar', [
                                                            'acaoOrcamentaria' => $acaoOrcamentaria,
                                                            'codItem' => $codItem2,
                                                            'item' => $item,
                                                            'dscTipoItemOrcamentarioFinanceiro' => $dscTipoItemOrcamentarioFinanceiro,
                                                            'vlrItem' => $vlrItem2,
                                                            'txtObservacao' => $txtObservacao2,
                                                            'numAno' => $ano,
                                                            'numMes' => $mes,
                                                            'numRp' => 2,
                                                        ])
                                                        {{-- Fim da modal para edição --}}

                                                    </div>
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-trash text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalExcluirItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem2) . $item }}"
                                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                                        {{-- Início da modal para excluir --}}
                                                        @include('pac.financeiro.modal.excluir', [
                                                            'table' => 'tab_evolucao_financeira',
                                                            'acaoOrcamentaria' => $acaoOrcamentaria,
                                                            'codItem' => $codItem2,
                                                            'item' => $item,
                                                            'dscTipoItemOrcamentarioFinanceiro' => $dscTipoItemOrcamentarioFinanceiro,
                                                            'vlrItem' => $vlrItem2,
                                                            'txtObservacao' => $txtObservacao2,
                                                            'numAno' => $ano,
                                                            'numMes' => $mes,
                                                            'numRp' => 2,
                                                        ])
                                                        {{-- Fim da modal para excluir --}}

                                                    </div>
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-eye pointer text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalLogOrcamentarioFinanceiro{{ substituirPipePorHifen($codItem2) }}"></i>
                                                        </label>
                                                        {!! app(App\Http\Controllers\TabNovoPacController::class)->modalTabelaLogOrcamentarioFinanceiro(
                                                            substituirPipePorHifen($codItem2),
                                                            $countAuditoria2 . ' ação(ões) realizada(s)',
                                                            $auditoria2,
                                                        ) !!}
                                                    </div>
                                                    <div class="col-8 text-right pr-0">
                                                        {{ converteValor('MYSQL', 'PTBR', $valorRp2) }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-2 text-center">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-2 text-center">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-8 text-muted text-right">
                                                        -
                                                    </div>
                                                </div>
                                            @endif
                                        </th>
                                        <th class="text-muted align-middle p-1" style="width: 15%!Important;">
                                            {{ $observacaoRp2 }}
                                        </th>
                                        <th class="text-right align-middle p-1"
                                            style="font-size: 0.95rem!Important; width: 12%!Important;">

                                            @if (isset($vlrItem3) && !empty($vlrItem3))
                                                <div class="row">
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-edit text-success" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditarItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem3) . $item }}"
                                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                                        {{-- Início da modal para edição --}}
                                                        @include('pac.financeiro.modal.editar', [
                                                            'acaoOrcamentaria' => $acaoOrcamentaria,
                                                            'codItem' => $codItem3,
                                                            'item' => $item,
                                                            'dscTipoItemOrcamentarioFinanceiro' => $dscTipoItemOrcamentarioFinanceiro,
                                                            'vlrItem' => $vlrItem3,
                                                            'txtObservacao' => $txtObservacao3,
                                                            'numAno' => $ano,
                                                            'numMes' => $mes,
                                                            'numRp' => 3,
                                                        ])
                                                        {{-- Fim da modal para edição --}}
                                                    </div>
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-trash text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalExcluirItensOrcamentariosFinanceiros{{ $acaoOrcamentaria . substituirPipePorHifen($codItem3) . $item }}"
                                                            style="font-size: 0.8rem!Important; cursor: pointer;"></i>

                                                        {{-- Início da modal para excluir --}}
                                                        @include('pac.financeiro.modal.excluir', [
                                                            'table' => 'tab_evolucao_financeira',
                                                            'acaoOrcamentaria' => $acaoOrcamentaria,
                                                            'codItem' => $codItem3,
                                                            'item' => $item,
                                                            'dscTipoItemOrcamentarioFinanceiro' => $dscTipoItemOrcamentarioFinanceiro,
                                                            'vlrItem' => $vlrItem3,
                                                            'txtObservacao' => $txtObservacao3,
                                                            'numAno' => $ano,
                                                            'numMes' => $mes,
                                                            'numRp' => 3,
                                                        ])
                                                        {{-- Fim da modal para excluir --}}


                                                    </div>
                                                    <div class="col-1 text-center">
                                                        <i class="fas fa-eye pointer text-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalLogOrcamentarioFinanceiro{{ substituirPipePorHifen($codItem3) }}"></i>
                                                        </label>
                                                        {!! app(App\Http\Controllers\TabNovoPacController::class)->modalTabelaLogOrcamentarioFinanceiro(
                                                            substituirPipePorHifen($codItem3),
                                                            $countAuditoria3 . ' ação(ões) realizada(s)',
                                                            $auditoria3,
                                                        ) !!}
                                                    </div>
                                                    <div class="col-8 text-right pr-0">
                                                        {{ converteValor('MYSQL', 'PTBR', $valorRp3) }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-2 text-center">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-2 text-center">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-8 text-muted text-right">
                                                        -
                                                    </div>
                                                </div>
                                            @endif
                                        </th>
                                        <th class="text-muted align-middle p-1" style="width: 15%!Important;">
                                            {{ $observacaoRp3 }}
                                        </th>
                                        <th class="text-right align-middle p-1"
                                            style="font-size: 0.95rem!Important; background-color: #efe6f1; width: 9%!Important;">
                                            {{ converteValor('MYSQL', 'PTBR', $totalMes) }}
                                        </th>
                                    </tr>
                                @endif
                            @endif
                        @endforeach

                        {{-- Adiciona a linha de subtotal para o ano vigente de agosto a dezembro --}}
                        @if ($ano == $anoVigente)
                            <tr>
                                <th class="align-middle" style="background-color: #efe6f1; font-weight: bold;">
                                    Necessidade Financeira
                                    {{ $ano }} ({{ mesNumeralParaExtensoCurto($subtotalStartMonth) }} - Dez)
                                </th>
                                <th class="text-right align-middle p-1"
                                    style="font-size: 0.95rem!Important; background-color: #efe6f1; font-weight: bold;">
                                    {{ converteValor('MYSQL', 'PTBR', $subtotalRp2) }}</th>
                                <th style="background-color: #efe6f1; font-weight: bold;"></th>
                                <th class="text-right align-middle p-1"
                                    style="font-size: 0.95rem!Important; background-color: #efe6f1; font-weight: bold;">
                                    {{ converteValor('MYSQL', 'PTBR', $subtotalRp3) }}</th>
                                <th style="background-color: #efe6f1; font-weight: bold;"></th>
                                <th class="text-right align-middle p-1"
                                    style="font-size: 0.95rem!Important; background-color: #efe6f1; font-weight: bold;">
                                    {{ converteValor('MYSQL', 'PTBR', $subtotalGeral) }}
                                </th>
                            </tr>
                        @endif

                        {{-- Adiciona a linha de total anual --}}
                        @if ($ano != $anoVigente)
                            <tr style="background-color: #c6e6c6; font-weight: bold;">
                                <th class="align-middle">Total {{ $ano }}</th>
                                <th class="text-right align-middle p-1">
                                    {{ converteValor('MYSQL', 'PTBR', $totalAnualRp2) }}
                                </th>
                                <th></th>
                                <th class="text-right align-middle p-1">
                                    {{ converteValor('MYSQL', 'PTBR', $totalAnualRp3) }}
                                </th>
                                <th></th>
                                <th class="text-right align-middle p-1">
                                    {{ converteValor('MYSQL', 'PTBR', $totalAnualGeral) }}
                                </th>
                            </tr>
                        @endif
                    @endforeach

                </tbody>
            </table>
        </div>
    @endif

</div>
