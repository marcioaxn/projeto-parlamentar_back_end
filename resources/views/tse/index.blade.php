@if (!empty($tse))
    @php
        if (isset($getParlamentar) && $getParlamentar) {
            isset($getParlamentar->num_total_votos) &&
            !is_null($getParlamentar->num_total_votos) &&
            $getParlamentar->num_total_votos != ''
                ? ($totalVotos = $getParlamentar->num_total_votos)
                : ($totalVotos = 0);
        } else {
            $totalVotos = 0;
        }
    @endphp
    <div class="row pl-1">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left">
            <div for="" class=" <?php isset($getParlamentar) && $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print 'bg-camara-sub-titulo-modal' : (isset($getParlamentar) && $getParlamentar->dsc_casa === 'Senado Federal' ? print 'bg-senado-sub-titulo-modal' : print 'bg-estado-sub-titulo-modal'); ?> rounded mt-2 pt-1 pb-1 pl-2">Principais Indicadores municipais
                conectados a este parlamentar</div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3 pt-1">

            <table id="tableTse" class="table table-sm mt-3 dt-responsive table-striped" cellspacing="0"
                style="width: 100% !Important;">

                <thead>
                    <tr>
                        <th class="text-center">
                            #
                        </th>
                        <th>
                            Município
                        </th>
                        <th class="text-right">

                            @if (isset($getParlamentar) && $getParlamentar)
                                Votos por município em <span
                                    class="font-numero">{{ $getParlamentar->num_ano_eleicao }}</span> ( total <span
                                    class="font-numero">{{ formatarNumeroInteiro($totalVotos) }}</span> )
                            @else
                                Votos por município
                            @endif

                        </th>
                        <th class="text-right">
                            IDH ( Brasil <span class="font-numero">0,727</span> )
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
                            <td style="width: 45%!Important;">
                                @php
                                    isset($value->nm_mun) && !is_null($value->nm_mun) && $value->nm_mun != ''
                                        ? ($municipioUrl = $value->cd_mun)
                                        : ($municipioUrl = '');
                                    isset($value->nm_mun) && !is_null($value->nm_mun) && $value->nm_mun != ''
                                        ? ($municipio = '/' . tirarAcentuacao(passarTextoParaMaiusculo($value->nm_mun)))
                                        : ($municipio = '');
                                @endphp
                                <a href="{!! route('uf-municipio', [$value->sg_uf, $municipioUrl]) !!}" target="_blank">
                                    {!! $value->sg_uf . $municipio !!}
                                </a>
                            </td>
                            <td class="text-right font-numero">
                                @php
                                    $value->qt_votos_nominais > 0 && $totalVotos > 0
                                        ? ($prcVotos = ($value->qt_votos_nominais / $totalVotos) * 100)
                                        : ($prcVotos = 0);
                                @endphp
                                {!! formatarNumeroInteiro($value->qt_votos_nominais) !!}&nbsp;
                                <span class="text-muted text-small"
                                    style="font-size: 0.8rem;">({!! converteValor('MYSQL', 'PTBR', $prcVotos) . '%' !!})</span>
                            </td>
                            <td class="text-right font-numero">
                                @if ($value->indicadores)
                                    @if (!is_null($value->indicadores->idc_idh_2010) && $value->indicadores->idc_idh_2010 < '0,727')
                                        <i class="fas fa-arrow-circle-down text-danger"></i>
                                    @elseif (!is_null($value->indicadores->idc_idh_2010) && $value->indicadores->idc_idh_2010 > '0,727')
                                        <i class="fas fa-arrow-circle-up text-success"></i>
                                    @elseif (!is_null($value->indicadores->idc_idh_2010) && ($value->indicadores->idc_idh_2010 = '0,727'))
                                        <i class="fas fa-check-circle text-primary"></i>
                                    @else
                                    @endif
                                    {!! $value->indicadores->idc_idh_2010 !!}
                                @else
                                    -
                                @endif
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
                        "lengthMenu": [
                            [5, 10, 25, 50, 100, -1],
                            ["5 ", "10 ", "25 ", "50 ", "100 ", "Todos "]
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
