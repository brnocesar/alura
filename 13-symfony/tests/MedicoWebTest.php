<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MedicoWebTest extends WebTestCase
{
    public function testPaginaAtualEhMaiorQueZero()
    {
        $client = static::createClient();
        $client->request('GET', '/medicos');

        $body = json_decode($client->getResponse()->getContent());
        $currentPage = $body->paginaAtual ?? null;

        $this->assertEquals(1, $currentPage);
    }
}