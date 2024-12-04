@extends('layouts.app')

@section('content')
    <div id="portal-breadcrumbs-wrapper" class="m-0 pl-0 mb-3 d-print-none">
        <nav id="breadcrumbs" aria-label="Histórico de navegação (Breadcrumbs)">
            <div class="content">
                <span class="sr-only">Você está aqui:</span>
                <span class="home">
                    <a href="{!! url('/') !!}">
                        <span class="fas fa-home" aria-hidden="true"></span>
                        <span class="sr-only">Página Inicial</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                @if (Auth::user()->trocarsenha == 1)
                    <span dir="ltr" id="breadcrumbs-2">
                        <span id="breadcrumbs-current">Alterar senha provisória</span>
                    </span>
                @else
                    <span dir="ltr" id="breadcrumbs-2">
                        <span id="breadcrumbs-current">Perfil</span>
                    </span>
                @endif

            </div>
        </nav>
    </div>

    <div class="card" style="border: 1px solid #c3c3c3;">

        <div class="card-header">
            <b>Perfil</b>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">

                    <div class="form-group">
                        <label for="exampleFormControlInput1"><b>Nome:</b></label>
                        <p class="pl-3 bg-light text-dark">{{ Auth::user()->name }}</p>
                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">

                    <div class="form-group">
                        <label for="exampleFormControlInput1"><b>Perfil de acesso:</b></label>
                        <p class="pl-3 bg-light text-dark">{{ $consulta->perfil->nom_perfil }}</p>
                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">

                    <div class="form-group">
                        <label for="exampleFormControlInput1"><b>Área do MDR:</b></label>
                        <p class="pl-3 bg-light text-dark">{{ $consulta->lotacao->sigla }} - {{ $consulta->lotacao->nome }}
                        </p>
                    </div>

                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">

                    <div class="form-group">
                        <label for="exampleFormControlInput1"><b>Data de criação do seu cadastro:</b></label>
                        <p class="pl-3 bg-light text-dark">{{ formatarDataComCarbonForHumans($consulta->created_at) }} -
                            {{ formatarTimeStampComCarbonParaBR($consulta->created_at) }}</p>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 17px !Important;">
            &nbsp;
        </div>

    </div>

    @if (Auth::user()->trocarsenha == 1)
        <div class="card">
            <div class="card-header text-white bg-danger">
                Por segurança o sistema fica bloqueado até que seja efetuada a alteração dessa primeira senha.
            </div>
        </div>
        <br>
    @endif
    @error('passwordOld')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('password')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('password_confirmation')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    {!! Form::open(['method' => 'POST', 'url' => route('update-senha')]) !!}
    <div class="card" style="border: 1px solid #c3c3c3;">
        <div class="card-header bg-info text-white">
            <b>Trocar Senha</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-muted" for="passwordOld">Senha atual</label>
                        {!! Form::password('passwordOld', [
                            'class' => 'form-control',
                            'id' => 'passwordOld',
                            'placeholder' => 'Senha atual',
                            'autofocus' => 'autofocus',
                            'required' => 'required',
                        ]) !!}
                    </div>
                    @if ($errors->has('passwordOld'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('passwordOld') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-muted" for="dimensaoId">Nova senha</label>
                        {!! Form::password('password', [
                            'class' => 'form-control',
                            'id' => 'password',
                            'placeholder' => 'Nova senha',
                            'required' => 'required',
                        ]) !!}
                        <small id="emailHelp" class="form-text text-muted">A senha pode ser alfanumérica e tem que ter
                            no mínimo 6 caracteres.</small>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-muted" for="dimensaoId">Confirme a senha</label>
                        {!! Form::password('password_confirmation', [
                            'class' => 'form-control',
                            'id' => 'password_confirmation',
                            'placeholder' => 'Confirme a senha',
                            'required' => 'required',
                        ]) !!}

                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right text-muted bg-white">
            {!! Form::submit('Alterar', ['class' => 'btn btn-secondary']) !!}
        </div>
    </div>
    <br>
    <br>
    {!! Form::close() !!}
@endsection
