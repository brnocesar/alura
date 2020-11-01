<?php

use App\Controller\EspecialidadeController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('especialidades_index', '/especialidades')
        ->controller([EspecialidadeController::class, 'index'])
        ->methods(['GET']);
    $routes->add('especialidades_store', '/especialidades')
        ->controller([EspecialidadeController::class, 'store'])
        ->methods(['POST']);
    $routes->add('especialidades_show', '/especialidades/{id}')
        ->controller([EspecialidadeController::class, 'show'])
        ->methods(['GET']);
    $routes->add('especialidades_show_medicos', '/especialidades/{especialidadeId}/medicos')
        ->controller([App\Controller\MedicoController::class, 'indexByEspecialidede'])
        ->methods(['GET']);
    $routes->add('especialidades_update', '/especialidades/{id}')
        ->controller([EspecialidadeController::class, 'update'])
        ->methods(['PUT']);
    $routes->add('especialidades_destroy', '/especialidades/{id}')
        ->controller([EspecialidadeController::class, 'destroy'])
        ->methods(['DELETE']);

    $routes->add('medicos_index', '/medicos')
        ->controller([App\Controller\MedicoController::class, 'index'])
        ->methods(['GET']);
    $routes->add('medicos_store', '/medicos')
        ->controller([App\Controller\MedicoController::class, 'store'])
        ->methods(['POST']);
    $routes->add('medicos_show', '/medicos/{id}')
        ->controller([App\Controller\MedicoController::class, 'show'])
        ->methods(['GET']);
    $routes->add('medicos_update', '/medicos/{id}')
        ->controller([App\Controller\MedicoController::class, 'update'])
        ->methods(['PUT']);
    $routes->add('medicos_destroy', '/medicos/{id}')
        ->controller([App\Controller\MedicoController::class, 'destroy'])
        ->methods(['DELETE']);
};