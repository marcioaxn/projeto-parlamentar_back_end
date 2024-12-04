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

    {!! Form::open([
        'method' => 'put',
        'url' => route('cliente.update', [$usuario->cod_user]),
    ]) !!}

    <div class="row justify-content-center">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-5 col-xxl-6 mb-3">

            @include('clientes.edit-dados-pessoais')

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-7 col-xxl-6 mb-3">

            @include('clientes.edit-configuracao-basica-sistema')

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-3">

            @include('clientes.edit-permissoes-modulos')

        </div>

    </div>

    {!! Form::close() !!}
@endsection
