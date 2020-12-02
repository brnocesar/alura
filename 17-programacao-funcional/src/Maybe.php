<?php

namespace Programacao\Funcional;

class Maybe
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public static function of($value)
    {
        return new self($value);
    }

    public function isNothing(): bool
    {
        return is_null($this->value);
    }

    public function getOrElse($default)
    {
        return $this->isNothing() ? $default : $this->value;
    }

    public function map(callable $fn)
    {
        if ($this->isNothing()) {
            return Maybe::of($this->value);
        }
        $value = $fn($this->value);

        return Maybe::of($value);
    }
}
