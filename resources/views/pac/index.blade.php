@extends('layouts.app')

@section('content')
    <!-- Início breadcrumbs -->
    <div id="portal-breadcrumbs-wrapper" class="m-0 pl-0 mb-3 d-print-none">
        <nav id="breadcrumbs" aria-label="Histórico de navegação (Breadcrumbs)">
            <div class="content">
                <span class="sr-only">Você está aqui:</span>
                <span class="home">
                    <a href="{!! url('/') !!}">
                        <span class="fas fa-home" aria-hidden="true"></span>
                        <span class="sr-only">Página Inicial</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                <span dir="ltr" id="breadcrumbs-2">
                    <a href="{!! url('novo-pac') !!}">
                        <span id="breadcrumbs-current">Novo PAC</span>
                    </a>
                </span>

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->

    <!-- Início apresentação dos cards de entrada -->
    <div class="row" id="informacao">
        <div class="col-xs-12 col-sm-12 col-md-12"
            style="margin: 0px Important; padding: 0px Important; padding-left: 19px; font-sizee: 21px;">
            <i class='fa fa-circle-notch fa-spin text-primary'></i><span class='sr-only'></span> Carregando...
        </div>
    </div>

    <div class="row" id="div1" style="display: none;">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">

            <div class="" style="font-size: 0.9rem !Important;">
                <div class="row">

                    <div class="col-12 pb-2">
                        <h4 class="card-title rounded-top bg-light-sub-titulo-modal mt-0 mr-0 mb-2 pt-2 pl-2 pb-1"
                            style="font-size: 1.1rem !Important;">
                            <i class="fas fa-filter text-info"></i> Filtro
                        </h4>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 mb-4">
                        {!! Form::select('codUnidade', $areasComEmpreendimento, $codigoUnidade, [
                            'class' => 'form-control',
                            'placeholder' => 'Pesquisar',
                            'id' => 'codUnidade',
                            'onchange' => 'window.location.href = "' . url('novo-pac') . '/" + this.value;',
                            'style' => 'width: 99% !Important; ',
                        ]) !!}
                    </div>

                </div>

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5 col-xl-5 col-xxl-5 mb-3">
            <div class="h-100 bg-white mb-3" style="font-size: 0.9rem !Important;">
                <div class="row">
                    <div class="col-12">
                        <h4 class="card-title rounded-top bg-light-sub-titulo-modal mt-0 mr-0 mb-2 pt-2 pl-2 pb-1"
                            style="font-size: 1.1rem !Important;">
                            <i class="fas fa-file-invoice-dollar text-info"></i> Resumo
                        </h4>
                    </div>
                    <div class="col-12 text-muted text-justify font-numero-pac">
                        <p class="text-muted m-0 p-0 pl-2 pr-2">
                            Total de <span class="text-dark"
                                style="font-size: 1.1rem!Important;">{{ $visResumo->qte_empreendimentos }}</span>
                            empreendimentos do Novo PAC
                        </p>
                    </div>
                    <div class="col-12 text-muted text-justify font-numero-pac">
                        <hr class="mt-1 mb-1 p-0">
                        <p class="text-muted m-0 p-0 pl-2 pr-2">
                            Valor a executar (R$) <span class="text-dark"
                                style="font-size: 1.1rem!Important;">{{ converteValor('MYSQL', 'PTBR', $visResumo->vlr_a_executar) }}</span>
                            <span class="text-primary"
                                style="font-size: 0.8rem!Important;">({{ prettify_numbers($visResumo->vlr_a_executar) }})</span>
                        </p>
                    </div>
                    <div class="col-12 text-muted text-justify font-numero-pac">
                        <hr class="mt-1 mb-1 p-0">
                        <p class="text-muted m-0 p-0 pl-2 pr-2">
                            Valor de investimento planejado 2023 - 2026 (R$) <span class="text-dark"
                                style="font-size: 1.1rem!Important;">{{ converteValor('MYSQL', 'PTBR', $visResumo->vlr_investimento_planejado_2023_a_2026) }}</span>
                            <span class="text-primary"
                                style="font-size: 0.8rem!Important;">({{ prettify_numbers($visResumo->vlr_investimento_planejado_2023_a_2026) }})</span>
                        </p>
                    </div>
                    <div class="col-12 text-muted text-justify font-numero-pac">
                        <hr class="mt-1 mb-1 p-0">
                        <p class="text-muted m-0 p-0 pl-2 pr-2">
                            OGU - Valor empenhado da LOA 2024 (R$) <span class="text-dark"
                                style="font-size: 1.1rem!Important;">{{ converteValor('MYSQL', 'PTBR', $visResumo->vlr_ogu_empenhado_loa_2024) }}</span>
                            <span class="text-primary"
                                style="font-size: 0.8rem!Important;">({{ prettify_numbers($visResumo->vlr_ogu_empenhado_loa_2024) }})</span>
                        </p>
                    </div>
                    <div class="col-12 text-muted text-justify font-numero-pac">
                        <hr class="mt-1 mb-1 p-0">
                        <p class="text-muted m-0 p-0 pl-2 pr-2">
                            OGU - Valor pago / repassado (LOA) 2024 (R$) <span class="text-dark"
                                style="font-size: 1.1rem!Important;">{{ converteValor('MYSQL', 'PTBR', $visResumo->vlr_ogu_pago_repassado_loa_2024) }}</span>
                            <span class="text-primary"
                                style="font-size: 0.8rem!Important;">({{ prettify_numbers($visResumo->vlr_ogu_pago_repassado_loa_2024) }})</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @include('pac.card-monitoramento')

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">

            <div class="" style="font-size: 0.9rem !Important;">
                <div class="row">

                    <div class="col-12 pb-2">
                        <h4 class="card-title rounded-top bg-light-sub-titulo-modal mt-0 mr-0 mb-2 pt-2 pl-2 pb-1"
                            style="font-size: 1.1rem !Important;">
                            <i class="fas fa-file-invoice-dollar text-info"></i> Resumo da parte Orçamentária/Financeira por
                            ano
                        </h4>
                    </div>

                    <div class="col-12 pb-2">
                        <h4 class="card-title rounded-top bg-light mt-0 mr-0 mb-2 pt-2 pl-2 pb-1"
                            style="font-size: 1.1rem !Important;">
                            <a href="{{ route('novo-pac.export-orcamentario-financeiro', [2024]) }}" id="download-link"
                                onclick="javascript: downloadPorcess();">
                                <i class="fas fa-file-excel text-success"></i> download planilha
                            </a>
                            <span id="download-message" class="pl-2 text-secondary" style="display: none;"><i
                                    class='fa fa-circle-notch fa-spin text-danger'></i>Processando o download. Em média leva
                                40 segundos para concluir.
                                Aguarde, por gentileza ...</span>
                        </h4>
                    </div>

                    <div class="col-12 pl-3">

                        <div class="row">

                            {{-- Início da parte do Crédito Disponível --}}
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 font-numero mb-2">
                                <div class="rounded p-2 mb-2" style="background-color: #c27ba0; color: #FFFFFF;">

                                    <div class="row">

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-9 col-xxl-12">
                                            1. CRÉDITO DISPONÍVEL (NÃO EMPENHADO)
                                        </div>

                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Ano</th>
                                                <th class="text-right" style="background-color: #f8f1f5;">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($evolucaoCreditoDisponivelPorAno as $value)
                                                <tr>
                                                    <th>
                                                        {{ $value->num_ano }}
                                                    </th>
                                                    <th class="text-right p-1"
                                                        style="font-size: 0.95rem!Important; background-color: #f8f1f5;">
                                                        {{ converteValor('MYSQL', 'PTBR', $value->vlr_total) }}
                                                    </th>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- Fim da parte do Crédito Disponível --}}

                            {{-- Início da parte do Saldo Empenhado --}}
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 font-numero mb-2">
                                <div class="rounded p-2 mb-2" style="background-color: #257ab3; color: #FFFFFF;">

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-9 col-xxl-12">
                                            2. SALDO EMPENHADO
                                        </div>
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Ano</th>
                                                <th class="text-right" style="background-color: #e9f1f7;">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($evolucaoSaldoEmpenhadoPorAno as $value)
                                                <tr>
                                                    <th>
                                                        {{ $value->num_ano }}
                                                    </th>
                                                    <th class="text-right p-1"
                                                        style="font-size: 0.95rem!Important; background-color: #e9f1f7;">
                                                        {{ converteValor('MYSQL', 'PTBR', $value->vlr_total) }}
                                                    </th>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Fim da parte do Saldo Empenhado --}}

                            {{-- Início da parte da Suplementação Orçamentária Necessária --}}
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 font-numero mb-2">
                                <div class="rounded p-2 mb-2" style="background-color: #338114; color: #FFFFFF;">

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-9 col-xxl-12">
                                            3. SUPLEMENTAÇÃO ORÇAMENTÁRIA NECESSÁRIA
                                        </div>
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Ano</th>
                                                <th class="text-right" style="background-color: #eaf2e7;">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($evolucaoSuplementacaoOrcamentariaPorAno as $value)
                                                <tr>
                                                    <th>
                                                        {{ $value->num_ano }}
                                                    </th>
                                                    <th class="text-right p-1"
                                                        style="font-size: 0.95rem!Important; background-color: #eaf2e7;">
                                                        {{ converteValor('MYSQL', 'PTBR', $value->vlr_total) }}
                                                    </th>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Fim da parte da Suplementação Orçamentária Necessária --}}

                            {{-- Início da parte da Necessidade Financeira --}}
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 font-numero mb-2">
                                <div class="rounded p-2 mb-2" style="background-color: #610774; color: #FFFFFF;">

                                    <div class="row">

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                            4. NECESSIDADE FINANCEIRA
                                        </div>

                                    </div>

                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" class="align-middle">Ano</th>
                                                <th colspan="2" class="text-center" style="width: 80%!Important;">
                                                    Valores por RP</th>
                                                <th rowspan="2" class="align-middle text-right"
                                                    style="background-color: #efe6f1;">Total</th>
                                            </tr>
                                            <tr>
                                                <th class="align-middle text-right" style="">RP 2</th>
                                                <th class="align-middle text-right" style="">RP 3</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($evolucaoFinanceira as $value)
                                                <tr>
                                                    <th>
                                                        {{ $value->num_ano }}
                                                    </th>
                                                    <th class="text-right p-1"
                                                        style="font-size: 0.95rem!Important; background-color: #efe6f1;">
                                                        {{ converteValor('MYSQL', 'PTBR', $value->vlr_rp2_total) }}
                                                    </th>
                                                    <th class="text-right p-1"
                                                        style="font-size: 0.95rem!Important; background-color: #efe6f1;">
                                                        {{ converteValor('MYSQL', 'PTBR', $value->vlr_rp3_total) }}
                                                    </th>
                                                    <th class="text-right p-1"
                                                        style="font-size: 0.95rem!Important; background-color: #efe6f1;">
                                                        {{ converteValor('MYSQL', 'PTBR', $value->vlr_rp2_total + $value->vlr_rp3_total) }}
                                                    </th>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            {{-- Fim da parte da Necessidade Financeira --}}

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">

            <div class="" style="font-size: 0.9rem !Important;">
                <div class="row">

                    <div class="col-12">
                        <h4 class="card-title rounded-top bg-light-sub-titulo-modal mt-0 mr-0 mb-2 pt-2 pl-2 pb-1"
                            style="font-size: 1.1rem !Important;">
                            <i class="fas fa-table text-info"></i> Tabela detalhada do Novo PAC
                        </h4>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mt-4 mb-4 table-responsive">

                        <style>
                            .btn-secondary {
                                color: #000000;
                                background-color: #fdfcfc;
                                border-color: #61686E;
                            }
                        </style>

                        <table id="tableNovosPac" class="table w-100 mt-0 pt-0">

                            <thead>
                                <tr>

                                    @foreach ($estruturaTableParaEditar as $estrutura)
                                        <th class="<?php $estrutura->data_type === 'character varying' || $estrutura->data_type === 'text' || $estrutura->data_type === 'integer' ? print 'text-left' : print 'text-right'; ?>" <?php $estrutura->column_name === 'nom_empreendimento_divulgacao' ? print 'id="tdNomEmpreendimentoDivulgacao"' : ''; ?>>
                                            {{ nomeCampoTabNovoPacNormalizado($estrutura->column_name) }}
                                        </th>
                                    @endforeach

                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($novosPac as $pac)
                                    <tr class="<?php $pac->dsc_situacao === 'Concluído' ? print('table-primary') : ''; ?>">
                                        @foreach ($estruturaTableParaEditar as $estrutura)
                                            @php
                                                $column_name = $estrutura->column_name;
                                                $data_type = $estrutura->data_type;
                                            @endphp
                                            @if ($data_type === 'date')
                                                <td class="font-numero text-right">
                                                    {{ converterData('EN', 'PTBR', $pac->$column_name) }}
                                                </td>
                                            @elseif ($data_type === 'timestamp without time zone')
                                                <td class="font-numero text-right">
                                                    <span
                                                        style="font-size: 0rem!Important;">{{ $pac->$column_name }}</span>
                                                    {{ formatarDataComCarbonParaBR($pac->$column_name) }}
                                                </td>
                                            @elseif ($data_type === 'numeric' || $data_type === 'double precision')
                                                <td class="font-numero text-right">
                                                    {{ converteValor('MYSQL', 'PTBR', $pac->$column_name) }}
                                                    <span><?php $column_name === 'prc_execucao_fisica' ? print '%' : print ''; ?></span>
                                                </td>
                                            @elseif ($data_type === 'integer')
                                                @if ($column_name === 'cod_pac')
                                                    <td class="font-numero text-left">
                                                        {{ $pac->cod_pac }}
                                                    </td>
                                                @else
                                                    <td class="font-numero text-left">
                                                        {{ $pac->areaResponsavel->sigla }}
                                                    </td>
                                                @endif
                                            @else
                                                @if ($column_name === 'nom_empreendimento_divulgacao')
                                                    <td>

                                                        <span
                                                            style="font-size: 0rem!Important;">{{ $pac->$column_name }}</span>

                                                        @if (Session::get('permissao') === '0001000' ||
                                                                Session::get('permissao') === '0000100' ||
                                                                Session::get('permissao') === '0000010')
                                                            <span class="pr-1">
                                                                <a href="{!! route('novo-pac.edit', [$pac->cod_pac, 'div3']) !!}">
                                                                    <i class="fas fa-edit text-success"
                                                                        style="font-size: 0.8rem!Important;"></i>
                                                                </a>
                                                            </span>
                                                        @endif

                                                        @if ($pac->auditoria->count() > 0)
                                                            <span class="pr-1">
                                                                <i class="fas fa-eye pointer text-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalLog{{ $pac->cod_pac }}"
                                                                    onclick="javascript: ajaxGetAuditCodPac('{{ $pac->cod_pac }}');"></i>
                                                                </label>

                                                                {{-- Início da Modal da auditoria por Empreendimento do Novo PAC --}}
                                                                <div class="modal fade" id="modalLog{{ $pac->cod_pac }}"
                                                                    tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                    aria-hidden="true"
                                                                    style="padding-top: 95px!Important;">
                                                                    <div class="modal-dialog modal-xl">

                                                                        <div class="modal-content">

                                                                            <div class="modal-header"
                                                                                style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                                                <p class="modal-title font-numero text-white"
                                                                                    style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                                                    <i class="fas fa-eye"></i> Ação(ões)
                                                                                    realizada(s) em
                                                                                    {{ $pac->nom_empreendimento_divulgacao }}
                                                                                </p>
                                                                            </div>
                                                                            <div id="divModalBody{{ $pac->cod_pac }}"
                                                                                class="modal-body font-numero mt-0 pt-0"
                                                                                style="max-height: 65vh; overflow-y: auto;">

                                                                                <p class="p-2 pt-4">
                                                                                    <i
                                                                                        class='fa fa-circle-notch fa-spin text-primary'></i><span
                                                                                        class='sr-only'></span>
                                                                                    Carregando...
                                                                                </p>

                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-outline-danger btn-sm"
                                                                                    data-bs-dismiss="modal">
                                                                                    Fechar
                                                                                </button>
                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>
                                                                {{-- Fim da Modal da auditoria por Empreendimento do Novo PAC --}}

                                                            </span>
                                                        @endif

                                                        {{ $pac->$column_name }}
                                                    </td>
                                                @else
                                                    <td>
                                                        {{ $pac->$column_name }}
                                                    </td>
                                                @endif
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                        <script type="text/javascript" charset="utf-8">
                            $(document).ready(function() {
                                var table = $('#tableNovosPac').DataTable({
                                    "language": {
                                        "url": "{{ asset('Portuguese-Brasil.json') }}",
                                        "decimal": ",",
                                        "thousands": "."
                                    },
                                    "order": [
                                        [7, "asc"]
                                    ],
                                    "lengthMenu": [
                                        [-1, 5, 10, 25, 50, 100],
                                        ["Todos ", "5 ", "10 ", "25 ", "50 ", "100 "]
                                    ],
                                    "paging": true,
                                    responsive: true,
                                    scrollx: true,
                                    fixedHeader: {
                                        header: true,
                                        headerOffset: $('#header').outerHeight() - 23
                                    },
                                    scrollCollapse: false,
                                    "columnDefs": [

                                        @php
                                            $contColuna = 0;
                                        @endphp

                                        @foreach ($estruturaTableParaEditar as $estrutura)
                                            @if (in_array($estrutura->column_name, $colunasVisiveis))
                                                {
                                                    "targets": [{{ $contColuna }}],
                                                    "visible": true,
                                                    "searchable": true
                                                },
                                            @else
                                                {
                                                    "targets": [{{ $contColuna }}],
                                                    "visible": false,
                                                    "searchable": true
                                                },
                                            @endif
                                            @php
                                                $contColuna++;
                                            @endphp
                                        @endforeach
                                    ],
                                    dom: 'Blfrtip',
                                    buttons: [{
                                            extend: 'excelHtml5',
                                            text: 'Exportar Excel (todas as colunas)',
                                            visibility: true
                                        },
                                        {
                                            extend: 'csvHtml5',
                                            text: 'Exportar CSV (todas as colunas)',
                                            visibility: true
                                        },
                                        {
                                            extend: 'colvis',
                                            text: 'Colunas Visíveis',
                                            visibility: true
                                        }
                                    ]
                                });

                            });
                        </script>

                        <!-- Início funções javascript -->
                        <script>
                            // Seleciona todos os elementos com a classe 'hover'
                            var cards = document.querySelectorAll(".hover");

                            // Itera sobre cada elemento selecionado
                            cards.forEach(function(card) {
                                // Adiciona um ouvinte de eventos para o evento 'mouseenter'
                                card.addEventListener("mouseenter", function(event) {
                                    // Remove a classe 'shadow-sm' e adiciona a classe 'shadow'
                                    card.classList.remove("shadow-sm");
                                    card.classList.add("shadow");
                                }, false);

                                // Adiciona um ouvinte de eventos para o evento 'mouseleave'
                                card.addEventListener("mouseleave", function(event) {
                                    // Remove a classe 'shadow' e adiciona a classe 'shadow-sm'
                                    card.classList.remove("shadow");
                                    card.classList.add("shadow-sm");
                                }, false);
                            });

                            function downloadPorcess() {

                                setTimeout(function() {
                                    $("#download-message").fadeIn("slow");
                                    $("#download-link").fadeOut("slow");
                                }, 3);

                                setTimeout(function() {
                                    $("#download-link").fadeIn("slow");
                                    $("#download-message").fadeOut("slow");
                                }, 36300);


                            }

                            function ajaxGetAuditCodPac(param_cod_pac) {
                                event.preventDefault(); // Evita o envio padrão do formulário

                                let data = {
                                    cod_pac: param_cod_pac,
                                };

                                $.ajax({
                                    url: "{{ url('novo-pac/auditoria/show/modal') }}", // URL para a qual a requisição será enviada
                                    type: "POST", // Tipo de requisição
                                    data: data, // Dados enviados na requisição
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content') // Adiciona o token CSRF para segurança
                                    },
                                    success: function(response) {
                                        // Executado em caso de sucesso

                                        $("#divModalBody" + param_cod_pac).empty();

                                        $("#divModalBody" + param_cod_pac).append(response);

                                    },
                                    error: function(xhr, status, error) {
                                        // Tratamento de erro
                                        let errorMessage = xhr.status + ': ' + xhr.statusText;
                                        alert('Erro - ' + errorMessage);
                                    }
                                });
                            }
                        </script>
                        <!-- Fim funções javascript -->

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Início funções javascript -->
    <script>
        setTimeout(function() {
            $("#div1").fadeIn("slow");
            $("#tdNomEmpreendimentoDivulgacao").click();
            $("#tdNomEmpreendimentoDivulgacao").click();
        }, 700);

        setTimeout(function() {
            $("#informacao").fadeOut("slow");
        }, 300);
    </script>
    <!-- Fim funções javascript -->
@endsection
