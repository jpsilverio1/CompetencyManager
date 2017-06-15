<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return "ola";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('users.profile', ['user' => User::findOrFail($id)]);
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

    public function deleteUserFromTeam($teamId) {
        \Auth::user()->teams()->detach($teamId);
        return redirect('/home');
    }
    public function deleteCompetencyFromUser($competenceId) {
        \Auth::user()->competencies()->detach($competenceId);
        return redirect('/home');
    }


    public function addCompetences(Request $request) {
        $user = \Auth::user();
        $names = $request->get('name');
        $competenceIds = $request->get('competence_id');
        $competenceLevels = $request->get('competence_level');
        for ($i=0; $i<sizeOf($names); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceLevel = $competenceLevels[$i];
            $results = $user->competencies()->where('competency_id', '=', $competenceId)->get();
            if ($results->isEmpty()) {
                echo "adicionar";
                $user->competencies()->attach([$competenceId => ['competency_level'=>$competenceLevel]]);
            } else {
                echo "update";
                //update competency level
                $user->competencies()->updateExistingPivot($competenceId, ['competency_level'=>$competenceLevel]);
            }
        }
        return redirect('/home');
    }
}


