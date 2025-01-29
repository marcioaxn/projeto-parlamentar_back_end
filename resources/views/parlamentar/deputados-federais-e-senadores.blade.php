<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">

    <div class="card shadow-sm">
        <div class="card-header <?php $getParlamentar->dsc_casa == 'Câmara dos Deputados' ? print 'bg-camara' : print 'bg-senado'; ?> p-1 pl-3">
            {{ $getParlamentar->dsc_tratamento . ' - ' . $getParlamentar->nom_parlamentar . ' - ' . $getParlamentar->sgl_partido . '/' . $getParlamentar->sgl_uf_representante }}
        </div>
        <div class="card-body bg-white pt-2 pb-2">

            <div class="row">

                <div
                    class="col-xs-12 col-sm-4 col-md-4 col-lg-3 col-xl-2 pt-4 d-flex align-items-center justify-content-center">

                    <figure class="figure">
                        <img src="<?php $getParlamentar->dsc_casa == 'Câmara dos Deputados' ? print asset('storage/fotos/deputados/' . $cod_parlamentar . '.jpg') : print asset('storage/fotos/senadores/' . $cod_parlamentar . '.jpg'); ?>" class="figure-img img-fluid shadow-sm rounded"
                            style="min-width: 225px !Important; width: 245px !Important; max-width: 245px !Important; min-height: 314px !Important; height: 314px !Important; max-height: 314px !Important;">
                    </figure>

                </div>

                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-6 pt-3 ">

                    <div class="row">

                        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 text-justify border-bottom divParlamentar mb-1"
                            style="padding-bottom: 0.4rem !Important;">
                            <span class="textoNormalTabela">Agenda do dia {{ date('d/m/Y') }}</span>
                        </div>

                        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 text-justify divParlamentar">

                            @if ($agendas->isNotEmpty()  && $agendas->count() > 0)
                                @foreach ($agendas as $agenda)
                                    <p class="text-info text-justify font-numero">
                                        {{ \Carbon\Carbon::parse($agenda->dat_inicio)->format('H:i') . ' às ' . \Carbon\Carbon::parse($agenda->dat_fim)->format('H:i') . ' - ' . $agenda->dsc_titulo }}
                                    </p>
                                @endforeach
                            @else
                                <p class="text-danger text-justify font-numero">
                                    Nenhuma agenda cadastrada para este dia.
                                </p>
                            @endif



                        </div>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-7 col-md-8 col-lg-3 col-xl-4 pt-3" style="padding-top: 1.15rem!Important;">

                    <div class="conditional-div pl-0 pl-sm-2">

                        <div class="row">

                            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 text-justify border-bottom divParlamentar"
                                style="padding-top: 0.1rem !Important; padding-bottom: 0.3rem !Important;">
                                <span class="textoNormalTabela">Lideranças, Cargos e comissões</span>
                            </div>

                            @if ($getParlamentar->dsc_casa === 'Câmara dos Deputados')

                                @if ($getParlamentar->cargosMesaDiretora)
                                    <p class="textoCargosEComissoes pt-2 pl-0">
                                        @php
                                            $getParlamentar->cargosMesaDiretora->titulo === 'Presidente'
                                                ? ($getParlamentar->cargosMesaDiretora->titulo =
                                                    'Presidente da Câmara dos Deputados')
                                                : ($getParlamentar->cargosMesaDiretora->titulo =
                                                    $getParlamentar->cargosMesaDiretora->titulo . ' da MESA DIRETORA');
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
        @if ($dteAtualizacaoCD !== null && $dteAtualizacaoSF !== null)
            <div class="card-footer <?php $getParlamentar->dsc_casa == 'Câmara dos Deputados' ? print 'bg-camara-footer' : print 'bg-senado-footer'; ?> pt-2 pb-2">
                <span class="textoTituloTabela">Dados atualizados em
                </span><span class="textoNormalTabela font-numero"><?php $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print formatarTimeStampComCarbonParaBR($dteAtualizacaoCD) . ', ' . formatarDataComCarbonForHumans($dteAtualizacaoCD) : print formatarTimeStampComCarbonParaBR($dteAtualizacaoSF) . ', ' . formatarDataComCarbonForHumans($dteAtualizacaoSF); ?></span>. <span
                    class="textoTituloTabela"> Fonte:</span> <span class="textoNormalTabela"><?php $getParlamentar->dsc_casa === 'Câmara dos Deputados' ? print $getParlamentar->dsc_casa : print $getParlamentar->dsc_casa; ?></span>
            </div>
        @endif
    </div>

</div>
