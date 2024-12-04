@extends('layouts.email')

@if(isset($name) && $name != '')
@section('name', $name)
@endif

@if(isset($textoEmail) && $textoEmail != '')
@section('textoEmail')
{!! $textoEmail !!}
@stop
@endif
