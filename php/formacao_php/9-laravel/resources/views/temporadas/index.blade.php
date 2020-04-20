@extends('layout')

@section('cabecalho')
Temporadas de {{$serie->nome}}
@endsection

@section('conteudo')

@if ( !empty($mensagem) )
    <div class="alert alert-success">
        {{$mensagem}}
    </div>
@endif

<div class="d-flex justify-content-end">
    <a href="{{ route('listar_series') }}" class="btn btn-dark mb-2"><i class="fas fa-backspace"></i></a>
</div>

<ul class="list-group">
    @foreach ($serie->temporadas as $temporada)
        <li class="list-group-item">
            Temporada {{ $temporada->numero }}
        </li>
    @endforeach
</ul>

@endsection
