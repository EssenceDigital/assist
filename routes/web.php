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
Route::post('/projects/start', 'ProjectsController@store');
Route::post('/projects/update-field', 'ProjectsController@updateField');
Route::get('/projects/{id}', 'ProjectsController@single');
Route::get('/projects/{client?}/{province?}/{location?}/{invoice?}', 'ProjectsController@filter');
Route::post('/projects/add-comment', 'ProjectsController@addComment');
Route::delete('/projects/delete-comment/{id}', 'ProjectsController@deleteComment');
Route::post('/projects/add-crew', 'ProjectsController@addCrew');
Route::delete('/projects/{project_id}/delete-crew/{id}', 'ProjectsController@deleteCrew');

Route::post('/timelines/update-field', 'TimelinesController@updateField');

Route::get('/users', 'UsersController@all');
Route::get('/users/{user_id}/projects', 'UsersController@projects');
Route::get('/users/{user_id}/projects/{project_id}/timesheets', 'UsersController@projectTimesheets');

Route::post('/timesheets/add', 'TimesheetsController@store');
Route::post('/timesheets/add-hours', 'WorkJobsController@store');
Route::post('/timesheets/add-travel', 'TravelJobsController@store');

