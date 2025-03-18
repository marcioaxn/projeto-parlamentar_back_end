@extends('layouts.app')

@section('content')


    <!-- Início apresentação dos cards de entrada -->
    <div class="row" id="informacao">
        <div class="col-xs-12 col-sm-12 col-md-12"
            style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;">
            <i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando...
        </div>
    </div>

    <div class="row p-1" id="div1" style="display: none;">

        @php
            /* Início da parte dos dados do parlamentar */
        @endphp
        @if (isset($cod_parlamentar) && !is_null($cod_parlamentar) && $cod_parlamentar != '')
            {{-- Início da apresentação do parlamentar e a agenda do dia --}}

            <div class="card bg-white shadow-sm mb-3">
                <div class="row">

                    <div
                        class="col-12 col-sm-4 col-md-4 col-lg-2 col-xl-2 p-1 d-flex align-items-center justify-content-center">

                        <img src="<?php $getParlamentar->dsc_casa == 'Câmara dos Deputados' ? print asset('storage/fotos/deputados/' . $cod_parlamentar . '.jpg') : print asset('storage/fotos/senadores/' . $cod_parlamentar . '.jpg'); ?>" class="rounded"
                            style="min-width: 225px !Important; width: 245px !Important; max-width: 245px !Important; min-height: 314px !Important; height: 314px !Important; max-height: 314px !Important;">

                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-10 col-xl-2 p-1 pl-2 text-justify">

                        @php
                            $getParlamentar->dsc_sexo === 'Masculino'
                                ? ($contracaoPrepositiva = 'do')
                                : ($contracaoPrepositiva = 'da');
                        @endphp

                        <div class="card-title border-bottom fs-4 text-white bg-senado-titulo-modal rounded mt-1 mb-3 pt-1 pb-1 pl-2">
                            Gabinete {{ $contracaoPrepositiva }}
                            {{ $getParlamentar->dsc_tratamento }} - <span class="fw-bold">
                                {{ $getParlamentar->nom_parlamentar }} </span> -
                            {{ $getParlamentar->sgl_partido . '/' . $getParlamentar->sgl_uf_representante }}
                        </div>

                        <div class="row pl-2">

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 col-xl-7 p-1">

                                <p><strong>Agenda do dia {{ date('d/m/Y') }}</strong></p>

                                @if ($agendas->isNotEmpty() && $agendas->count() > 0)
                                    @foreach ($agendas as $agenda)
                                        <p class="text-info text-justify font-numero">
                                            {{ \Carbon\Carbon::parse($agenda->dat_inicio)->format('H:i') . ' às ' . \Carbon\Carbon::parse($agenda->dat_fim)->format('H:i') . ' - ' . $agenda->dsc_titulo }}
                                        </p>
                                    @endforeach
                                @else
                                    <div class="alert alert-warning">Nenhuma agenda cadastrada para este dia.</div>
                                @endif

                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5 col-xl-5 p-1">

                                <div class="row g-0 m-0 pr-2">

                                    <p><strong>Lideranças, Cargos e comissões</strong></p>

                                    @if ($getParlamentar->dsc_casa === 'Câmara dos Deputados')

                                        @if ($getParlamentar->cargosMesaDiretora)
                                            <p class="textoCargosEComissoes pt-2 pl-0">
                                                @php
                                                    $getParlamentar->cargosMesaDiretora->titulo === 'Presidente'
                                                        ? ($getParlamentar->cargosMesaDiretora->titulo =
                                                            'Presidente da Câmara dos Deputados')
                                                        : ($getParlamentar->cargosMesaDiretora->titulo =
                                                            $getParlamentar->cargosMesaDiretora->titulo .
                                                            ' da MESA DIRETORA');
                                                @endphp
                                                <span class="text-bold"><?php $getParlamentar->cargosMesaDiretora->titulo === 'Presidente da Câmara dos Deputados' ? print '<i class="fas fa-medal text-success"></i> ' : ''; ?>{!! mb_strtoupper($getParlamentar->cargosMesaDiretora->titulo, 'UTF-8') !!}</span>
                                            </p>
                                        @endif

                                        @if ($getParlamentar->liderancaDeputados->count() > 0)
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-1">

                                                @php
                                                    $contLideranca = 1;
                                                @endphp

                                                @foreach ($getParlamentar->liderancaDeputados as $lideranca)
                                                    <p class="textoCargosEComissoes">
                                                        {!! '<span class="font-numero">' . $contLideranca . '</span>. ' . $lideranca->titulo !!} do(a)
                                                        {{ $lideranca->tipo }} <?php $lideranca->nome != $lideranca->tipo ? print ' ' . $lideranca->nome : ''; ?> desde
                                                        <span
                                                            class="font-numero">{{ formatarDataComCarbonParaBR($lideranca->dataInicio) }}</span>
                                                    </p>

                                                    @php
                                                        $contLideranca++;
                                                    @endphp
                                                @endforeach

                                            </div>
                                        @endif

                                        @if ($getParlamentar->comissoesDeputados->count() > 0)
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-1">

                                                @foreach ($getParlamentar->comissoesDeputados as $comissao)
                                                    <div class="badge bg-camara-badge" data-bs-toggle="popover"
                                                        data-bs-trigger="hover focus"
                                                        data-bs-content="{!! $comissao->siglaOrgao !!} - {{ $comissao->nomePublicacao }}"
                                                        data-bs-placement="auto" style="cursor: help;">
                                                        <?php $comissao->siglaOrgao === 'CINDRE' ? print '<i class="fas fa-exclamation-triangle" style="font-size: 0.8rem !Important; color: #EDBE18;"></i> ' : ''; ?>{!! $comissao->siglaOrgao !!}</div>
                                                @endforeach

                                            </div>
                                        @endif
                                    @endif

                                    @if ($getParlamentar->dsc_casa === 'Senado Federal')

                                        @if ($getParlamentar->cargosMesaDiretoraSenado)
                                            <p class="textoCargosEComissoes mb-1 pt-2 pl-0 pb-0">
                                                @php
                                                    $getParlamentar->cargosMesaDiretoraSenado->Cargo === 'PRESIDENTE'
                                                        ? ($getParlamentar->cargosMesaDiretoraSenado->Cargo =
                                                            'Presidente do Senado Federal')
                                                        : ($getParlamentar->cargosMesaDiretoraSenado->Cargo =
                                                            $getParlamentar->cargosMesaDiretoraSenado->Cargo .
                                                            ' da MESA DO SENADO');
                                                @endphp
                                                <span class="text-bold"><?php $getParlamentar->cargosMesaDiretoraSenado->Cargo === 'Presidente do Senado Federal' ? print '<i class="fas fa-medal text-primary"></i> ' : ''; ?>{!! mb_strtoupper($getParlamentar->cargosMesaDiretoraSenado->Cargo, 'UTF-8') !!}</span>
                                            </p>
                                        @endif

                                        @if ($getParlamentar->liderancaSenadores->count() > 0 || $getParlamentar->cargosSenadores)
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-1">

                                                @php
                                                    $contLideranca = 1;
                                                @endphp

                                                @foreach ($getParlamentar->liderancaSenadores as $lideranca)
                                                    <p class="textoCargosEComissoes mb-1 pb-0">

                                                        @if (isset($lideranca->SiglaPartido) && !is_null($lideranca->SiglaPartido) && $lideranca->SiglaPartido != '')
                                                            {!! '<span class="font-numero">' .
                                                                $contLideranca .
                                                                '</span>. ' .
                                                                retornaTextoTirandoParteDoTexto($lideranca->DescricaoTipoLideranca, ' do Senado Federal') !!}
                                                            do
                                                            <span
                                                                style="font-size: 1rem !Important;">{!! $lideranca->SiglaPartido !!}</span>
                                                            no {{ $lideranca->SiglaCasaLideranca }} desde
                                                            <span
                                                                class="font-numero">{{ formatarDataComCarbonParaBR($lideranca->DataDesignacao) }}</span>
                                                        @else
                                                            {!! '<span class="font-numero">' . $contLideranca . '</span>. ' . $lideranca->UnidadeLideranca !!}
                                                            <?php isset($lideranca->NomeBloco) && !is_null($lideranca->NomeBloco) && $lideranca->NomeBloco != '' ? print 'do ' . $lideranca->NomeBloco : ''; ?>
                                                            desde
                                                            <span
                                                                class="font-numero">{{ formatarDataComCarbonParaBR($lideranca->DataDesignacao) }}</span>
                                                        @endif
                                                    </p>

                                                    @php
                                                        $contLideranca++;
                                                    @endphp
                                                @endforeach

                                                @php
                                                    $contCargo = $contLideranca;
                                                @endphp

                                                @foreach ($getParlamentar->cargosSenadores as $cargo)
                                                    @if (!is_null($cargo->colegiadoAtivo))
                                                        <p class="textoCargosEComissoes mb-1 pb-0">
                                                            {!! '<span class="font-numero">' .
                                                                $contCargo .
                                                                '</span>. ' .
                                                                primeiraLetraMaiuscula($cargo->DescricaoCargo) .
                                                                ' do(a) ' .
                                                                $cargo->SiglaComissao .
                                                                ' desde <span class="font-numero">' .
                                                                formatarDataComCarbonParaBR($cargo->DataInicio) .
                                                                '</span>' !!}
                                                        </p>

                                                        @php
                                                            $contCargo++;
                                                        @endphp
                                                    @endif
                                                @endforeach

                                            </div>
                                        @endif

                                        @if ($getParlamentar->comissoesSenadores->count() > 0)
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-1">

                                                @foreach ($getParlamentar->comissoesSenadores as $comissao)
                                                    @if (substr($comissao->SiglaComissao, 0, 1) === 'C')
                                                        <a href="https://legis.senado.leg.br/comissoes/comissao?codcol={!! $comissao->CodigoComissao !!}"
                                                            target="_blank">
                                                            <div class="badge bg-senado-badge" data-bs-toggle="popover"
                                                                data-bs-trigger="hover focus"
                                                                data-bs-content="{!! $comissao->SiglaCasaComissao !!} - {!! $comissao->SiglaComissao !!} - {{ $comissao->NomeComissao }}"
                                                                data-bs-placement="auto" style="cursor: help;"><i
                                                                    class="fas fa-link text-warning"
                                                                    style="font-size: 0.6rem !Important;"></i>
                                                                {!! $comissao->SiglaComissao !!}</div>
                                                        </a>
                                                    @else
                                                        <div class="badge bg-senado-badge" data-bs-toggle="popover"
                                                            data-bs-trigger="hover focus"
                                                            data-bs-content="{!! $comissao->SiglaCasaComissao !!} - {!! $comissao->SiglaComissao !!} - {{ $comissao->NomeComissao }}"
                                                            data-bs-placement="auto" style="cursor: help;">
                                                            {!! $comissao->SiglaComissao !!}</div>
                                                    @endif
                                                @endforeach

                                            </div>
                                        @endif


                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- Fim da apresentação do parlamentar e a agenda do dia --}}

            {{-- Início da montagem das tabs de navegação --}}

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-1 align-items-center justify-content-left">

                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    @php
                        $contTabs = 1;
                        isset($tabselecionada) && !empty($tabselecionada)
                            ? ($tabselecionada = $tabselecionada)
                            : ($tabselecionada = 'Contatos');
                    @endphp

                    @foreach ($temas as $tema)
                        @php
                            $idTab = 'tab' . md5($tema);
                        @endphp

                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $tabselecionada === $tema ? 'active' : '' }}"
                                id="{{ $idTab }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $idTab }}"
                                type="button" role="tab" aria-controls="{{ $idTab }}" aria-selected="true"><i
                                    class="{{ iconeServicosGabiente($tema) }}"></i> {{ $tema }}</button>
                        </li>

                        @php
                            $contTabs++;
                        @endphp
                    @endforeach
                </ul>

                @php
                    $contTabs = 1;
                @endphp

                <div class="tab-content" id="myTabContent">

                    @foreach ($temas as $tema)
                        @php
                            $idTab = 'tab' . md5($tema);
                        @endphp
                        <div class="tab-pane fade {{ $tabselecionada === $tema ? 'show active' : '' }} p-2 pt-2"
                            id="{{ $idTab }}" role="tabpanel" aria-labelledby="{{ $idTab }}-tab">

                            @if ($tema === 'Agenda/Audiências/Eventos')
                                @include('agendas.agenda')
                            @elseif ($tema === 'Contatos')
                                @include('contatos.contato')
                            @endif

                        </div>
                        @php
                            $contTabs++;
                        @endphp
                    @endforeach

                </div>

            </div>

            {{-- Fim da montagem das tabs de navegação --}}

            @php
                /* Fim do loop dos temas */
            @endphp
        @endif
        @php
            /* Fim da parte dos dados do parlamentar */
        @endphp

    </div>
    <!-- Fim apresentação dos cards de entrada -->

    <!-- Início funções javascript -->
    <script>
        setTimeout(function() {
            $("#div1").fadeIn("slow");
        }, 700);

        setTimeout(function() {
            $("#informacao").fadeOut("slow");
        }, 300);
    </script>
    <!-- Fim funções javascript -->
@endsection
