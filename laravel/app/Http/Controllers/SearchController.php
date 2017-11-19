<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Competency;

use App\User;

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

        $users=User::where('name','LIKE','%'.$query.'%')->limit(20)->get();

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
        $competencies=Competency::where('name','LIKE','%'.$query.'%')->whereNotIn('id', $blackListedIds)->limit(20)->get();

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


}