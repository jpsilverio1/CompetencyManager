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
use  App\CompetenceProficiencyLevel;

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* User has to be authenticated to acess all of the routes listed below*/
Route::group(['middleware' => 'auth'], function() {
	
	Route::get('tasks/show_form/{taskId}', 'TaskController@showForm')->name('show-task-form');
	
    Route::resource('tasks', 'TaskController');
    Route::resource('competences', 'CompetenceController');


    Route::resource('users','UserController');
	Route::resource('jobroles','JobRoleController');
	Route::resource('learningaids','LearningAidController');
    Route::post('learningaids-index', ['as' => 'learningaids-index', 'uses' => 'LearningAidController@sort']);
	Route::resource('dashboards', 'DashboardController');
	
	/* dashboard routes */
	Route::get('/dashboards/tasks/finished','DashboardController@finishedTasksReport')->name('finished-tasks-report');;
	Route::get('/dashboards/tasks/not-finished','DashboardController@notFinishedTasksReport')->name('not-finished-tasks-report');
	Route::get('/dashboards/tasks/not-initialized','DashboardController@notInitializedTasksReport')->name('not-initialized-tasks-report');
	Route::get('/dashboards/tasks/unfeasible','DashboardController@unfeasibleTasksReport')->name('unfeasible-tasks-report');
	Route::get('/dashboards/competences/covered','DashboardController@coveredCompetencesReport')->name('covered-competences-report');
	Route::get('/dashboards/competences/needed','DashboardController@neededCompetencesReport')->name('needed-competences-report');
	Route::get('/dashboards/competences/most-learned','DashboardController@mostLearnedCompetencesReport')->name('most-learned-competences-report');
	Route::get('/dashboards/collaboration/most-collaborative-users','DashboardController@mostCollaborativeUsersReport')->name('most-collaborative-users-report');
	Route::get('/dashboards/collaboration/most-collaborative-groups','DashboardController@mostCollaborativeGroupsReport')->name('most-collaborative-groups-report');
	Route::get('/dashboards/users/highest-competence-number','DashboardController@usersWithHighestCompetenceNumberReport')->name('users-with-highest-competence-number-report');
	Route::get('/dashboards/users/most-tasks-performed','DashboardController@usersWithMoreTasksPerformedReport')->name('users-with-more-tasks-performed-report');

    /* pivot tables deletion routes */
    Route::delete('/user-team/{teamId}', array('as'=>'user-team','uses'=>'UserController@deleteUserFromTeam'));
    Route::delete('/user-competence/{competenceId}', 'UserController@deleteCompetenceFromUser');
    Route::delete('/task-competency/{taskId}/{competencyId}', 'TaskController@deleteCompetencyFromTask');
    Route::delete('/jobrole-competency/{jobroleId}/{competencyId}', 'JobRoleController@deleteCompetencyFromJobRole');
    Route::delete('/team-member/{teamId}/{memberId}', 'TeamController@deleteMemberFromTeam');
    Route::delete('/learningaid-competency/{learningAidId}/{competencyId}','LearningAidController@deleteCompetencyFromLearningAid');
    Route::delete('delete-competence-parent/{competenceId}', array('as'=>'delete-competence-parent','uses'=>'CompetenceController@deleteParentFromCompetence'));
    Route::delete('delete-competence-child/{competenceId}/{competenceChildId}', array('as'=>'delete-competence-child','uses'=>'CompetenceController@deleteCompetenceChild'));


	Route::get('/learningaid-finish/{learningAidId}', 'LearningAidController@finishLearningAid');

    Route::post('/user-competences', 'UserController@addCompetences');
	Route::post('/user-competence', 'UserController@addCompetenceToUser');
    Route::post('/user-endorsements', 'EndorsementController@addEndorsement');

    Route::get('task-initialize/{taskId}',array('as'=>'task-initialize','uses'=>'TaskController@initializeTask'));
    Route::get('task-finish/{taskId}',array('as'=>'task-finish','uses'=>'TaskController@finishTask'));
	
	Route::post('task-answer-form', 'AnswerController@addAnswer');

    Route::post('competence-parent/{competenceId}', array('as'=>'competence-parent','uses'=>'CompetenceController@addOrUpdateCompetenceParent'));
    Route::post('competence-child/{competenceId}', array('as'=>'competence-child','uses'=>'CompetenceController@addChildCompetence'));



    /* autocomplete-related routes */
    Route::get('search-competence',array('as'=>'search-competence','uses'=>'SearchController@autocompleteCompetence'));
    Route::get('search-parent-competence',array('as'=>'search-parent-competence','uses'=>'SearchController@autocompleteParentCompetence'));
    Route::get('search-child-competence',array('as'=>'search-child-competence','uses'=>'SearchController@autocompleteChildCompetence'));
    Route::get('search-user',array('as'=>'search-user','uses'=>'SearchController@autocompleteUser'));
	
	Route::get('search-jobrole',array('as'=>'search-jobrole','uses'=>'SearchController@autoCompleteJobRoles'));

    Route::get('search-team-candidate',array('as'=>'search-team-candidate','uses'=>'SearchController@autocompleteUser'));
    Route::post('search-competence-db',array('as'=>'search-competence-db','uses'=>'SearchController@searchCompetence'));
    Route::get('search-task',array('as'=>'search-task','uses'=>'SearchController@autocompleteTask'));
    Route::get('search-learningAid',array('as'=>'search-learningAid','uses'=>'SearchController@autocompleteLearningAid'));
    
    Route::post('tasks.store-team',array('as'=>'tasks.store-team','uses'=>'TaskController@storeTaskTeam'));
    

    Route::get('testao/{taskId}', function ($taskId) {
        return view('tasks.teste', ['task' => App\Task::findOrFail($taskId)]);

    });
});

Route::get('verify/{email}/{verifyToken}','Auth\RegisterController@sendEmailDone')->name('sendEmailDone');



