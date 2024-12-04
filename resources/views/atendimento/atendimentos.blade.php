@if ($atendimentos->count() > 0)
    @php
        isset($cod_parlamentar) && !is_null($cod_parlamentar) && $cod_parlamentar != ''
            ? ($cod_parlamentar = $cod_parlamentar)
            : ($cod_parlamentar = null);
    @endphp
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2">

        <div class="row justify-content-start row-cols-md-3">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 m-0 mt-3 mb-2">
                <i class="fas fa-exclamation-triangle text-primary"></i> Cards ordenados por ordem de data de atendimento
            </div>

            @php
                $contAtendimento = 1;
            @endphp

            @foreach ($atendimentos as $atendimento)
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 m-0 mt-2 mb-4">

                    <div class="card h-100 shadow rounded-3 border-1 border-info m-0 mt-2 pt-2 mb-3 pb-2"
                        style="min-height: 15.9rem!Important; background-color: #f7f7f7 !Important; border: 1px dotted #c2ddf4 !Important;">

                        <div class="row m-0 mb-2">

                            <div class="col-11 d-flex align-items-center justify-content-center h-100">

                                <div class="row">

                                    <div
                                        class="col-xs-12 col-sm-12 col-md-12 col-lg-12 d-flex align-items-center justify-content-center pt-2">

                                        <span class="tituloCard">{!! '<span class="font-numero">' . $contAtendimento . '</span>. ' . $atendimento->assunto->dsc_assunto !!}</span>

                                    </div>

                                    <div
                                        class="col-xs-12 col-sm-12 col-md-12 col-lg-12 d-flex align-items-center justify-content-center">

                                        <span>{!! '<span class="font-numero">' . formatarDataComCarbonParaBR($atendimento->dte_atendimento) . '</span>' !!}</span>
                                    </div>

                                </div>

                            </div>

                            <div class="col-1 d-flex align-items-center justify-content-center ml-0 pl-0 h-100"
                                style="display: flex; align-items: center; justify-content: center;">

                                <div class="dropdown">

                                    <a href="" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v" style="color: #1351B4"></i>
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#cardModal"
                                                data-card-id="{{ $atendimento->cod_atendimento }}"><i
                                                    class="fas fa-search-plus"></i> Abrir detalhes</a>
                                        </li>
                                        @if (Session::get('permissao') === '0000100')
                                            <li>
                                                <a class="dropdown-item" href="{!! route('atendimento.editar', [$atendimento->cod_atendimento, $cod_parlamentar]) !!}"><i
                                                        class="fas fa-edit text-success"></i> Editar</a>
                                            </li>
                                        @endif
                                    </ul>

                                </div>

                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <hr class="">
                            </div>

                        </div>

                        @if ($atendimento->demandas->count() > 0)
                            <div class="row m-0 mt-1 mb-1 pt-4">

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 mb-2 pl-3 text-justify"
                                    style="min-height: 4rem!Important;">

                                    <table class="table table-sm">

                                        <thead>
                                            <tr>
                                                <td class="text-bold border-top-0"
                                                    style="font-size: 0.75rem !Important;">Demanda</td>
                                                <td class="text-bold border-top-0"
                                                    style="font-size: 0.75rem !Important;">Prazo</td>
                                                <td class="text-bold border-top-0"
                                                    style="font-size: 0.75rem !Important;">Status</td>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($atendimento->demandas as $demanda)
                                                <tr>
                                                    <td style="width: 50% !Important; font-size: 0.75rem !Important;">
                                                        {!! $demanda->dsc_demanda !!}
                                                    </td>
                                                    <td style="width: 17% !Important; font-size: 0.75rem !Important;">
                                                        <span
                                                            class="font-numero">{!! formatarDataComCarbonParaBR($demanda->dte_prazo) !!}</span><br /><span
                                                            style="font-size: 0.75rem !Important;">({!! formatarDataComCarbonForHumans($demanda->dte_prazo) !!})</span>
                                                    </td>
                                                    <td style="font-size: 0.75rem !Important;">
                                                        <div class="row p-0">
                                                            <div class="col-1 text-right pt-1">
                                                                <span class="circleStatus"
                                                                    style="background-color: {{ $demanda->status->nom_cor_graduacao }} !Important;"></span>
                                                            </div>
                                                            <div class="col-9 text-left">
                                                                {!! $demanda->status->dsc_status !!}
                                                            </div>

                                                        </div>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>


                                </div>

                            </div>
                        @endif

                        @if ($atendimento->convidados->count() > 0 && 1 != 1)
                            <div class="row m-0 mb-1">

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 mb-2 pl-3 text-justify"
                                    style="min-height: 4rem!Important;">

                                    <table class="table table-bordered table-sm" style="">

                                        <thead>
                                            <tr>
                                                <td class="text-bold">Convidado(a)</td>
                                                <td class="text-bold">Cargo</td>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($atendimento->convidados as $convidado)
                                                <tr>
                                                    <td style="width: 50% !Important;">
                                                        {!! $convidado->nom_convidado !!}
                                                    </td>
                                                    <td>
                                                        {!! $convidado->interlocutor->dsc_interlocutor !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>


                                </div>

                            </div>
                        @endif

                        @if ($atendimento->arquivos->count() > 0 && 1 != 1)
                            <div class="row m-0 mb-1">

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 mb-2 pl-3 text-justify"
                                    style="min-height: 4rem!Important;">

                                    <table class="table table-bordered table-sm" style="">

                                        <thead>
                                            <tr>
                                                <td colspan="2" class="text-bold">Arquivo(s)</td>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($atendimento->arquivos as $arquivo)
                                                <tr>
                                                    <td style="width: 90% !Important;">
                                                        {!! $arquivo->txt_assunto !!}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{!! asset($arquivo->nom_arquivo) !!}" target="_blank">
                                                            <i class="fas fa-file-pdf text-danger"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>


                                </div>

                            </div>
                        @endif

                    </div>

                </div>

                @php
                    $contAtendimento++;
                @endphp
            @endforeach

            <script>
                function abrirEdicao(conAtendimento, totalAtendimentos) {

                    for (let index = 1; index <= totalAtendimentos; index++) {

                        if (document.getElementById('divEditarAtendimento_' + index)) {

                            $('#divEditarAtendimento_' + index).fadeOut('slow');

                        }

                    }

                    $('html, body').animate({
                        scrollTop: 0 + ($('#app').position().top) + 475
                    }, 'slow');
                    setTimeout(function() {
                        $('#divEditarAtendimento_' + conAtendimento).fadeIn('slow');
                    }, 400);

                }
            </script>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 m-0 mt-0 mb-1">
                <div class="row">

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1">

                        Legenda status da demanda:

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="d-flex flex-row mb-3" style="font-size: 0.75rem !Important;">

                            <div class="pt-2 pl-2 pr-2">
                                <span class="circleStatus" style="background-color: #5C636A !Important;"></span>
                            </div>

                            <div class="pt-1 pl-2 pr-2">
                                Recebida
                            </div>

                            <div class="pt-2 pl-2 pr-2">
                                <span class="circleStatus" style="background-color: #198754 !Important;"></span>
                            </div>

                            <div class="pt-1 pl-2 pr-2">
                                Em andamento
                            </div>

                            <div class="pt-2 pl-2 pr-2">
                                <span class="circleStatus" style="background-color: #FFC107 !Important;"></span>
                            </div>

                            <div class="pt-1 pl-2 pr-2">
                                Aguardando outra demanda / Suspensa
                            </div>

                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="d-flex flex-row mb-3" style="font-size: 0.75rem !Important;">

                            <div class="pt-1 pl-2 pr-2">
                                <span class="circleStatus" style="background-color: #0D6EFD !Important;"></span>
                            </div>

                            <div class="pt-1 pl-2 pr-2">
                                Atendida
                            </div>
                            <div class="pt-1 pl-2 pr-2">
                                <span class="circleStatus" style="background-color: #BB2D3B !Important;"></span>
                            </div>

                            <div class="pt-1 pl-2 pr-2">
                                Não atendida
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @php
        // Início da parte da modal dos atendimentos
    @endphp

    <div class="modal fade" id="cardModal" tabindex="-1"
        style="padding-top: 6rem!Important; min-width: 95vw!Important;">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" style="min-width: 95vw!Important;">
            <div class="modal-content">
                <div class="modal-body" style="min-height: 79vh!Important; background-color: #FFFFFF !Important;">

                    @if (isset($getParlamentar))
                        <div class="text-left text-secondary m-0 p-0" style="font-size: 0.9rem !Important;">
                            {!! $getParlamentar->dsc_tratamento . ' - <strong>' . $getParlamentar->nom_parlamentar . '</strong>' !!}
                        </div>
                    @else
                        <div class="text-left text-secondary m-0 p-0" style="font-size: 0.9rem !Important;">
                            &nbsp;
                        </div>
                    @endif



                    <div class="text-right m-0 mb-3 p-0"
                        style="margin-top: -23px !Important; padding-top: -17px !Important;">
                        <i class="far fa-times-circle text-secondary" data-bs-dismiss="modal" aria-label="Close"
                            style="cfont-size: 1rem !Important; cursor: pointer;"></i>
                    </div>

                    <div id="carouselExampleControlsNoTouching" class="carousel slide m-0 p-0" data-bs-touch="false"
                        data-bs-interval="false">

                        <div class="carousel-inner">

                            @php
                                $contAtendimento = 1;
                            @endphp

                            @foreach ($atendimentos as $atendimento)
                                <div id="{!! $atendimento->cod_atendimento !!}" class="carousel-item <?php $contAtendimento == 1 ? print 'active' : ''; ?>">

                                    <div class="row">

                                        @php
                                            // Início botões de navegação do carrossel na modal
                                        @endphp

                                        <div
                                            class="d-flex justify-content-between col-xs-12 col-sm-12 col-md-12 col-lg-12 m-0 pt-1 pb-1 mb-0">

                                            @if ($contAtendimento != 1)
                                                <button class="btn btn-outline-secondary btn-sm mt-0 mb-0"
                                                    type="button" data-bs-target="#carouselExampleControlsNoTouching"
                                                    data-bs-slide="prev"
                                                    style="font-size: 0.7rem !Important; height: 29px !Important;">
                                                    <i class="fas fa-arrow-left"
                                                        style="font-size: 0.6rem !Important;"></i>
                                                    <span class="visually-hidden">Atendimento
                                                        anterior</span>
                                                </button>
                                            @else
                                                <span>&nbsp;</span>
                                            @endif

                                            @if ($contAtendimento != $atendimentos->count())
                                                <button class="btn btn-outline-secondary btn-sm mt-0 mb-0"
                                                    type="button" data-bs-target="#carouselExampleControlsNoTouching"
                                                    data-bs-slide="next"
                                                    style="font-size: 0.7rem !Important; height: 29px !Important;">
                                                    <span class="visually-hidden">Próximo
                                                        Atendimento</span>
                                                    <i class="fas fa-arrow-right"
                                                        style="font-size: 0.6rem !Important;"></i>
                                                </button>
                                            @else
                                                <span>&nbsp;</span>
                                            @endif

                                        </div>

                                        @php
                                            // Fim botões de navegação do carrossel na modal
                                        @endphp

                                    </div>

                                    <div class="pt-1 pb-1 pl-2 <?php isset($getParlamentar) && $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print 'bg-camara-titulo-modal' : print 'bg-senado-titulo-modal'; ?> rounded">
                                        <p class="modal-title text-white text-bold"
                                            style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                            {!! '<span class="font-numero">' . $contAtendimento . '</span>. ' . $atendimento->assunto->dsc_assunto !!}
                                        </p>
                                    </div>

                                    @php
                                        $column_name = null;
                                        $data_type = null;
                                    @endphp

                                    <div class="row">

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-1 text-left">
                                            <div for=""
                                                class=" <?php isset($getParlamentar) && $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print 'bg-camara-sub-titulo-modal' : print 'bg-senado-sub-titulo-modal'; ?> rounded mt-3 pt-1 pb-1 pl-2">Detalhes do
                                                atendimento
                                            </div>
                                        </div>

                                        @foreach ($estruturaTableAtendimento as $table)
                                            @php
                                                $columnName = $table->column_name;
                                                $data_type = $table->data_type;
                                                $ordinalPosition = $table->ordinal_position;
                                            @endphp

                                            @if (
                                                $columnName != 'cod_atendimento' &&
                                                    $columnName != 'cod_interlocutor' &&
                                                    $columnName != 'nom_interlocutor' &&
                                                    $columnName != 'cod_assunto' &&
                                                    $columnName != 'dsc_cargo_representante' &&
                                                    $atendimento->$columnName != '')
                                                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 mt-2">

                                                    @if ($columnName === 'nom_representante')
                                                        <p for="{!! $columnName !!}"
                                                            class="m-0 pt-0 pb-0 pl-1 mb-1"
                                                            style="font-size: 0.99rem !Important;">
                                                            Nome e Cargo do(a) Representante
                                                        </p>
                                                    @else
                                                        <p for="{!! $columnName !!}"
                                                            class="m-0 pt-0 pb-0 pl-1 mb-1"
                                                            style="font-size: 0.99rem !Important;">
                                                            {!! nomeCampoNormalizadoTabAtendimento($columnName) !!}</p>
                                                    @endif

                                                    @if ($columnName === 'cod_cargo')
                                                        <p class="text-left m-0 p-1 rounded p-1 text-bold">
                                                            {!! $atendimento->quemAtendeu->dsc_cargo !!}
                                                        </p>
                                                    @elseif ($columnName === 'dte_atendimento')
                                                        <p class="text-left m-0 p-1 rounded p-1 font-numero text-bold">
                                                            {!! formatarDataComCarbonParaBR($atendimento->dte_atendimento) !!}
                                                        </p>
                                                    @elseif ($columnName === 'nom_representante')
                                                        <p class="text-left m-0 p-1 rounded p-1 font-numero text-bold">
                                                            {!! $atendimento->nom_representante . ' / ' . $atendimento->dsc_cargo_representante !!}
                                                        </p>
                                                    @else
                                                        <p class="text-left m-0 p-1 rounded p-1 text-bold">
                                                            {!! $atendimento->$columnName !!}
                                                        </p>
                                                    @endif

                                                </div>
                                            @endif
                                        @endforeach

                                    </div>

                                    <div class="row mb-0">

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">
                                            <div for=""
                                                class=" <?php isset($getParlamentar) && $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print 'bg-camara-sub-titulo-modal' : print 'bg-senado-sub-titulo-modal'; ?> rounded mt-3 pt-1 pb-1 pl-2">Convidados
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 mb-1 pl-3 text-justify"
                                            style="min-height: 2rem!Important;">

                                            @if ($atendimento->convidados->count() > 0)
                                                <div class="row pl-2">

                                                    @foreach ($atendimento->convidados as $convidado)
                                                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 mt-2">

                                                            <p>
                                                                {!! $convidado->nom_convidado . ' - ' . $convidado->interlocutor->dsc_interlocutor !!}
                                                            </p>

                                                        </div>
                                                    @endforeach

                                                </div>
                                            @else
                                                <p class="pl-2">
                                                    Sem convidados
                                                </p>
                                            @endif


                                        </div>

                                    </div>

                                    @php
                                        // Início da parte das demandas na modal
                                    @endphp

                                    <div class="row mb-0">

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">
                                            <div for=""
                                                class=" <?php isset($getParlamentar) && $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print 'bg-camara-sub-titulo-modal' : print 'bg-senado-sub-titulo-modal'; ?> rounded mt-3 pt-1 pb-1 pl-2">Demandas</div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 mb-1 pl-3 text-justify"
                                            style="min-height: 2rem!Important;">

                                            @if ($atendimento->demandas->count() > 0)
                                                <div class="row pl-2">

                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 mb-2 pl-3 text-justify"
                                                        style="min-height: 4rem!Important;">

                                                        <table class="table table-sm">

                                                            <thead>
                                                                <tr>
                                                                    <td class="text-bold border-top-0"
                                                                        style="font-size: 0.75rem !Important;">Demanda
                                                                    </td>
                                                                    <td class="text-bold border-top-0"
                                                                        style="font-size: 0.75rem !Important;">
                                                                        Área Responsável
                                                                    </td>
                                                                    <td class="text-bold border-top-0"
                                                                        style="font-size: 0.75rem !Important;">Prazo
                                                                    </td>
                                                                    <td class="text-bold border-top-0"
                                                                        style="font-size: 0.75rem !Important;">Status
                                                                    </td>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($atendimento->demandas as $demanda)
                                                                    <tr>
                                                                        <td
                                                                            style="width: 40% !Important; font-size: 0.75rem !Important;">
                                                                            {!! $demanda->dsc_demanda !!}
                                                                        </td>
                                                                        <td
                                                                            style="width: 10% !Important; font-size: 0.75rem !Important;">
                                                                            {!! $demanda->orgaoResponsavel->sigla !!}
                                                                        </td>
                                                                        <td
                                                                            style="width: 25% !Important; font-size: 0.75rem !Important;">
                                                                            <span
                                                                                class="font-numero">{!! formatarDataComCarbonParaBR($demanda->dte_prazo) !!}</span>
                                                                            <span
                                                                                style="font-size: 0.75rem !Important;">(
                                                                                {!! retornaTextoTrocandoParteDoTexto(formatarDataComCarbonForHumans($demanda->dte_prazo)) !!} )</span>
                                                                        </td>
                                                                        <td
                                                                            style="width: 25% !Important; font-size: 0.75rem !Important;">
                                                                            <div class="row p-0">
                                                                                <div class="col-1 text-right pt-1">
                                                                                    <span class="circleStatus"
                                                                                        style="background-color: {{ $demanda->status->nom_cor_graduacao }} !Important;"></span>
                                                                                </div>
                                                                                <div class="col-9 text-left">
                                                                                    {!! $demanda->status->dsc_status !!}
                                                                                </div>

                                                                            </div>

                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>

                                                        </table>


                                                    </div>

                                                </div>
                                            @else
                                                <p class="pl-2">
                                                    Sem demandas
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                    @php
                                        // Fim da parte das demandas na modal
                                    @endphp

                                    @php
                                        // Início da parte dos arquivos na modal
                                    @endphp

                                    <div class="row mb-0">

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">
                                            <div for=""
                                                class=" <?php isset($getParlamentar) && $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print 'bg-camara-sub-titulo-modal' : print 'bg-senado-sub-titulo-modal'; ?> rounded mt-3 pt-1 pb-1 pl-2">Anexos</div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-2 mb-1 pl-3 text-justify"
                                            style="min-height: 2rem!Important;">

                                            @if ($atendimento->arquivos->count() > 0)
                                                <div class="row pl-2">

                                                    @foreach ($atendimento->arquivos as $arquivo)
                                                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 mt-2">
                                                            <a href="{!! asset($arquivo->nom_arquivo) !!}" target="_blank">
                                                                <i class="fas fa-file-pdf text-danger"></i>
                                                                {!! $arquivo->txt_assunto !!}
                                                            </a>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            @else
                                                <p class="pl-2">
                                                    Sem anexos
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                    @php
                                        // Fim da parte dos arquivos na modal
                                    @endphp

                                </div>
                                @php
                                    $contAtendimento++;
                                @endphp
                            @endforeach

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#cardModal').on('show.bs.modal', function(event) {

                // Seleciona o carousel
                var carousel = document.getElementById('carouselExampleControlsNoTouching');

                // Atualiza o item ativo do carousel
                var carouselItems = carousel.querySelectorAll('.carousel-item');
                carouselItems.forEach(function(item) {
                    item.classList.remove('active');
                });

                // Inicializa o carousel
                var carouselInstance = new bootstrap.Carousel(carousel);

                var button = $(event.relatedTarget);
                var cardId = button.data('card-id');
                $("#" + cardId).addClass("active");
            });
        });
    </script>

    @php
        // Fim da parte da modal dos atendimentos
    @endphp

    <script>
        $(document).ready(function() {
            // Seletor do seu carrossel
            var $carousel = $('.carousel');

            // Adicione a função de navegação com as setas do teclado
            $(document).keydown(function(e) {
                if (e.keyCode == 37) {
                    // Setinha esquerda
                    $carousel.carousel('prev');
                } else if (e.keyCode == 39) {
                    // Setinha direita
                    $carousel.carousel('next');
                }
            });
        });
    </script>
@else
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-3">
        <p>
            Sem atendimento cadastrado
        </p>
    </div>
@endif
