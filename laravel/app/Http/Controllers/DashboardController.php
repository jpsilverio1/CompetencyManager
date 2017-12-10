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
	
        return view('dashboards.index', ['users' => 'oi']);
    }
	
	public function taskReports()
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
			
        return view('dashboards.tasks_reports', ['user' => 'oi'	]);
    }
	
	public function competencesReports() {
		return view('dashboards.competences_reports', ['user' => 'oi'	]);
	}
	
	public function usersReports() {
		return view('dashboards.users_reports', ['user' => 'oi'	]);
	}
	
	public function collaborationReports() {
		return view('dashboards.collaboration_reports', ['user' => 'oi'	]);
	}
	
	public function otherReports() {
		return view('dashboards.other_reports', ['user' => 'oi'	]);
	}
}
