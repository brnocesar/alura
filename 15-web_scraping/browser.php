<?php

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

require_once 'vendor/autoload.php';

$homeURL = 'https://vitormattos.github.io/poc-lineageos-cellphone-list-statics//';

$client = HttpClient::create();

$browser = new HttpBrowser($client);

$crawler = $browser->request('GET', $homeURL);


// compara retorno dos dois pacotes
// $html = $crawler->html();
// var_dump($html);
// echo (bool) $html == $client->request('GET', $homeURL)->getContent(); // 1


/* (1) modifica user agent */
// echo "=> original\n";
// print_r($browser->getRequest());
// $browser->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246');
// $crawler = $browser->request('GET', $homeURL);
// echo "=> modificado\n{$browser->getServerParameter('HTTP_USER_AGENT')}\n";


/* (2) fazendo login */
// $login = $browser->clickLink('Login'); // retorna instancia de DomCrawler
// var_dump($login->html());

// $crawler = $browser->back(); // voltando para pagina anterior
// var_dump($crawler->html());

// preenche formulário de autenticação
// $crawler = $browser->submitForm('Go', [
//     'username' => 'bolachinha',
//     'password' => '1234'
// ], 'GET');
// var_dump($crawler->html());



/* (3) coletando dados */
// echo "Nome da página: {$crawler->filterXPath('//title')->text()}\n"; // seletor XPath
// echo "Título da página: {$crawler->filterXPath('//h2')->text()}\n";

// echo "Nome da página: {$crawler->filter('title')->text()}\n"; // seletor CSS
// echo "Título da página: {$crawler->filter('.title-page')->text()}\n";

// echo "Celulares na página:\n";
// // var_dump($crawler->filter('article .title'));
// $celulares = $crawler->filter('article .title')->each(function ($node) {
//     return $node->text();
// });
// print_r($celulares);


/* (4.1) baixando imagens */
// $imagens = $crawler->filter('article .img-thumbnail')->images();
// // shell_exec('rm -rf arquivos/');
// if ( !is_dir('arquivos') ) {
//     mkdir('arquivos');
//     shell_exec("echo *.png > arquivos/.gitignore");
// }
// foreach ($imagens as $imagem) {
//     $url = $imagem->getUri();
//     $nome = basename($url);
//     file_put_contents(
//         "arquivos/$nome", 
//         file_get_contents($url) // "parece" mais rapido que fopen
//         // fopen($url, 'rb')
//     );
//     echo "$url\n";
// }

/* (4.2) baixando imagens e coletando atributos */
// na instrução acima poderiamos ter acessado o atributo 'src', mas o método image() ja retorna a URL bonitinha
// $atributos = $crawler->filter('article .img-thumbnail')->each(function ($node) {
//     return $node->attr('alt');
// });
// print_r($atributos);

/* (4.3) baixando arquivos */
// $arquivos = $crawler->filter('link[rel="stylesheet"],script[src]')->each(function ($node) {
//     return $node->attr('src') ?? $node->attr('href');
// });
// print_r($arquivos);


/* (5.1) Paginação - coletando os modelos de celulares */
$totalItems = $crawler->filter('header')->text();
$totalItems = preg_replace('/\D/', '', $totalItems);
$totalPaginas = ceil($totalItems / 10);

// $celulares = $crawler->filter('article .title')->each(function ($node) {
//     return $node->text();
// });

// // for ($i=2; $i <= $totalPaginas; $i++) {
// //     $crawler = $browser->request('GET', "{$homeURL}{$i}");

// //     $celulares = array_merge($celulares, $crawler->filter('article .title')->each(function ($node) {
// //         return $node->text();
// //     }));
// // }
// print_r($celulares);

/* (5.2) Coletando todas as informações de cada celular */
$celulares = $crawler->filter('article')->each(function ($node) {

    $dados['modelo'] =  $node->filter('.title')->text();
    $indices = $node->filter('th')->each(function ($attr) {
        return $attr->text();
    });
    $valores = $node->filter('td')->each(function ($attr) {
        return $attr->text();
    });

    $dados = array_merge($dados, array_combine($indices, $valores));

    return $dados;
});
print_r($celulares);