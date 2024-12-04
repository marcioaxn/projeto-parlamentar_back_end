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
                    <span id="breadcrumbs-current">Fundos Regionais</span>
                </span>

            </div>
        </nav>
        <div class="cover-richtext-tile tile-content mb-0 p-0 pt-2">

            <hr class="mt-0 mb-0">

            <h1 class="tituloSemUpper mb-0 p-0 pt-1 pl-2">Fundos de Desenvolvimento Regional</h1>

        </div>
    </div>
    <!-- Fim breadcrumbs -->

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-4">

            <table>
                <thead>

                    <tr>

                        @foreach ($estruturaTableParaEditar as $table)
                            <th>{{ $table->column_name }}</th>
                        @endforeach

                    </tr>

                </thead>

                <tbody>

                    @foreach ($empreendimentos as $empreendimento)
                        <tr class="<?php $empreendimento->dsc_situacao === 'Concluído' ? print 'table-primary' : ''; ?>">
                            @foreach ($estruturaTableParaEditar as $estrutura)
                                @php
                                    $column_name = $estrutura->column_name;
                                    $data_type = $estrutura->data_type;
                                @endphp
                                @if ($data_type === 'date')
                                    <td class="font-numero text-right">
                                        {{ converterData('EN', 'PTBR', $empreendimento->$column_name) }}
                                    </td>
                                @elseif ($data_type === 'timestamp without time zone')
                                    <td class="font-numero text-right">
                                        <span style="font-size: 0rem!Important;">{{ $empreendimento->$column_name }}</span>
                                        {{ formatarDataComCarbonParaBR($empreendimento->$column_name) }}
                                    </td>
                                @elseif ($data_type === 'numeric' || $data_type === 'double precision')
                                    <td class="font-numero text-right">
                                        {{ converteValor('MYSQL', 'PTBR', $empreendimento->$column_name) }}
                                        <span><?php $column_name === 'prc_execucao_fisica' ? print '%' : print ''; ?></span>
                                    </td>
                                @elseif ($data_type === 'integer')
                                    @if ($column_name === 'cod_pac')
                                        <td class="font-numero text-left">
                                            {{ $empreendimento->cod_pac }}
                                        </td>
                                    @else
                                        <td class="font-numero text-left">
                                            {{ $empreendimento->areaResponsavel->sigla }}
                                        </td>
                                    @endif
                                @else
                                    @if ($column_name === 'nom_empreendimento_divulgacao')
                                        <td>

                                            <span
                                                style="font-size: 0rem!Important;">{{ $empreendimento->$column_name }}</span>

                                            @if (Session::get('permissao') === '0001000' ||
                                                    Session::get('permissao') === '0000100' ||
                                                    Session::get('permissao') === '0000010')
                                                <span class="pr-1">
                                                    <a href="{!! route('novo-pac.edit', [$empreendimento->cod_pac, 'div3']) !!}">
                                                        <i class="fas fa-edit text-success"
                                                            style="font-size: 0.8rem!Important;"></i>
                                                    </a>
                                                </span>
                                            @endif

                                            @if ($empreendimento->auditoria->count() > 0)
                                                <span class="pr-1">
                                                    <i class="fas fa-eye pointer text-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalLog{{ $empreendimento->cod_pac }}"
                                                        onclick="javascript: ajaxGetAuditCodPac('{{ $empreendimento->cod_pac }}');"></i>
                                                    </label>

                                                    {{-- Início da Modal da auditoria por Empreendimento do Novo PAC --}}
                                                    <div class="modal fade" id="modalLog{{ $empreendimento->cod_pac }}"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true" style="padding-top: 95px!Important;">
                                                        <div class="modal-dialog modal-xl">

                                                            <div class="modal-content">

                                                                <div class="modal-header"
                                                                    style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                                                                    <p class="modal-title font-numero text-white"
                                                                        style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                                        <i class="fas fa-eye"></i> Ação(ões)
                                                                        realizada(s) em
                                                                        {{ $empreendimento->nom_empreendimento_divulgacao }}
                                                                    </p>
                                                                </div>
                                                                <div id="divModalBody{{ $empreendimento->cod_pac }}"
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

                                            {{ $empreendimento->$column_name }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $empreendimento->$column_name }}
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
@endsection
