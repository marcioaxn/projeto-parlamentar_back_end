@extends('layouts.app')

@section('scriptsjs')
    <script src="{{ asset('js/jquery.mask.min.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="container center mt-0 pt-0">
        <div class="row" style="font-family: 'Montserrat', sans-serif; margin: 0; padding: 0;">
            <div class="col-12 textoPaginaLogin text-justify mb-4">
                Para acessar o <strong>{{ env('APP_NAME_CURTO') ?? 'Visão 360°' }}</strong>, é necessário que a
                CGIGeo/DIGEC/SE efetue o seu cadastro. Qualquer dúvida ou problema, entre em contato pelo e-mail
                visao.360@mdr.gov.br ou pelo chat no Teams (marcio.neto@mdr.gov.br / rafael.tabares@mdr.gov.br)
            </div>
            <div class="col-12 col-md-2 col-lg-4 text-center">
                &nbsp;
            </div>
            <div class="col-12 col-md-8 col-lg-4 text-center">
                <form id="formLogin" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label text-right">{{ __('E-mail') }}</label>
                        <div class="col-md-8">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="Seu email" required autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label text-right">{{ __('Senha') }}</label>
                        <div class="col-md-8">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="Sua senha" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-3">
                            <button type="submit" class="btn btn-primary" id="login-btn">
                                {{ __('Entrar') }}
                            </button>
                            <div id="loading-message" class="mb-3" style="display: none; margin-top: 10px;">
                                <span class="spinner-border spinner-border-sm text-primary" role="status"
                                    aria-hidden="true"></span>
                                Autenticando...
                            </div>

                            <a href="{{ route('password.update') }}">Esqueci a senha</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-2 col-lg-4 text-center">
                &nbsp;
            </div>
            <div class="col-12 col-md-12 col-lg-12 pt-1 text-center">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4 text-center">
                        &nbsp;
                    </div>
                    <div class="col-12 col-md-4 col-lg-4 text-center">
                        <!-- <img src="{{ asset('img/mdr.png') }}" style="width: 75%;"> -->
                    </div>
                    <div class="col-12 col-md-4 col-lg-4 text-center">
                        &nbsp;
                    </div>
                </div>
            </div>
            <div class="col-12 text-center mb-3" style="margin: 0; padding: 0; font-size: 1.3rem;">
                <img src="{{ asset('img/logo_midr_30.png') }}" class="img-fluid">
            </div>
        </div>
    </div>
    <script>
        document.getElementById('login-btn').addEventListener('click', function(event) {
            event.preventDefault();

            var campo_email = $('#email').val();
            var campo_senha = $('#password').val();

            if (campo_email === '') {
                alert("O campo e-mail é obrigatório.");
                return false;
            } else if (campo_senha === '') {
                alert("O campo senha é obrigatório.");
                return false;
            } else {

                this.style.display = 'none';
                document.getElementById('loading-message').style.display = 'block';
                document.body.classList.add('embossed-glass');

                let form = document.getElementById('formLogin');

                setTimeout(function() {
                    form.submit();
                }, 150);
            }

        });
    </script>
@endsection