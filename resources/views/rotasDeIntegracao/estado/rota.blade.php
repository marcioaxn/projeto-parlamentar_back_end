<div class="card sticky-top border border-0">
    <div class="card-body cardTemas shadow-sm pl-2" style="cursor: default!Important;">
        <i class="fas fa-route text-info"></i> Rotas de Integração Nacional <a class="d-print-none"
            href="https://www.gov.br/mdr/pt-br/assuntos/desenvolvimento-regional/rotas-de-integracao-nacional"
            target="_blank"><i class="fas fa-link"></i><span style="font-size: 0.6rem!Important;">fonte</span></a>
    </div>
</div>

<div class="row mt-1 pl-0">

    <style>
        @media screen {
            .hidden-on-screen {
                display: none;
            }
        }

        @media print {
            .hidden-on-screen {
                display: block;
            }
        }
    </style>

    @if ($rotasEstado)

        @foreach ($rotasEstado as $rota)
            <div class="col-3 col-xs-6 col-sm-3 col-md-6 col-lg-4 col-xl-3 m-1 pt-1 ">

                <?php
                $retornoRota = iconRotasIntegracaoNacional($rota->nom_rota);
                ?>

                <a href="{!! $retornoRota[1] !!}" class="" target="_blank">
                    <img src="{!! asset('img/rotas/' . $retornoRota[0]) !!}" class="img-fluid img-thumbnail" alt="{{ $rota->nom_rota }}"
                        style="width: 7rem!Important;">
                    <figcaption class="figure-caption text-start pt-1">presente em
                        (<strong>{{ $rota->num_quantidade }}</strong>)
                        município(s)</figcaption>
                </a>

            </div>
        @endforeach
    @else
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th class="text-danger">
                            Município não é integrante das rotas
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

</div>
