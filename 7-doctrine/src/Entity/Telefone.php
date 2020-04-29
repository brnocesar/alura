<?php 

namespace Alura\Doctrine\Entity;

/**
 * @Entity
 * @Table(name="telefones")
 */
class Telefone
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", nullable=false, unique=true)
     */
    private $id;
    /**
     * @Column(type="string", nullable=false, length=19)
     */
    private $numero;
    /**
     * @ManyToOne(targetEntity="Aluno")
     */
    private $aluno;


    public function getId(): int
    {
        return $this->id;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;
        return $this;
    }

    public function getAluno(): Aluno
    {
        return $this->aluno;
    }

    public function setAluno(Aluno $aluno): self
    {
        $this->aluno = $aluno;
        return $this;
    }
}