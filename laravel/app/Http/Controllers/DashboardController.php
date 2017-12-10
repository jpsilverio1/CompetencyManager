<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Khill\Lavacharts\Lavacharts;

class DashboardController extends Controller
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
        //$allUsers = User::paginate(10);
        return view('dashboards.index', ['users' => 'oi']);
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
		

				

				//$lava = new Lavacharts; // See note below for Laravel

				$population = \Lava::DataTable();

				$population->addDateColumn('Year')
						   ->addNumberColumn('Number of People')
						   ->addRow(['2006', 623452])
						   ->addRow(['2007', 685034])
						   ->addRow(['2008', 716845])
						   ->addRow(['2009', 757254])
						   ->addRow(['2010', 778034])
						   ->addRow(['2011', 792353])
						   ->addRow(['2012', 839657])
						   ->addRow(['2013', 842367])
						   ->addRow(['2014', 873490]);

				\Lava::AreaChart('Population', $population, [
					'title' => 'Population Growth',
					'legend' => [
						'position' => 'in'
					]
				]);
			
        return view('dashboards.show', ['user' => 'oi'	]);
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
