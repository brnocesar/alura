<?php

require_once 'global.php';
echo '<pre>';

createProdutos(3, getCategoriaId('Roupas'));


function createProdutos($numero_pordutos, $categoria_id)
{
    $tipo_roupa = ['Blusa', 'Camisa', 'Camiseta', 'Bermuda', 'Calça', 'Jaqueta'];
    $sexo_roupa = ['Masculina', 'Feminina', 'Unisex'];
    $cor_roupa  = ['Preta', 'Vermelha', 'Azul', 'Amarela', 'Verde', 'Branca', 'Marrom', 'Rosa'];

    for ($i=0; $i<$numero_pordutos; $i++) {
    
        $tipo_index = rand(0, count($tipo_roupa)-1);
        $sexo_index = rand(0, count($sexo_roupa)-1);
        $cor_index  = rand(0, count($cor_roupa)-1);
    
        storeProduto(
            "$tipo_roupa[$tipo_index] $sexo_roupa[$sexo_index] $cor_roupa[$cor_index]",
            rand(1, 100),
            rand(1, 50),
            $categoria_id
        );
        
        echo "Item criado\n";
    }
}


function storeProduto($roupa, $preco, $quantidade, $categoria_id)
{
    $query =   "INSERT INTO produtos (nome, preco, quantidade, categoria_id)
    VALUES (:nome, :preco, :quantidade, :categoria_id)";

    $conexao = Conexao::pegarConexao();
    $stmt = $conexao->prepare($query);

    $stmt->bindValue(':nome', $roupa);
    $stmt->bindValue(':preco', $preco);
    $stmt->bindValue(':quantidade', $quantidade);
    $stmt->bindValue(':categoria_id', $categoria_id);

    $stmt->execute();
}


function getCategoriaId($categoria)
{
    $query = "SELECT id FROM categorias WHERE nome=:nome";
    $conexao = Conexao::pegarConexao();
    $stmt = $conexao->prepare($query);
    
    $stmt->bindValue(':nome', $categoria);
    $stmt->execute();
    $resultado = $stmt->fetch();
    
    return $resultado ? $resultado['id'] : createCategoria($categoria);
}


function createCategoria($categoria)
{
    $query = "INSERT INTO categorias (nome) VALUES (:nome)";
    $conexao = Conexao::pegarConexao();
    
    $stmt = $conexao->prepare($query);
    $stmt->bindValue(':nome', $categoria);
    $stmt->execute();

    return getCategoriaId($categoria);
}

echo '</pre>';
