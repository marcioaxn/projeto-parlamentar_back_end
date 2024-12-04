<div class="container center mt-0 pt-0">
    <div class="row"
        style="font-family: 'Montserrat', sans-serif!Important; margin: 0px!Important; padding: 0px!Important;">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 textoPaginaLogin text-justify mb-4">
            Para acessar o <strong>{{ config('app.name', 'Laravel') }}</strong>, é necessário que a CGIE/DIGEC/SE efetue
            o seu cadastro.
            Qualquer dúvida ou problema entre em contato pelo e-mail cgie@mdr.gov.br, pelo telefone (61) 2034-4211 ou
            pelo chat no Teams (marcio.neto@mdr.gov.br / rafael.tabares@mdr.gov.br)
        </div>
        <div class="col-12 col-md-2 col-lg-4 text-center">
            &nbsp;
        </div>
        <div class="col-12 col-md-8 col-lg-4 text-center">
            <form id="formLogin" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group row">
                    <label for="cpf" class="col-sm-3 col-form-label"
                        style="text-align: right!Important;">{{ __('CPF') }}</label>
                    <div class="col-md-8">

                        <input id="cpf" type="text"
                            class="form-control {{ $errors->has('cpf') ? ' is-invalid' : '' }} cpf" name="cpf"
                            value="{{ old('cpf') }}" placeholder="Seu CPF" required autofocus>
                        @if ($errors->has('cpf'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('cpf') }}</strong>
                            </span>
                        @endif


                        <script type="text/javascript">
                            $('.cpf').mask('000.000.000-00', {
                                reverse: true
                            });
                        </script>

                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12 text-center" style="height: 4px !Important;">
                    &nbsp;
                </div>
                <div class="form-group row">
                    <label for="cpf" class="col-sm-3 col-form-label"
                        style="text-align: right!Important;">{{ __('Senha') }}</label>
                    <div class="col-md-8">
                        <input id="password" type="password"
                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                            placeholder="Sua senha" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-12" style="font-size: 0.9rem!Important;">
                        <div class="checkbox">
                            <label style="padding-top: 13px !Important; cursor: pointer;">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : 'checked' }}>
                                {{ __('Manter conexão ativa') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12 text-center" style="height: 4px !Important;">
                    &nbsp;
                </div>
                <div class="form-group row text-right">
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            &nbsp;&nbsp;&nbsp;&nbsp;{{ __('Entrar') }}&nbsp;&nbsp;&nbsp;&nbsp;
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-2 col-lg-4 mb-0 text-center">
            &nbsp;
        </div>
        <div class="col-12 col-md-12 col-lg-12 pt-1 text-center">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-4 text-center">
                    &nbsp;
                </div>
                <div class="col-12 col-md-4 col-lg-4 text-center">
                    <!-- <img src="{{ asset('img/mdr.png') }}" style="width: 75% !Important;"> -->
                </div>
                <div class="col-12 col-md-4 col-lg-4 text-center">
                    &nbsp;
                </div>
            </div>
        </div>
        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center mb-3"
            style="margin: 0px!Important; padding: 0px!Important; font-size: 1.3rem!Important;">
            <img src="{{ asset('img/logo_midr_30.png') }}" class="img-fluid">
        </div>
    </div>
</div>
