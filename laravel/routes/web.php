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
Route::post('/user-competences', 'UserController@addCompetences');
Route::get("/jessica", function(){
    return View::make("jessica");
});
Route::delete('/task/{id}', function ($id) {
    Task::findOrFail($id)->delete();

    return redirect('/');
});
Route::delete('/user-team/{teamId}', 'UserController@deleteUserFromTeam');
Route::delete('/user-competency/{competencyId}', 'UserController@deleteCompetencyFromUser');
Route::resource('competences', 'CompetenceController');
Route::resource('users', 'UserController');
Route::resource('teams', 'TeamController');
Route::resource('tasks', 'TaskController');

Route::get('search',array('as'=>'search','uses'=>'SearchController@autocomplete'));
Route::get('autocomplete',array('as'=>'autocomplete','uses'=>'SearchController@index'));
//Route::get('autocomplete',array('as'=>'autocomplete','uses'=>'SearchController@index'));
//Route::get('search',array('as'=>'searchajax','uses'=>'SearchController@autoComplete'));
