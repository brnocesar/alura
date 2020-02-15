<?php

require_once 'global.php';

try {
    $produto = new Produto();

    $produto->nome = $_POST['nome'];
    $produto->quantidade = $_POST['quantidade'];
    $produto->preco = $_POST['preco'];
    $produto->categoria_id = $_POST['categoria_id'];
    $produto->inserir();

    header('Location: produtos.php');

} catch (Exception $erro) {
    Erro::trataErro($erro);
}