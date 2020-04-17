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
