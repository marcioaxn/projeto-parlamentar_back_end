@extends('layouts.app')

@section('content')
    <div class="card border-danger mt-4 mb-3">
        <div class="card-header bg-danger text-white">Cadastro inativo</div>
        <div class="card-body text-danger">
            <p class="card-text">
                <i class="fas fa-exclamation-triangle text-danger"></i> Seu cadastro está inativo. Você acessa o sistema, mas não pode consultar ou efetivar qualquer ação.
            </p>
        </div>
    </div>
@endsection
