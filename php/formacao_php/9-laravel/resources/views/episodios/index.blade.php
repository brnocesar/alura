@extends('layout')

@section('cabecalho')
Episódios da {{$temporada->numero}}<sup>a</sup> Temporada de {{$temporada->serie->nome}}
@endsection

@section('conteudo')

@if ( !empty($mensagem) )
    <div class="alert alert-success">
        {{$mensagem}}
    </div>
@endif

<div class="d-flex justify-content-end">
    <a href="{{ route('listar_temporadas', $temporada->serie->id) }}" class="btn btn-dark mb-2" style="font-size: 80%">
        <i class="fas fa-backspace mr-2"></i>Voltar
    </a>
</div>

<ul class="list-group">
    @foreach ($temporada->episodios as $episodio)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Episódio {{ $episodio->numero }}
        </li>
    @endforeach
</ul>

@endsection
