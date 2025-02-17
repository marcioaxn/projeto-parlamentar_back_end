<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store,
      must-revalidate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Início ICON Brasil -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo_02_transparente.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logo_02_transparente.png') }}" />
    <!-- Fim ICON Brasil -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('scriptscss')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Fontawesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Início CSS and JS Select with find -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/session_timeout.js') }}" type="text/javascript"></script>

    <script src="{{ asset('./assets/js/plugins/moment.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('./assets/js/plugins/nouislider.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('./assets/js/plugins/moment.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/datepicker_traducao_brasil.js') }}" type="text/javascript"></script>

    <link
        href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/sb-1.5.0/sp-2.2.0/sl-1.7.0/datatables.min.css"
        rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/sb-1.5.0/sp-2.2.0/sl-1.7.0/datatables.min.js">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>

    <script src="{{ asset('js/core-init.js') }}" defer></script>

    <script src="https://unpkg.com/imask"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/core/core.css') }}" />

</head>

<body style="font-family: Inter !Important;">



    <div class="container-fluid py-4" id="app">

        <div class="row">

            <div class="col-12 mb-4 pb-2">
                @include('layouts.header')
            </div>

            <div class="col-12">
                @yield('content')
            </div>

        </div>

    </div>

    @include('components.modal')

    @if (Session::has('flash_message_errors'))
        <div class="modal fade" id="minhaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false" style="padding-top: 150px!Important;">
            <div class="modal-dialog  modal-xl">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                        <p class="modal-title text-white"
                            style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                            Ops!</p>
                    </div>
                    <div class="modal-body">

                        @if (is_array(Session::get('flash_message_errors')))
                            @php
                                $erros = Session::get('flash_message_errors');
                                $contErro = 1;
                            @endphp
                            @foreach ($erros as $erro)
                                <p>{!! $contErro . '. ' . $erro !!}</p>
                                @php
                                    $contErro++;
                                @endphp
                            @endforeach
                        @else
                            {!! Session::get('flash_message_errors') !!}
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var minhaModal = new bootstrap.Modal(document.getElementById('minhaModal'));
            minhaModal.show();
        </script>

        <?php Session::forget('flash_message_errors'); ?>
    @endif

    @php
        // Início da modal para diálogo com o cliente de mensagens contendo erros
    @endphp

    <div class="modal fade" id="modalMensagemErro" tabindex="-1" aria-labelledby="modalMensagemErroLabel"
        aria-hidden="true" style="padding-top: 150px!Important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg,#690a06 0%,#ff0c00 100%);color: white;">
                    <p class="modal-title text-white" id="modalMensagemErroLabel">Ops!</p>
                </div>
                <div class="modal-body">
                    <div id="divTextoModalMensagemErro"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    @php
        // Fim da modal para diálogo com o cliente de mensagens contendo erros
    @endphp

    @php
        // Início da modal para diálogo com o cliente de mensagens com sucesso
    @endphp

    <div class="modal fade" id="modalMensagemSucesso" tabindex="-1" aria-labelledby="modalMensagemSucessoLabel"
        aria-hidden="true" style="padding-top: 150px!Important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg,#013d1a 0%,#3bad6b 100%);color: white;">
                    <p class="modal-title text-white" id="modalMensagemSucessoLabel">Sucesso!</p>
                </div>
                <div class="modal-body">
                    <div id="divTextoModalMensagemSucesso"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    @php
        // Fim da modal para diálogo com o cliente de mensagens com sucesso
    @endphp

    <input type="hidden" name="temaProposto" id="temaProposto">

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>

    <script>
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    </script>

    @if (\Session::has('theme'))
        {

        <script>
            switchThemePorSession();
            // Função para alternar entre tema escuro e claro
            function switchThemePorSession() {
                const body = document.body;
                const currentTheme = '<?php print Session::get('theme'); ?>';

                if (currentTheme === 'dark') {

                    body.setAttribute('data-theme', 'dark');
                    document.getElementById('temaProposto').value = 'dark';

                } else {

                    body.setAttribute('data-theme', 'light');
                    document.getElementById('temaProposto').value = 'light';

                }
            }
        </script>

        }
    @endif

    <script>
        // Selecionando o ícone do tema
        const themeToggle = document.querySelector('#theme-toggle');

        // Escutando por cliques no ícone do tema
        themeToggle.addEventListener('click', switchTheme, false);

        // Função para alternar entre tema escuro e claro
        function switchTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');

            if (currentTheme === 'dark') {
                body.setAttribute('data-theme', 'light');
                document.getElementById('temaProposto').value = 'light';

                $.get("{{ route('theme.update', '') }}/" + "light", function(data) {

                });
            } else {
                body.setAttribute('data-theme', 'dark');
                document.getElementById('temaProposto').value = 'dark';

                $.get("{{ route('theme.update', '') }}/" + "dark", function(data) {

                });
            }
        }

        function trimChar(string, charToRemove) {
            while (string.charAt(0) == charToRemove) {
                string = string.substring(1);
            }

            while (string.charAt(string.length - 1) == charToRemove) {
                string = string.substring(0, string.length - 1);
            }

            return string;
        }
    </script>

    <script type="text/javascript" nonce="{{ request()->header('X-Nonce') }}">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.mascara-dinheiro').forEach(function(input) {
                input.addEventListener('input', formatarMoeda);
                input.addEventListener('click', function(event) {
                    setTimeout(() => {
                        input.setSelectionRange(input.value.length, input.value.length);
                    }, 0);
                });
            });
        });

        function formatarMoeda(event) {
            const input = event.target;
            let value = input.value.replace(/\D/g, '');

            if (value.length === 0) {
                input.value = '';
                return;
            }

            value = (parseInt(value, 10) / 100).toFixed(2) + '';
            value = value.replace(".", ",");
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

            input.value = value;

            // Move o cursor para o final do texto
            setTimeout(() => {
                input.setSelectionRange(input.value.length, input.value.length);
            }, 0);
        }

        function limitInputToMax100(inputId) {
            const input = document.getElementById(inputId);

            input.addEventListener('input', function() {
                let inputNumeric = convertToEnglishFormat(this.value);

                // Verifica se o valor é maior que 100
                if (inputNumeric > 100.00) {
                    alert("O valor digitado (" + inputNumeric +
                        ") é maior que 100%, pode ser um possível erro de digitação o sistema irá alterar o valor para 100, que é menor que o valor digitado. Caso haja uma discordância, peço, por gentileza, que entre em contato pelo e-mail visao.360@mdr.gov.br"
                    );
                    // Se for, define o valor como 100
                    this.value = formatToBrazilianCurrency(100);
                } else if (isNaN(inputNumeric)) {
                    // Se o valor digitado não for um número válido, define como 0
                    this.value = formatToBrazilianCurrency(0);
                }
            });
        }

        function convertToEnglishFormat(value) {
            // Remove todos os pontos e substitui a vírgula por ponto
            return parseFloat(value.replace(/\./g, '').replace(',', '.'));
        }

        function formatToBrazilianCurrency(value) {
            return value.toFixed(2).toString().replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleciona todos os inputs relevantes e adiciona um evento de input
            document.querySelectorAll('.mascara-dinheiro-soma').forEach(function(input) {
                input.addEventListener('input', formatarMoeda);
                input.addEventListener('click', function(event) {
                    setTimeout(() => {
                        input.setSelectionRange(input.value.length, input.value.length);
                    }, 0);
                });

                // Adiciona evento para somar valores quando o input mudar
                input.addEventListener('input', function() {
                    sumFinancialValues(input);
                });
            });

            // Função para somar valores financeiros
            function sumFinancialValues(input) {
                // Obtém os atributos necessários do input
                const acaoOrcamentaria = input.getAttribute('data-acao-orcamentaria-financeiro');
                const contAno = input.getAttribute('data-cont-ano');

                // Seleciona todos os inputs relacionados à mesma Ação Orçamentária e ano
                const inputs = document.querySelectorAll(
                    `input[data-acao-orcamentaria-financeiro="${acaoOrcamentaria}"][data-cont-ano="${contAno}"]`
                );

                // Inicializa a soma
                let total = 0;
                inputs.forEach(function(input) {
                    const value = convertToEnglishFormat(input.value);
                    if (!isNaN(value)) {
                        total += value;
                    }
                });

                // Formata o total para o formato de moeda brasileiro
                const formattedTotal = formatToBrazilianCurrency(total);

                // Atualiza o conteúdo da div com a soma
                const resultDiv = document.getElementById(`vlrFinanceiroTotal${acaoOrcamentaria}${contAno}`);
                if (resultDiv) {
                    resultDiv.innerHTML =
                        `<span style="color: red;">${formattedTotal} este valor só será válido após você clicar em Salvar.</span>`;
                }
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            // Seleciona todos os inputs relevantes e adiciona um evento de input
            document.querySelectorAll('.mascara-dinheiro-soma-financeiro').forEach(function(input) {
                input.addEventListener('input', formatarMoeda);
                input.addEventListener('click', function(event) {
                    setTimeout(() => {
                        input.setSelectionRange(input.value.length, input.value.length);
                    }, 0);
                });

                // Adiciona evento para somar valores quando o input mudar
                input.addEventListener('input', function() {
                    sumOrcamentarioValues(input);
                });
            });

            // Função para somar valores financeiros
            function sumOrcamentarioValues(input) {
                // Obtém os atributos necessários do input
                const acaoOrcamentaria = input.getAttribute('data-acao-orcamentaria');
                const contAno = input.getAttribute('data-cont-ano');

                // Seleciona todos os inputs relacionados à mesma Ação Orçamentária e ano
                const inputs = document.querySelectorAll(
                    `input[data-acao-orcamentaria="${acaoOrcamentaria}"][data-cont-ano="${contAno}"]`);

                // Inicializa a soma
                let total = 0;
                inputs.forEach(function(input) {
                    const value = convertToEnglishFormat(input.value);
                    if (!isNaN(value)) {
                        total += value;
                    }
                });

                // Formata o total para o formato de moeda brasileiro
                const formattedTotal = formatToBrazilianCurrency(total);

                // Atualiza o conteúdo da div com a soma
                const resultDiv = document.getElementById(`vlrOrcamentarioTotal${acaoOrcamentaria}${contAno}`);
                if (resultDiv) {
                    resultDiv.innerHTML =
                        `<span style="color: red;">${formattedTotal} este valor só será válido após você clicar em Salvar.</span>`;
                }
            }
        });
    </script>

</body>

</html>
