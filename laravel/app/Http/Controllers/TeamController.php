<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateTeamFormRequest;
use Illuminate\Http\Request;
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
            return view('teams.create', ['team' => $team]);
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
		
		$names = $request->get('name');
		$description = $request->get('description');

		for ($i=0; $i<sizeOf($names); $i++) {
			$team = new \App\Team; 
			$team->name = $names[$i];
			$team->description = $description[$i];
			$team->save();
			
		} 
		
		$allTeams = Team::paginate(10);
        return view('teams.index', ['teams' => $allTeams, 'message' => 'As equipes foram cadastradas com sucesso!']);

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
        //
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
        //
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
