<!-- Header Principal -->
<nav id="navMenu" class="navbar fixed-top navbar-expand-xl navbar-light bg-light mb-4 py-2"
    style="background-color: #FFFFFF !Important; -webkit-box-shadow: -1px 5px 5px 0px rgba(148,148,148,0.25); -moz-box-shadow: -1px 5px 5px 0px rgba(148,148,148,0.45); box-shadow: -1px 4px 4px 0px rgba(148,148,148,0.41);">
    <div class="container-fluid">
        <!-- Logo e Marca -->
        <a class="navbar-brand mr-0 pr-0" href="{{ url('app') }}">
            <img src='{{ URL::asset('/img/logo_02_transparente.png') }}' alt="logo" height="33"
                class="d-inline-block align-top">
        </a>

        <!-- Botão Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
            aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Conteúdo do Header -->
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <!-- Menu Principal -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ml-0 pl-0">
                <li class="nav-item">
                    <a class="nav-link text-bold active" href="{{ url('app') }}" style="font-size: 1.5rem!Important;">
                        {{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}
                    </a>
                </li>
            </ul>

            <!-- Área Direita -->
            <div class="d-flex align-items-center">
                <!-- Busca -->
                <div class="me-3" id="main-searchbox">
                    <div class="header-search" id="main-searchbox"style="background-color: #FBFBFB;">
                        @auth
                            <div id="session-time-left" class="text-center textoNormal"
                                style="background-color: #FBFBFB; font-size: 0.7rem!Important; color: #727272 !Important;">
                                Tempo restante da sessão: </div>
                        @endauth
                    </div>
                </div>

                <!-- Ações do Usuário -->
                <div class="dropdown">
                    @guest
                        <div class="d-flex align-items-center">
                            <button class="btn btn-link text-dark me-2" id="theme-toggle">
                                <i class="fas fa-adjust"></i>
                            </button>
                            <a class="btn btn-outline-primary btn-sm" href="{{ url('login') }}">
                                <i class="fas fa-user me-1"></i>Entrar
                            </a>
                        </div>
                    @else
                        <!-- Menu Logado -->
                        <button class="btn btn-link dropdown-toggle d-flex align-items-center" type="button"
                            data-bs-toggle="dropdown">
                            <span class="badge bg-orange text-white rounded-circle me-2">
                                {{ pergarPrimeiraLetraUser(Auth::user()->name) }}
                            </span>
                            <span class="text-muted small">
                                Olá, {{ retornarPrimeiroUltimoNome(Auth::user()->name) }}
                            </span>
                        </button>

                        <!-- Dropdown Menu -->
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (\Session::has('bln_administrar_usuarios') && \Session::get('bln_administrar_usuarios') == 1)
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard-clientes') }}">
                                        Administração Clientes
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('trocar-senha') }}">
                                    Trocar Senha
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-1"></i>Sair
                                </a>
                            </li>
                        </ul>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="mb-2">&nbsp;</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
