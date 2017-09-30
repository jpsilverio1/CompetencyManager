<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;

use App\Category;

use App\JobRole;

class JobRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allJobRoles = JobRole::paginate(10);
        return view('jobroles.index', ['jobroles' => $allJobRoles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new \App\JobRole;
        return view('jobroles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->get('name');
		$description = $request->get('description');

        $jobrole = new \App\JobRole;
        $jobrole->name = $name;
        $jobrole->description = $description;
        $jobrole->save();

        $competenceIds = $request->get('competence_ids');
        $competenceProficiencyLevels = $request->get('competency_proficiency_levels');
        for ($i=0; $i<sizeOf($competenceIds); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceProficiencyLevel = $competenceProficiencyLevels[$i];
            $results = $jobrole->competencies()->where('competency_id', '=', $competenceId)->get();
            if ($results->isEmpty()) {
                //add competency
                $jobrole->competencies()->attach([$competenceId => ['competence_proficiency_level_id'=>$competenceProficiencyLevel]]);
            } else {
                //update competency level
                $jobrole->competencies()->updateExistingPivot($competenceId, ['competence_proficiency_level_id'=>$competenceProficiencyLevel]);
            }
        }
        return Redirect::route('jobroles.show',$jobrole->id)->withMessage('O cargo foi cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jobrole = JobRole::findOrFail($id);
		return view('jobroles.show', ['jobrole' => $jobrole]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jobrole = JobRole::findOrFail($id);
		return view('jobroles.edit', ['jobrole' => $jobrole]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
