<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateTeamFormRequest;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Team;


class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allTeams = Team::paginate(10);
        return view('teams.index', ['teams' => $allTeams]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $team = new \App\Team;
        if (\Auth::user()->isManager()) {
            return view('teams.create2', ['team' => $team]);
        } else {
            return redirect('/home');
        }
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeamFormRequest $request)
    {
		$name = $request->get('name');
        $description = $request->get('description');
        $team = new \App\Team;
        $team->name = $name;
        $team->description = $description;
        $team->save();
        $names = $request->get('competence_names');
        $competenceIds = $request->get('competence_ids');
        for ($i=0; $i<sizeOf($names); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceName = $names[$i];
            $team->competencies()->attach($competenceId);
        }
        $userNames = $request->get('user_names');
        $userIds = $request->get('user_ids');
        var_dump($userNames);
        var_dump($userIds);
        for ($i=0; $i<sizeOf($userNames); $i++) {
            $userId = $userIds[$i];
            $userName = $userNames[$i];
            $team->teamMembers()->attach($userId);
        }
        return view('teams.show', ['team' => $team, 'message' => 'A equipe foi cadastrada com sucesso!']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$team = Team::where('id', $id)->first();
		return view('teams.show', ['team' => $team]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = Team::where('id', $id)->first();
		return view('teams.edit', ['team' => $team]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTeamFormRequest $request, $id)
    {
        $names = $request->get('name');
		$description = $request->get('description');

		for ($i=0; $i<sizeOf($names); $i++) {
			Team::findOrFail($id)->update(['name' => $names[$i], 'description' => $description[$i]]);
		} 
		$team = Team::findOrFail($id);
        return view('teams.show', ['id' => $id, 'team' => $team, 'message' => 'A equipe foi atualizada com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
		$team->competencies()->detach();
		$team->teamMembers()->detach();
		$team->delete(); 

		$allTeams = Team::paginate(10);
        return view('teams.index', ['teams' => $allTeams, 'message' => 'A equipe foi excluída com sucesso!']);
	
    }
}
