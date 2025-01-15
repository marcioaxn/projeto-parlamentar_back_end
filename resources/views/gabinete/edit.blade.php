@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Editar Gabinete</div>
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

                {!! Form::model($gabinete, ['route' => ['gabinetes.update', $gabinete->cod_gabinete], 'method' => 'PUT']) !!} {{-- Correção aqui --}}
                    @include('gabinete._form')
                    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
