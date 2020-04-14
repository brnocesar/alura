<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Controle de Séries</title>
    </head>

    <body>
        <div class="container">
            <div class="jumbotron">
                <h1>Adicionar Série</h1>
            </div>

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
        </div>
    </body>
</html>
