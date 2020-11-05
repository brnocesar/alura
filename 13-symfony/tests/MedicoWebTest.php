<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MedicoWebTest extends WebTestCase
{
    public function testRequisicaoFalhaSemAutenticacao()
    {
        $client = static::createClient();
        $client->request('GET', '/medicos');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}