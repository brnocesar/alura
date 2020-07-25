@extends('layout')

@section('cabecalho')
Temporadas de <i>{{ $serie->nome }}</i>
@endsection

@section('conteudo')

@include('mensagem')

<div class="d-flex justify-content-end">
    <a href="{{ route('listar_series') }}" class="btn btn-dark mb-2" style="font-size: 80%">
        <i class="fas fa-backspace mr-2"></i>Voltar
    </a>
</div>

@if ( $serie->capa )
    <div class="d-flex justify-content-center mt-2">
        <img src="{{$serie->capa_url}}" alt="Imagem de {{$serie->nome}}" class="img-thumbnail" height="250px" width="250px" />
    </div>
@endif

<ul class="list-group mt-3">
    @foreach ($serie->temporadas as $temporada)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="{{ route('listar_episodios', $temporada->id) }}">
                Temporada {{ $temporada->numero }}
            </a>
            <span class="badge badge-success">
                {{ $temporada->getEpisodiosAssistidos()->count() }} / {{$temporada->episodios->count()}}
            </span>
        </li>
    @endforeach
</ul>

@endsection
