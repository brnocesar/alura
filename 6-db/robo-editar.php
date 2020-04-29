<?php

require_once 'global.php';
echo '<pre>';

print_r(getCategoriaId('jihsadjkdhak'));

function getCategoriaId($categoria)
{
    $query = "SELECT id FROM categorias WHERE nome=:nome";
    $conexao = Conexao::pegarConexao();
    $stmt = $conexao->prepare($query);
    
    $stmt->bindValue(':nome', $categoria);
    $stmt->execute();
    $resultado = $stmt->fetch();
    
    return $resultado/*  ? $resultado['id'] : 'deu ruim' */;
}

echo '</pre>';
