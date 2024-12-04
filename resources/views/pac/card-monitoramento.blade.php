<div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 col-xl-7 col-xxl-7 mb-4">
    <div class="h-100 bg-white mb-3" style="font-size: 0.9rem !Important;">
        <div class="row">
            <div class="col-12">
                <h4 class="card-title rounded-top bg-warning-sub-titulo-modal mt-0 mr-0 mb-2 pt-2 pl-2 pb-1"
                    style="font-size: 1.1rem !Important;">
                    <i class="fas fa-exclamation-triangle text-danger"></i> Monitoramento
                </h4>
            </div>
            <div class="col-12">

                <style>
                    .table th {
                        text-align: center;
                    }
                </style>

                <table class="table table-striped table-sm mb-0">
                    <thead>
                        <tr>
                            <th>
                                Área
                            </th>
                            <th>
                                Concluídos
                            </th>
                            <th>
                                Não atualizados nos últimos 30 dias <span class="text-bold">¹</span>
                            </th>
                            <th>
                                Atualizados nos últimos 30 dias <span class="text-bold">¹</span>
                            </th>
                            <th class="text-bold">
                                <strong>
                                    Total
                                </strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalConcluidos = 0;
                            $totalNaoAtualizados = 0;
                            $totalAtualizados = 0;
                            $totalGeral = 0;
                        @endphp

                        @foreach ($visResumoAjustado as $result)
                            @php
                                $totalConcluidos += $result->qte_concluidos;
                                $totalNaoAtualizados += $result->qte_empreendimentos_nao_atualizados_30_dias;
                                $totalAtualizados += $result->qte_empreendimentos_atualizados_30_dias;
                                $totalGeral += $result->total;
                            @endphp
                            <tr class="<?php $result->codigoUnidade == $codigoUnidade ? print 'table-warning' : ''; ?>">
                                <th>
                                    <a href="{{ url('novo-pac') . '/' . $result->codigoUnidade }}">{{ $result->sigla }}</a>
                                </th>
                                <th class="font-numero">
                                    {{ $result->qte_concluidos }}
                                </th>
                                <th class="font-numero">
                                    {{ $result->qte_empreendimentos_nao_atualizados_30_dias }}
                                </th>
                                <th class="font-numero">
                                    {{ $result->qte_empreendimentos_atualizados_30_dias }}
                                </th>
                                <th class="font-numero text-bold">
                                    {{ $result->total }}
                                </th>
                            </tr>
                        @endforeach

                        @if (Session::get('permissao') === '0000010' || Session::get('permissao') === '0000001')
                            <tr>
                                <th class="text-bold">
                                    <strong>
                                        Total
                                    </strong>
                                </th>
                                <th class="font-numero text-bold text-primary" style="width: 25%!Important;">
                                    {{ $totalConcluidos }}
                                </th>
                                <th class="font-numero text-bold text-danger" style="width: 25%!Important;">
                                    {{ $totalNaoAtualizados }}
                                </th>
                                <th class="font-numero text-bold text-success" style="width: 25%!Important;">
                                    {{ $totalAtualizados }}
                                </th>
                                <th class="font-numero text-bold">
                                    {{ $totalGeral }}
                                </th>
                            </tr>
                        @endif

                    </tbody>
                </table>
                <div class="text-muted font-numero mt-0 pt-1">
                    {!! ' <span class="text-bold">¹</span> Considerando os últimos 30 dias a partir de ' . date('d/m/Y') . '.' !!}
                </div>
            </div>

        </div>
    </div>
</div>
