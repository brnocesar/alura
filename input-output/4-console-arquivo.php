<?php 

/* 1 */
echo "Digite o último curso que você fez: " . PHP_EOL;
$teclado = fopen('php://stdin', 'r'); // tudo que começa com 'php://' é fechado automáticamente
// $novoCurso = fgets($teclado); // com quebra-linha no final
$novoCurso = trim(fgets($teclado)); // sem quebra-linha no final

file_put_contents('arquivos/lista-cursos.txt', "\n$novoCurso", FILE_APPEND);

/* 2 */
echo "Digite mais um curso que você fez: " . PHP_EOL;
$outroCurso = trim(fgets(STDIN)); // sem quebra-linha no final

file_put_contents('arquivos/lista-cursos.txt', "\n$outroCurso", FILE_APPEND);