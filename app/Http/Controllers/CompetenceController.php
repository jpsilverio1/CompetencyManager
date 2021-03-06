<?php


namespace App\Http\Controllers;
use App\Http\Requests\CreateCompetenceFormRequest;
use App\Http\Requests\EditCompetenceFormRequest;
use Illuminate\Support\Facades\Redirect;

use DB;

use App\Category;
use Illuminate\Http\Request;
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

    public function index(Request $request)
    {
        $sortType = $request->get('sort');
        if ($sortType == "name") {
            $allCompetences = Competency::orderBy('name')->paginate(10);
            return view('competences.index', ['competences' => $allCompetences, 'message' => '','sortType' => 'date']);
        } else {
            $allCompetences = Competency::paginate(10);
            return view('competences.index', ['competences' => $allCompetences, 'message' => '', 'sortType' => 'name']);
        }
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
        $parentIds = $request->get('parent_ui_id');
        $competenceUIIds = $request->get('competence_ui_id');
        $isNewCompetence = $request->get('isNewCompetence');
        $dbIds = $request->get('competence_db_id');
        $nameIdx = 0;
        $dbIdIdx =0;
        $parentIdDbIdMap =[];
        for ($i=0; $i<sizeOf($isNewCompetence); $i++) {
            if ($isNewCompetence[$i] === "true") {
                //add to database
                $uiId = $competenceUIIds[$i];
                $competence = new \App\Competency;
                $competence->name = $names[$nameIdx];
                $competence->description = $description[$nameIdx];
                $parentUiId = $parentIds[$i];
                $nameIdx+=1;
                if ($parentUiId > 0) {
                    $parentDBId = $parentIdDbIdMap[$parentUiId];
                    $competence->parent_id = $parentDBId;
                }
                $competence->save();
				
				\DB::table('basic_statistics')->where('name', 'competences_count')->increment('value');

                $dbId = $competence->id;
                //add to map
                $parentIdDbIdMap[$uiId] = $dbId;
            } else {
                //add to map of ids
                $dbId = $dbIds[$dbIdIdx];
                $uiId = $competenceUIIds[$i];
                $parentIdDbIdMap[$uiId] = $dbId;
                $dbIdIdx+=1;
                $competence = Competency::findOrFail($dbId);
                $parentUiId = $parentIds[$i];
                echo "<br>";
                if ($parentUiId > 0) {
                    $parentDBId = $parentIdDbIdMap[$parentUiId];
                    $competence->parent_id = $parentDBId;
                    $competence->save();
                } else {
                    $competence->makeRoot()->save();
                }
            }
        }

		if (sizeOf($names) > 1) { // if we have more than one competence name, we redirect to competences index page
			$allCompetences = Competency::paginate(10);
			return view('competences.index', ['competences' => $allCompetences, 'message' => 'As competências foram cadastradas com sucesso!', "sortType" => 'date']);
		} else { // if you have just one competence name, then it must be the name of the only created competence, so we'll redirect to it
			$competence = Competency::where('name', '=', $names[0])->first();
			return view('competences.show', ['id' => $competence, 'competence' => $competence, 'message' => 'A competência foi cadastrada com sucesso!']);
		}
    }
	public function deleteParentFromCompetence($competenceId) {
        $competence = Competency::findOrFail($competenceId);
        $competence->makeRoot()->save();
        return Redirect::route('competences.edit', $competenceId)->withMessage('Relaçao de parentesco removida com sucesso!');
    }

    public function deleteCompetenceChild($competenceId, $competenceChildId) {
        $competence = Competency::findOrFail($competenceId);
        $competenceChild = Competency::findOrFail($competenceChildId);
        $competenceChild->makeRoot()->save();
        return Redirect::route('competences.edit', $competenceId)->withMessage('Relaçao de parentesco removida com sucesso!');
    }

    public function addOrUpdateCompetenceParent($competenceId, Request $request) {
        $newParentId = $request->get('parent_id');
        $competence = Competency::findOrFail($competenceId);
        $competence->parent_id = $newParentId;
        $competence->save();
        return Redirect::route('competences.edit', $competenceId)->withMessage('Competência pai atualizada com sucesso!');

    }

    public function addChildCompetence($competenceId, Request $request) {
        $newChildId = $request->get('child_id');
        $competenceChild = Competency::findOrFail($newChildId);

        $competenceChild->parent_id = $competenceId;
        $competenceChild->save();

        return Redirect::route('competences.edit', $competenceId)->withMessage('Subcompetência adicionada com sucesso!');;

    }
	public function update(EditCompetenceFormRequest $request, $id)
	{
        $name = $request->get('name');
        $description = $request->get('description');
        Competency::findOrFail($id)->update(['name' => $name, 'description' => $description]);
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
		DB::table("user_endorsements")->where('competence_id', '=',$competence->id)->delete();
		$competence->skilledUsers()->detach();
		$competence->tasksThatRequireIt()->detach();
        $competence->learningAidsThatRequireIt()->detach();
        $children = $competence->children;
        $parentNode = $competence->parent;
        echo " parent = $parentNode";
        foreach($children as $result) {
            $result->parent()->associate($parentNode)->save();
        }
		$competence->delete();
		
		\DB::table('basic_statistics')->where('name', 'competences_count')->decrement('value');
		
        if (Competency::isBroken()) {
            Competency::fixTree();
            echo "consertar";
        }
        return Redirect::route('competences.index')->withMessage('A competência foi excluída com sucesso!');

	}

}
