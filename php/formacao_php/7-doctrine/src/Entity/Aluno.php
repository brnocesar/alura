<?php 

namespace Alura\Doctrine\Entity;

/**
 * @Entity
 */
class Aluno
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", nullable=false, unique=true)
     */
    private $id;
    /**
     * @Column(type="string", nullable=false)
     */
    private $nome;

    /** 
     * @return mixed
    */
    public function getId(): int
    {
        return $this->id;
    }

    /** 
     * @return mixed
    */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     * @return Aluno
     */ 
    public function setNome($nome): self
    {
        $this->nome = $nome;
        return $this;
    }
}