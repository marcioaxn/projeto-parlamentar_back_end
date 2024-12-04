@php
    /**
     * Exibe a evolução orcamentária das ações orçamentárias.
     *
     * Este código é responsável por renderizar um accordion com informações sobre a evolução orcamentária
     * de cada ação orçamentária. Cada ação é exibida em uma div separada, e o accordion contém
     * informações sobre a necessidade orcamentária de cada mês e ano.
     *
     * @param array $acoesOrcamentariasExplode As ações orçamentárias a serem exibidas.
     * @param object $novoPac->evolucaoOrcamentaria Objeto contendo as informações de evolução orcamentária.
     */
@endphp

@if ($result)
    @if (isset($result['2. Preenchimento Facultativo'][10]['value']) &&
            !empty($result['2. Preenchimento Facultativo'][10]['value']))

        @php
            $acoesOrcamentariasExplode = explode(',', $result['2. Preenchimento Facultativo'][10]['value']);
        @endphp

        <div class="row mt-0 pt-0">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 font-numero mt-0 pt-0 mb-4"
                style="font-size: 1.1rem!Important;">
                Neste empreendimento, <?php count($acoesOrcamentariasExplode) > 1 ? print 'constam' : print 'consta'; ?> <span class="text-bold">{{ count($acoesOrcamentariasExplode) }}
                    <?php count($acoesOrcamentariasExplode) > 1 ? print 'Ações Orçamentárias' : print 'Ação Orçamentária'; ?></span>,
                <?php count($acoesOrcamentariasExplode) > 1 ? print 'as quais estão dispostas e identificadas pelo código e pela descrição.' : print 'a qual está disposta e identificada pelo código e pela descrição.'; ?> Após a identificação da Ação Orçamentária, os <span class="text-bold">Exercícios
                    Orçamentários (anos)</span> estão
                organizados em ordem crescente. Dentro de cada exercício, há uma tabela com os meses correspondentes,
                seguida de dois campos: o primeiro campo é reservado para digitar a <span class="text-bold">necessidade
                    orcamentária</span>, e o segundo
                campo é para digitar a <span class="text-bold">observação</span> acerca dessa necessidade, caso haja. O
                Exercício Orçamentário visível ao abrir o formulário é o relativo ao ano vigente. Para tornar visível ou
                ocultar outro Exercício Orçamentário, clique sobre a frase correspondente a cada Ação Orçamentária,
                como,
                por exemplo, 'Exercício Orçamentário de 2025'.
            </div>

            @foreach ($acoesOrcamentariasExplode as $acaoOrcamentaria)
                @if (count($acoesOrcamentariasExplode) >= 2)
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4 font-numero mb-4">
                    @else
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 font-numero mb-4">
                @endif
                <div class="rounded bg-senado pt-2 pb-2 pl-2 font-numero" style="font-size: 1rem!Important;">
                    {{ $acoesOrcamentarias[$acaoOrcamentaria] }}
                </div>

                <div class="" id="accordionAcaoOrcamentaria{{ $acaoOrcamentaria }}">

                    @for ($contAno = 2024; $contAno <= date('Y') + 1; $contAno++)
                        <div class="accordion-item m-0 p-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button text-primary" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseAno{{ $acaoOrcamentaria . $contAno }}"
                                    aria-expanded="<?php $contAno == date('Y') ? print 'true' : print 'false'; ?>"
                                    aria-controls="collapseAno{{ $acaoOrcamentaria . $contAno }}">
                                    Exercício Orçamentário de {{ $contAno }}
                                </button>
                            </h2>
                            <div id="collapseAno{{ $acaoOrcamentaria . $contAno }}"
                                class="accordion-collapse collapse <?php $contAno == date('Y') ? print 'show' : ''; ?> "
                                data-bs-parent="#accordionAcaoOrcamentaria{{ $acaoOrcamentaria }}">
                                <div class="accordion-body">

                                    <table class="table table-sm">

                                        <thead>
                                            <tr>
                                                <th class="text-right">Mês</th>
                                                <th class="text-right">Necessidade Orçamentária</th>
                                                <th>Observação</th>
                                            </tr>
                                        </thead>

                                        @php
                                            $vlrOrcamentarioTotal = 0;
                                        @endphp

                                        @for ($mes = 1; $mes <= 12; $mes++)
                                            @php
                                                $codEvolucaoOrcamentaria = null;
                                                $vlrOrcamentario = null;
                                                $txtObservacaoOrcamentario = null;
                                                $dataAudit = [];
                                                $quantidadeAudit = 0;
                                            @endphp
                                            @foreach ($novoPac->evolucaoOrcamentaria as $evolucaoOrcamentaria)
                                                @if (
                                                    $evolucaoOrcamentaria->cod_acao_orcamentaria == $acaoOrcamentaria &&
                                                        $evolucaoOrcamentaria->num_ano == $contAno &&
                                                        $evolucaoOrcamentaria->num_mes == $mes)
                                                    @php

                                                        if ($evolucaoOrcamentaria->auditoria->count() > 0) {
                                                            $dataAudit = $evolucaoOrcamentaria->auditoria;
                                                            $quantidadeAudit = $evolucaoOrcamentaria->auditoria->count();
                                                        } else {
                                                            $dataAudit = [];
                                                            $quantidadeAudit = 0;
                                                        }

                                                        $codEvolucaoOrcamentaria =
                                                            $evolucaoOrcamentaria->cod_evolucao_orcamentaria;
                                                        $vlrOrcamentario = converteValor(
                                                            'MYSQL',
                                                            'PTBR',
                                                            $evolucaoOrcamentaria->vlr_orcamentario,
                                                        );
                                                        $txtObservacaoOrcamentario =
                                                            $evolucaoOrcamentaria->txt_observacao_orcamentario;

                                                        $vlrOrcamentarioTotal +=
                                                            $evolucaoOrcamentaria->vlr_orcamentario;
                                                    @endphp
                                                @endif
                                            @endforeach

                                            <tr>
                                                <th class="text-right align-middle">
                                                    <i class="fas fa-eye pointer text-danger <?php $quantidadeAudit > 0 ? print 'visible' : print 'invisible'; ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalLog{{ 'DetalhePacEvolucaoOrcamentaria' . $codEvolucaoOrcamentaria }}"></i>
                                                    </label>
                                                    {!! app(App\Http\Controllers\TabEvolucaoOrcamentariaController::class)->modalTabelaLog(
                                                        'DetalhePacEvolucaoOrcamentaria' . $codEvolucaoOrcamentaria,
                                                        $quantidadeAudit .
                                                            ' ação(ões) realizada(s) para a Necessidade Orçamentária de ' .
                                                            mesNumeralParaExtensoCurto($mes) .
                                                            '/' .
                                                            $contAno .
                                                            ' da Ação Orçamentária ' .
                                                            $acaoOrcamentaria,
                                                        $dataAudit,
                                                    ) !!}
                                                    {{ mesNumeralParaExtensoCurto($mes) . '/' . $contAno }}
                                                </th>
                                                <th>
                                                    {!! Form::text(
                                                        'evolucaoOrcamentaria[vlr_orcamentario][' . $acaoOrcamentaria . '][' . $contAno . '][' . $mes . ']',
                                                        $vlrOrcamentario,
                                                        [
                                                            'class' => 'form-control text-dark text-right mascara-dinheiro-soma-financeiro font-numero',
                                                            'id' => 'vlr_orcamentario_' . $acaoOrcamentaria . '_' . $contAno . '_' . $mes,
                                                            'autocomplete' => 'off',
                                                            'data-acao-orcamentaria' => $acaoOrcamentaria,
                                                            'data-cont-ano' => $contAno,
                                                        ],
                                                    ) !!}

                                                </th>
                                                <th>
                                                    {!! Form::textarea(
                                                        'evolucaoOrcamentaria[txt_observacao_orcamentario][' . $acaoOrcamentaria . '][' . $contAno . '][' . $mes . ']',
                                                        $txtObservacaoOrcamentario,
                                                        [
                                                            'class' => 'form-control text-dark',
                                                            'id' => 'txt_observacao_orcamentario_' . $acaoOrcamentaria . '_' . $contAno . '_' . $mes,
                                                            'rows' => 1,
                                                            'cols' => 50,
                                                        ],
                                                    ) !!}
                                                </th>
                                            </tr>
                                        @endfor

                                        <tr>
                                            <th class="text-bold align-middle"
                                                style="background-color: #FFF2CC!Important;">
                                                Necessidade Orçamentária para {{ $acaoOrcamentaria }} em
                                                {{ $contAno }}
                                            </th>
                                            <th colspan="2" class="text-bold align-middle"
                                                style="background-color: #FFF2CC!Important;">
                                                <div id="vlrOrcamentarioTotal{{ $acaoOrcamentaria . $contAno }}">
                                                    {{ converteValor('MYSQL', 'PTBR', $vlrOrcamentarioTotal) }}
                                                </div>
                                            </th>
                                        </tr>

                                    </table>

                                </div>
                            </div>
                        </div>
                    @endfor

                </div>

        </div>
    @endforeach

    </div>

@endif
@else
@endif
