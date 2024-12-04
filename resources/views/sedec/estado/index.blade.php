<div class="card sticky-top border border-0">
    <div class="card-body cardTemas shadow-sm" style="cursor: default!Important;">
        <img class="mt-0 pt-0 rounded" src="{!! asset('img/icones/areaInvestimento/defesaCivilCores2.png') !!}" height="18"
            alt="">&nbsp;&nbsp;Reconhecimento de Desastre pela Defesa Civil <a class="d-print-none"
            href="https://paineis.mdr.gov.br/2.%20Defesa%20Civil%20-%20Sistema%20S2iD/eyJrIjoiN2UxYzc4ZWMtMzg3OS00NWVjLTgyZGQtMDhjNWIxYzg5NTAxIiwidCI6Ijk2MTFlY2UxLTM0MTQtNGMzNS1hM2YwLTdkMTAwNDI5MGNkNiJ9"
            target="_blank"><i class="fas fa-link"></i><span style="font-size: 0.6rem!Important;">fonte</span></a>
    </div>
</div>

<div class="row mt-1 pl-1">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive">

        <table class="table table-borderless mt-0 pt-0 mb-0 pb-0">
            @if ($getGrupoReconhecimentosPorCodIbgeEstadoPorTempo)
                <thead>
                    <tr>
                        <th class="borda_table_indicadores text-muted text-small" style="font-size: 0.8rem !Important;">
                            <i class="fas fa-info-circle text-primary" style="font-size: 0.8rem!Important;"></i> <span
                                class="text-bold">Emergência ou calamidade pública</span>
                            no último ano pela SEDEC
                        </th>
                    </tr>
                </thead>
            @else
                <thead>
                    <tr>
                        <th class="text-primary">
                            Não houve reconhecimento de situação de emergência ou calamidade pública nos dez anos
                        </th>
                    </tr>
                </thead>
            @endif

        </table>

        @php
            $contReconhecimento = 0;
            $contReconhecimentoGeral = 0;
        @endphp

        <div class="row mt-0 pt-0">

            @foreach ($getGrupoReconhecimentosPorCodIbgeEstadoPorTempo as $s2id)
                @if ($contReconhecimento == 0)
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <table class="table table-borderless mt-0 pt-0">
                @endif

                <tr>
                    <th class="borda_table_indicadores">
                        {{ $s2id->nom_desastre }} <span
                            class="font-numero text-primary">({{ formatarNumeroInteiro($s2id->num_quantidade) }})</span>
                        @if ($s2id->bln_ocorreu_ultimos_sete_dias)
                            @if ($s2id->num_quantidade < 2)
                                <span class="font-numero text-danger"> <i class="fas fa-exclamation-triangle"></i>
                                    ocorreu
                                    nas últimas 72
                                    horas</span>
                            @else
                                <span class="font-numero text-danger"> <i class="fas fa-exclamation-triangle"></i> ao
                                    menos
                                    um ocorreu nas últimas
                                    72 horas</span>
                            @endif
                        @endif
                    </th>
                </tr>

                @if ($contReconhecimento == 6 || $contReconhecimentoGeral == count($getGrupoReconhecimentosPorCodIbgeEstadoPorTempo) - 1)
                    </table>
        </div>
        @endif

        @php
            $contReconhecimento++;
            $contReconhecimentoGeral++;
        @endphp

        @if ($contReconhecimento == 7)
            @php
                $contReconhecimento = 0;
            @endphp
        @endif
        @endforeach

    </div>
</div>

</div>
