<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 *  @var Laravel\Lumen\Routing\Router $router
*/
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/api/login', 'TokenController@generateToken');

$router->group(['prefix' => '/api', 'middleware' => 'autenticador'], function () use ($router) {

    $router->group(['prefix' => '/series'], function () use ($router) {
        $router->get('/', 'SeriesController@index');
        $router->post('/', 'SeriesController@store');
        $router->get('/{id}', 'SeriesController@show');
        $router->put('/{id}', 'SeriesController@update');
        $router->delete('/{id}', 'SeriesController@destroy');

        $router->get('/{serieId}/episodios', 'EpisodeController@perSerie');
    });

    $router->group(['prefix' => '/episodios'], function () use ($router) {
        $router->get('/', 'EpisodeController@index');
        $router->post('/', 'EpisodeController@store');
        $router->get('/{id}', 'EpisodeController@show');
        $router->put('/{id}', 'EpisodeController@update');
        $router->delete('/{id}', 'EpisodeController@destroy');
    });
});
