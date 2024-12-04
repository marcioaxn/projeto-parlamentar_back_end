@extends('layouts.app')

@section('content')
    <script src="https://unpkg.com/html-docx-js/dist/html-docx.js"></script>

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
                    <span id="breadcrumbs-current">Relatórios</span>
                </span>

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->

    <div class="row">

        @php
            $contUf = 1;

            foreach ($sglUfRepresentante as $uf) {
                $parlamentares = $tabParlamentares->getParlamentaresPorUF($sglPartidos, $dscCasa, $uf);

                if ($parlamentares->count() > 0) {
                    if (
                        isset($uf->nomeunidadefederacao) &&
                        !is_null($uf->nomeunidadefederacao) &&
                        $uf->nomeunidadefederacao != ''
                    ) {
                    }
                }

                $contUf++;
            }
        @endphp

        <style>
            .background-tr {
                background-image: url('{{ converterImagemParaBase64(asset('img/bg9.jpg')) }}');
                background-size: 100% 100%;
            }
        </style>

        <div id="conteudo">

            <table class="table" style="width: 90%; height: 290px!Important;">
                <tr>
                    <td style="width: 45%;" class="background-tr">
                        <!-- Essa é a coluna 1 -->

                        <table style="width: 100%;">

                            <tr>
                                <td class="p-1" style="width: 30%;">
                                    <!-- aqui terá uma imgaem -->
                                    <img src="{{ converterImagemParaBase64(asset('/storage/fotos/senadores/4994.jpg')) }}"
                                        style="width: 9rem" alt="imagem">
                                </td>
                                <td style="width: 70%;">

                                    <table style="width: 100%;">
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 1 -->
                                                Texto 1
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 2 -->
                                                Texto 2
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 3 -->
                                                Texto 3
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 4 -->
                                                Texto 4
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 5 -->
                                                Texto 5
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <!-- aqui terá texto 6 -->
                                    Texto 6
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <!-- aqui terá texto 7 -->
                                    Texto 7
                                </td>
                            </tr>

                        </table>

                    </td>

                    <td style="width: 45%;" class="background-tr">
                        <!-- Essa é a coluna 1 -->

                        <table style="width: 100%;">

                            <tr>
                                <td class="p-1" style="width: 30%;">
                                    <!-- aqui terá uma imgaem -->
                                    <img src="{{ converterImagemParaBase64(asset('/storage/fotos/senadores/4994.jpg')) }}"
                                        style="width: 9rem" alt="imagem">
                                </td>
                                <td style="width: 70%;">

                                    <table style="width: 100%;">
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 1 -->
                                                Texto 1
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 2 -->
                                                Texto 2
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 3 -->
                                                Texto 3
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 4 -->
                                                Texto 4
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!-- aqui terá texto 5 -->
                                                Texto 5
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <!-- aqui terá texto 6 -->
                                    Texto 6
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <!-- aqui terá texto 7 -->
                                    Texto 7
                                </td>
                            </tr>

                        </table>

                    </td>

                </tr>

            </table>

        </div>

        <button onclick="gerarDocumento()">Gerar Documento Word</button>
        <script>
            function gerarDocumento() {
                const conteudo = document.getElementById('conteudo').innerHTML;
                const win1252Bytes = unescape(encodeURIComponent(conteudo.replace(/[\u0080-\uffff]/g, function(c) {
                    return '&#' + c.charCodeAt(0) + ';';
                })));
                const documento = htmlDocx.asBlob('<!DOCTYPE html><html><head><meta charset="windows-1252"></head><body>' +
                    win1252Bytes + '</body></html>');
                const url = window.URL.createObjectURL(documento);
                const arquivo = document.createElement('a');
                arquivo.href = url;
                arquivo.download = 'documento.docx';
                arquivo.click();
            }
        </script>

    </div>
@endsection
