<?php

namespace App\DataFixtures;

use App\Entity\Especialidade;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EspecialidadesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $especialidades = [
            'Cardiologia', 'Dermatologia', 'Endocrinologia', 'Geriatria', 'Ginecologia e obstetrícia',
            'Medicina do Trabalho', 'Nefrologia', 'Neurologia', 'Oftalmologia', 'Ortopedia', 
            'Otorrinolaringologia', 'Pediatria', 'Pneumologia', 'Reumatologia', 'Urologia',
        ];

        foreach ($especialidades as $descricao) {

            $especialidade = new Especialidade();
            $especialidade->setDescricao($descricao);
            
            $manager->persist($especialidade);
        }

        $manager->flush();
    }
}
