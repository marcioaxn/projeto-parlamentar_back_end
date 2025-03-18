<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo_02_transparente.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logo_02_transparente.png') }}" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link
        href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/sb-1.5.0/sp-2.2.0/sl-1.7.0/datatables.min.css"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/core/core.css') }}" />

    @yield('scriptscss')

    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
</head>

<body style="font-family: 'Inter', sans-serif; background-color: #fdfdfd;">

    <div class="container-fluid py-4" id="app">
        <div class="row">
            <div class="col-12 mb-4 pb-3">
                @include('layouts.header')
            </div>
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>

    @include('components.modal')

    <!-- Modals for Errors and Success Messages -->
    @if (Session::has('flash_message_errors'))
        <div class="modal fade" id="minhaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false" style="padding-top: 150px!Important;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Ops!</h5>
                    </div>
                    <div class="modal-body">
                        @if (is_array(Session::get('flash_message_errors')))
                            @foreach (Session::get('flash_message_errors') as $index => $error)
                                <p>{{ $index + 1 }}. {{ $error }}</p>
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
            document.addEventListener('DOMContentLoaded', function() {
                var minhaModal = new bootstrap.Modal(document.getElementById('minhaModal'));
                minhaModal.show();
            });
        </script>
        <?php Session::forget('flash_message_errors'); ?>
    @endif

    <div class="modal fade" id="modalMensagemErro" tabindex="-1" aria-labelledby="modalMensagemErroLabel"
        aria-hidden="true" style="padding-top: 150px!Important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Ops!</h5>
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

    <div class="modal fade" id="modalMensagemSucesso" tabindex="-1" aria-labelledby="modalMensagemSucessoLabel"
        aria-hidden="true" style="padding-top: 150px!Important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Sucesso!</h5>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/session_timeout.js') }}" type="text/javascript"></script>
    <script src="{{ asset('./assets/js/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('./assets/js/plugins/nouislider.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/datepicker_traducao_brasil.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/sb-1.5.0/sp-2.2.0/sl-1.7.0/datatables.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script src="{{ asset('js/core-init.js') }}" defer></script>
    <script src="https://unpkg.com/imask"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltip initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Popover initialization
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });

            // Theme toggle
            const themeToggle = document.querySelector('#theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', switchTheme, false);
            }

            function switchTheme() {
                const body = document.body;
                const currentTheme = body.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                body.setAttribute('data-theme', newTheme);
                document.getElementById('temaProposto').value = newTheme;

                $.get("{{ route('theme.update', '') }}/" + newTheme, function(data) {});
            }

            // Currency formatting
            document.querySelectorAll('.mascara-dinheiro').forEach(function(input) {
                input.addEventListener('input', formatarMoeda);
                input.addEventListener('click', function(event) {
                    setTimeout(() => {
                        input.setSelectionRange(input.value.length, input.value.length);
                    }, 0);
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

                setTimeout(() => {
                    input.setSelectionRange(input.value.length, input.value.length);
                }, 0);
            }
        });
    </script>

    @if (\Session::has('theme'))
        <script>
            switchThemePorSession();

            function switchThemePorSession() {
                const body = document.body;
                const currentTheme = '<?php print Session::get('theme'); ?>';
                body.setAttribute('data-theme', currentTheme);
                document.getElementById('temaProposto').value = currentTheme;
            }
        </script>
    @endif

    <script type="text/javascript" nonce="{{ request()->header('X-Nonce') }}">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

</body>

</html>
