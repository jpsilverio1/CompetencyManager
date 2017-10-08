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
        return view('learningaids.index', ['learningAids' => $allLearningAids]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $learningAid = new \App\LearningAid;
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
        $learningAid = new \App\LearningAid;
        $learningAid->name = $name;
        $learningAid->description = $description;
        //$learningAid->author_id = $author_id;
        $learningAid->save();

        $competenceIds = $request->get('competence_ids');
        $competenceProficiencyLevels = $request->get('competency_proficiency_levels');
        for ($i=0; $i<sizeOf($competenceIds); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceProficiencyLevel = $competenceProficiencyLevels[$i];
            $results = $learningAid->competencies()->where('competency_id', '=', $competenceId)->get();
            if ($results->isEmpty()) {
                //add competency
                $learningAid->competencies()->attach([$competenceId => ['competence_proficiency_level_id'=>$competenceProficiencyLevel]]);
            } else {
                //update competency level
                $learningAid->competencies()->updateExistingPivot($competenceId, ['competence_proficiency_level_id'=>$competenceProficiencyLevel]);
            }
        }
        return Redirect::route('learningaids.show',$learningAid->id)->withMessage('O treinamento foi cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $message = null)
    {
        $learningAid = LearningAid::findOrFail($id);
        return view('learningaids.show', ['learningAid' => $learningAid, 'message' => $message]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $learningAid = LearningAid::findOrFail($id);
        return view('learningaids.edit', ['learningAid' => $learningAid]);
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
        $learningAid = LearningAid::findOrFail($id);

        $learningAid->name = $name;
        $learningAid->description = $description;
        $learningAid->save();
        $competenceIds = $request->get('competence_ids');
        $competenceProficiencyLevels = $request->get('competency_proficiency_levels');
        for ($i=0; $i<sizeOf($competenceIds); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceProficiencyLevel = $competenceProficiencyLevels[$i];
            $results = $learningAid->competencies()->where('competency_id', '=', $competenceId)->get();
            if ($results->isEmpty()) {
                //add competency
                $learningAid->competencies()->attach([$competenceId => ['competence_proficiency_level_id'=>$competenceProficiencyLevel]]);
            } else {
                //update competency level
                $learningAid->competencies()->updateExistingPivot($competenceId, ['competence_proficiency_level_id'=>$competenceProficiencyLevel]);
            }
        }
        return Redirect::route('learningaids.show',$learningAid->id)->withMessage('O treinamento foi atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $learningAid = LearningAid::findOrFail($id);
        $learningAid->competencies()->detach();
        $learningAid->delete();

        return Redirect::route('learningaids.index')->withMessage('O treinamento foi excluído com sucesso!');

    }

    public function deleteCompetencyFromLearningAid($learningAidId, $competencyId) {
        $learningAid = LearningAid::findOrFail($learningAidId);
        $learningAid->competencies()->detach($competencyId);
        return Redirect::route('learningaids.edit', $learningAidId);
    }
}