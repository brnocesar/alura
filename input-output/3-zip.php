<?php

// usando wrapper 'zip://', carrega conteudo de um arquivo zipado
echo "1) arquivo zipado" . PHP_EOL . file_get_contents('zip://arquivos/arquivos.zip#arquivo.txt');

echo "2) arquivo dentro de pasta zipada" . PHP_EOL . file_get_contents('zip://arquivos/pasta.zip#pasta/arquivo.txt');
