@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center pt-3 vh-95">
        <div class="card shadow p-4" style="max-width: 500px; width: 100%; border-radius: 15px;">
            <div class="card-body">
                <div class="text-center pb-4">
                    <h2 class="font-weight-bold" style="color: #2c3e50;">Acesso ao {{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}</h2>
                    <p class="text-muted">Para acessar o sistema, é necessário estar cadastrado e vinculado a um gabinete. Caso não tenha acesso, entre em contato por e-mail: <a href="mailto:{{ env('APP_EMAIL') }}">{{ env('APP_EMAIL') }}</a></p>
                </div>

                <form id="formLogin" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">{{ __('E-mail') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" placeholder="Digite seu e-mail" required autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="password" class="form-label">{{ __('Senha') }}</label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            placeholder="Digite sua senha" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-block" id="login-btn">
                            {{ __('Entrar') }}
                        </button>
                        <div id="loading-message" class="text-center mt-2" style="display: none;">
                            <span class="spinner-border spinner-border-sm text-primary" role="status"
                                aria-hidden="true"></span>
                            Autenticando...
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('password.update') }}" class="text-decoration-none">Esqueci minha senha</a>
                    </div>
                </form>
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

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        }
        .card {
            border: none;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        a {
            color: #3498db;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #2980b9;
        }
    </style>
@endsection