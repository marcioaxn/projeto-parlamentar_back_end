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
                    <span id="breadcrumbs-current">Cadastrar novo cliente</span>
                </span>

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Cadastrar') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nome') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>

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
                                        value="@mdr.gov.br" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="codigoUnidade"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Sua lotação de exercício') }}</label>

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
                                    {!! Form::select('codigoUnidade', $getPluckOrganizacao, old('codigoUnidade'), [
                                        'class' => 'form-control text-dark ' . $codUnidadeIsInvalid,
                                        'style' => 'cursor: pointer; width: 100% !Important;',
                                        'id' => 'codigoUnidade',
                                        'placeholder' => 'Selecione',
                                        'autocomplete' => 'off',
                                        'required' => 'required',
                                    ]) !!}

                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            $('#codigoUnidade').select2();
                                            $(document).on("select2:open", () => {
                                                document.querySelector(".select2-container--open .select2-search__field").focus()
                                            });
                                        });
                                    </script>

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
                                    {!! Form::select('cod_perfil', $getPluckPerfil, old('cod_perfil'), [
                                        'class' => 'form-control text-dark ' . $codPerfilIsInvalid,
                                        'style' => 'cursor: pointer; width: 100% !Important;',
                                        'id' => 'cod_perfil',
                                        'placeholder' => 'Selecione',
                                        'autocomplete' => 'off',
                                        'required' => 'required',
                                    ]) !!}

                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            $('#cod_perfil').select2();
                                            $(document).on("select2:open", () => {
                                                document.querySelector(".select2-container--open .select2-search__field").focus()
                                            });
                                        });
                                    </script>

                                    @error('cod_perfil')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Salvar o Cadastro') }}
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
