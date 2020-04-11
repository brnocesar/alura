<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cursos</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body>
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="/listar-cursos">Home</a>
            
            <?php if (isset($_SESSION['logado'])) : ?>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/logout">Sair</a>
                    </li>
                </ul>
            <?php endif; ?>
        </nav>

        <div class="container">
            <div class="jumbotron">
                <h1><?= $titulo; ?></h1>
            </div>