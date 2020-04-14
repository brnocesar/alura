@extends('layout')

@section('cabecalho')
Séries
@endsection

@section('conteudo')

<div class="d-flex justify-content-end">
    <a href="/series/criar" class="btn btn-dark mb-2">Adicionar</a>
</div>

<ul class="list-group">
    <?php foreach ($series as $serie): ?>
    <li class="list-group-item"><?= $serie; ?></li>
    <?php endforeach; ?>
</ul>

@endsection
