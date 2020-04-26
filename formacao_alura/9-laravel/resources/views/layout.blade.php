<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/576ed9b8b5.js" crossorigin="anonymous"></script>
        <title>@yield('titulo')</title>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-2 d-flex justify-content-between">
            <div class="container">
                <a class="navbar-brand" href="{{ route('listar_series') }}">Séries</a>
                <a class="navi-link btn btn-danger ml-2 mr-2" style="font-size: 80%" href="{{ route('deslogar') }}">
                    <i class="fas fa-sign-out-alt mr-2"></i>Sair
                </a>
            </div>
        </nav>

        <div class="container">
            <div class="jumbotron" style="background-color: #f1e3fd;">
                <h1>@yield('cabecalho')</h1>
            </div>

            @yield('conteudo')

        </div>
    </body>
</html>
