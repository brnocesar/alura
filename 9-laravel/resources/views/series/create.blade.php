@extends('layout')

@section('cabecalho')
Adicionar Série
@endsection

@section('conteudo')

@include('mensagem')

<div class="d-flex justify-content-end">
    <a href="{{ route('listar_series') }}" class="btn btn-dark mb-2" style="font-size: 80%"><i class="fas fa-backspace mr-2"></i>Voltar</a>
</div>

<form method="post" enctype="multipart/form-data">
    @csrf
    <div class="row mt-2">
        <div class="col col-12">
            <label for="nome">Nome</label>
            <input id="nome" type="text" class="form-control" name="nome">
        </div>
    </div>
    <div class="row mt-2 mb-3">
        <div class="col col-3">
            <label for="qtd_temporadas">Temporadas</label>
            <input type="number" class="form-control" id="qtd_temporadas" name="qtd_temporadas">
        </div>
        <div class="col col-3">
            <label for="ep_por_temporada">Ep. / temporada</label>
            <input type="number" class="form-control" id="ep_por_temporada" name="ep_por_temporada">
        </div>
        <div class="col col-6">
            <label for="capa">Capa</label>
            <input id="capa" type="file" class="form-control" name="capa">
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-primary"><i class="fas fa-save"></i></button>
    </div>
</form>

@endsection
