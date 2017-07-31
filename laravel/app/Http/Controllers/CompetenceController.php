<?php


namespace App\Http\Controllers;
use App\Http\Requests\CreateCompetenceFormRequest;

use Illuminate\Support\Facades\Redirect;

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
        return view('competences.index', ['competences' => $allCompetences, 'message' => '']);
	}
	 
	 
	public function create()
	{
        if (\Auth::user()->isManager()) {
            return view('competences.create');
        } else {
            return redirect('/home');
        }
	}
	
	public function show($id) 
	{
        $competence = Competency::findOrFail($id);
		return view('competences.show', ['competence' => $competence]);
	}
	
	public function edit($id) 
	{
        $competence = Competency::findOrFail($id);
		return view('competences.edit', ['competence' => $competence]);
	}
	
	public function store(CreateCompetenceFormRequest $request)
	{
        $names = $request->get('name');
        $description = $request->get('description');

        for ($i=0; $i<sizeOf($names); $i++) {
            $competence = new \App\Competency;
            $competence->name = $names[$i];
            $competence->description = $description[$i];
            $competence->save();
        }
        $allCompetences = Competency::paginate(10);
        return view('competences.index', ['competences' => $allCompetences, 'message' => 'As competências foram cadastradas com sucesso!']);
	}	
	
	public function update(CreateCompetenceFormRequest $request, $id)
	{
        $names = $request->get('name');
        $description = $request->get('description');
		
        for ($i=0; $i<sizeOf($names); $i++) {
			Competency::findOrFail($id)->update(['name' => $names[$i], 'description' => $description[$i]]);
        }
		$competence = Competency::findOrFail($id);
        return view('competences.show', ['id' => $id, 'competence' => $competence, 'message' => 'A competência foi atualizada com sucesso!']);
	}

	
	public function destroy($id)
	{
		$competence = Competency::findOrFail($id);
		/* Deixando aqui para tentar fazer funcionar futuramente
		foreach ($competence->skilledUsers() as $user) {
			$user->endorsements()->detach();
		} */
		DB::table("user_endorsements")->where('competency_id', '=',$competence->id)->delete();
		$competence->skilledUsers()->detach();
		$competence->tasksThatRequireIt()->detach();
		$competence->teamsThatHaveIt()->detach(); 
		$competence->delete();

        return Redirect::route('competences.index')->withMessage('A competência foi excluída com sucesso!');

	}
	
}
