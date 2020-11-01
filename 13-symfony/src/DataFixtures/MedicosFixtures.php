<?php

namespace App\DataFixtures;

use App\Entity\Medico;
use App\Factory\MedicoFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class MedicosFixtures extends Fixture implements DependentFixtureInterface
{
    private $especialidadeRepository;
    private $medicoFactory;

    public function __construct(EspecialidadeRepository $especialidadeRepository, MedicoFactory $medicoFactory)
    {
        $this->especialidadeRepository = $especialidadeRepository;
        $this->medicoFactory = $medicoFactory;
    }

    public function load(ObjectManager $manager)
    {
        $nomes = [
            'Bruno', 'Bruna', 'Breno', 'Beatriz', 'Bruce', 'Bianca', 'Braian', 'Bárbara', 'Basílio', 
            'Bella', 'Benedito', 'Berenice', 'Benjamim', 'Brenda', 'Bóris', 'Branca', 'Bonifácio', 'Beatrice',
            'Brandon', 'Bia', 'Bernardo', 'Betina', 'Barbosa', 'Betânia', 'Bento', 'Becky', 'Benício', 'Brittany',
        ];
        $totalNomes = count($nomes);

        $sobrenomes = [
            'Cahill', 'Caldwell', 'Callahan', 'Cameron', 'Campbell', 'Cannon', 'Carey', 'Carlson', 'Carpenter', 
            'Carson', 'Carter', 'Carver', 'Casey', 'Cassidy', 'Castillo', 'Castro', 'Chandler', 'Chaney', 'Chapman',
            'Chase', 'Chavez', 'Childers', 'Choate', 'Christian', 'Clancy', 'Clark', 'Clay', 'Clayton', 'Cleveland',
            'Clifford', 'Cobb', 'Coffey', 'Cole', 'Coleman', 'Collins', 'Compton', 'Connolly', 'Conrad', 'Conway', 
            'Cooper', 'Costello', 'Crawford', 'Cross', 'Cruz', 'Cullen', 'Cunningham', 'Curtis',
        ];
        $totalSobrenomes = count($sobrenomes);

        $especialidades = array_column(
            json_decode(json_encode($this->especialidadeRepository->findAll())),
            'id'
        );
        $totalEspecialidades = count($especialidades);

        
        $totalMedicos = $_ENV['MEDICOS_INICIAIS'];
        for ($i=0; $i < $totalMedicos; $i++) {

            try {
                $medicoDados = json_encode([
                    'crm' => substr(
                        str_replace(['0.', ' '], '', microtime()), 
                        mt_rand(0,10), 
                        6
                    ),
                    'nome' => "{$nomes[mt_rand(0, $totalNomes)]} {$sobrenomes[mt_rand(0, $totalSobrenomes)]}",
                    'especialidadeId' => $especialidades[mt_rand(0, $totalEspecialidades)]
                ]);

                $medico = $this->medicoFactory->createEntity($medicoDados);
                
                $manager->persist($medico);

            } catch (Exception $e) {}
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EspecialidadesFixtures::class
        ];
    }
}
