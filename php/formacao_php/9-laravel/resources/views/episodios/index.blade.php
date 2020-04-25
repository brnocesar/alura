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

<form action="{{ route('assistir_episodios', $temporada->id) }}" method="POST">
    @csrf
    <ul class="list-group">

        @foreach ($temporada->episodios as $episodio)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Episódio {{ $episodio->numero }}
                <input type="checkbox" name="episodios[]" value="{{ $episodio->id }}" {{ $episodio->assistido ? 'checked' : '' }}>
            </li>
        @endforeach

    </ul>
    <div class="d-flex justify-content-end mt-2">
        <button class="btn btn-primary mt-2 mb-2"><i class="fas fa-save"></i></button>
    </div>
</form>

@endsection
