<?php

namespace App\Http\Controllers;

use App\LearningAid;
use Illuminate\Http\Request;

use App\Competency;

use App\User;

use App\Task;

use App\JobRole;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('/home');
    }
    public function autoCompleteUser(Request $request) {
        $query = $request->get('term','');
        $blackListedIds = $request->get('blacklistedIds',[]);
        $users=User::where('name','LIKE','%'.$query.'%')->whereNotIn('id', $blackListedIds)->limit(20)->get();

        $data=array();
        foreach ($users as $user) {
            $data[]=array('value'=>$user->name,'id'=>$user->id);
        }
        if(count($data))
            return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }
    public function autoCompleteCompetence(Request $request) {
        $query = $request->get('term','');
        $blackListedIds = $request->get('blacklistedIds',[]);
        $competencies=Competency::where('name','LIKE',$query.'%')->whereNotIn('id', $blackListedIds)->limit(20)->get();

        $data=array();
        foreach ($competencies as $competence) {
            $data[]=array('value'=>$competence->name,'id'=>$competence->id, 'description' => $competence->description);
        }
        if(count($data))
            return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }
    public function searchCompetence(Request $request) {
        $competenceId = $request->get('term','');
        $competence = Competency::findOrFail($competenceId);
        return ['name'=>$competence->name,'description'=>$competence->description];
    }

    public function autoCompleteTask(Request $request) {
        $query = $request->get('term','');
        $tasks=Task::where('title','LIKE','%'.$query.'%')->limit(20)->get();

        $data=array();
        foreach ($tasks as $task) {
            $data[]=array('value'=>$task->title,'id'=>$task->id);
        }
        if(count($data))
            return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }

    public function autoCompleteLearningAid(Request $request) {
        $query = $request->get('term','');
        $learningAids=LearningAid::where('name','LIKE','%'.$query.'%')->limit(20)->get();

        $data=array();
        foreach ($learningAids as $learningAid) {
            $data[]=array('value'=>$learningAid->name,'id'=>$learningAid->id);
        }
        if(count($data))
            return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }
	
	public function autoCompleteJobRoles(Request $request) {
        $query = $request->get('term','');
        $jobroles=JobRole::where('name','LIKE','%'.$query.'%')->limit(20)->get();

        $data=array();
        foreach ($jobroles as $jobrole) {
            $data[]=array('value'=>$jobrole->name,'id'=>$jobrole->id);
        }
        if(count($data))
            return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }
}