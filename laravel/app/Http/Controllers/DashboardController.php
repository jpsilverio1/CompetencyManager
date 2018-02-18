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
	 
	public function basicStatisticsTableForDashboard($users_count, $competences_count, $learningaids_count, $jobroles_count, $tasks_count) {
		$datatable_basic_statistics = \Lava::DataTable();
		$datatable_basic_statistics->addStringColumn('Estatísticas Básicas');
		$datatable_basic_statistics->addNumberColumn('Valor');
		$datatable_basic_statistics->addRow(["Quantidade de Usuários", $users_count]);
		$datatable_basic_statistics->addRow(["Quantidade de Competências", $competences_count]);
		$datatable_basic_statistics->addRow(["Quantidade de Treinamentos", $learningaids_count]);
		$datatable_basic_statistics->addRow(["Quantidade de Cargos", $jobroles_count]);
		$datatable_basic_statistics->addRow(["Quantidade de Tarefas", $tasks_count]);
		
		return \Lava::TableChart('basic_statistics_table', $datatable_basic_statistics, [
			'title' => 'Estatísticas Básicas',
			'legend' => [
				'position' => 'in'
			]
		]);
	}
	
	public function feasibleTasksPieChart($feasible_tasks_count, $not_feasible_tasks_count) {
		$feasible_tasks_pie_chart = \Lava::DataTable();
		$feasible_tasks_pie_chart->addStringColumn('Tarefas');
		$feasible_tasks_pie_chart->addNumberColumn('Porcentagem');
		$feasible_tasks_pie_chart->addRow(['Executáveis',$feasible_tasks_count]);
		$feasible_tasks_pie_chart->addRow(['Não-executáveis',$not_feasible_tasks_count]);
		
		return \Lava::PieChart('feasible_tasks_pie_chart', $feasible_tasks_pie_chart, [
			'title' => 'Gráfico de Tarefas Executáveis vs Não-Executáveis',
			'legend' => [
				'position' => 'in'
			]
		]);
	}
	
	public function averageCollaborationLevelIndicator($average_collaboration_level){
		$average_collaboration_level_circle = \Lava::DataTable();
		$average_collaboration_level_circle->addStringColumn('Índice médio de Colaboração');
		$average_collaboration_level_circle->addNumberColumn('Índice Médio de Colaboração');
		$average_collaboration_level_circle->addRow(['Colaboração', $average_collaboration_level]);
		
		
		return \Lava::GaugeChart('average_collaboration_level_circle', $average_collaboration_level_circle, [
			'title' => 'Indicador do Nível Médio de Colaboração',
			'width'      => 400,
			'greenFrom'  => 0.7,
			'greenTo'    => 1.0,
			'yellowFrom' => 0.5,
			'yellowTo'   => 0.69,
			'redFrom'    => 0,
			'redTo'      => 0.49,
			'min' => 0,
			'max' => 1,
			'majorTicks' => [
				'Crítico',
				'Médio',
				'Alto'
			]
		]);
	}
	
	public function learningAidsColumnChartForDashboard() {
		$oneWeeksAgo = Carbon::now()->subWeeks(1);
		$twoWeeksAgo = Carbon::now()->subWeeks(2);
		$threeWeeksAgo = Carbon::now()->subWeeks(3);
		$fourWeeksAgo = Carbon::now()->subWeeks(4);
		
		$users_Learningaids_Week1 = DB::table('learning_aids_user')->whereBetween("completed_on", [$fourWeeksAgo, $threeWeeksAgo])->count();
		$users_Learningaids_Week2 = DB::table('learning_aids_user')->whereBetween("completed_on", [$threeWeeksAgo, $twoWeeksAgo])->count();
		$users_Learningaids_Week3 = DB::table('learning_aids_user')->whereBetween("completed_on", [$twoWeeksAgo, $oneWeeksAgo])->count();
		$users_Learningaids_Week4 = DB::table('learning_aids_user')->whereBetween("completed_on", [$oneWeeksAgo, Carbon::now()])->count();
		
		$datatable_columnChart = \Lava::DataTable();
		$datatable_columnChart->addDateColumn('Semana');
		
		$datatable_columnChart->addNumberColumn('Quantidade');
		
		
		$datatable_columnChart->addRow([$fourWeeksAgo, $users_Learningaids_Week1]);
		$datatable_columnChart->addRow([$threeWeeksAgo, $users_Learningaids_Week2]);
		$datatable_columnChart->addRow([$twoWeeksAgo,$users_Learningaids_Week3]);
		$datatable_columnChart->addRow([$oneWeeksAgo, $users_Learningaids_Week4]);
		

		\Lava::ColumnChart('finished_tasks_div', $datatable_columnChart, [
			'title' => 'Tarefas Finalizadas',
			'legend' => [
				'position' => 'none'
			],
			'hAxis' => [
				'format' => 'd MMM, y',
			],
			'vAxis' => [
				'baseline' => 0,
				'minValue' => 0.0,
			],
		]);
	}
	
	public function index()
    {
		$users_count = DB::table('basic_statistics')->where('name', 'users_count')->select('value')->first()->value;
		$competences_count = DB::table('basic_statistics')->where("name", "competences_count")->select('value')->first()->value;
		$learningaids_count = DB::table('basic_statistics')->where("name", "learningaids_count")->select('value')->first()->value;
		$jobroles_count = DB::table('basic_statistics')->where("name", "jobroles_count")->select('value')->first()->value;
		$tasks_count = DB::table('basic_statistics')->where("name", "tasks_count")->select('value')->first()->value;
		$feasible_tasks_count = DB::table('basic_statistics')->where("name", "=", "feasible_tasks_count")->select('value')->first()->value;
		$not_feasible_tasks_count = $tasks_count - $feasible_tasks_count;
		$average_collaboration_level = DB::table('basic_statistics')->where("name", "average_collaboration_level")->first()->value;
		
		// Tabela de Estatísticas Básicas
		$this->basicStatisticsTableForDashboard($users_count, $competences_count, $learningaids_count, $jobroles_count, $tasks_count);
		
		// Grafico de Pizza de Tasks Executáveis
		$this->feasibleTasksPieChart($feasible_tasks_count, $not_feasible_tasks_count);
		
		// Circulo exibindo nível médio de colaboração, altera cor de acordo com numero
		$this->averageCollaborationLevelIndicator($average_collaboration_level);
		
		// Grafico de Barras com número de treinamentos nas últimas 4 semanas
		$this->learningAidsColumnChartForDashboard();
		
        return view('dashboards.index');
    }
	
	public function show($id) {
		return;
	}
	
	public function tasksColumnChart($taskStatus) {
		$oneWeeksAgo = Carbon::now()->subWeeks(1);
		$twoWeeksAgo = Carbon::now()->subWeeks(2);
		$threeWeeksAgo = Carbon::now()->subWeeks(3);
		$fourWeeksAgo = Carbon::now()->subWeeks(4);
		$fiveWeeksAgo = Carbon::now()->subWeeks(5);
		$sixWeeksAgo = Carbon::now()->subWeeks(6);
		$sevenWeeksAgo = Carbon::now()->subWeeks(7);
		$eightWeeksAgo = Carbon::now()->subWeeks(8);
		$nineWeeksAgo = Carbon::now()->subWeeks(9);
		$tenWeeksAgo = Carbon::now()->subWeeks(10);
		$elevenWeeksAgo = Carbon::now()->subWeeks(11);
		$tweelveWeeksAgo = Carbon::now()->subWeeks(12);
		
		
		
		$users_Learningaids_Week1 = DB::table('learning_aids_user')->whereBetween("completed_on", [$tweelveWeeksAgo, $elevenWeeksAgo])->count();
		$users_Learningaids_Week2 = DB::table('learning_aids_user')->whereBetween("completed_on", [$elevenWeeksAgo, $tenWeeksAgo])->count();
		$users_Learningaids_Week3 = DB::table('learning_aids_user')->whereBetween("completed_on", [$tenWeeksAgo, $nineWeeksAgo])->count();
		$users_Learningaids_Week4 = DB::table('learning_aids_user')->whereBetween("completed_on", [$nineWeeksAgo, $eightWeeksAgo])->count();
		$users_Learningaids_Week5 = DB::table('learning_aids_user')->whereBetween("completed_on", [$eightWeeksAgo, $sevenWeeksAgo])->count();
		$users_Learningaids_Week6 = DB::table('learning_aids_user')->whereBetween("completed_on", [$sevenWeeksAgo, $sixWeeksAgo])->count();
		$users_Learningaids_Week7 = DB::table('learning_aids_user')->whereBetween("completed_on", [$sixWeeksAgo, $fiveWeeksAgo])->count();
		$users_Learningaids_Week8 = DB::table('learning_aids_user')->whereBetween("completed_on", [$fiveWeeksAgo, $fourWeeksAgo])->count();
		$users_Learningaids_Week9 = DB::table('learning_aids_user')->whereBetween("completed_on", [$fourWeeksAgo, $threeWeeksAgo])->count();
		$users_Learningaids_Week10 = DB::table('learning_aids_user')->whereBetween("completed_on", [$threeWeeksAgo, $twoWeeksAgo])->count();
		$users_Learningaids_Week11 = DB::table('learning_aids_user')->whereBetween("completed_on", [$twoWeeksAgo, $oneWeeksAgo])->count();
		$users_Learningaids_Week12 = DB::table('learning_aids_user')->whereBetween("completed_on", [$oneWeeksAgo, Carbon::now()])->count();
		
		$datatable_columnChart = \Lava::DataTable();
		$datatable_columnChart->addDateColumn('Semana');
		$datatable_columnChart->addNumberColumn('Quantidade');
		
		$datatable_columnChart->addRow([$tweelveWeeksAgo, $users_Learningaids_Week1]);
		$datatable_columnChart->addRow([$elevenWeeksAgo, $users_Learningaids_Week2]);
		$datatable_columnChart->addRow([$tenWeeksAgo,$users_Learningaids_Week3]);
		$datatable_columnChart->addRow([$nineWeeksAgo, $users_Learningaids_Week4]);
		$datatable_columnChart->addRow([$eightWeeksAgo, $users_Learningaids_Week5]);
		$datatable_columnChart->addRow([$sevenWeeksAgo, $users_Learningaids_Week6]);
		$datatable_columnChart->addRow([$sixWeeksAgo, $users_Learningaids_Week7]);
		$datatable_columnChart->addRow([$fiveWeeksAgo, $users_Learningaids_Week8]);
		$datatable_columnChart->addRow([$fourWeeksAgo, $users_Learningaids_Week9]);
		$datatable_columnChart->addRow([$threeWeeksAgo, $users_Learningaids_Week10]);
		$datatable_columnChart->addRow([$twoWeeksAgo, $users_Learningaids_Week11]);
		$datatable_columnChart->addRow([$oneWeeksAgo, $users_Learningaids_Week12]);

		\Lava::ColumnChart('finished_tasks_chart_div', $datatable_columnChart, [
			'title' => 'Tarefas Finalizadas por Semana',
			'legend' => [
				'position' => 'none'
			],
			'hAxis' => [
				'format' => 'd MMM, y',
			],
			'vAxis' => [
				'baseline' => 0,
				'minValue' => 0.0,
			],
		]);
	}
	
	public function finishedTasksReport() {
		// Tarefas Finalizadas - gráfico de colunas indicando número por semanas
		$oneWeeksAgo = Carbon::now()->subWeeks(1);
		$twoWeeksAgo = Carbon::now()->subWeeks(2);
		$threeWeeksAgo = Carbon::now()->subWeeks(3);
		$fourWeeksAgo = Carbon::now()->subWeeks(4);
		$fiveWeeksAgo = Carbon::now()->subWeeks(5);
		$sixWeeksAgo = Carbon::now()->subWeeks(6);
		$sevenWeeksAgo = Carbon::now()->subWeeks(7);
		$eightWeeksAgo = Carbon::now()->subWeeks(8);
		$nineWeeksAgo = Carbon::now()->subWeeks(9);
		$tenWeeksAgo = Carbon::now()->subWeeks(10);
		$elevenWeeksAgo = Carbon::now()->subWeeks(11);
		$tweelveWeeksAgo = Carbon::now()->subWeeks(12);
		
		$finishedTasks_Week1 = 0;
		$finishedTasks_Week2 = 0;
		$finishedTasks_Week3 = 0;
		$finishedTasks_Week4 = 0;
		$finishedTasks_Week5 = 0;
		$finishedTasks_Week6 = 0;
		$finishedTasks_Week7 = 0;
		$finishedTasks_Week8 = 0;
		$finishedTasks_Week9 = 0;
		$finishedTasks_Week10 = 0;
		$finishedTasks_Week11 = 0;
		$finishedTasks_Week12 = 0;
		
		$datatable_columnChart = \Lava::DataTable();
		$datatable_columnChart->addDateColumn('Semana');
		$datatable_columnChart->addNumberColumn('Quantidade');
		
		// Tarefas Finalizadas - tabela
		$datatable = \Lava::DataTable();
		$datatable->addStringColumn('Tarefa');
		$datatable->addStringColumn('Status');

		
		$tasks = \App\Task::all();
		foreach ($tasks as $task) {
			if ($task->taskStatus() == "finished") {
				$datatable->addRow(["<a href='".route('tasks.show', $task->id)."'>".$task->title."</a>", "Finalizada"]);
				if (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($tweelveWeeksAgo, $elevenWeeksAgo)) {
					$finishedTasks_Week1 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($elevenWeeksAgo, $tenWeeksAgo)) {
					$finishedTasks_Week2 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($tenWeeksAgo, $nineWeeksAgo)) {
					$finishedTasks_Week3 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($nineWeeksAgo, $eightWeeksAgo)) {
					$finishedTasks_Week4 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($eightWeeksAgo, $sevenWeeksAgo)) {
					$finishedTasks_Week5 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($sevenWeeksAgo, $sixWeeksAgo)) {
					$finishedTasks_Week6 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($sixWeeksAgo, $fiveWeeksAgo)) {
					$finishedTasks_Week7 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($fiveWeeksAgo, $fourWeeksAgo)) {
					$finishedTasks_Week8 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($fourWeeksAgo, $threeWeeksAgo)) {
					$finishedTasks_Week9 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($threeWeeksAgo, $twoWeeksAgo)) {
					$finishedTasks_Week10 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($twoWeeksAgo, $oneWeeksAgo)) {
					$finishedTasks_Week11 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->end_date)->between($oneWeeksAgo, Carbon::now())) {
					$finishedTasks_Week12 += 1;
				}
			}
		}

		\Lava::TableChart('Tarefas Finalizadas', $datatable, [
			'title' => 'Tarefas Finalizadas',
			'legend' => [
				'position' => 'in'
			],
			'allowHtml' => true,
		]);
		
		
		$datatable_columnChart->addRow([$tweelveWeeksAgo, $finishedTasks_Week1]);
		$datatable_columnChart->addRow([$elevenWeeksAgo, $finishedTasks_Week2]);
		$datatable_columnChart->addRow([$tenWeeksAgo,$finishedTasks_Week3]);
		$datatable_columnChart->addRow([$nineWeeksAgo, $finishedTasks_Week4]);
		$datatable_columnChart->addRow([$eightWeeksAgo, $finishedTasks_Week5]);
		$datatable_columnChart->addRow([$sevenWeeksAgo, $finishedTasks_Week6]);
		$datatable_columnChart->addRow([$sixWeeksAgo, $finishedTasks_Week7]);
		$datatable_columnChart->addRow([$fiveWeeksAgo, $finishedTasks_Week8]);
		$datatable_columnChart->addRow([$fourWeeksAgo, $finishedTasks_Week9]);
		$datatable_columnChart->addRow([$threeWeeksAgo, $finishedTasks_Week10]);
		$datatable_columnChart->addRow([$twoWeeksAgo, $finishedTasks_Week11]);
		$datatable_columnChart->addRow([$oneWeeksAgo, $finishedTasks_Week12]);
		
		\Lava::ColumnChart('finished_tasks_chart_div', $datatable_columnChart, [
			'title' => 'Tarefas Finalizadas por Semana',
			'legend' => [
				'position' => 'none'
			],
			'hAxis' => [
				'format' => 'd MMM, y',
			],
			'vAxis' => [
				'baseline' => 0,
				'minValue' => 0.0,
			],
		]);
		
		return view('dashboards.finished_tasks_report');
	}
	
	public function notFinishedTasksReport() {
		// Tarefas Não-finalizadas - gráfico de colunas indicando número por semanas
		$oneWeeksAgo = Carbon::now()->subWeeks(1);
		$twoWeeksAgo = Carbon::now()->subWeeks(2);
		$threeWeeksAgo = Carbon::now()->subWeeks(3);
		$fourWeeksAgo = Carbon::now()->subWeeks(4);
		$fiveWeeksAgo = Carbon::now()->subWeeks(5);
		$sixWeeksAgo = Carbon::now()->subWeeks(6);
		$sevenWeeksAgo = Carbon::now()->subWeeks(7);
		$eightWeeksAgo = Carbon::now()->subWeeks(8);
		$nineWeeksAgo = Carbon::now()->subWeeks(9);
		$tenWeeksAgo = Carbon::now()->subWeeks(10);
		$elevenWeeksAgo = Carbon::now()->subWeeks(11);
		$tweelveWeeksAgo = Carbon::now()->subWeeks(12);
		
		$notFinishedTasks_Week1 = 0;
		$notFinishedTasks_Week2 = 0;
		$notFinishedTasks_Week3 = 0;
		$notFinishedTasks_Week4 = 0;
		$notFinishedTasks_Week5 = 0;
		$notFinishedTasks_Week6 = 0;
		$notFinishedTasks_Week7 = 0;
		$notFinishedTasks_Week8 = 0;
		$notFinishedTasks_Week9 = 0;
		$notFinishedTasks_Week10 = 0;
		$notFinishedTasks_Week11 = 0;
		$notFinishedTasks_Week12 = 0;
		
		$datatable_columnChart = \Lava::DataTable();
		$datatable_columnChart->addDateColumn('Semana');
		$datatable_columnChart->addNumberColumn('Quantidade');
		
		// Tarefas Não-finalizadas - tabela
		$datatable = \Lava::DataTable();
		$datatable->addStringColumn('Tarefa');
		$datatable->addStringColumn('Status');

		
		$tasks = \App\Task::all();
		foreach ($tasks as $task) {
			if ($task->taskStatus() == "initialized") {
				$datatable->addRow(["<a href='".route('tasks.show', $task->id)."'>".$task->title."</a>", "Não-finalizada"]);
				if (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($tweelveWeeksAgo, $elevenWeeksAgo)) {
					$notFinishedTasks_Week1 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($elevenWeeksAgo, $tenWeeksAgo)) {
					$notFinishedTasks_Week2 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($tenWeeksAgo, $nineWeeksAgo)) {
					$notFinishedTasks_Week3 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($nineWeeksAgo, $eightWeeksAgo)) {
					$notFinishedTasks_Week4 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($eightWeeksAgo, $sevenWeeksAgo)) {
					$notFinishedTasks_Week5 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($sevenWeeksAgo, $sixWeeksAgo)) {
					$notFinishedTasks_Week6 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($sixWeeksAgo, $fiveWeeksAgo)) {
					$notFinishedTasks_Week7 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($fiveWeeksAgo, $fourWeeksAgo)) {
					$notFinishedTasks_Week8 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($fourWeeksAgo, $threeWeeksAgo)) {
					$notFinishedTasks_Week9 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($threeWeeksAgo, $twoWeeksAgo)) {
					$notFinishedTasks_Week10 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($twoWeeksAgo, $oneWeeksAgo)) {
					$notFinishedTasks_Week11 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->start_date)->between($oneWeeksAgo, Carbon::now())) {
					$notFinishedTasks_Week12 += 1;
				}
			}
		}

		\Lava::TableChart('not_finished_tasks_table_div', $datatable, [
			'title' => 'Tarefas Não-finalizadas',
			'legend' => [
				'position' => 'in'
			],
			'allowHtml' => true,
		]);
		
		
		$datatable_columnChart->addRow([$tweelveWeeksAgo, $notFinishedTasks_Week1]);
		$datatable_columnChart->addRow([$elevenWeeksAgo, $notFinishedTasks_Week2]);
		$datatable_columnChart->addRow([$tenWeeksAgo,$notFinishedTasks_Week3]);
		$datatable_columnChart->addRow([$nineWeeksAgo, $notFinishedTasks_Week4]);
		$datatable_columnChart->addRow([$eightWeeksAgo, $notFinishedTasks_Week5]);
		$datatable_columnChart->addRow([$sevenWeeksAgo, $notFinishedTasks_Week6]);
		$datatable_columnChart->addRow([$sixWeeksAgo, $notFinishedTasks_Week7]);
		$datatable_columnChart->addRow([$fiveWeeksAgo, $notFinishedTasks_Week8]);
		$datatable_columnChart->addRow([$fourWeeksAgo, $notFinishedTasks_Week9]);
		$datatable_columnChart->addRow([$threeWeeksAgo, $notFinishedTasks_Week10]);
		$datatable_columnChart->addRow([$twoWeeksAgo, $notFinishedTasks_Week11]);
		$datatable_columnChart->addRow([$oneWeeksAgo, $notFinishedTasks_Week12]);
		
		\Lava::ColumnChart('not_finished_tasks_chart_div', $datatable_columnChart, [
			'title' => 'Tarefas Não-finalizadas por Semana',
			'legend' => [
				'position' => 'none'
			],
			'hAxis' => [
				'format' => 'd MMM, y',
			],
			'vAxis' => [
				'baseline' => 0,
				'minValue' => 0.0,
			],
		]);
		
		return view('dashboards.not_finished_tasks_report');
	}
	
	
	public function notInitializedTasksReport() {
		// Tarefas Não-inicializadas - gráfico de colunas indicando número por semanas
		$oneWeeksAgo = Carbon::now()->subWeeks(1);
		$twoWeeksAgo = Carbon::now()->subWeeks(2);
		$threeWeeksAgo = Carbon::now()->subWeeks(3);
		$fourWeeksAgo = Carbon::now()->subWeeks(4);
		$fiveWeeksAgo = Carbon::now()->subWeeks(5);
		$sixWeeksAgo = Carbon::now()->subWeeks(6);
		$sevenWeeksAgo = Carbon::now()->subWeeks(7);
		$eightWeeksAgo = Carbon::now()->subWeeks(8);
		$nineWeeksAgo = Carbon::now()->subWeeks(9);
		$tenWeeksAgo = Carbon::now()->subWeeks(10);
		$elevenWeeksAgo = Carbon::now()->subWeeks(11);
		$tweelveWeeksAgo = Carbon::now()->subWeeks(12);
		
		$notInitializedTasks_Week1 = 0;
		$notInitializedTasks_Week2 = 0;
		$notInitializedTasks_Week3 = 0;
		$notInitializedTasks_Week4 = 0;
		$notInitializedTasks_Week5 = 0;
		$notInitializedTasks_Week6 = 0;
		$notInitializedTasks_Week7 = 0;
		$notInitializedTasks_Week8 = 0;
		$notInitializedTasks_Week9 = 0;
		$notInitializedTasks_Week10 = 0;
		$notInitializedTasks_Week11 = 0;
		$notInitializedTasks_Week12 = 0;
		
		$datatable_columnChart = \Lava::DataTable();
		$datatable_columnChart->addDateColumn('Semana');
		$datatable_columnChart->addNumberColumn('Quantidade');
		
		// Tarefas Não-inicializadas - tabela
		$datatable = \Lava::DataTable();
		$datatable->addStringColumn('Tarefa');
		$datatable->addStringColumn('Status');

		
		$tasks = \App\Task::all();
		foreach ($tasks as $task) {
			if ($task->taskStatus() == "created") {
				$datatable->addRow(["<a href='".route('tasks.show', $task->id)."'>".$task->title."</a>", "Não-inicializada"]);
				if (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($tweelveWeeksAgo, $elevenWeeksAgo)) {
					$notInitializedTasks_Week1 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($elevenWeeksAgo, $tenWeeksAgo)) {
					$notInitializedTasks_Week2 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($tenWeeksAgo, $nineWeeksAgo)) {
					$notInitializedTasks_Week3 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($nineWeeksAgo, $eightWeeksAgo)) {
					$notInitializedTasks_Week4 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($eightWeeksAgo, $sevenWeeksAgo)) {
					$notInitializedTasks_Week5 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($sevenWeeksAgo, $sixWeeksAgo)) {
					$notInitializedTasks_Week6 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($sixWeeksAgo, $fiveWeeksAgo)) {
					$notInitializedTasks_Week7 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($fiveWeeksAgo, $fourWeeksAgo)) {
					$notInitializedTasks_Week8 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($fourWeeksAgo, $threeWeeksAgo)) {
					$notInitializedTasks_Week9 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($threeWeeksAgo, $twoWeeksAgo)) {
					$notInitializedTasks_Week10 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($twoWeeksAgo, $oneWeeksAgo)) {
					$notInitializedTasks_Week11 += 1;
				} elseif (Carbon::createFromFormat('Y-m-d H:i:s',$task->created_at)->between($oneWeeksAgo, Carbon::now())) {
					$notInitializedTasks_Week12 += 1;
				}
			}
		}

		\Lava::TableChart('not_initialized_tasks_table_div', $datatable, [
			'title' => 'Tarefas Não-inicializadas',
			'legend' => [
				'position' => 'in'
			],
			'allowHtml' => true,
		]);
		
		$datatable_columnChart->addRow([$tweelveWeeksAgo, $notInitializedTasks_Week1]);
		$datatable_columnChart->addRow([$elevenWeeksAgo, $notInitializedTasks_Week2]);
		$datatable_columnChart->addRow([$tenWeeksAgo,$notInitializedTasks_Week3]);
		$datatable_columnChart->addRow([$nineWeeksAgo, $notInitializedTasks_Week4]);
		$datatable_columnChart->addRow([$eightWeeksAgo, $notInitializedTasks_Week5]);
		$datatable_columnChart->addRow([$sevenWeeksAgo, $notInitializedTasks_Week6]);
		$datatable_columnChart->addRow([$sixWeeksAgo, $notInitializedTasks_Week7]);
		$datatable_columnChart->addRow([$fiveWeeksAgo, $notInitializedTasks_Week8]);
		$datatable_columnChart->addRow([$fourWeeksAgo, $notInitializedTasks_Week9]);
		$datatable_columnChart->addRow([$threeWeeksAgo, $notInitializedTasks_Week10]);
		$datatable_columnChart->addRow([$twoWeeksAgo, $notInitializedTasks_Week11]);
		$datatable_columnChart->addRow([$oneWeeksAgo, $notInitializedTasks_Week12]);
		
		\Lava::ColumnChart('not_initialized_tasks_chart_div', $datatable_columnChart, [
			'title' => 'Tarefas Não-inicializadas por Semana',
			'legend' => [
				'position' => 'none'
			],
			'hAxis' => [
				'format' => 'd MMM, y',
			],
			'vAxis' => [
				'baseline' => 0,
				'minValue' => 0.0,
			],
		]);
		
		return view('dashboards.not_initialized_tasks_report');
	}
	
	
	public function unfeasibleTasksReport() {
		// Tabela de Tarefas Não-executáveis
		$datatable = \Lava::DataTable();
		$datatable->addStringColumn('Tarefa');
		$datatable->addStringColumn('Status');
		// $datatable->addStringColumn('Competências Requeridas'); -> é uma ideia, mas isso acarretaria MUITO mais processamento
		
		$tasks = \App\Task::all();
		$all_learning_aids_competencies = DB::table('learning_aids_competencies')->select('competency_id')->distinct()->get();
		$all_user_competences = DB::table('user_competences')->select('competence_id')->distinct()->get();
		$unfeasible_tasks_ids = array();
		$unfeasible_tasks_titles = array();
		foreach ($tasks as $task) {
			$found_competence = false;
			foreach ($task->competencies as $task_competence) {
				foreach($all_learning_aids_competencies as $learning_aid_competence) {
					if ($learning_aid_competence->competency_id == $task_competence->id) {
						$found_competence = true;
						break;
					}
				}
				if ($found_competence == true) {
					break;
				}
				foreach($all_user_competences as $user_competence) {
					if ($user_competence->competence_id == $task_competence->id) {
						$found_competence = true;
						break;
					}
				}
				if ($found_competence == true) {
					break;
				}
			}
			if ($found_competence == false) {
				$datatable->addRow(["<a href='".route('tasks.show', $task->id)."'>".$task->title."</a>", "Não-executável", ]);
			}
		}
		
		\Lava::TableChart('unfeasible_tasks_report_table', $datatable, [
			'title' => 'Tabela de Tarefas Não-executáveis',
			'legend' => [
				'position' => 'in'
			],
			'allowHtml' => true,
		]);
		
		return view('dashboards.unfeasible_tasks_report');
	}
	
	
	public function coveredCompetencesReport() {
		$all_learning_aids_competencies = DB::table('learning_aids_competencies')->select('competency_id')->distinct()
			->join('user_competences', 'user_competences.competence_id', '<>', 'learning_aids_competencies.competency_id')
			->get();
		//$all_user_competences = DB::table('user_competences')->select('competence_id')->distinct()->get();
		
		
		var_dump($all_learning_aids_competencies);
		
		return view('dashboards.covered_competences_report');
	}
	
	
	public function neededCompetencesReport() {
		return view('dashboards.needed_competences_report');
	}
	
	
	public function mostLearnedCompetencesReport() {
		return view('dashboards.most_learned_competences_report');
	}
	
	
	public function mostCollaborativeUsersReport() {	
		$personal_competence_level_id_max = \DB::table('personal_competence_proficiency_levels')->max('id');
		$users_with_collaboration_level = DB::table('users')->select('name')->join('answers', 'users.id', '=', 'answers.evaluated_user_id')->select(DB::raw('avg(personal_competence_level_id) / ' . $personal_competence_level_id_max . ' as collab_level, name, evaluated_user_id'))->groupBy('evaluated_user_id', 'name')->orderBy('collab_level', 'desc')->get();
		
		$datatable = \Lava::DataTable();
		$datatable->addStringColumn('Nome do Usuário');
		$datatable->addNumberColumn('Nível de Colaboração');
		
		foreach ($users_with_collaboration_level as $user) {
			$datatable->addRow(["<a href='".route('users.show', $user->evaluated_user_id)."'>".$user->name."</a>", $user->collab_level]);
			
		}
		
		\Lava::TableChart('most_collaborative_users_report_table', $datatable, [
			'title' => 'Tabela de Usuários mais Colaborativos',
			'legend' => [
				'position' => 'in'
			],
			'allowHtml' => true,
		]);
		
		return view('dashboards.most_collaborative_users_report');
	}
	
	
	public function mostCollaborativeGroupsReport() {
		return view('dashboards.most_collaborative_groups_report');
	}
	
	public function usersWhoDidntAnswerCollaborationFormReport() {
		
		return view('dashboards.users_who_didnt_answer_collaboration_form_report');
	}
	
	public function usersWithHighestCompetenceNumberReport() {
		return view('dashboards.users_with_highest_competence_number_report');
	}
	
	public function usersWithMoreTasksPerformedReport() {
		return view('dashboards.users_with_more_tasks_performed_report');
	}
	
}
