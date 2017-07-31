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

/* User has to be authenticated to acess all of the routes listed below*/
Route::group(['middleware' => 'auth'], function() {
    Route::resource('tasks', 'TaskController');
    Route::resource('teams', 'TeamController');
    Route::resource('competences', 'CompetenceController');
    Route::resource('users','UserController');

    /* pivot tables deletion routes */
    Route::delete('/user-team/{teamId}', 'UserController@deleteUserFromTeam');
    Route::delete('/user-competency/{competencyId}', 'UserController@deleteCompetencyFromUser');
    Route::delete('/task-competence/{taskId}/{competencyId}', 'TaskController@deleteCompetenceFromTask');
    Route::delete('/team-member/{teamId}/{memberId}', 'TeamController@deleteMemberFromTeam');

    Route::post('/user-competences', 'UserController@addCompetences');
    Route::post('/user-endorsements', 'EndorsementController@addEndorsement');

    /* autocomplete-related routes */
    Route::get('search-competence',array('as'=>'search-competence','uses'=>'SearchController@autocompleteCompetence'));
    Route::get('search-user',array('as'=>'search-user','uses'=>'SearchController@autocompleteUser'));
});





