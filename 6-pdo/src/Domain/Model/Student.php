<?php

namespace Alura\Pdo\Domain\Model;

use DateTimeInterface;
use DomainException;

class Student
{
    private ?int $id;
    private string $name;
    private DateTimeInterface $birthDate;

    public function __construct(?int $id, string $name, \DateTimeInterface $birthDate)
    {
        $this->id        = $id;
        $this->name      = $name;
        $this->birthDate = $birthDate;
    }

    public function defineId(int $id): void
    {
        if ( !is_null($this->id) ) {
            throw new DomainException('ID só pode ser definido uma vez');
        }

        $this->id = $id;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function changeName(int $name): void
    {
        $this->name = $name;
    }

    public function birthDate(): DateTimeInterface
    {
        return $this->birthDate;
    }

    public function age(): int
    {
        return $this->birthDate
            ->diff(new \DateTimeImmutable())
            ->y;
    }
}
