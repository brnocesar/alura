<?php
    require_once 'global.php';

    try {
        $categoria = new Categoria();
        $nome = $_POST['nome']; // a chave da variavel POST deve ser igual ao 'name' do input no HTML
        $categoria->nome = $nome;
        $categoria->inserir();

        header('Location: categorias.php'); // redireciona para o index
    
    } catch (Exception $erro) {
        Erro::trataErro($erro);
    }