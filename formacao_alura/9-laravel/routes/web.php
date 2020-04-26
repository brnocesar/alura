<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/series', 'SeriesController@index')->name('listar_series');
Route::get('/series/criar', 'SeriesController@create')->name('adicionar_serie');
Route::post('/series/criar', 'SeriesController@store')->name('registra_serie');
Route::delete('/series/{id}', 'SeriesController@destroy')->name('deleta_serie');
Route::post('/series/{id}/editar-nome', 'SeriesController@editarNome');

Route::get('/series/{serieId}/temporadas', 'TemporadasController@index')->name('listar_temporadas');
Route::get('temporadas/{temporada}/episodios', 'EpisodiosController@index')->name('listar_episodios');
Route::post('temporadas/{temporada}/episodios/assitir', 'EpisodiosController@assistir')->name('assistir_episodios');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/entrar', 'AutenticacaoController@index')->name('pagina_login');
Route::post('/entrar', 'AutenticacaoController@entrar')->name('realizar_login');
Route::get('/registrar', 'AutenticacaoController@create')->name('pagina_registro');
Route::post('/registrar', 'AutenticacaoController@store')->name('realizar_registro');
Route::get('/sair', 'AutenticacaoController@sair')->name('deslogar');
