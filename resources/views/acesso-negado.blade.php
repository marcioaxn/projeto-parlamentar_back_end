@extends('layouts.app')

@section('content')
    <!-- Início breadcrumbs -->
    <div id="portal-breadcrumbs-wrapper" class="m-0 pl-0 mb-1 d-print-none">
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
                    <span id="breadcrumbs-current">Acesso não autorizado</span>
                </span>

            </div>
        </nav>
    </div>
    <!-- Fim breadcrumbs -->

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-3 mt-4 pt-4">
            <div class="card text-bg-secondary mb-3">
                <div class="card-header">Sem permissão</div>
                <div class="card-body bg-white">
                    <p class="card-text"><i class="fas fa-exclamation-circle text-danger"></i> O endereço que você tentou acessar é restrito a clientes com permissão. Caso você necessite de acesso, entre em contato com a equipe de gestão do sistema {{ config('app.name') }} pelo e-mail <span class="text-primary">{{ config('app.email') }}</span> .</p>
                </div>
            </div>
        </div>
    </div>
@endsection
