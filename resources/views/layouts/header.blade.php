<!-- Header Principal -->
<nav id="navMenu" class="navbar fixed-top navbar-expand-xl navbar-light bg-white py-2"
    style="background-color: #FFFFFF !Important; -webkit-box-shadow: -1px 5px 5px 0px rgba(148,148,148,0.25); -moz-box-shadow: -1px 5px 5px 0px rgba(148,148,148,0.45); box-shadow: -1px 4px 4px 0px rgba(148,148,148,0.41);">
    <div class="container-fluid">
        <!-- Logo e Marca -->
        <a class="navbar-brand me-0 pe-0" href="{{ url('app') }}">
            <img src="{{ asset('img/logo_02_transparente.png') }}" alt="Logo" height="33"
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
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-0 ps-0">
                <li class="nav-item">
                    <a class="nav-link text-bold active" href="{{ url('app') }}" style="font-size: 1.5rem;">
                        {{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}
                    </a>
                </li>
            </ul>

            <!-- Área Direita -->
            <div class="d-flex align-items-center">
                <!-- Busca -->


                <!-- Ações do Usuário -->
                <div class="dropdown d-flex flex-column align-items-end justify-content-end">
                    @guest
                        <!-- Menu Visitante -->
                        <div class="d-flex align-items-center">
                            <button class="btn btn-link text-dark me-2" id="theme-toggle" title="Alternar tema">
                                <i class="fas fa-adjust"></i>
                            </button>
                            <a class="btn btn-outline-primary btn-sm" href="{{ url('login') }}">
                                <i class="fas fa-user me-1"></i>
                            </a>
                        </div>
                    @else
                        <!-- Menu Logado -->
                        <button class="btn btn-link text-decoration-none p-0 m-0 border-0 d-flex align-items-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">

                            <span
                                class="bg-orange text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                style="width: 32px; height: 32px; background-color: #ff9800 !important; font-weight: bold;">
                                {{ pergarPrimeiraLetraUser(Auth::user()->name) }}
                            </span>

                            <span class="text-muted small">
                                Olá, {{ retornarPrimeiroUltimoNome(Auth::user()->name) }}
                            </span>

                        </button>

                        <div class="me-3 mr-0 pt-2 pr-0" id="main-searchbox">
                            <div class="header-search">
                                @auth
                                    <div id="session-time-left" class="text-small text-info"
                                        style="font-size: 0.7rem!Important;">
                                        Tempo restante da sessão:
                                    </div>
                                @endauth
                            </div>
                        </div>


                        <!-- Dropdown Menu -->
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (\Session::has('bln_administrar_usuarios') && \Session::get('bln_administrar_usuarios') == 1)
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard-clientes') }}">
                                        <i class="fas fa-users-cog me-2"></i>Administração Clientes
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('trocar-senha') }}">
                                    <i class="fas fa-key me-2"></i>Trocar Senha
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Sair
                                </a>
                            </li>
                        </ul>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Espaçamento para o conteúdo abaixo do header fixo -->
<div class="mb-2">&nbsp;</div>

<!-- Formulário de Logout -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
