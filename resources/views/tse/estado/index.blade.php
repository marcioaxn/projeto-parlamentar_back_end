<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3 pt-1 text-left">

        <div for="" class="bg-geral-sub-titulo-modal rounded mt-3 pt-1 pb-1 pl-2">Governador(a)</div>

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-2 pt-1 text-left">

                <div class="row ml-1 mr-1">

                    <div
                        class="col-12 col-xs-12 col-sm-6 col-md-3 col-lg-3 pt-1 pb-2 pl-0 d-flex align-items-center border-bottom text-justify ">

                        <span class="text-bold" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ $tseGovernador->nm_candidato }}"
                            style="cursor: help;">{!! $tseGovernador->nm_urna_candidato . ' - ' . $tseGovernador->sg_partido !!}</span>
                        &nbsp;eleito em &nbsp;<span class="font-numero">{{ $tseGovernador->ano_eleicao }}</span>

                    </div>

                    <div
                        class="col-12 col-xs-12 col-sm-6 col-md-9 col-lg-9 pt-1 pb-2 pl-0 d-flex align-items-center border-bottom text-justify ">

                        @php
                            $tseGovernador->qt_votos_total > 0 ? ($percentualVotos = ($tseGovernador->qt_votos_nominais / $tseGovernador->qt_votos_total) * 100) : ($percentualVotos = 0);
                        @endphp

                        Total de&nbsp;<span
                            class="font-numero text-bold">{{ formatarNumeroInteiro($tseGovernador->qt_votos_total) }}</span>
                        &nbsp; de votos no estado &nbsp;(<span class="text-bold">{{ $sgl_uf }}</span>).

                    </div>

                </div>

            </div>

        </div>

    </div>

    @include('tse.estado.senadores')

    @include('tse.estado.deputadosFederais')

</div>
