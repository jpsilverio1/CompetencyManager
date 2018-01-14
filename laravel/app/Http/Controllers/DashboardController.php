<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Khill\Lavacharts\Lavacharts;

use Carbon\Carbon;

use DB;

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
		
		// Tabela de Estatísticas Básicas
		$users_count = DB::table('basic_statistics')->where('name', 'users_count')->select('value')->first()->value;
		$competences_count = DB::table('basic_statistics')->where("name", "competences_count")->select('value')->first()->value;
		$learningaids_count = DB::table('basic_statistics')->where("name", "learningaids_count")->select('value')->first()->value;
		$jobroles_count = DB::table('basic_statistics')->where("name", "jobroles_count")->select('value')->first()->value;
		$tasks_count = DB::table('basic_statistics')->where("name", "tasks_count")->select('value')->first()->value;
		//$basic_statistics_table = 0;
		
		$datatable_basic_statistics = \Lava::DataTable();
		$datatable_basic_statistics->addStringColumn('Estatística');
		$datatable_basic_statistics->addNumberColumn('Valor');
		$datatable_basic_statistics->addRow(["Quantidade de Usuários", $users_count]);
		$datatable_basic_statistics->addRow(["Quantidade de Competências", $competences_count]);
		$datatable_basic_statistics->addRow(["Quantidade de Treinamentos", $learningaids_count]);
		$datatable_basic_statistics->addRow(["Quantidade de Cargos", $jobroles_count]);
		$datatable_basic_statistics->addRow(["Quantidade de Tarefas", $tasks_count]);
		
		\Lava::TableChart('basic_statistics_table', $datatable_basic_statistics, [
			'title' => 'Estatísticas Básicas',
			'legend' => [
				'position' => 'in'
			]
		]);
		
		// Grafico de Pizza
		$feasible_tasks_count = DB::table('basic_statistics')->where("name", "=", "feasible_tasks_count")->select('value')->first()->value;
		
		$not_feasible_tasks_count = $tasks_count - $feasible_tasks_count;
		var_dump($not_feasible_tasks_count);
		
		// Circulo exibindo numero, altera cor de acordo com numero
		$average_collaboration_level = DB::table('basic_statistics')->where("name", "average_collaboration_level")->get();
		
		$date_now = Carbon::now();
		$oneWeeksAgo = $date_now->subWeeks(1);
		$twoWeeksAgo = $date_now->subWeeks(2);
		$threeWeeksAgo = $date_now->subWeeks(3);
		$fourWeeksAgo = $date_now->subWeeks(4);
		
		// Grafico de barras
		
		//$all_learning_aids = \App\LearningAid->all();
		
		//$w1 = $all_learning_aids->learnindAidStatus() == "finished" ? $w1++; skip;
		
		
		$users_Learningaids_Week1 = DB::table('learning_aids_user')->whereBetween("completed_on", [$fourWeeksAgo, $threeWeeksAgo])->count();
		$users_Learningaids_Week2 = DB::table('learning_aids_user')->whereBetween("completed_on", [$threeWeeksAgo, $twoWeeksAgo])->count();
		$users_Learningaids_Week3 = DB::table('learning_aids_user')->whereBetween("completed_on", [$twoWeeksAgo, $oneWeeksAgo])->count();
		$users_Learningaids_Week4 = DB::table('learning_aids_user')->whereBetween("completed_on", [$oneWeeksAgo, $date_now])->count();
		
		var_dump($users_Learningaids_Week1);
		var_dump($users_Learningaids_Week2);
		var_dump($users_Learningaids_Week3);
		var_dump($users_Learningaids_Week4);
		
		var_dump($oneWeeksAgo);
		
