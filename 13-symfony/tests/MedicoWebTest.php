<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MedicoWebTest extends WebTestCase
{
    public function testRequisicaoFalhaSemAutenticacao()
    {
        $client = static::createClient();
        $client->request('GET', '/medicos');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testEspecialidadesSaoListadasAoAutenticar()
    {
        $client = static::createClient();
        $token = $this->login($client);
        $client->request('GET', '/medicos', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token"
        ]);

        $body = json_decode($client->getResponse()->getContent());
        $currentPage = $body->paginaAtual ?? null;

        $this->assertEquals(1, $currentPage);
    }

    private function login(KernelBrowser $client): string
    {
        $client->request(
            'POST', '/login', 
            [], 
            [], 
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode([
                'username' => 'usuario',
                'password' => '123456'
            ])
        );

        $token = json_decode($client->getResponse()->getContent());
        
        return $token->access_token;
    }
}