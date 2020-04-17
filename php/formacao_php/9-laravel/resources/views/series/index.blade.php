@extends('layout')

@section('cabecalho')
Séries
@endsection

@section('conteudo')

@if ( !empty($mensagem) )
    <div class="alert alert-success">
        {{$mensagem}}
    </div>
@endif

<div class="d-flex justify-content-end">
    <a href="/series/criar" class="btn btn-dark mb-2">Adicionar</a>
</div>

<ul class="list-group">
    @foreach ($series as $serie)
        <li class="list-group-item">
            {{ $serie->nome }}
            <form method="POST" action="/series/{{ $serie->id }}"
                onsubmit="return confirm('Tem certeza que vai excluir {{ addslashes($serie->nome)}}?')"
            >
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Excluir</button>
            </form>
        </li>
    @endforeach
</ul>

@endsection
