<?php

/*namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateLearningAidFormRequest;
use App\Http\Requests\EditLearningAidFormRequest;
use Illuminate\Support\Facades\Redirect;


use App\LearningAid;
*/

namespace App\Http\Controllers;
use App\Http\Requests\CreateLearningAidFormRequest;
use App\Http\Requests\EditLearningAidFormRequest;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\LearningAid;


class LearningAidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allLearningAids = LearningAid::paginate(10);
        return view('learningaids.index', ['learningaids' => $allLearningAids]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $learningaid = new \App\LearningAid;
        return view('learningaids.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLearningAidFormRequest $request)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        //$author_id = \Auth::user()->id;
        echo "ola mundo feliz - $name $description";
        $learningaid = new \App\LearningAid;
        $learningaid->name = $name;
        $learningaid->description = $description;
        //$learningaid->author_id = $author_id;
        $learningaid->save();

        $competenceIds = $request->get('competence_ids');
        $competenceProficiencyLevels = $request->get('competency_proficiency_levels');
        for ($i=0; $i<sizeOf($competenceIds); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceProficiencyLevel = $competenceProficiencyLevels[$i];
            $results = $learningaid->competencies()->where('competency_id', '=', $competenceId)->get();
            if ($results->isEmpty()) {
                //add competency
                $learningaid->competencies()->attach([$competenceId => ['competence_proficiency_level_id'=>$competenceProficiencyLevel]]);
            } else {
                //update competency level
                $learningaid->competencies()->updateExistingPivot($competenceId, ['competence_proficiency_level_id'=>$competenceProficiencyLevel]);
            }
        }
        return Redirect::route('learningaids.show',$learningaid->id)->withMessage('O treinamento foi cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $message = null)
    {
        $learningaid = LearningAid::findOrFail($id);
        return view('learningaids.show', ['learningaid' => $learningaid, 'message' => $message]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $learningaid = LearningAid::findOrFail($id);
        return view('learningaids.edit', ['learningaid' => $learningaid]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditLearningAidFormRequest $request, $id)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        $learningaid = LearningAid::findOrFail($id);

        $learningaid->name = $name;
        $learningaid->description = $description;
        $learningaid->save();
        $competenceIds = $request->get('competence_ids');
        $competenceProficiencyLevels = $request->get('competency_proficiency_levels');
        for ($i=0; $i<sizeOf($competenceIds); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceProficiencyLevel = $competenceProficiencyLevels[$i];
            $results = $learningaid->competencies()->where('competency_id', '=', $competenceId)->get();
            if ($results->isEmpty()) {
                //add competency
                $learningaid->competencies()->attach([$competenceId => ['competence_proficiency_level_id'=>$competenceProficiencyLevel]]);
            } else {
                //update competency level
                $learningaid->competencies()->updateExistingPivot($competenceId, ['competence_proficiency_level_id'=>$competenceProficiencyLevel]);
            }
        }
        return Redirect::route('learningaids.show',$learningaid->id)->withMessage('O treinamento foi atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $learningaid = LearningAid::findOrFail($id);
        $learningaid->competencies()->detach();
        $learningaid->delete();

        return Redirect::route('learningaids.index')->withMessage('O treinamento foi excluído com sucesso!');

    }

    public function deleteCompetencyFromLearningAid($learningAidId, $competencyId) {
        $learningaid = LearningAid::findOrFail($learningAidId);
        $learningaid->competencies()->detach($competencyId);
        return Redirect::route('learningaids.edit', $learningAidId);
    }
}






/*class LearningAidController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        //
        $allLearningaids = LearningAid::paginate(10);
        return view('learningaids.index', ['learningaids' => $allLearningaids, 'message' => '']);
    }


    public function create()
    {
        //
        if (\Auth::user()->isManager()) {
            return view('learningaids.create');
        } else {
            return redirect('/home');
        }
    }


    public function store(CreateLearningAidFormRequest $request)
    {
        $names = $request->get('name');
        $description = $request->get('description');

        for ($i=0; $i<sizeOf($names); $i++) {
            $learningaid = new \App\LearningAid();
            $learningaid->name = $names[$i];
            $learningaid->description = $description[$i];
            $learningaid->save();
        }
        $allLearningAids = LearningAid::paginate(10);
        return view('learningaids.index', ['learningaids' => $allLearningAids, 'message' => 'Os treinamentos foram cadastrados com sucesso!']);
    }


    public function show($id)
    {
        //
        $learningaid = LearningAid::findOrFail($id);
        return view('learningaids.show', ['learningaid' => $learningaid]);
    }


    public function edit($id)
    {
        $learningaid = LearningAid::findOrFail($id);
        return view('learningaids.edit', ['learningaid' => $learningaid]);
    }


    public function update(EditLearningAidFormRequest $request, $id)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        LearningAid::findOrFail($id)->update(['name' => $name, 'description' => $description]);
        $learningaid = LearningAid::findOrFail($id);
        return view('learningaids.show', ['id' => $id, 'learningaid' => $learningaid, 'message' => 'O treinamento foi atualizado com sucesso!']);
    }

    public function destroy($id)
    {
        //
        $learningaid = LearningAid::findOrFail($id);

        //DB::table("user_endorsements")->where('competence_id', '=',$competence->id)->delete();
        $learningaid->unskilledUsers()->detach();
        //$learningaid->tasksThatRequireIt()->detach();
        //$learningaid->teamsThatHaveIt()->detach();
        $learningaid->delete();

        return Redirect::route('learningaids.index')->withMessage('O treinamento foi excluído com sucesso!');

    }
}
*/