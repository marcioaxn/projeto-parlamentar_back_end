<nav class="br-skiplink d-print-none">
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
<header class="br-header mb-4 pt-2 pb-1" id="header" data-sticky="data-sticky">
    <div class="container-fluid m-0 pl-3 pr-4">
        <div class="header-top">
            <div class="header-logo">
                <a href="https://www.gov.br/pt-br">
                    <img src='{{ URL::asset('/img/govbr-logo.png') }}' alt="logo" /></a>
                <span class="br-divider vertical"></span>
                <div class="header-sign">
                    <a href="https://www.gov.br/mdr/pt-br">
                        <span class="d-none d-sm-block">Ministério da Integração e do Desenvolvimento
                            Regional</span>
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
                    <div class="br-list bg-light">
                        @auth
                            <div class="header">
                                <div class="title bg-light">Acesso Rápido</div>
                            </div>
                            @if (!is_null(\Route::currentRouteAction()) && \Route::currentRouteAction() != '' && Auth::user()->trocarsenha == 0)
                            @endif

                            @if (\Session::has('bln_administrar_usuarios') && \Session::get('bln_administrar_usuarios') == 1)
                                <a class="br-item bg-light @if (
                                    \Route::currentRouteAction() === 'App\Http\Controllers\UsersController@dashboardClientes' ||
                                        \Route::currentRouteAction() === 'App\Http\Controllers\UsersController@edit') ) text-bold @endif"
                                    href="{!! route('dashboard-clientes') !!}">Administração Clientes</a>
                            @endif
                        @endauth
                    </div>
                </div>
                @auth
                    <span class="br-divider vertical mx-half mx-sm-1 d-print-none"></span>
                    <div class="header-functions dropdown d-print-none">
                        <div class="d-print-none">
                            <button id="theme-toggle" style="cursor: pointer;" class="br-button circle small d-print-none"
                                type="button" aria-label="Funcionalidade 4">
                                <i class="fas fa-adjust" aria-hidden="true"></i>
                            </button>
                        </div>
                        <span class="sr-only d-print-none">Mudar para o modo de alto contraste</span>
                        <button class="br-button circle small" type="button" data-toggle="dropdown"
                            aria-label="Abrir Funcionalidades do Sistema">
                            <i class="fas fa-th" aria-hidden="true"></i>
                        </button>
                    </div>
                @endauth

                <div class="header-login d-print-none">
                    <div class="header-sign-in d-print-none">
                        <!-- BOTÃO PARA ENTRAR E SAIR DO SISTEMA -->
                        @guest
                            <div>
                                <button id="theme-toggle" style="cursor: pointer;" class="br-button circle small"
                                    type="button" aria-label="Funcionalidade 4">
                                    <i class="fas fa-adjust" aria-hidden="true"></i>
                                </button>
                                <a class="br-sign-in small text-primary" href="{!! url('login') !!}"
                                    style="color: #1351B4!Important;">
                                    <i class="fas fa-user" aria-hidden="true"></i><span class="d-sm-inline">Entrar</span>
                                </a>
                            </div>
                            <span class="sr-only">Mudar para o modo de alto contraste</span>
                        @else
                            <div class="dropdown">
                                <button class="br-sign-in" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="br-avatar" title="{{ retornarPrimeiroUltimoNome(Auth::user()->name) }}">
                                        <span class="content bg-orange-vivid-30 text-pure-0"
                                            style="font-size: 1.1rem!Important;">{{ pergarPrimeiraLetraUser(Auth::user()->name) }}</span>
                                    </span>
                                    <span class="ml-2 text-gray-80 text-weight-regular">
                                        Olá,
                                        <span class="text-weight-semi-bold">
                                            {{ retornarPrimeiroUltimoNome(Auth::user()->name) }}
                                        </span>
                                    </span>
                                    <i class="fas fa-caret-down" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item {{ \Route::currentRouteAction() === 'App\Http\Controllers\UsersController@paginaTrocarSenha' ? 'active' : '' }} "
                                            href="{{ route('trocar-senha') }}">Trocar senha</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span class="text-danger text-bold"><i class="fas fa-sign-out-alt"></i>
                                                Sair</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
                    <button class="br-button small circle" type="button" aria-label="Menu" data-toggle="menu"
                        data-target="#main" id="navigation">
                        <i class="fas fa-bars" aria-hidden="false"></i>
                    </button>
                </div>
                <div class="header-info">
                    <div class="header-title tituloCabecalho">
                        <a href="{!! url('/') !!}">
                            {{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="header-search" id="main-searchbox"style="background-color: #FBFBFB;">
                @auth
                    <div id="session-time-left" class="text-center textoNormal"
                        style="background-color: #FBFBFB; font-size: 0.7rem!Important; color: #727272 !Important;">
                        Tempo restante da sessão: </div>
                @endauth
            </div>
        </div>
    </div>
</header>
