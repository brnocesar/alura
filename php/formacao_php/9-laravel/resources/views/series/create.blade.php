@extends('layout')

@section('cabecalho')
Adicionar Série
@endsection

@section('conteudo')

<div class="d-flex justify-content-end">
    <a href="/series" class="btn btn-dark mb-2">Voltar</a>
</div>

<form method="post">
    <div class="form-group">
        <label for="nome">Nome</label>
        <input id="nome" type="text" class="form-control" name="nome">
    </div>
    <div class="d-flex justify-content-end">
        <button class="btn btn-primary">Adicionar</button>
    </div>
</form>

@endsection
