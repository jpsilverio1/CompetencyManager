<?php


namespace App\Http\Controllers;
use App\Http\Requests\CreateCompetenceFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

use App\Category;
use App\Competency;

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
        $allCompetences = Competency::paginate(10);
        return view('competences.index', ['competences' => $allCompetences]);
	}
	 
	 
	public function create()
	{
		$competence = new \App\Competency;
        if (\Auth::user()->isManager()) {
            return view('competences.create', ['competency' => $competence]);
        } else {
            return redirect('/home');
        }

	}
	
	public function show($id) 
	{
        $competence = Competency::findOrFail($id);
		return view('competences.show', ['competence' => $competence]);
	}
	
	public function store(CreateCompetenceFormRequest $request)
	{
		$names = $request->get('name');
		$description = $request->get('description');
		
		/* tentativa de enviar dados de volta pra página do formulário quando há erro (não funciona) */
		$competence = new \App\Competency; 
		$competence->name = $names[0];
		$competence->description = $description[0];
		
		$validator = Validator::make($request->all(), [
			'name.*' => 'required|unique:competencies,name',
			'description.*' => 'required',
		]);
		
		if ($validator->fails()) {
            return redirect('competences/create')
                        ->withErrors($validator)
						->with(['competency' => $competence]);
        }
		/* fim da tentativa */

		for ($i=0; $i<sizeOf($names); $i++) {
			$competence = new \App\Competency; 
			$competence->name = $names[$i];
			$competence->description = $description[$i];
			$competence->save();
			
		} 
		//return redirect()->route('competences.show', ['id' => $competence->id, 'message' => 'oi' ]);
		return \Redirect::route('competences.create', 
			array($competence->id))
			->with('message', 'A competência foi cadastrada.');
	}	
}
