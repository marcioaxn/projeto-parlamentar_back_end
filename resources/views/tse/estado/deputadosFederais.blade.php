<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3 pt-1 text-left">

    <div for="" class="bg-camara-titulo-modal-municipio rounded mt-3 pt-1 pb-1 pl-2">
        <span class="text-bold">Deputados Federais</span> <a class="d-print-none text-white"
            href="https://www.congressonacional.leg.br/parlamentares/em-exercicio" target="_blank"><i
                class="fas fa-link"></i><span style="font-size: 0.6rem!Important;">fonte</span></a>
    </div>

    <div class="row pl-1">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive mb-2 pt-1 text-left">

            <table id="tableTseDeputadosFederais" class="table table-sm mt-3 dt-responsive" cellspacing="0"
                style="width: 100% !Important;">

                <thead>
                    <tr>
                        <th id="thCountDF" class="text-center">
                            #
                        </th>
                        <th>
                            Deputado(a) Federal
                        </th>
                        <th>
                            Partido na eleição e atual
                        </th>
                        <th>
                            Situação eleição
                        </th>
                        <th>
                            Ano eleição - Reeleito
                        </th>
                        <th>
                            Em exercício
                        </th>
                        <th class="text-right">
                            Total votos
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @if ($tseDeputadosFederais)

                        @php
                            $contTse = 1;
                        @endphp
                        @foreach ($tseDeputadosFederais as $key => $value)
                            <tr>
                                <th class="<?php $value->dsc_situacao_atual === 'Exercício' ? print 'bg-tr-camara' : ''; ?> text-center font-numero" style="width: 3%!Important; {!! $value->dsc_situacao_atual === 'Exercício'
                                        ? 'background-color: #e4ece8 !Important; color: #000000 !Important;'
                                        : '' !!}">
                                    {!! $contTse !!}
                                </th>
                                <th class="<?php $value->dsc_situacao_atual === 'Exercício' ? print 'bg-tr-camara' : ''; ?>" style="width: 14%!Important; {!! $value->dsc_situacao_atual === 'Exercício'
                                        ? 'background-color: #e4ece8 !Important; color: #000000 !Important;'
                                        : '' !!}">

                                    @if ($value->dsc_situacao_atual === 'Exercício')
                                        <a class="text-dark" href="{!! url('parlamentar/' . $value->cod_parlamentar) !!}" target="_blank"
                                            data-bs-toggle="popover" data-bs-trigger="hover focus"
                                            data-bs-content="{{ $value->nm_candidato }}">
                                            {!! $value->nm_urna_candidato !!}
                                        </a>
                                    @else
                                        <a class="text-dark"
                                            href="https://divulgacandcontas.tse.jus.br/divulga/#/candidato/2022/2040602022/{{ $sgl_uf }}/{{ $value->sq_candidato_1 }}"
                                            target="_blank" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                            data-bs-content="{{ $value->nm_candidato }}">
                                            {!! $value->nm_urna_candidato !!}
                                        </a>
                                    @endif

                                </th>
                                <th class="<?php $value->dsc_situacao_atual === 'Exercício' ? print 'bg-tr-camara' : ''; ?>" style="width: 12%!Important; {!! $value->dsc_situacao_atual === 'Exercício'
                                        ? 'background-color: #e4ece8 !Important; color: #000000 !Important;'
                                        : '' !!}">

                                    @php
                                        $sglPartido = null;
                                        $sglPartidoTse = $value->sg_partido;

                                        if ($value->dsc_situacao_atual === 'Exercício') {
                                            $sglPartidoAtual = $value->sgl_partido_atual;

                                            if ($sglPartidoTse != $sglPartidoAtual) {
                                                $sglPartido = $sglPartidoTse . '<i class="fas fa-angle-right"></i>' . $sglPartidoAtual;
                                            } else {
                                                $sglPartido = $sglPartidoTse;
                                            }
                                        } else {
                                            $sglPartido = $sglPartidoTse;
                                        }

                                    @endphp

                                    {!! $sglPartido !!}


                                </th>
                                <th class="<?php $value->dsc_situacao_atual === 'Exercício' ? print 'bg-tr-camara' : ''; ?>" style="width: 10%!Important; {!! $value->dsc_situacao_atual === 'Exercício'
                                        ? 'background-color: #e4ece8 !Important; color: #000000 !Important;'
                                        : '' !!}">
                                    {!! $value->ds_sit_tot_turno !!}
                                </th>
                                <th class="<?php $value->dsc_situacao_atual === 'Exercício' ? print 'bg-tr-camara' : ''; ?>" style="width: 7%!Important; {!! $value->dsc_situacao_atual === 'Exercício'
                                        ? 'background-color: #e4ece8 !Important; color: #000000 !Important;'
                                        : '' !!}">
                                    <span class="font-numero">{!! $value->ano_eleicao !!}</span> -
                                    {{ $value->ds_sit_tot_turno != 'NÃO ELEITO' && passarTextoParaMaiusculo($value->st_reeleicao) === 'SIM' ? 'SIM' : 'NÃO' }}
                                </th>
                                <th class="<?php $value->dsc_situacao_atual === 'Exercício' ? print 'bg-tr-camara' : ''; ?>" style="width: 8%!Important; {!! $value->dsc_situacao_atual === 'Exercício'
                                        ? 'background-color: #e4ece8 !Important; color: #000000 !Important;'
                                        : '' !!}">

                                    @if ($value->dsc_situacao_atual === 'Exercício')
                                        SIM
                                    @endif

                                </th>
                                <th class="<?php $value->dsc_situacao_atual === 'Exercício' ? print 'bg-tr-camara' : ''; ?> text-right" style="width: 4%!Important; {!! $value->dsc_situacao_atual === 'Exercício'
                                        ? 'background-color: #e4ece8 !Important; color: #000000 !Important;'
                                        : '' !!}">
                                    {!! '<span class="font-numero">' . formatarNumeroInteiro($value->qt_votos_total) . '</span>' !!}
                                </th>
                            </tr>
                            @php
                                $contTse++;
                            @endphp
                        @endforeach

                    @endif

                </tbody>

            </table>

            <script type="text/javascript" charset="utf-8">
                $(document).ready(function() {
                    var table = $('#tableTseDeputadosFederais').DataTable({
                        "language": {
                            "url": "{{ asset('Portuguese-Brasil.json') }}",
                            "decimal": ",",
                            "thousands": "."
                        },
                        "order": [
                            [0, "asc"]
                        ],
                        "lengthMenu": [
                            [5, 10, 25, 50, 100, -1],
                            ["5 ", "10 ", "25 ", "50 ", "100 ", "Todos "]
                        ],
                        "pageLength": 25,
                        "paging": true,
                        "searching": false,
                        scrollx: true,
                        fixedHeader: {
                            header: true,
                            headerOffset: $('#header').outerHeight() - 2
                        },
                        scrollCollapse: false,
                    });

                });
            </script>

        </div>

    </div>

</div>
