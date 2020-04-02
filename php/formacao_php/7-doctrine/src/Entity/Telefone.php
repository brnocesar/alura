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
     * @return mixed
    */
    public function getId(): int
    {
        return $this->id;
    }

    /** 
     * @return mixed
    */
    public function getNumero(): string
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     * @return Telefone
     */ 
    public function setNumero(string $numero): self
    {
        $this->numero = $numero;
        return $this;
    }
}