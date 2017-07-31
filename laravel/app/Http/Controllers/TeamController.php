<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamFormRequest;
use App\Http\Requests\EditTeamFormRequest;
use Illuminate\Support\Facades\Redirect;
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
        return view('teams.create');
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
        $userIds = $request->get('user_ids');
        for ($i=0; $i<sizeOf($userIds); $i++) {
            $userId = $userIds[$i];
            $team->teamMembers()->attach($userId);
        }
        return Redirect::route('teams.show',$team->id)->withMessage('A equipe foi cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $message = null)
    {
		$team = Team::where('id', $id)->first();
		return view('teams.show', ['team' => $team, 'message' => $message]);
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
    public function update(EditTeamFormRequest $request, $id)
    {
        $description = $request->get('description');
        $name = $request->get('name');
        $userIds = $request->get('user_ids');

        $team = Team::findOrFail($id);
        $team->name = $name;
        $team->description = $description;
        $team->save();

        for ($i=0; $i<sizeOf($userIds); $i++) {
            $userId = $userIds[$i];
            $team->teamMembers()->attach($userId);
        }
        return Redirect::route('teams.show',$team->id)->withMessage('A equipe foi atualizada com sucesso!');
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
        return Redirect::route('teams.index')->withMessage('A equipe foi excluída com sucesso!');

    }

    public function deleteMemberFromTeam($teamId, $memberId) {
        $team = Team::findOrFail($teamId);
        $team->teamMembers()->detach($memberId);
        return $this->edit($teamId);
    }
}
