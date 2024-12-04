<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3 pt-1 text-left">

    <div for="" class="bg-senado-titulo-modal-municipio rounded mt-3 pt-1 pb-1 pl-2">
        <span class="text-bold">Senadores</span> <a class="d-print-none text-white"
            href="https://www.congressonacional.leg.br/parlamentares/em-exercicio" target="_blank"><i
                class="fas fa-link"></i><span style="font-size: 0.6rem!Important;">fonte</span></a>
    </div>

    <div class="row pl-1">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive mb-2 pt-1 text-left">

            <table id="tableTseSenadores" class="table table-sm mt-3 dt-responsive" cellspacing="0"
                style="width: 100% !Important;">

                <thead>
                    <tr>
                        <th class="text-center">
                            #
                        </th>
                        <th>
                            Senador(a)
                        </th>
                        <th>
                            Cargo
                        </th>
                        <th>
                            Partido na eleição e atual
                        </th>
                        <th>
                            Ano eleição - Reeleito
                        </th>
                        <th class="text-right">
                            Total votos
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @if ($tseSenadores)

                        @php
                            $contTse = 1;
                        @endphp
                        @foreach ($tseSenadores as $key => $value)
                            <tr>
                                <th class="{!! $value->dsc_situacao_tp === 'Exercício' ? 'bg-tr-senado' : '' !!} text-center font-numero"
                                    style="width: 3%!Important; {!! $value->dsc_situacao_tp === 'Exercício'
                                        ? 'background-color: #e2ecf3 !Important; color: #000000 !Important;'
                                        : '' !!}">
                                    {!! $contTse !!}
                                </th>
                                <th class="{!! $value->dsc_situacao_tp === 'Exercício' ? 'bg-tr-senado' : '' !!}"
                                    style="width: 25%!Important; {!! $value->dsc_situacao_tp === 'Exercício'
                                        ? 'background-color: #e2ecf3 !Important; color: #000000 !Important;'
                                        : '' !!}">

                                    @if (isset($value->cod_parlamentar) && !is_null($value->cod_parlamentar) && $value->cod_parlamentar != '')
                                        <a class="text-dark" href="{!! url('parlamentar/' . $value->cod_parlamentar) !!}" target="_blank"
                                            data-bs-toggle="popover" data-bs-trigger="hover focus"
                                            data-bs-content="{{ $value->nm_candidato }}">
                                            {!! $value->ds_cargo === 'Senador' || $value->ds_cargo === 'SENADOR'
                                                ? $value->nm_urna_candidato
                                                : $value->nom_parlamentar_sem_formatacao !!}
                                        </a>
                                    @else
                                        {!! $value->ds_cargo === 'Senador' || $value->ds_cargo === 'SENADOR'
                                            ? $value->nm_urna_candidato
                                            : $value->nm_candidato !!}
                                    @endif

                                </th>
                                <th class="{!! $value->dsc_situacao_tp === 'Exercício' ? 'bg-tr-senado' : '' !!}"
                                    style="width: 12%!Important; {!! $value->dsc_situacao_tp === 'Exercício'
                                        ? 'background-color: #e2ecf3 !Important; color: #000000 !Important;'
                                        : '' !!}">

                                    {!! $value->ds_cargo === '1º Suplente' || $value->ds_cargo === '2º Suplente'
                                        ? '<span class="pl-3">' . passarTextoParaMaiusculo($value->ds_cargo) . '</span>'
                                        : '<span class="text-bold">TITULAR</span>' !!}

                                </th>
                                <th class="{!! $value->dsc_situacao_tp === 'Exercício' ? 'bg-tr-senado' : '' !!}"
                                    style="width: 12%!Important; {!! $value->dsc_situacao_tp === 'Exercício'
                                        ? 'background-color: #e2ecf3 !Important; color: #000000 !Important;'
                                        : '' !!}">
                                    @php
                                        $sglPartido = null;
                                        $sglPartidoTse = $value->sg_partido;

                                        if ($value->dsc_situacao_tp === 'Exercício') {
                                            $sglPartidoAtual = $value->sgl_partido_atual;

                                            if ($sglPartidoTse != $sglPartidoAtual) {
                                                $sglPartido =
                                                    $sglPartidoTse .
                                                    '<i class="fas fa-angle-right"></i>' .
                                                    $sglPartidoAtual;
                                            } else {
                                                $sglPartido = $sglPartidoTse;
                                            }
                                        } else {
                                            $sglPartido = $sglPartidoTse;
                                        }

                                    @endphp

                                    {!! $sglPartido !!}
                                </th>
                                <th class="{!! $value->dsc_situacao_tp === 'Exercício' ? 'bg-tr-senado' : '' !!}"
                                    style="width: 12%!Important; {!! $value->dsc_situacao_tp === 'Exercício'
                                        ? 'background-color: #e2ecf3 !Important; color: #000000 !Important;'
                                        : '' !!}">

                                    {!! $value->ds_cargo === 'Senador' || $value->ds_cargo === 'SENADOR'
                                        ? '<span class="font-numero">' . $value->ano_eleicao . '</span> - ' . passarTextoParaMaiusculo($value->st_reeleicao)
                                        : '' !!}

                                </th>
                                <th class="{!! $value->dsc_situacao_tp === 'Exercício' ? 'bg-tr-senado' : '' !!} text-right"
                                    style="width: 12%!Important; {!! $value->dsc_situacao_tp === 'Exercício'
                                        ? 'background-color: #e2ecf3 !Important; color: #000000 !Important;'
                                        : '' !!}">

                                    {!! $value->ds_cargo === 'Senador' || $value->ds_cargo === 'SENADOR'
                                        ? '<span class="font-numero">' . formatarNumeroInteiro($value->qt_votos_total) . '</span>'
                                        : '' !!}

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
                    var table = $('#tableTseSenadores').DataTable({
                        "language": {
                            "url": "{{ asset('Portuguese-Brasil.json') }}",
                            "decimal": ",",
                            "thousands": "."
                        },
                        "order": [
                            [0, "asc"]
                        ],
                        "lengthMenu": [
                            [-1, 5, 10, 25, 50, 100],
                            ["Todos ", "5 ", "10 ", "25 ", "50 ", "100 "]
                        ],
                        "paging": false,
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
