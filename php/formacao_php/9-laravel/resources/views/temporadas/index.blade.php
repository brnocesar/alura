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
    <a href="{{ route('listar_series') }}" class="btn btn-dark mb-2" style="font-size: 80%">
        <i class="fas fa-backspace mr-2"></i>Voltar
    </a>
</div>

<ul class="list-group">
    @foreach ($serie->temporadas as $temporada)
        <li class="list-group-item">
            <a href="#">
                Temporada {{ $temporada->numero }}
            </a>
        </li>
    @endforeach
</ul>

@endsection
