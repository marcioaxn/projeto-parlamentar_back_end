@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>Lista de Gabinetes</h1>
            <a href="{{ route('gabinetes.create') }}" class="btn btn-primary mb-3">Novo Gabinete</a>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table id="gabinetes-table" class="table table-bordered w-100"> {{-- w-100 para ocupar toda a largura --}}
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Parlamentar</th>
                            <th>Nome do Gabinete</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gabinetes as $gabinete)
                            <tr>
                                <th>{{ $gabinete->cod_gabinete }}</th>
                                <th>{{ $gabinete->parlamentar->nom_parlamentar ?? 'Não Informado' }}</th>
                                <th>{{ $gabinete->nom_gabinete }}</th>
                                <th>{{ $gabinete->sta_ativo ? 'Sim' : 'Não' }}</th>
                                <th>
                                    <a href="{{ route('gabinetes.edit', $gabinete) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i> Editar</a>
                                    <form action="{{ route('gabinetes.destroy', $gabinete) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir?')"><i class="fas fa-trash"></i> Excluir</button>
                                    </form>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('#gabinetes-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        },
        responsive: true,
        // outras opções do DataTables, se necessário
    });
});
</script>
@endsection
