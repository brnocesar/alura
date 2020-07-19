<?php 

$diretorioAtual = dir('.');

echo "Lista arquivos do diretório atual: " . $diretorioAtual->path . PHP_EOL;
while ( $algumArquivo = $diretorioAtual->read() ) {
    echo $algumArquivo . PHP_EOL;
}