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
                <h1>Séries</h1>
            </div>

            <a href="#" class="btn btn-dark mb-2">Adicionar</a>

            <ul class="list-group">
                <?php foreach ($series as $serie): ?>
                <li class="list-group-item"><?= $serie; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </body>
</html>
