<?php include __DIR__ . '/../inicio-html.php'; ?>

<form action="/login" method="post">
    
    <div class="form-group">
        <label for="email">E-mail: </label>
        <input name="email" id="email" type="email" class="form-control">
    </div>

    <div class="form-group">
        <label for="senha">Senha: </label>
        <input name="senha" id="senha" type="password" class="form-control">
    </div>
    
    <button class="btn btn-primary">
        Entrar
    </button>
</form>

<?php include __DIR__ . '/../fim-html.php'; ?>