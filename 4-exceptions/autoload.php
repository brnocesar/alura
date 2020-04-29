<?php

function loadxx($namespace){

    $namespace = str_replace("\\", "/", $namespace);

    $caminhoAbsoluto = __DIR__ . "/" . $namespace . ".php";

    include_once $caminhoAbsoluto;
}

spl_autoload_register(__NAMESPACE__."\loadxx");

?>