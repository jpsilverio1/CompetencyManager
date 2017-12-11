<?php

namespace App\Http\Controllers;

use App\Competency;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
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
        $allUsers = User::orderBy('name')->paginate(10);
        return view('users.index', ['users' => $allUsers]);
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
        return view('users.edit', ['user' => User::findOrFail($id)]);
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
		$name = $request->get('name');
		$email = $request->get('email');
		$level = $request->get('level');
		$old_password = $request->get('password-old');
		$new_password = $request->get('password-new');
		$new_password_confirm = $request->get('password-new-confirm');
		
		#Checar senha antiga usando MD5 com senha neste form
		#Checar senha nova com confirmação de senha nova 
		#Checar nível

        return view('users.profile', ['id' => $id, 'user' => \Auth::user(), 'message' => 'Seu perfil foi atualizado com sucesso!']);
		
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
    public function deleteCompetenceFromUser($competenceId) {
        \Auth::user()->competences()->detach($competenceId);
        return redirect('/home');
    }

    public function addCompetences(Request $request) {
        $user = \Auth::user();
        $names = $request->get('name');
        $competenceIds = $request->get('competence_id');
        $competenceProficiencyLevels = $request->get('competence_proficiency_level');
        var_dump($competenceProficiencyLevels);
        for ($i=0; $i<sizeOf($names); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceLevel = $competenceProficiencyLevels[$i];
            $results = $user->competences()->where('competence_id', '=', $competenceId)->get();
            if ($results->isEmpty()) {
                echo "adicionar";
                $user->competences()->attach([$competenceId => ['competence_proficiency_level_id'=>$competenceLevel]]);
            } else {
                echo "update";
                //update competency level
                $user->competences()->updateExistingPivot($competenceId, ['competence_proficiency_level_id'=>$competenceLevel]);
            }
        }
        return redirect('/home');
    }
}


