@if ($result)
    @if (isset($result['2. Preenchimento Facultativo'][10]['value']) &&
            !empty($result['2. Preenchimento Facultativo'][10]['value']))

        @php
            $acoesOrcamentariasExplode = explode(',', $result['2. Preenchimento Facultativo'][10]['value']);
        @endphp

        <div class="row">

            @foreach ($acoesOrcamentariasExplode as $acaoOrcamentaria)
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 font-numero mb-4">
                    <p>{{ $acoesOrcamentarias[$acaoOrcamentaria] }}</p>

                    @php
                        $anoAtual = date('Y');
                        $anoPosterior = date('Y') + 1;
                    @endphp

                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 font-numero">

                            <table class="table table-borderless table-sm">

                                @for ($mes = 1; $mes <= 12; $mes++)
                                    <tr>
                                        <th>{{ mesNumeralParaExtensoCurto($mes) . '/' . $anoAtual }}</th>
                                        <th>
                                            {!! Form::text($acaoOrcamentaria . '_' . $anoAtual . '_' . $mes, null, [
                                                'class' => 'form-control text-dark text-right mascara-dinheiro font-numero',
                                                'id' => $acaoOrcamentaria . '_' . $anoAtual . '_' . $mes,
                                                'autocomplete' => 'off',
                                            ]) !!}
                                        </th>
                                    </tr>
                                @endfor

                                <tr>
                                    <th>Necessidade Financeira ({{ $anoAtual }})</th>
                                    <th>-</th>
                                </tr>

                            </table>

                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 font-numero">

                            <table class="table table-borderless table-sm">

                                @for ($mes = 1; $mes <= 6; $mes++)
                                    <tr>
                                        <th>{{ mesNumeralParaExtensoCurto($mes) . '/' . $anoPosterior }}</th>
                                        <th>
                                            {!! Form::text($acaoOrcamentaria . '_' . $anoPosterior . '_' . $mes, null, [
                                                'class' => 'form-control text-dark text-right mascara-dinheiro font-numero',
                                                'id' => $acaoOrcamentaria . '_' . $anoPosterior . '_' . $mes,
                                                'autocomplete' => 'off',
                                            ]) !!}
                                        </th>
                                    </tr>
                                @endfor

                                <tr>
                                    <th>Necessidade Financeira ({{ $anoPosterior }})</th>
                                    <th>-</th>
                                </tr>

                            </table>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    @endif
@else
@endif
