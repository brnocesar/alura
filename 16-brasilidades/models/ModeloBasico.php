<?php

abstract class ModeloBasico
{
    public function __construct() {
        //
    }

    protected function removeFormatacao($info): string
    {
        return str_replace([".", "-", "/", "(", ")", " "], "", $info);
    }
}