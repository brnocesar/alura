<?php include __DIR__ . '/../inicio-html.php'; ?>

<span class="d-flex justify-content-end">
    <a href="/listar-cursos" class="btn btn-primary mb-2">Voltar</a>
</span>

<form action="/salvar-curso<?= isset($curso) ? '?id=' . $curso->getId() : ''; ?>" method="POST">
    <div class="form-group">
        <label for="descricao">Descrição</label>
        <input id="descricao" class="form-control" type="text" name="descricao"
            value="<?= isset($curso) ? $curso->getDescricao() : ''; ?>"
        >
    </div>
    <span class="d-flex justify-content-end">
        <button class="btn btn-primary">Salvar</button>
    </span>
</form>

<?php include __DIR__ . '/../fim-html.php'; ?>