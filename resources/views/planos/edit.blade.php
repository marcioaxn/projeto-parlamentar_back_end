@extends('layouts.app')

@section('content')
    {!! Form::model($plano, ['route' => ['planos.update', $plano->cod_plano], 'method' => 'PUT']) !!}
    <div class="row">
        <div class="col-md-12">
            <h1>Editar Plano</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('nom_plano', 'Nome do Plano') !!}
                {!! Form::text('nom_plano', old('nom_plano', $plano->nom_plano), ['class' => 'form-control', 'required']) !!}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('dsc_plano', 'Descrição') !!}
                {!! Form::textarea('dsc_plano', old('dsc_plano', $plano->dsc_plano), [
                    'class' => 'form-control',
                    'rows' => 4,
                    'required',
                ]) !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('val_plano', 'Preço') !!}
                {!! Form::number('val_plano', old('val_plano', $plano->val_plano), [
                    'class' => 'form-control',
                    'step' => '0.01',
                    'required',
                ]) !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('lim_usuarios', 'Limite de Usuários') !!}
                {!! Form::number('lim_usuarios', old('lim_usuarios', $plano->lim_usuarios), ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="sta_ativo">Status</label>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="sta_ativo" name="sta_ativo" value="1"
                        {{ old('sta_ativo', $plano->sta_ativo) ? 'checked' : '' }}>
                    <label class="form-check-label" for="sta_ativo">Ativo</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('planos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
