<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">

    <div class="card shadow-sm">
        <div class="card-header bg-estado p-1 pl-3">
            {!! $getParlamentar->dsc_tratamento . ' - ' . $getParlamentar->nom_parlamentar !!}
        </div>
        <div class="card-body bg-white pt-2 pb-2">

            <div class="row">

                <div
                    class="col-xs-12 col-sm-4 col-md-4 col-lg-3 col-xl-2 pt-4 d-flex align-items-center justify-content-center">

                    <figure class="figure">
                        @php
                            $fileName =
                                'storage/fotos/tse/F' .
                                $getParlamentar->sgl_uf_representante .
                                $cod_parlamentar .
                                '_div.';
                            if (file_exists($fileName.'jpg')) {
                                $fileName =
                                'storage/fotos/tse/F' .
                                $getParlamentar->sgl_uf_representante .
                                $cod_parlamentar .
                                '_div.jpg';
                            }
                            if (file_exists($fileName.'jpeg')) {
                                $fileName =
                                'storage/fotos/tse/F' .
                                $getParlamentar->sgl_uf_representante .
                                $cod_parlamentar .
                                '_div.jpeg';
                            }
                            if (!file_exists($fileName)) {
                                $fileName = 'storage/fotos/avatar/avatar.jpg';
                            }
                        @endphp
                        <img src="<?php $getParlamentar->dsc_casa == 'Câmara dos Deputados' ? print asset('storage/fotos/deputados/' . $cod_parlamentar . '.jpg') : print asset($fileName); ?>" class="figure-img img-fluid shadow-sm rounded"
                            style="min-width: 225px !Important; width: 245px !Important; max-width: 245px !Important; min-height: 314px !Important; height: 314px !Important; max-height: 314px !Important;">
                    </figure>

                </div>

                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-6 col-xl-7 pt-3 ">

                    <div class="row">

                        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 text-justify border-bottom divParlamentar"
                            style="padding-bottom: 0.4rem !Important;">
                            <span class="textoNormalTabela">Dados gerais</span>
                        </div>

                        <div
                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                            <span class="textoTituloTabela">Nome:</span>
                        </div>

                        <div
                            class="col-9 col-xs-4 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center border-bottom text-justify divParlamentar">

                            <span class="textoNormalTabela">{!! primeiraLetraMaiuscula($getParlamentar->nom_parlamentar_sem_formatacao) !!}</span>

                        </div>

                        <div
                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                            <span class="textoTituloTabela">Nome civil:</span>
                        </div>

                        <div
                            class="col-9 col-xs-4 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center border-bottom text-justify divParlamentar">

                            <span class="textoNormalTabela">{!! primeiraLetraMaiuscula($getParlamentar->nom_parlamentar_completo) !!}</span>

                        </div>

                        <div
                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                            <span class="textoTituloTabela">Situação:</span>
                        </div>

                        <div
                            class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                            <span class="textoNormalTabela">{!! $getParlamentar->dsc_participacao . ' -' !!} <?php $getParlamentar->dsc_situacao != 'Exercício' ? print '<span class="text-danger">' . $getParlamentar->dsc_situacao . '</span>' : print $getParlamentar->dsc_situacao; ?></span>

                        </div>

                        <div
                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                            <span class="textoTituloTabela">Formação <span class="text-small text-muted">(TSE)</span>
                                :</span>
                        </div>

                        <div
                            class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                            <span class="textoNormalTabela">{!! primeiraLetraMaiuscula($getParlamentar->dsc_escolaridade) !!}</span>

                        </div>

                        <div
                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                            <span class="textoTituloTabela">Última ocupação <span
                                    class="text-small text-muted">(TSE)</span> :</span>
                        </div>

                        <div
                            class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                            <span class="textoNormalTabela">{!! primeiraLetraMaiuscula($getParlamentar->dsc_ocupacao) !!}</span>

                        </div>

                        <div
                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                            <span class="textoTituloTabela">Aniversário:</span>
                        </div>

                        <div
                            class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                            <span class="textoNormalTabela font-numero">
                                {!! formatarDataComCarbonParaBR($getParlamentar->dte_nascimento) !!} &nbsp;&nbsp;<span
                                    style="font-weight: normal !Important; font-size: 0.7rem !Important;">{!! retornaTextoTirandoParteDoTexto(formatarDataComCarbonForHumans($getParlamentar->dte_nascimento), 'há ') !!}</span>
                            </span>

                        </div>

                        <div
                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                            <span class="textoTituloTabela">Cidade natal:</span>
                        </div>

                        <div
                            class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                            <span class="textoNormalTabela">{!! primeiraLetraMaiuscula($getParlamentar->nom_municipio_nascimento) !!}/{!! $getParlamentar->sgl_uf_nascimento !!}</span>

                        </div>

                        @if ($bln_acesso_inrestrito == 1)
                            <div
                                class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                                <span class="textoTituloTabela">Celular:</span>
                                <span data-bs-toggle="modal" data-bs-target="#modalNovoCelular">
                                    <i class="fas fa-plus-circle text-success d-print-none" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Incluir novo número de celular"
                                        style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                </span>

                                <!-- Modal -->
                                <div class="modal fade" id="modalNovoCelular" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false" style="padding-top: 150px!Important;">
                                    <div class="modal-dialog  modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                style="background: linear-gradient(135deg,#898989 0%,#acacac 100%);color: white;">
                                                <p class="modal-title text-white"
                                                    style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                    Cadastrar número de celular</p>
                                            </div>
                                            <div class="modal-body">
                                                <input id="num_celular" type="text"
                                                    class="form-control font-numero @error('num_celular') is-invalid @enderror"
                                                    name="num_celular" value="" required
                                                    autocomplete="num_celular" autofocus
                                                    placeholder="Número do celular com DDD">

                                                <div id=""
                                                    class="form-text pl-3 textoPequeno text-primary font-numero">
                                                    Ex.: (61)
                                                    98888-9999</div>

                                                <script type="text/javascript">
                                                    $('#num_celular').mask('(00) 00000-0000');
                                                </script>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-primary"
                                                    onclick="javascript: gravar_celular($('#num_celular').val(),'{!! $cod_parlamentar !!}');">Salvar</button>
                                            </div>

                                            <script>
                                                function gravar_celular(num_celular, cod_parlamentar) {

                                                    $('#modalNovoCelular').modal('toggle');

                                                    @auth
                                                    $.get('<?php print url('gravar-celular-parlamentar'); ?>' + '/' + num_celular + '/' + cod_parlamentar, function(data) {
                                                        $("#divCelular").empty();

                                                        $("#divCelular").append(
                                                            '<div class="col-xs-12 col-sm-12 col-md-12" style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;"><i class="fa fa - circle - notch fa - spin text - primary "></i><span class="sr - only "></span> Carregando...</div>'
                                                        );

                                                        $("#divCelular").empty();

                                                        $("#divCelular").append(data);

                                                        $("#num_celular").val('');

                                                    });
                                                @else
                                                    alert('Por gentileza, é necessário efetuar um novo login (acesso) ao sistema.');
                                                @endauth

                                                }
                                            </script>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 col-lg-8 m-0 p-0 pt-1 pb-1 d-flex align-items-center text-justify border-bottom divParlamentar"
                                id="divCelular">

                                <div class="row pl-4" style="width: 100%!Important;">

                                    @if ($getParlamentar->celulares->count() > 0)
                                        <?php $contCelular = 1; ?>
                                        @foreach ($getParlamentar->celulares as $celular)
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-<?php $getParlamentar->celulares->count() <= 1 ? print '12' : print '6'; ?> col-xl-<?php $getParlamentar->celulares->count() <= 1 ? print '12' : print '6'; ?> m-0 p-0"
                                                style="margin-left: -3px!Important; padding-left: -3px!Important;">
                                                <span class="textoNormalTabela font-numero">
                                                    {!! applyMask($celular->num_celular, '(##) #####-####') !!}
                                                </span>
                                                &nbsp;
                                                <span class="m-0 p-0">
                                                    <span data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarCelular{!! $celular->cod_celular !!}"
                                                        class="d-print-none m-0 p-0">
                                                        <i class="fas fa-edit text-primary d-print-none"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Editar número de celular"
                                                            style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                                    </span>

                                                    <!-- Modal -->
                                                    <div class="modal fade"
                                                        id="modalEditarCelular{!! $celular->cod_celular !!}" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true"
                                                        data-bs-backdrop="static" data-bs-keyboard="false"
                                                        style="padding-top: 150px!Important;">
                                                        <div class="modal-dialog  modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background: linear-gradient(135deg,#898989 0%,#acacac 100%);color: white;">
                                                                    <p class="modal-title text-white"
                                                                        style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                                        Editar número de celular</p>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input id="num_celular{!! $celular->cod_celular !!}"
                                                                        type="text"
                                                                        class="form-control font-numero @error('num_celular') is-invalid @enderror"
                                                                        name="num_celular"
                                                                        value="{!! applyMask($celular->num_celular, '(##) #####-####') !!}" required
                                                                        autocomplete="num_celular" autofocus
                                                                        placeholder="Número do celular com DDD">

                                                                    <div id=""
                                                                        class="form-text pl-3 textoPequeno text-primary font-numero">
                                                                        Ex.: {!! applyMask($celular->num_celular, '(##) #####-####') !!}</div>

                                                                    <script type="text/javascript">
                                                                        $('#num_celular{!! $celular->cod_celular !!}').mask('(00) 00000-0000');
                                                                    </script>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="button" class="btn btn-primary"
                                                                        onclick="javascript: editar_celular('{!! $celular->cod_celular !!}',$('#num_celular{!! $celular->cod_celular !!}').val(),'{!! $cod_parlamentar !!}');">Alterar</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </span>
                                                <span class="m-0 p-0">
                                                    <span data-bs-toggle="modal"
                                                        data-bs-target="#modalExcluirCelular{!! $celular->cod_celular !!}"
                                                        class="d-print-none m-0 p-0">
                                                        <i class="fas fa-trash-alt text-danger"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Excluir número de celular"
                                                            style="font-size: 0.65rem !Important; cursor: pointer !Important;"></i>
                                                    </span>

                                                    <!-- Modal -->
                                                    <div class="modal fade"
                                                        id="modalExcluirCelular{!! $celular->cod_celular !!}"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true" data-bs-backdrop="static"
                                                        data-bs-keyboard="false"
                                                        style="padding-top: 150px!Important;">
                                                        <div class="modal-dialog  modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                                    <p class="modal-title text-white"
                                                                        style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                                        Excluir número de celular</p>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <p>
                                                                        Número: <span
                                                                            class="font-numero">{!! applyMask($celular->num_celular, '(##) #####-####') !!}</span>
                                                                    </p>

                                                                    <p class="">
                                                                        Deseja realmente excluir este número de
                                                                        celular?
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="button" class="btn btn-danger"
                                                                        onclick="javascript: excluir_celular('{!! $celular->cod_celular !!}',$('#num_celular{!! $celular->cod_celular !!}').val(),'{!! $cod_parlamentar !!}');">Sim,
                                                                        excluir</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </span>
                                                <?php $contCelular++; ?>
                                            </div>
                                        @endforeach
                                    @else
                                        {{ '-' }}
                                    @endif
                                </div>

                            </div>

                            <script>
                                function editar_celular(cod_celular, num_celular, cod_parlamentar) {

                                    $('#modalEditarCelular' + cod_celular).modal('toggle');

                                    @auth
                                    $.get('<?php print url('gravar-celular-parlamentar'); ?>' + '/' + num_celular + '/' + cod_parlamentar + '/' + cod_celular, function(data) {
                                        $("#divCelular").empty();

                                        $("#divCelular").append(
                                            '<div class="col-xs-12 col-sm-12 col-md-12" style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;"><i class="fa fa - circle - notch fa - spin text - primary "></i><span class="sr - only "></span> Carregando...</div>'
                                        );

                                        $("#divCelular").empty();

                                        $("#divCelular").append(data);

                                    });
                                @else
                                    alert('Por gentileza, é necessário efetuar um novo login (acesso) ao sistema.');
                                @endauth

                                }

                                function excluir_celular(cod_celular, num_celular, cod_parlamentar) {

                                    $('#modalExcluirCelular' + cod_celular).modal('toggle');

                                    @auth
                                    $.get('<?php print url('gravar-celular-parlamentar'); ?>' + '/' + num_celular + '/' + cod_parlamentar + '/' + cod_celular + '/Sim', function(
                                        data) {
                                        $("#divCelular").empty();

                                        $("#divCelular").append(
                                            '<div class="col-xs-12 col-sm-12 col-md-12" style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;"><i class="fa fa - circle - notch fa - spin text - primary "></i><span class="sr - only "></span> Carregando...</div>'
                                        );

                                        $("#divCelular").empty();

                                        $("#divCelular").append(data);

                                    });
                                @else
                                    alert('Por gentileza, é necessário efetuar um novo login (acesso) ao sistema.');
                                @endauth

                                }
                            </script>
                        @endif

                        <div
                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                            <span class="textoTituloTabela">Telefone:</span>
                        </div>

                        <div
                            class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                            <span class="textoNormalTabela font-numero">
                                {{ $getParlamentar->num_telefone }}
                            </span>

                        </div>

                        <div
                            class="col-3 col-xs-4 col-sm-4 col-md-4 col-lg-4 d-flex align-items-center text-justify border-bottom divParlamentar">
                            <span class="textoTituloTabela">E-mail:</span>
                        </div>

                        <div
                            class="col-9 col-xs-8 col-sm-8 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                            <span class="textoNormalTabela">{!! strtolower(limpaStringSemTirarHifem($getParlamentar->dsc_email)) !!}</span>

                        </div>

                    </div>

                </div>

                <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3 col-xl-3 pt-3 r-xl-4 pr-xl-4"
                    style="padding-top: 1.15rem!Important;">

                    <div class="conditional-div pl-0 pl-sm-2 pr-xl-1">
                        <div class="row">

                            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 pr-0 text-justify border-bottom divParlamentar"
                                style="padding-top: 0.1rem !Important; padding-bottom: 0.3rem !Important;">
                                <span class="textoNormalTabela">Dados do TSE</span>
                            </div>

                            <div
                                class="col-4 col-xs-4 col-sm-5 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                <span class="textoTituloTabela">Partido/UF:</span>
                            </div>

                            <div
                                class="col-8 col-xs-8 col-sm-7 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                <span class="textoNormalTabela">{!! $getParlamentar->sgl_partido !!} /
                                    {!! $getParlamentar->sgl_uf_representante !!}</span>

                            </div>

                            <div
                                class="col-4 col-xs-4 col-sm-5 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                <span class="textoTituloTabela">Ano eleição:</span>
                            </div>

                            <div
                                class="col-8 col-xs-8 col-sm-7 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                <span class="textoNormalTabela font-numero">
                                    @php
                                        isset($getParlamentar->num_ano_eleicao) &&
                                        !is_null($getParlamentar->num_ano_eleicao) &&
                                        $getParlamentar->num_ano_eleicao != ''
                                            ? print $getParlamentar->num_ano_eleicao
                                            : print '-';
                                    @endphp
                                </span>

                            </div>

                            <div
                                class="col-4 col-xs-4 col-sm-5 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                <span class="textoTituloTabela">Reeleito:</span>
                            </div>

                            <div
                                class="col-8 col-xs-8 col-sm-7 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                <span class="textoNormalTabela">
                                    @php
                                        isset($getParlamentar->dsc_reeleito) &&
                                        !is_null($getParlamentar->dsc_reeleito) &&
                                        $getParlamentar->dsc_reeleito != ''
                                            ? print $getParlamentar->dsc_reeleito
                                            : print '-';
                                    @endphp
                                </span>

                            </div>

                            <div
                                class="col-4 col-xs-4 col-sm-5 col-md-4 col-lg-4 text-justify border-bottom divParlamentar">
                                <span class="textoTituloTabela">TSE votos:</span>
                            </div>

                            <div
                                class="col-8 col-xs-8 col-sm-7 col-md-8 col-lg-8 d-flex align-items-center text-justify border-bottom divParlamentar">

                                <span class="textoNormalTabela font-numero">
                                    @php
                                        isset($getParlamentar->num_total_votos) &&
                                        !is_null($getParlamentar->num_total_votos) &&
                                        $getParlamentar->num_total_votos != ''
                                            ? print formatarNumeroInteiro($getParlamentar->num_total_votos)
                                            : print '-';
                                    @endphp
                                </span>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>
