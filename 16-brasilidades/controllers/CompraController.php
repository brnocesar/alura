<?php

require_once("models/Cliente.php");
require_once("models/Viagem.php");

if ($_POST) {
    $nome       = $_POST['nome'];
    $cpf        = $_POST['cpf'];
    $cnpj       = $_POST['cnpj'];
    $telefone   = $_POST['telefone'];
    $email      = $_POST['email'];
    $cep        = $_POST['cep'];
    $endereco   = $_POST['endereco'];
    $bairro     = $_POST['bairro'];
    $numero     = $_POST['numero'];
    $cidade     = $_POST['cidade'];
    $uf         = $_POST['uf'];
    $origem     = $_POST['origem'];
    $destino    = $_POST['destino'];
    $data_ida   = $_POST['data_ida'];
    $data_volta = $_POST['data_volta'];
    $classe     = $_POST['classe'];
    $adultos    = $_POST['adultos'];
    $criancas   = $_POST['criancas'];
    $preco      = "1.500,50";

}

try{
    $cliente = new Cliente($nome, $cpf, $cnpj, $telefone, $email, $cep, $endereco, $bairro, $numero, $cidade, $uf);
    $viagem  = new Viagem($origem, $destino, $data_ida, $data_volta, $classe, $adultos, $criancas, $preco);

} catch(Exception $erro) {
    echo "<script>alert('" . $erro->getMessage() . "')</script>";
    echo "<script>history.back()</script>";
}
