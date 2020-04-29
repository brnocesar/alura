<?php

namespace Alura\BuscadorDeFormacoes;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{

    private $httpClient;
    private $crawler;
    
    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }

    public function buscar(string $url): array
    {
        $resposta = $this->httpClient->request('GET', $url);

        $html = $resposta->getBody();
        $this->crawler->addHtmlContent($html);
        
        $elementosFormacoes = $this->crawler->filter('h4.formacao__title');

        $formacoes = [];

        foreach ($elementosFormacoes as $elemento) {
            $formacoes[] = $elemento->textContent;
        }

        return $formacoes;
    }
}
