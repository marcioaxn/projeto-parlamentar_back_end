<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store,
      must-revalidate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Início ICON Brasil -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-32x32.png') }}" />
    <!-- Fim ICON Brasil -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('scriptscss')

    <!-- Fonte Rawline-->
    <link rel="stylesheet"
        href="https://cdngovbr-ds.estaleiro.serpro.gov.br/design-system/fonts/rawline/css/rawline.css" />
    <!-- Fonte Raleway-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700,800,900&amp;display=swap" />
    <!-- Design System de Governo-->
    <link rel="stylesheet" href="{{ asset('css/core/core.css') }}" />
    <!-- Fontawesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/core-init.js') }}" defer></script>

    <!-- Início CSS and JS Select with find -->
    <link href="{{ asset('select2/select2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('select2/select2.min.js') }}"></script>

</head>

<body style="font-family: Raleway !Important;">
    <div class="divMain" id="app">
        <div class="template-base">
            <nav class="br-skiplink">
                <a class="br-item" href="#main-content" accesskey="1">
                    Ir para o conteúdo (1/4) <span class="br-tag text ml-1">1</span>
                </a>
                <a class="br-item" href="#header-navigation" accesskey="2">
                    Ir para o menu (2/4) <span class="br-tag text ml-1">2</span>
                </a>
                <a class="br-item" href="#main-searchbox" accesskey="3">
                    Ir para a busca (3/4) <span class="br-tag text ml-1">3</span>
                </a>
                <a class="br-item" href="#footer" accesskey="4">
                    Ir para o rodapé (4/4) <span class="br-tag text ml-1">4</span>
                </a>
            </nav>
            <header class="br-header mb-4" id="header" data-sticky="data-sticky">
                <div class="container-fluid">
                    <div class="header-top">
                        <div class="header-logo">
                            <a href="https://www.gov.br/pt-br">
                                <img src='{{ URL::asset('/img/govbr-logo.png') }}' alt="logo" /></a>
                            <span class="br-divider vertical"></span>
                            <div class="header-sign">
                                <a href="https://www.gov.br/mdr/pt-br">
                                    Ministério da Integração e do Desenvolvimento Regional
                                </a>
                            </div>
                        </div>
                        <div class="header-actions">
                            @auth
                            @endauth
                            <div class="header-links dropdown">
                                <button class="br-button circle small" type="button" data-toggle="dropdown"
                                    aria-label="Abrir Acesso Rápido">
                                    <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
                                </button>
                                <div class="br-list">
                                    @auth
                                        <!--
                                                                                    <div class="header">
                                                                                        <div class="title">Acesso Rápido</div>
                                                                                    </div>
                                                                                    <a class="br-item" href="javascript:void(0)">Link de acesso 1</a>
                                                                                    <a class="br-item" href="javascript:void(0)">Link de acesso 2</a>
                                                                                    <a class="br-item" href="javascript:void(0)">Link de acesso 3</a>
                                                                                    <a class="br-item" href="javascript:void(0)">Link de acesso 4</a>
                                                                                -->
                                    @endauth
                                </div>
                            </div>
                            @auth
                                <span class="br-divider vertical mx-half mx-sm-1"></span>
                                <div class="header-functions dropdown">
                                    <div>
                                        <button id="theme-toggle" style="cursor: pointer;" class="br-button circle small"
                                            type="button" aria-label="Funcionalidade 4">
                                            <i class="fas fa-adjust" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <span class="sr-only">Mudar para o modo de alto contraste</span>
                                    <!--
                                                                        <button class="br-button circle small" type="button" data-toggle="dropdown" aria-label="Abrir Funcionalidades do Sistema">
                                                                            <i class="fas fa-th" aria-hidden="true"></i>
                                                                        </button>
                                                                    -->
                                    <div class="br-list">
                                        <!--
                                                                            <div class="header">
                                                                                <div class="title">
                                                                                    Funcionalidades do Sistema
                                                                                </div>
                                                                            </div>
                                                                            <div class="br-item">
                                                                                <button class="br-button circle small" type="button" aria-label="Funcionalidade 1">
                                                                                    <i class="fas fa-chart-bar" aria-hidden="true"></i>
                                                                                    <span class="text">Funcionalidade 1</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="br-item">
                                                                                <button class="br-button circle small" type="button" aria-label="Funcionalidade 2">
                                                                                    <i class="fas fa-headset" aria-hidden="true"></i>
                                                                                    <span class="text">Funcionalidade 2</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="br-item">
                                                                                <button class="br-button circle small" type="button" aria-label="Funcionalidade 3">
                                                                                    <i class="fas fa-comment" aria-hidden="true"></i>
                                                                                    <span class="text">Funcionalidade 3</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="br-item">
                                                                                <button class="br-button circle small" type="button" aria-label="Funcionalidade 4">
                                                                                    <i class="fas fa-adjust" aria-hidden="true"></i>
                                                                                    <span class="text">Funcionalidade 4</span>
                                                                                </button>
                                                                            </div>
                                                                        -->
                                    </div>
                                </div>
                            @endauth

                            <div class="header-login">
                                <div class="header-sign-in">
                                    <!-- BOTÃO PARA ENTRAR E SAIR DO SISTEMA -->
                                    @guest
                                        <div>
                                            <button id="theme-toggle" style="cursor: pointer;"
                                                class="br-button circle small" type="button"
                                                aria-label="Funcionalidade 4">
                                                <i class="fas fa-adjust" aria-hidden="true"></i>
                                            </button>
                                            <a href="{!! url('login') !!}" class="br-sign-in small"
                                                data-trigger="login">
                                                <i class="fas fa-user" aria-hidden="true"></i><span
                                                    class="d-sm-inline">Entrar</span>
                                            </a>
                                        </div>
                                        <span class="sr-only">Mudar para o modo de alto contraste</span>
                                    @else
                                        <div>
                                            <button class="br-sign-in" type="button" id="avatar-dropdown-trigger"
                                                data-toggle="dropdown" data-target="avatar-menu"
                                                aria-label="Avatar com dropdown">
                                                <span class="br-avatar" title="Fulano da Silva">
                                                    <span
                                                        class="content bg-orange-vivid-30 text-pure-0">{{ pergarPrimeiraLetraUser(Auth::user()->name) }}</span>
                                                </span>
                                                <span class="ml-2 text-gray-80 text-weight-regular">
                                                    Olá,
                                                    <span class="text-weight-semi-bold">
                                                        {{ retornarPrimeiroUltimoNome(Auth::user()->name) }}
                                                    </span>
                                                </span>
                                                <i class="fas fa-caret-down" aria-hidden="true"></i>
                                            </button>
                                            <div class="br-list" id="avatar-menu" hidden="hidden">
                                                <a class="br-item" href="{{ url('trocarSenha') }}">
                                                    Perfil
                                                </a>
                                                <a id="logoutButton" class="br-item" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    Sair
                                                </a>
                                            </div>
                                        </div>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    @endguest
                                </div>
                                <div class="header-avatar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="header-bottom">
                        <div class="header-menu">
                            <div class="header-menu-trigger" id="header-navigation">
                                <button class="br-button small circle" type="button" aria-label="Menu"
                                    data-toggle="menu" data-target="#main-navigation" id="navigation">
                                    <i class="fas fa-bars" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="header-info">
                                <div class="header-title tituloCabecalho">{{ config('app.name', 'Laravel') }}</div>
                            </div>
                        </div>

                        <div class="header-search" id="main-searchbox"style="background-color: #FBFBFB;">
                            @auth
                                <div id="session-expire" class="text-center textoNormal"
                                    style="background-color: #FBFBFB; font-size: 0.7rem!Important;"></div>
                            @endauth
                            <!--
                                <div class="br-input has-icon">
                                    <label for="searchbox">
                                        Texto da pesquisa
                                    </label>
                                    <input id="searchbox" type="text" placeholder="O que você procura?"/>
                                    <button class="br-button circle small" type="button" aria-label="Pesquisar">
                                        <i class="fas fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <button class="br-button circle search-close ml-1" type="button" aria-label="Fechar Busca" data-dismiss="search">
                                    <i class="fas fa-times" aria-hidden="true"></i>
                                </button>
                            -->
                        </div>
                    </div>
                </div>
            </header>
            <main class="d-flex flex-fill mb-5" id="main">

                <div class="container-fluid">

                    <div class="row">

                        <div class="br-menu mb-0 pt-0 pb-0" id="main-navigation">
                            <div class="menu-container">
                                <div class="menu-panel">
                                    <div class="menu-header">
                                        <div class="menu-title"><img src='{{ URL::asset('/img/govbr-logo.png') }}'
                                                alt="Imagem ilustrativa" /><span>{{ config('app.name', 'Laravel') }}</span>
                                        </div>
                                        <div class="menu-close">
                                            <button class="br-button circle" type="button"
                                                aria-label="Fechar o menu" data-dismiss="menu"><i
                                                    class="fas fa-times" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <nav class="menu-body">
                                        @guest
                                        @else
                                            @include('layouts.nav.nav_logado')
                                        @endguest
                                    </nav>
                                    <div class="menu-footer">
                                        <!--
                                            <div class="menu-logos">
                                                <a href="{!! url('fluxo-processo') !!}">
                                                    <img src="{{ asset('img/mapeamento-processo.png') }}" alt="mapeamento do processo" />
                                                </a>
                                                <a href="https://gitlab.app.mi.gov.br/marcio.neto/mdr-entregas" target="_blank">
                                                    <img src="{{ asset('img/git-lab.png') }}" alt="respositório Git" />
                                                </a>
                                                <a href="https://laravel.com/docs/6.x" target="_blank">
                                                    <img src="{{ asset('img/laravel.png') }}" alt="Framework utilizada" />
                                                </a>
                                            </div>

                                            <div class="menu-links">
                                                <a href="javascript: void(0)">
                                                    <span class="mr-1">Link externo 1</span>
                                                    <i class="fas fa-external-link-square-alt" aria-hidden="true"></i>
                                                </a>
                                                <a href="javascript: void(0)">
                                                    <span class="mr-1">Link externo 2</span>
                                                    <i class="fas fa-external-link-square-alt" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        -->
                                        <div class="menu-info">
                                            <div class="text-justify text-down-01 textoNormal mb-5">
                                                A <strong>CGIE</strong>/DIGEC/SE elaborou este sistema como um protótipo
                                                e, caso sejam
                                                bem-sucedidos, o conceito e as regras de negócio serão incorporados ao
                                                sistema MIDR Investe como um módulo adicional.
                                            </div>
                                            <div class="text-justify text-down-01 textoNormal">
                                                Qualquer dúvida ou problema entre em contato pelo
                                                e-mail midr.entrega@mdr.gov.br, pelo telefone (61) 2034-4211 ou pelo
                                                chat no
                                                Teams (marcio.neto@mdr.gov.br / rafael.tabares@mdr.gov.br)
                                            </div>
                                        </div>

                                        <div class="menu-info">
                                            <div class="text-justify text-down-01 mb-5">
                                                <p class="tituloMenuLateral">
                                                    Fluxo do Processo
                                                </p>
                                                <p class="m-0 p-1 border border-primary">
                                                    <a href="{!! url('fluxo-processo') !!}">
                                                        <img class="m-0 p-0 img-fluid"
                                                            src="{{ asset('img/fluxo_processo.png') }}"
                                                            alt="Fluxo do Processo">
                                                    </a>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="menu-info">
                                            <div class="text-justify text-down-01 mb-5">
                                                <p class="tituloMenuLateral">
                                                    <a href="#">
                                                        Perguntas frequentes
                                                    </a>
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="menu-scrim" data-dismiss="menu" tabindex="0"></div>
                            </div>
                        </div>
                        <!-- main-navigation -->
                        <div class="divMain col-12 mt-0 pt-0 pr-3">
                            @auth
                                @yield('content')
                            @else
                                @include('auth.loginI-pagina-principal')
                            @endauth

                            @if (Session::has('flash_message'))
                                <div class="modal fade" id="minhaModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                    data-bs-keyboard="false" style="padding-top: 150px!Important;">
                                    <div class="modal-dialog  modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                style="background: linear-gradient(135deg,#013d1a 0%,#3bad6b 100%);color: white;">
                                                <p class="modal-title text-white"
                                                    style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                                                    Planilha Validada!</p>
                                            </div>
                                            <div class="modal-body">

                                                @if (is_array(Session::get('flash_message')))
                                                    @php
                                                        $erros = Session::get('flash_message');
                                                        $contErro = 1;
                                                    @endphp
                                                    @foreach ($erros as $erro)
                                                        <p>{!! $contErro . '. ' . $erro !!}</p>
                                                        @php
                                                            $contErro++;
                                                        @endphp
                                                    @endforeach
                                                @else
                                                    {!! Session::get('flash_message') !!}
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    var minhaModal = new bootstrap.Modal(document.getElementById('minhaModal'));
                                    minhaModal.show();
                                </script>

                                <?php Session::forget('flash_message'); ?>
                            @endif

                        </div>
                    </div>
                </div>
            </main>
            <footer class="br-footer pt-3" id="footer">
                <div class="container-fluid">
                    <div class="info">
                        <div class="text-down-01 text-medium pb-3">
                            Protótipo desenvolvido pela Coordenação-Geral de Informações Estratégicas/DIGEC/SE
                            <a href="https://opensource.org/license/mit/">licença MIT</a>.
                        </div>
                    </div>
                </div>
            </footer>
            <!--
                <div class="br-cookiebar default d-none" tabindex="-1"></div>
            -->
        </div>
    </div>

    <!--
        Local para incluir a modal de diálogo com o cliente do sistema
    -->

    <input type="hidden" name="temaProposto" id="temaProposto">

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

</body>

</html>
