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
	
	/*
	Route::get('/dashboards/tasks','DashboardController@taskReports');
	Route::get('/dashboards/competences','DashboardController@competencesReports');
	Route::get('/dashboards/users','DashboardController@usersReports');
	Route::get('/dashboards/collaboration','DashboardController@collaborationReports');
	Route::get('/dashboards/other','DashboardController@otherReports'); */
	
    Route::resource('tasks', 'TaskController');
    //Route::resource('teams', 'TeamController');
    Route::resource('competences', 'CompetenceController');
    Route::resource('users','UserController');
	Route::resource('jobroles','JobRoleController');
	Route::resource('learningaids','LearningAidController');
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
	//Route::get('/dashboards/collaboration/unanswered-collaboration-form','DashboardController@usersWhoDidntAnswerCollaborationFormReport')->name('users-who-didnt-answer-collaboration-form-report');
	Route::get('/dashboards/users/highest-competence-number','DashboardController@usersWithHighestCompetenceNumberReport')->name('users-with-highest-competence-number-report');
	Route::get('/dashboards/users/most-tasks-performed','DashboardController@usersWithMoreTasksPerformedReport')->name('users-with-more-tasks-performed-report');

    /* pivot tables deletion routes */
    Route::delete('/user-team/{teamId}', array('as'=>'user-team','uses'=>'UserController@deleteUserFromTeam'));
    Route::delete('/user-competence/{competenceId}', 'UserController@deleteCompetenceFromUser');
    Route::delete('/task-competency/{taskId}/{competencyId}', 'TaskController@deleteCompetencyFromTask');
    Route::delete('/jobrole-competency/{jobroleId}/{competencyId}', 'JobRoleController@deleteCompetencyFromJobRole');
    Route::delete('/team-member/{teamId}/{memberId}', 'TeamController@deleteMemberFromTeam');
    Route::delete('/learningaid-competency/{learningAidId}/{competencyId}','LearningAidController@deleteCompetencyFromLearningAid');
	
	Route::get('/learningaid-finish/{learningAidId}', 'LearningAidController@finishLearningAid');

    Route::post('/user-competences', 'UserController@addCompetences');
    Route::post('/user-endorsements', 'EndorsementController@addEndorsement');

    Route::get('task-initialize/{taskId}',array('as'=>'task-initialize','uses'=>'TaskController@initializeTask'));
    Route::get('task-finish/{taskId}',array('as'=>'task-finish','uses'=>'TaskController@finishTask'));
	
	Route::post('task-answer-form', 'AnswerController@addAnswer');
	

    Route::get('competence-proficiency-level',function(){
        $competenceProficiencyLevels = CompetenceProficiencyLevel::all();
        $lista = [];
        foreach ($competenceProficiencyLevels as $level) {
            $lista[$level->id] = $level->name;
        }
        return Response::json($lista);
    })->name('competence-proficiency-level');

    /* autocomplete-related routes */
    Route::get('search-competence',array('as'=>'search-competence','uses'=>'SearchController@autocompleteCompetence'));
    Route::get('search-user',array('as'=>'search-user','uses'=>'SearchController@autocompleteUser'));

    Route::get('search-team-candidate',array('as'=>'search-team-candidate','uses'=>'SearchController@autocompleteUser'));
    Route::post('search-competence-db',array('as'=>'search-competence-db','uses'=>'SearchController@searchCompetence'));
    Route::get('search-task',array('as'=>'search-task','uses'=>'SearchController@autocompleteTask'));
    Route::get('search-learningAid',array('as'=>'search-learningAid','uses'=>'SearchController@autocompleteLearningAid'));
    
    Route::post('tasks.store-team',array('as'=>'tasks.store-team','uses'=>'TaskCOntroller@storeTaskTeam'));
    

    Route::get('testao/{taskId}', function ($taskId) {
        return view('tasks.teste', ['task' => App\Task::findOrFail($taskId)]);

    });
});





