@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Criar Gabinete</div>
            <div class="card-body bg-white">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {!! Form::open(['route' => 'gabinetes.store']) !!} {{-- Método padrão é POST --}}
                    @include('gabinete._form') {{-- Inclui o formulário parcial --}}
                    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('gabinetes.index') }}" class="btn btn-secondary">Cancelar</a>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection



