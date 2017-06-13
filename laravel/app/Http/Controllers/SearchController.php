<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Competency;

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

    public function autoComplete(Request $request) {
        $query = $request->get('term','');
        $competencies=Competency::where('name','LIKE','%'.$query.'%')->get();

        $data=array();
        foreach ($competencies as $competence) {
            $data[]=array('value'=>$competence->name,'id'=>$competence->id);
        }
        if(count($data))
            return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }


}