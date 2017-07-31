<?php

namespace App\Http\Controllers;

class HomeController extends Controller
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
    public function index()
    {
        $teams = \Auth::user()->teams;
        $competences = \Auth::user()->competencies;
        // Logic that determines where to send the user
        if (\Auth::user()->level == 'manager') {
            return view('manager_home', [
                'teams' => $teams, 'competences' => $competences
            ]);
        }
        return view('home', [
            'teams' => $teams, 'competences' => $competences
        ]);
    }


}
