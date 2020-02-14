<?php

require_once 'classes/config.php';

spl_autoload_register('carregarClasse');

function carregarClasse($nomeClasse)
{
    // $arquivo = 'classes/' . $nomeClasse . '.php';
    // if ( file_exists($arquivo) ) {
    if ( file_exists('classes/' . $nomeClasse . '.php') ) {
        require_once 'classes/' . $nomeClasse . '.php';
    }
}