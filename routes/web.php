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

Route::get('/test-dates', 'DashboardController@test');

Route::get('/app', 'DashboardController@index');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

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
Route::post('/users/add', 'UsersController@store');
Route::get('/users/{id}', 'UsersController@single');
Route::post('/users/change-password', 'UsersController@changePassword');
Route::post('/users/change-personal-password', 'DashboardController@changePersonalPassword');
Route::post('/users/update-field', 'UsersController@updateField');
Route::get('/users/{user_id}/projects', 'UsersController@projects');
Route::get('/users/{user_id}/projects/{project_id}/timesheets', 'UsersController@projectTimesheets');

Route::get('/timesheets', 'TimesheetsController@all');
Route::post('/timesheets/add', 'TimesheetsController@store');
Route::post('/timesheets/update', 'TimesheetsController@update');
Route::post('/timesheets/add-hours', 'WorkJobsController@store');
Route::post('/timesheets/add-travel', 'TravelJobsController@store');
Route::post('/timesheets/add-equipment', 'EquipmentRentalsController@store');
Route::post('/timesheets/add-other', 'OtherCostsController@store');
Route::post('/timesheets/update-hours', 'WorkJobsController@update');
Route::post('/timesheets/update-travel', 'TravelJobsController@update');
Route::post('/timesheets/update-equipment', 'EquipmentRentalsController@update');
Route::post('/timesheets/update-other', 'OtherCostsController@update');
Route::delete('/timesheets/delete-hours/{id}', 'WorkJobsController@delete');
Route::delete('/timesheets/delete-travel/{id}', 'TravelJobsController@delete');
Route::delete('/timesheets/delete-equipment/{id}', 'EquipmentRentalsController@delete');
Route::delete('/timesheets/delete-other/{id}', 'OtherCostsController@delete');
Route::get('/timesheets/{from_date?}/{to_date?}/{project_id?}/{user_id?}', 'TimesheetsController@filter');