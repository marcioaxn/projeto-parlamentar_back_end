@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalhes do Gabinete</h1>

        <p>Código: {{ $cod_gabinete->cod_gabinete }}</p>
        <p>Nome: {{ $cod_gabinete->nom_gabinete }}</p> {{-- Acesso direto, o Accessor já descriptografa --}}
        <p>Parlamentar: {{ $cod_gabinete->parlamentar->nome ?? 'N/A' }}</p>
        <p>Status: {{ $cod_gabinete->sta_ativo ? 'Ativo' : 'Inativo' }}</p>

        <a href="{{ route('gabinetes.index') }}">Voltar</a>
        <a href="{{ route('gabinetes.edit', $cod_gabinete) }}">Editar</a>
    </div>
@endsection
