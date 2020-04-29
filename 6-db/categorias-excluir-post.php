<?php

require_once 'global.php';

try {
    $categoria = new Categoria( $_GET['id'] );
    $categoria->excluir();

    header('Location: categorias.php');

} catch (Exception $erro) {
    Erro::trataErro($erro);
}