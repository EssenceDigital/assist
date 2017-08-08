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

Auth::routes();

Route::get('/', function () {
    return view('index');
});

Route::get('/app', function () {
    return view('app');
});

Route::get('/projects', 'ProjectsController@all');
Route::get('/projects/clients', 'ProjectsController@clients');
Route::get('/projects/{client?}/{province?}/{invoice?}', 'ProjectsController@filter');

