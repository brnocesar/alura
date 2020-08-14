<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Medico
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;
    /**
     * @ORM\Column(type="string")
     */
    public $crm;
    /**
     * @ORM\Column(type="string")
     */
    public $nome;

    /**
     * @ORM\ManyToOne(targetEntity=Medico::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidade;

    public function getEspecialidade(): ?self
    {
        return $this->especialidade;
    }

    public function setEspecialidade(?self $especialidade): self
    {
        $this->especialidade = $especialidade;

        return $this;
    }
    
}