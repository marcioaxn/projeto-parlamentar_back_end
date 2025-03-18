<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}</title>

    <!-- Início ICON Brasil -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo_02_transparente.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logo_02_transparente.png') }}" />
    <!-- Fim ICON Brasil -->
    <link rel="stylesheet" href="{{ asset('landingpage/css/main.css') }}" />
    <noscript>
        <link rel="stylesheet" href="{{ asset('landingpage/css/noscript.css') }}" />
    </noscript>
</head>

<body class="landing is-preload">

    <!-- Page Wrapper -->
    <div id="page-wrapper">

        <!-- Header -->
        <header id="header" class="alt">
            <h1>
                <a href="{{ url('/') }}"
                    style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
                    <img src="{{ URL::asset('/img/logo_02_transparente.png') }}" alt="logo" height="33">
                    {{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}
                </a>
            </h1>
        </header>

        <!-- Banner -->
        <section id="banner">
            <div class="inner">
                <h2>{{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}</h2>
                <p>O Parlamentum otimiza o trabalho parlamentar, <br />proporcionando um hub de informações, gerando
                    insights sobre a base eleitoral.<br />Mais eficiência, melhores decisões e atendimento
                    aprimorado à população.</p>
                <ul class="actions special">
                    <li><a href="{{ route('login') }}" class="button primary">Entrar</a></li>
                </ul>
            </div>
            <a href="#two" class="more scrolly">Leia mais</a>
        </section>

        <!-- Two -->
        <section id="two" class="wrapper alt style2">
            <section class="spotlight">
                <div class="image"><img
                        src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OTJ8fGV2ZW50JTIwbWVldGluZ3xlbnwwfHwwfHx8Mg%3D%3D"
                        alt="" /></div>
                <div class="content">
                    <h2>Agenda parlamentar</h2>
                    <p>Aliquam ut ex ut augue consectetur interdum. Donec hendrerit imperdiet. Mauris eleifend fringilla
                        nullam aenean mi ligula.</p>
                </div>
            </section>
            <section class="spotlight">
                <div class="image"><img
                        src="https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="" /></div>
                <div class="content">
                    <h2>Tortor dolore feugiat<br />
                        elementum magna</h2>
                    <p>Aliquam ut ex ut augue consectetur interdum. Donec hendrerit imperdiet. Mauris eleifend fringilla
                        nullam aenean mi ligula.</p>
                </div>
            </section>
            <section class="spotlight">
                <div class="image"><img
                        src="https://images.unsplash.com/photo-1541140911322-98afe66ea6da?q=80&w=1935&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="" /></div>
                <div class="content">
                    <h2>Augue eleifend aliquet<br />
                        sed condimentum</h2>
                    <p>Aliquam ut ex ut augue consectetur interdum. Donec hendrerit imperdiet. Mauris eleifend fringilla
                        nullam aenean mi ligula.</p>
                </div>
            </section>
        </section>

        <!-- Three -->
        <section id="three" class="wrapper style3 special">
            <div class="inner">
                <header class="major">
                    <h2>Accumsan mus tortor nunc aliquet</h2>
                    <p>Aliquam ut ex ut augue consectetur interdum. Donec amet imperdiet eleifend<br />
                        fringilla tincidunt. Nullam dui leo Aenean mi ligula, rhoncus ullamcorper.</p>
                </header>
                <ul class="features">
                    <li class="icon fa-paper-plane">
                        <h3>Arcu accumsan</h3>
                        <p>Augue consectetur sed interdum imperdiet et ipsum. Mauris lorem tincidunt nullam amet leo
                            Aenean ligula consequat consequat.</p>
                    </li>
                    <li class="icon solid fa-laptop">
                        <h3>Ac Augue Eget</h3>
                        <p>Augue consectetur sed interdum imperdiet et ipsum. Mauris lorem tincidunt nullam amet leo
                            Aenean ligula consequat consequat.</p>
                    </li>
                    <li class="icon solid fa-code">
                        <h3>Mus Scelerisque</h3>
                        <p>Augue consectetur sed interdum imperdiet et ipsum. Mauris lorem tincidunt nullam amet leo
                            Aenean ligula consequat consequat.</p>
                    </li>
                    <li class="icon solid fa-headphones-alt">
                        <h3>Mauris Imperdiet</h3>
                        <p>Augue consectetur sed interdum imperdiet et ipsum. Mauris lorem tincidunt nullam amet leo
                            Aenean ligula consequat consequat.</p>
                    </li>
                    <li class="icon fa-heart">
                        <h3>Aenean Primis</h3>
                        <p>Augue consectetur sed interdum imperdiet et ipsum. Mauris lorem tincidunt nullam amet leo
                            Aenean ligula consequat consequat.</p>
                    </li>
                    <li class="icon fa-flag">
                        <h3>Tortor Ut</h3>
                        <p>Augue consectetur sed interdum imperdiet et ipsum. Mauris lorem tincidunt nullam amet leo
                            Aenean ligula consequat consequat.</p>
                    </li>
                </ul>
            </div>
        </section>

        <!-- CTA -->
        <section id="cta" class="wrapper style4">
            <div class="inner">
                <header>
                    <h2>Arcue ut vel commodo</h2>
                    <p>Aliquam ut ex ut augue consectetur interdum endrerit imperdiet amet eleifend fringilla.</p>
                </header>
                <ul class="actions stacked">
                    <li><a href="#" class="button fit primary">Activate</a></li>
                    <li><a href="#" class="button fit">Learn More</a></li>
                </ul>
            </div>
        </section>

        <!-- CTA -->
        <section id="login" class="wrapper style4">
            <div class="inner">
                <header>
                    <h2>Entrar</h2>
                </header>

                @include('auth.login-sem-layout')
            </div>
        </section>

        <!-- Footer -->
        <footer id="footer">
            <ul class="icons">
                <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
                <li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
                <li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
                <li><a href="#" class="icon brands fa-dribbble"><span class="label">Dribbble</span></a></li>
                <li><a href="#" class="icon solid fa-envelope"><span class="label">Email</span></a></li>
            </ul>
            <ul class="copyright">
                <li>&copy; {{ date('Y') }} {{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}</li>
                <li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
            </ul>
        </footer>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('landingpage/js/jquery.min.js') }}"></script>
    <script src="{{ asset('landingpage/js/jquery.scrollex.min.js') }}"></script>
    <script src="{{ asset('landingpage/js/jquery.scrolly.min.js') }}"></script>
    <script src="{{ asset('landingpage/js/browser.min.js') }}"></script>
    <script src="{{ asset('landingpage/js/breakpoints.min.js') }}"></script>
    <script src="{{ asset('landingpage/js/util.js') }}"></script>
    <script src="{{ asset('landingpage/js/main.js') }}"></script>

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

<!--
 Spectral by HTML5 UP
 html5up.net | @ajlkn
 Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
