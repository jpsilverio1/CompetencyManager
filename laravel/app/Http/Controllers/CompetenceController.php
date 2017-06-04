<?php


namespace App\Http\Controllers;
use App\Http\Requests\CreateCompetenceFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

use App\Category;

class CompetenceController extends Controller
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
		$competence = new \App\Competency; 
        return view('competences.create', ['competency' => $competence]);
	}
	 
	 
	public function create()
	{
		$competence = new \App\Competency; 
        return view('competences.create', ['competency' => $competence]);
	}
	
	public function show($id) 
	{
		
		$competence = DB::table('competencies')->where('id', $id)->first();
		return view('competences.create', ['competency' => $competence]);
	}
	
	public function store(CreateCompetenceFormRequest $request)
	{
			
		/* $validator = Validator::make($request->all(), [
			'name.*' => 'required|unique:competencies,name',
			'description.*' => 'required',
		]);
		
		if ($validator->fails()) {
            return redirect('competences/create')
                        ->withErrors($validator)
                        ->withInput();
        } */
		
		$names = $request->get('name');
		$description = $request->get('description');

		for ($i=0; $i<sizeOf($names); $i++) {
			$competence = new \App\Competency; 
			$competence->name = $names[$i];
			$competence->description = $description[$i];
			$competence->save();
			
		} 
		return \Redirect::route('competences.show', 
			array($competence->id))
			->with('message', 'A competência foi cadastrada.');
	}	
}
