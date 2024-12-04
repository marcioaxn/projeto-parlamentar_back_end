@php
    /**
     * Exibe a evolução financeira das ações orçamentárias.
     *
     * Este código é responsável por renderizar um accordion com informações sobre a evolução financeira
     * de cada ação orçamentária. Cada ação é exibida em uma div separada, e o accordion contém
     * informações sobre a necessidade financeira de cada mês e ano.
     *
     * @param array $acoesOrcamentariasExplode As ações orçamentárias a serem exibidas.
     * @param object $novoPac->evolucaoFinanceira Objeto contendo as informações de evolução financeira.
     */
@endphp

<style>
    .bg-acao-orcamentaria {
        background-color: #fdfdfd !Important;
        color: #000000 !Important;
        font-family: Raleway;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.069em;
        text-align: left;
    }
</style>

@if ($result)
    @if (isset($result['2. Preenchimento Facultativo'][10]['value']) &&
            !empty($result['2. Preenchimento Facultativo'][10]['value']))

        @php
            $acoesOrcamentariasExplode = explode(',', $result['2. Preenchimento Facultativo'][10]['value']);
        @endphp

        <div class="row mt-0 pt-0">

            @if (count($acoesOrcamentariasExplode) > 1)
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 font-numero mt-0 pt-0 mb-4"
                    style="font-size: 1.1rem!Important;">
                    Neste empreendimento, <?php count($acoesOrcamentariasExplode) > 1 ? print 'constam' : print 'consta'; ?> <span
                        class="text-bold">{{ count($acoesOrcamentariasExplode) }}
                        <?php count($acoesOrcamentariasExplode) > 1 ? print 'Ações Orçamentárias' : print 'Ação Orçamentária'; ?></span>.
                </div>
            @endif

            {!! Form::hidden('cod_pac', $codPac, ['id' => 'cod_pac']) !!}

            @foreach ($acoesOrcamentariasExplode as $acaoOrcamentaria)
                {!! Form::hidden('cod_acao_orcamentaria_orcamentario_financeiro', $acaoOrcamentaria, [
                    'id' => 'cod_acao_orcamentaria_orcamentario_financeiro',
                ]) !!}
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 font-numero mb-4">

                    <div class="text-bold border border-secondary rounded bg-acao-orcamentaria mt-2 pt-4 pb-3 pl-2 font-numero position-relative"
                        style="font-size: 1rem!Important;">

                        <div class="row m-1">

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-10"
                                style="text-transform: uppercase;">
                                {{ $acoesOrcamentarias[$acaoOrcamentaria] }}
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-10">

                                <div class="row pt-4">
                                    @include('pac.financeiro.tipos-monitoramento.credito-disponivel')
                                    @include('pac.financeiro.tipos-monitoramento.saldo-empenhado')
                                    @include('pac.financeiro.tipos-monitoramento.suplementacao-orcamentaria')
                                    @include('pac.financeiro.tipos-monitoramento.necessidade-financeira')
                                </div>

                                <div id="divAddItensOrcamentariosFinanceiros">

                                    @include('pac.financeiro.modal.index')

                                </div>

                            </div>

                        </div>

                        <span
                            class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-warning text-black"
                            style="margin-left: 77px!Important;">
                            Ação Orçamentária
                            <span class="visually-hidden">&nbsp;</span>
                        </span>

                    </div>

                </div>
            @endforeach

            @include('pac.financeiro.script')

        </div>

    @endif
@else
@endif
