@extends('layout')

@section('cabecalho')
Adicionar Série
@endsection

@section('conteudo')

<div class="d-flex justify-content-end">
    <a href="/series" class="btn btn-dark mb-2"><i class="fas fa-backspace"></i></a>
</div>

<form method="post" action="{{ route('registra_serie') }}">
    @csrf
    <div class="form-group">
        <label for="nome">Nome</label>
        <input id="nome" type="text" class="form-control" name="nome">
    </div>
    <div class="d-flex justify-content-end">
        <button class="btn btn-primary"><i class="fas fa-save"></i></button>
    </div>
</form>

@endsection
