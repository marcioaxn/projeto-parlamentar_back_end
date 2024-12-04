@if (!empty($tse))
    <div class="row pl-1">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3 text-left">
            <div for="" class="bg-geral-sub-titulo-modal rounded mt-3 pt-1 pb-1 pl-2">Parlamentares que receberam
                votos</div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2">

            <table id="tableTse" class="table table-sm mt-3 dt-responsive table-striped" cellspacing="0"
                style="width: 100% !Important;">

                <thead>
                    <tr>
                        <th class="text-center">
                            #
                        </th>
                        <th>
                            Parlamentar
                        </th>
                        <th>
                            Cargo
                        </th>
                        <th>
                            Reeleito
                        </th>
                        <th>
                            Votos no município
                        </th>
                        <th>
                            % de votos no município
                        </th>
                        <th>
                            Total votos
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $contTse = 1;
                    @endphp
                    @foreach ($tse as $key => $value)
                        <tr>
                            <td class="text-center font-numero" style="width: 3%!Important;">
                                {!! $contTse !!}
                            </td>
                            <td style="width: 25%!Important;">
                                {!! $value->nm_candidato !!}
                            </td>
                            <td class="{!! $value->dsc_situacao === 'Exercício' ? 'text-primary' : '' !!}" style="width: 25%!Important;">
                                {!! $value->ds_cargo === '1º Suplente' || $value->ds_cargo === '2º Suplente' ? 'Senador' : '' !!}
                                {!! $value->ds_cargo !!} <span class="badge {!! $value->dsc_situacao === 'Exercício' ? 'bg-primary' : 'bg-secondary' !!} text-white"
                                    style="font-weight: normal !Important;">{!! $value->ano_eleicao !!}</span>
                            </td>
                            <td style="width: 25%!Important;">
                                -
                            </td>
                            <td style="width: 25%!Important;">
                                -
                            </td>
                            <td style="width: 25%!Important;">
                                -
                            </td>
                            <td style="width: 25%!Important;">
                                -
                            </td>
                        </tr>
                        @php
                            $contTse++;
                        @endphp
                    @endforeach

                </tbody>

            </table>

            <script type="text/javascript" charset="utf-8">
                $(document).ready(function() {
                    var table = $('#tableTse').DataTable({
                        "language": {
                            "url": "{{ asset('Portuguese-Brasil.json') }}",
                            "decimal": ",",
                            "thousands": "."
                        },
                        "order": [
                            [0, "asc"]
                        ],
                        columnDefs: [{
                            targets: [4], // Índice da coluna que contém os números formatados
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    return parseFloat(data).toLocaleString(
                                        'en-US'); // 'en-US' para ponto como separador de milhar
                                }
                                return data;
                            }
                        }],
                        "lengthMenu": [
                            [-1, 5, 10, 25, 50, 100],
                            ["Todos ", "5 ", "10 ", "25 ", "50 ", "100 "]
                        ],
                        "paging": true,
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
@endif
