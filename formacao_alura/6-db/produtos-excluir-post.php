<?php

    require_once 'global.php';

    try {

        $produto = new Produto($_GET['id']);
        $produto->excluir();

        header('Location: produtos.php');

    } catch (Exception $erro) {
        Erro::trataErro($erro);
    }