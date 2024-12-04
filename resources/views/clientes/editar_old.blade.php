@extends('layouts.app')

@section('content')
    <!-- Início breadcrumbs -->
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

                <span dir="ltr" id="breadcrumbs-2">
                    <a href="{!! url('/') !!}">
                        <span id="breadcrumbs-current">Principal</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                <span dir="ltr" id="breadcrumbs-2">
                    <a href="{!! route('dashboard-clientes') !!}">
                        <span id="breadcrumbs-current">Administração Clientes</span>
                    </a>
                </span>

                <span class="pl-1 pr-1">></span>

                <span dir="ltr" id="breadcrumbs-2">
                    <span id="breadcrumbs-current">Editar dados do cliente</span>
                </span>

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Editar Dados do Usuário') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('usuario.editar', $usuario->email) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nome') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{!! $usuario->name !!}" autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('E-mail institucional') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{!! $usuario->email !!}" autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="codigoUnidade"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Lotação de exercício') }}</label>

                                <div class="col-md-6">
                                    @error('codigoUnidade')
                                        @php
                                            $codUnidadeIsInvalid = 'is-invalid';
                                        @endphp
                                    @else
                                        @php
                                            $codUnidadeIsInvalid = '';
                                        @endphp
                                    @enderror

                                    {!! Form::select('codigoUnidade', $getPluckOrganizacao, $usuario->codigoUnidade, [
                                        'class' => 'form-control text-dark ' . $codUnidadeIsInvalid,
                                        'style' => 'cursor: pointer; width: 100% !Important;',
                                        'id' => 'codigoUnidade',
                                        'placeholder' => 'Selecione',
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    @error('codigoUnidade')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="cod_perfil"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Perfil de acesso') }}</label>

                                <div class="col-md-6">
                                    @error('cod_perfil')
                                        @php
                                            $codPerfilIsInvalid = 'is-invalid';
                                        @endphp
                                    @else
                                        @php
                                            $codPerfilIsInvalid = '';
                                        @endphp
                                    @enderror

                                    {!! Form::select('cod_perfil', $getPluckPerfil, $usuario->cod_perfil, [
                                        'class' => 'form-control text-dark ' . $codPerfilIsInvalid,
                                        'style' => 'cursor: pointer; width: 100% !Important;',
                                        'id' => 'cod_perfil',
                                        'placeholder' => 'Selecione',
                                        'autocomplete' => 'off',
                                    ]) !!}


                                    @error('cod_perfil')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ativo"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Ativar ou desativar') }}</label>

                                <div class="col-md-6">
                                    @error('ativo')
                                        @php
                                            $atrAtivoIsInvalid = 'is-invalid';
                                        @endphp
                                    @else
                                        @php
                                            $atrAtivoIsInvalid = '';
                                        @endphp
                                    @enderror

                                    @if ($usuario->ativo)
                                        @php
                                            $ativo = 'Ativo';
                                        @endphp
                                    @else
                                        @php
                                            $ativo = 'Inativo';
                                        @endphp
                                    @endif

                                    {!! Form::select('ativo', [1 => 'Ativo', 0 => 'Inativo'], $usuario->ativo, [
                                        'class' => 'form-control text-dark ' . $atrAtivoIsInvalid,
                                        'style' => 'cursor: pointer; width: 100% !Important;',
                                        'id' => 'ativo',
                                        'placeholder' => 'Selecione',
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    @error('ativo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <a href="{!! route('dashboard-clientes') !!}" class="btn btn-outline-secondary">
                                        {{ __('Voltar') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Salvar Dados') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
