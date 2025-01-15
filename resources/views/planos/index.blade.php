@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Gerenciamento de Planos</h1>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('planos.create') }}" class="btn btn-primary mb-3">Novo Plano</a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($planos as $plano)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <th>{{ $plano->nom_plano }}</th>
                            <th>{{ $plano->dsc_plano }}</th>
                            <th>{{ number_format($plano->val_plano, 2, ',', '.') }}</th>
                            <th>
                                {{-- <a href="{{ route('planos.show', $plano->cod_plano) }}" class="btn btn-outline-info btn-sm">Ver</a> --}}
                                <a href="{{ route('planos.edit', $plano->cod_plano) }}"
                                    class="btn btn-outline-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                                <form action="{{ route('planos.destroy', $plano->cod_plano) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Tem certeza que deseja excluir este plano?')"><i class="fas fa-trash"></i> Excluir</button>
                                </form>
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
