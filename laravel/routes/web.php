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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::delete('/task/{id}', function ($id) {
    Task::findOrFail($id)->delete();

    return redirect('/');
});
Route::delete('/user-team/{teamId}', 'UserController@deleteUserFromTeam');
Route::resource('competences', 'CompetenceController');
Route::resource('users', 'UserController');
Route::resource('teams', 'TeamController');
Route::resource('tasks', 'TaskController');