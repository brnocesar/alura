<?php 

/* 1 */
$tela = fopen('php://stdout', 'w');
fwrite($tela, "1) Olá mundo (saída padrão)\n");

/* 2 */
$tela = fopen('php://stderr', 'w');
fwrite($tela, "2) Olá mundo (saída de erros)\n");

/* 3 */
fwrite(STDOUT, "3) Olá mundo (saída padrão com constante)\n");
fwrite(STDERR, "3) Olá mundo (saída de erros com constante)\n");

/* 4 */
echo "4) Jogando conteúdo de um stream para outro:" . PHP_EOL;
$conteudoArquivo = fopen('zip://arquivos/arquivos.zip#arquivo.txt', 'r');
stream_copy_to_stream($conteudoArquivo, STDOUT);