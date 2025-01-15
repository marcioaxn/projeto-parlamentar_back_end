@extends('layouts.app')

@section('content')
    <div class="">
        <h1>Lista de Contratos</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('contratos.create') }}" class="btn btn-primary mb-3">Novo Contrato</a>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gabinete</th>
                    <th>Plano</th>
                    <th>Data Início</th>
                    <th>Data Fim</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $contCOntrato = 1;
                @endphp
                @forelse ($contratos as $contrato)
                    <tr>
                        <td>{{ $contCOntrato }}</td>
                        <td>{{ $contrato->gabinete ? $contrato->gabinete->nom_gabinete : 'Nenhum' }}</td>
                        <td>{{ $contrato->plano ? $contrato->plano->nom_plano : 'Nenhum' }}</td>
                        <td>{{ $contrato->dat_inicio ? $contrato->dat_inicio->format('d/m/Y') : 'Não definida' }}</td>
                        <td>{{ $contrato->dat_fim ? $contrato->dat_fim->format('d/m/Y') : 'Não definida' }}</td>
                        <td>{{ $contrato->val_total }}</td>
                        <td>{{ $contrato->sta_ativo }}</td>
                        <td>
                            <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-sm btn-outline-info"><i
                                    class="fas fa-binoculars"></i> Visualizar</a>
                            <a href="{{ route('contratos.edit', $contrato) }}" class="btn btn-sm btn-outline-warning"><i
                                    class="fas fa-edit"></i> Editar</a>
                            <form action="{{ route('contratos.destroy', $contrato) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Tem certeza que deseja excluir?')"><i class="fas fa-trash"></i>
                                    Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @php
                        $contCOntrato = 1;
                    @endphp
                @empty
                    <tr>
                        <td colspan="8">Nenhum contrato encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $contratos->links() }}
    </div>
@endsection