/*
		$users_Learningaids_Week1 = \App\LearningAid::whereBetween("completed_on", [$fourWeeksAgo, $threeWeeksAgo])->count();
		$users_Learningaids_Week2 = \App\LearningAid::whereBetween("completed_on", [$threeWeeksAgo, $twoWeeksAgo])->count();
		$users_Learningaids_Week3 = \App\LearningAid::whereBetween("completed_on", [$twoWeeksAgo, $oneWeeksAgo])->count();
		$users_Learningaids_Week4 = \App\LearningAid::whereBetween("completed_on", [$oneWeeksAgo, $date_now])->count();
		
*/
		$datatable_columnChart = \Lava::DataTable();
		$datatable_columnChart->addDateColumn('Semana X');
		
		$datatable_columnChart->addNumberColumn('Count');
		
		$datatable_columnChart->addRow([$oneWeeksAgo, 2]);
		$datatable_columnChart->addRow([$twoWeeksAgo,4]);
		$datatable_columnChart->addRow([$threeWeeksAgo, 0]);
		$datatable_columnChart->addRow([$fourWeeksAgo, 10]);
		

		\Lava::ColumnChart('Tarefas Finalizadas', $datatable_columnChart, [
			'title' => 'Tarefas Finalizadas',
			'legend' => [
				'position' => 'in'
			],
			'hAxis' => [
				'title' => 'Time of Day',
				'format' => 'h:mm a',
			],
		]);
		
		
		
		/*
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
		 */
	
        return view('dashboards.index', ['users' => 'oi']);
    }
	
	public function show($id) {
		return;
	}
	
	public function finishedTasksReport() {
		// Tarefas Finalizadas
		$datatable = \Lava::DataTable();
		$datatable->addStringColumn('Tarefa');
		$datatable->addStringColumn('Status');

		
		$tasks = \App\Task::all();
		foreach ($tasks as $task) {
			if ($task->taskStatus() == "finished") {
				$datatable->addRow([$task->title, "Finalizada"]);
			}
		}

		\Lava::TableChart('Tarefas Finalizadas', $datatable, [
			'title' => 'Tarefas Finalizadas',
			'legend' => [
				'position' => 'in'
			]
		]);
		return view('dashboards.finished_tasks_report', ['user' => 'oi'	]);
	}
	
	public function notFinishedTasksReport() {
		return view('dashboards.not_finished_tasks_report', ['user' => 'oi'	]);
	}
	
	
	public function notInitializedTasksReport() {
		return view('dashboards.not_initialized_tasks_report', ['user' => 'oi'	]);
	}
	
	
	public function unfeasibleTasksReport() {
		return view('dashboards.unfeasible_tasks_report', ['user' => 'oi'	]);
	}
	
	
	public function coveredCompetencesReport() {
		return view('dashboards.covered_competences_report', ['user' => 'oi'	]);
	}
	
	
	public function neededCompetencesReport() {
		return view('dashboards.needed_competences_report', ['user' => 'oi'	]);
	}
	
	
	public function mostLearnedCompetencesReport() {
		return view('dashboards.most_learned_competences_report', ['user' => 'oi'	]);
	}
	
	
	public function mostCollaborativeUsersReport() {
		return view('dashboards.most_collaborative_users_report', ['user' => 'oi'	]);
	}
	
	
	public function mostCollaborativeGroupsReport() {
		return view('dashboards.most_collaborative_groups_report', ['user' => 'oi'	]);
	}
	
	public function usersWhoDidntAnswerCollaborationFormReport() {
		return view('dashboards.users_who_didnt_answer_collaboration_form_report', ['user' => 'oi'	]);
	}
	
	public function usersWithHighestCompetenceNumberReport() {
		return view('dashboards.users_with_highest_competence_number_report', ['user' => 'oi'	]);
	}
	
	public function usersWithMoreTasksPerformedReport() {
		return view('dashboards.users_with_more_tasks_performed_report', ['user' => 'oi'	]);
	}
	
	public function taskReports()
    {	
		// Tarefas Finalizadas
		$datatable = \Lava::DataTable();
		$datatable->addStringColumn('Tarefa');
		$datatable->addStringColumn('Status');

		
		$tasks = \App\Task::all();
		foreach ($tasks as $task) {
			if ($task->taskStatus() == "finished") {
				$datatable->addRow([$task->title, "Finalizada"]);
			}
		}

		\Lava::TableChart('Tarefas Finalizadas', $datatable, [
			'title' => 'Tarefas Finalizadas',
			'legend' => [
				'position' => 'in'
			]
		]);
		
		// Tarefas Finalizadas - Chart ao longo do tempo
		
		$tasks = \App\Task::all();
		
		$datatable_columnChart = \Lava::DataTable();
		$datatable_columnChart->addDateColumn('Semana 1');
		$datatable_columnChart->addDateColumn('Semana 2');
		$datatable_columnChart->addDateColumn('Semana 3');
		$datatable_columnChart->addDateColumn('Semana 4');
		$datatable_columnChart->addDateColumn('Semana 5');
		$datatable_columnChart->addDateColumn('Semana 6');
		$datatable_columnChart->addDateColumn('Semana 7');
		$datatable_columnChart->addDateColumn('Semana 8');
		$datatable_columnChart->addNumberColumn('Tarefas Finalizadas');

		$count_finalizadas = 0;
		
		$date_now = Carbon::now();
		$oneWeeksAgo = $date_now->subWeeks(1);
		$twoWeeksAgo = $date_now->subWeeks(2);
		$threeWeeksAgo = $date_now->subWeeks(3);
		$fourWeeksAgo = $date_now->subWeeks(4);
		$fiveWeeksAgo = $date_now->subWeeks(5);
		$sixWeeksAgo = $date_now->subWeeks(6);
		$sevenWeeksAgo = $date_now->subWeeks(7);
		$eightWeeksAgo = $date_now->subWeeks(8);
		
		
		/*$tasksWeek1 = \App\Task::whereBetween("end_date", [$eightWeeksAgo, $sevenWeeksAgo])->count();
		$tasksWeek2 = \App\Task::whereBetween("end_date", [$sevenWeeksAgo, $sixWeeksAgo])->count();
		$tasksWeek3 = \App\Task::whereBetween("end_date", [$sixWeeksAgo, $fiveWeeksAgo])->count();
		$tasksWeek4 = \App\Task::whereBetween("end_date", [$fiveWeeksAgo, $fourWeeksAgo])->count();
		$tasksWeek5 = \App\Task::whereBetween("end_date", [$fourWeeksAgo, $threeWeeksAgo])->count();
		$tasksWeek6 = \App\Task::whereBetween("end_date", [$threeWeeksAgo, $twoWeeksAgo])->count();
		$tasksWeek7 = \App\Task::whereBetween("end_date", [$twoWeeksAgo, $oneWeeksAgo])->count();
		$tasksWeek8 = \App\Task::whereBetween("end_date", [$oneWeeksAgo, $date_now])->count();
		
		// TODO: checar status finalizado pra cada $tasksWeekX anterior. Talvez retirar da Collection as que não serem finalizadas, e depois contar. Não sei ainda.
		
		foreach ($tasks as $task) {
			if ($task->taskStatus() == "finished") {
				$count_finalizadas += 1;
			}
		}
		$datatable_columnChart->addRow([$oneWeeksAgo, $tasksWeek1]);
		$datatable_columnChart->addRow([$twoWeeksAgo, $tasksWeek2]);
		$datatable_columnChart->addRow([$threeWeeksAgo, $tasksWeek3]);
		$datatable_columnChart->addRow([$fourWeeksAgo, $tasksWeek4]);
		$datatable_columnChart->addRow([$fiveWeeksAgo, $tasksWeek5]);
		$datatable_columnChart->addRow([$sixWeeksAgo, $tasksWeek6]);
		$datatable_columnChart->addRow([$sevenWeeksAgo, $tasksWeek7]);
		$datatable_columnChart->addRow([$eightWeeksAgo, $tasksWeek8]);

		\Lava::ColumnChart('Tarefas Finalizadas/Chart', $datatable_columnChart, [
			'title' => 'Tarefas Finalizadas/Chart',
			'legend' => [
				'position' => 'in'
			]
		]); */
		
		// Tarefas inicializadas, mas não finalizadas
		$datatable2 = \Lava::DataTable();
		$datatable2->addStringColumn('Tarefa');
		$datatable2->addStringColumn('Status');

		
		$tasks = \App\Task::all();
		foreach ($tasks as $task) {
			if ($task->taskStatus() == "initialized") {
				$datatable2->addRow([$task->title, "Não-finalizada"]);
			}
		}

		\Lava::TableChart('Tarefas Inicializadas', $datatable2, [
			'title' => 'Tarefas Inicializadas',
			'legend' => [
				'position' => 'in'
			]
		]);
		
		// Tarefas criadas, mas não inicializadas
		$datatable3 = \Lava::DataTable();
		$datatable3->addStringColumn('Tarefa');
		$datatable3->addStringColumn('Status');
		
		$tasks = \App\Task::all();
		foreach ($tasks as $task) {
			if ($task->taskStatus() == "created") {
				$datatable3->addRow([$task->title, "Não-Inicializada"]);
			}
		}

		\Lava::TableChart('Tarefas Não-Inicializadas', $datatable3, [
			'title' => 'Tarefas Não-Inicializadas',
			'legend' => [
				'position' => 'in'
			]
		]);
		
		// 
		
		// Tarefas não-executáveis
			
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
