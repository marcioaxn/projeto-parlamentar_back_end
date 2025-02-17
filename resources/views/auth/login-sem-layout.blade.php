<style>
    /* Reset básico */
    * {
        box-sizing: border-box;
    }

    /* Container do formulário */
    #formLogin {
        width: 100%;
        max-width: 600px;
        /* ajuste conforme necessário */
        margin: 0 auto;
        padding: 20px;
    }

    /* Form Group */
    .form-group {
        margin-bottom: 1rem;
    }

    /* Linhas */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    /* Colunas */
    .col-sm-3 {
        flex: 0 0 25%;
        max-width: 25%;
        padding: 0 15px;
    }

    .col-md-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        padding: 0 15px;
    }

    /* Labels */
    .col-form-label {
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        font-size: inherit;
        line-height: 1.5;
    }

    .text-right {
        text-align: right;
    }

    /* Campos de entrada */
    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    /* Validação */
    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #dc3545;
    }

    .is-invalid+.invalid-feedback {
        display: block;
    }

    /* Botão */
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
            border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn-primary {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    /* Spinner */
    .spinner-border {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        vertical-align: -0.125em;
        border: 0.2em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }

    @keyframes spinner-border {
        to {
            transform: rotate(360deg);
        }
    }

    /* Offset */
    .offset-md-3 {
        margin-left: 25%;
    }

    /* Espaçamentos */
    .mb-0 {
        margin-bottom: 0 !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    /* Responsividade */
    @media (min-width: 768px) {
        .col-md-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
    }

    @media (min-width: 576px) {
        .col-sm-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }

    /* Mensagem de loading */
    #loading-message {
        display: none;
        margin-top: 10px;
        color: #0d6efd;
    }
</style>
@auth()

    <div class="container center mt-0 pt-0">
        <div class="row" style="font-family: 'Montserrat', sans-serif; margin: 0; padding: 0;">
            <div class="col-12 textoPaginaLogin text-justify mb-4">
                Você está logado como {{ auth()->user()->name }}
            </div>

        </div>

    </div>
@else
    @section('scriptsjs')
        <script src="{{ asset('js/jquery.mask.min.js') }}" type="text/javascript"></script>
    @endsection

    <div class="container center mt-0 pt-0">
        <div class="row" style="font-family: 'Montserrat', sans-serif; margin: 0; padding: 0;">
            <div class="col-12 textoPaginaLogin text-justify mb-4">
                Para acessar o <strong>{{ env('APP_NAME_CURTO') ?? 'Parlamentum' }}</strong>, é necessário que já esteja
                cadastrado e relacionado a um gabinete. Entre em contato por e-mail {{ env('APP_EMAIL') ?? '' }}
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
                                name="email" value="{{ old('email') }}" placeholder="Seu email" required>
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
                                {{ __('Acessar') }}
                            </button>
                            <div id="loading-message" class="mb-3"
                                style="display: none; margin-top: 10px; color: #FFFFFF!Important;">
                                <span class="spinner-border spinner-border-sm text-white" role="status"
                                    aria-hidden="true"></span>
                                Autenticando...
                            </div>

                            <a href="{{ route('password.update') }}">Esqueci a senha</a>
                        </div>
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
@endauth
