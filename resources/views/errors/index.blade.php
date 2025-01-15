@extends('layouts.app')

@section('content')
    <div class="row" style="font-size: 12px !Important;">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-4">

            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Página de identificação de erro</div>
                <div class="card-body">
                    <h5 class="card-title">Erro:</h5>
                    <p class="card-text">{{ $mensagem }}</p>
                </div>
            </div>

        </div>

    </div>
@endsection
