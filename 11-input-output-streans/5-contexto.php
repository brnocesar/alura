<?php 

/* 1 */
$contextoReq = stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => "X-from: PHP\r\nContent-Type: text/plain",
        'content' => 'alguma coisa'
    ]
]);

echo "1) Fazendo requisição especificando alguns parâmetros por meio de contextos:" . PHP_EOL;
echo file_get_contents('http://httpbin.org/post', false, $contextoReq);


/* 2 */
$contextoPassword = stream_context_create([
    'zip' => [
        'password' => '1234'
    ]
]);
echo "2) Arquivo zipado com senha" . PHP_EOL . file_get_contents('zip://arquivos/password1234.zip#lista-cursos.txt', false, $contextoPassword) . PHP_EOL;

/* 3 */
echo "3) Passando \"contexto\" para o fopen():" . PHP_EOL . "fopen(arquivo, modo, false, contexto)" . PHP_EOL;