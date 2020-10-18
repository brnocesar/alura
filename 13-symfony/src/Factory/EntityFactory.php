<?php

namespace App\Factory;

interface EntityFactory
{
    public function createEntity(string $json);
}

