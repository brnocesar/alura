<?php
    require 'autoload.php';

    use App\Alura\Contato;

    $usuario = new App\Alura\Usuario($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['genero']);
    $contato = new Contato($_POST['endereco'], $_POST['cep'], $_POST['telefone']);

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="/assets/bootstrap.min.css">
        <title>Strings</title>
    </head>

    <body>
        <div class="mx-5 my-5">
            <h1>Cadastro feito com sucesso</h1>
            <p><?php echo htmlspecialchars($usuario->getTratamentoSobrenome()); ?>, seguem os dados de sua conta:</p>
            <ul class="list-group">
                <li class="list-group-item">Primeiro nome: <?php echo htmlspecialchars($usuario->getNome()); ?></li>
                <li class="list-group-item">Sobrenome: <?php echo htmlspecialchars($usuario->getSobrenome()); ?></li>
                <li class="list-group-item">Usuário: <?php echo htmlspecialchars($usuario->getUsuario()); ?></li>
                <li class="list-group-item">Senha: <?php echo htmlspecialchars($usuario->getSenha()); ?></li>
                <li class="list-group-item">Telefone: <?php echo htmlspecialchars($contato->getTelefone()); ?></li>
                <li class="list-group-item">Email: <?php echo htmlspecialchars($usuario->getEmail()); ?></li>
                <li class="list-group-item">Endereço: <?php echo htmlspecialchars($contato->getEnderecoCep()); ?></li>
            </ul>
        </div>
    </body>
</html>
