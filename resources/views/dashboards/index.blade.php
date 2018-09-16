@extends('layouts.app')
@section('content')
    <div class="container">
        
		<div class="panel panel-default">
			<div class="panel-body">
			<div class="panel-heading text-center text-capitalize"><h2>Dashboard</h2></div>
			
			<div class="row">
				
				<div class="col-xs-5 col-xs-offset-1">
					<?= Lava::render('ColumnChart', 'learning_aids_column_chart_for_dashboard', 'learning_aids_column_chart_for_dashboard_div') ?>
					<div id="learning_aids_column_chart_for_dashboard_div"></div>
				</div>
				
				<div class="col-xs-5 col-xs-offset-1">
					<?= Lava::render('TableChart', 'basic_statistics_table', 'basic_statistics_table_div') ?>
					<div id="basic_statistics_table_div"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-5 col-xs-offset-1">
					<?= Lava::render('PieChart', 'feasible_tasks_pie_chart', 'feasible_tasks_pie_chart_div') ?>
					<div id="feasible_tasks_pie_chart_div"></div>
				</div>
				<div class="col-xs-5 col-xs-offset-1">
					@if (Lava::exists('GaugeChart', 'average_collaboration_level_circle'))
						<?= Lava::render('GaugeChart', 'average_collaboration_level_circle', 'average_collaboration_level_circle_div') ?>
							<div id="average_collaboration_level_circle_div"></div>
					@else
						<div id="average_collaboration_level_circle_div">
							<h6><b>Indicador de nível médio de colaboraçao</b></h6>
							Ainda não há dados suficientes para exibiçao desta métrica.
						</div>

					@endif

				</div>
			</div>
			
			<div class="row">
				<div class="panel-heading text-center text-capitalize"><h2>Relatórios</h2></div>
				
				<div class="col-xs-5 col-xs-offset-1">
					<h3>Tarefas</h3>
					<div><a href="{{ route('finished-tasks-report') }}">Listar Tarefas Finalizadas </a></div>
					<div><a href="{{ route('not-finished-tasks-report') }}">Listar Tarefas em Andamento </a></div>
					<div><a href="{{ route('not-initialized-tasks-report') }}">Listar Tarefas Não-inicializadas </a></div>
					<div><a href="{{ route('unfeasible-tasks-report') }}">Listar Tarefas Não-executáveis </a></div>
					
					<h3>Usuários</h3>
					<div><a href="{{ route('users-with-highest-competence-number-report') }}">Listar Usuários com maior Número de Competências</a></div>
					<div><a href="{{ route('users-with-more-tasks-performed-report') }}">Listar Usuários com maior Participação em Tarefas</a></div>
				</div>
				
				<div class="col-xs-5">
					<h3>Competências</h3>
					<div><a href="{{ route('covered-competences-report') }}">Listar Competências Abrangidas (em Treinamentos ou Pessoas)</a></div>
					<div><a href="{{ route('needed-competences-report') }}">Listar Competências Não-mapeadas (em Treinamentos ou Pessoas)</a></div>
					<div><a href="{{ route('most-learned-competences-report') }}">Listar Competências com Maior Nível de Aprendizado Médio</a></div>
					
					<h3>Colaboração</h3>
					<div><a href="{{ route('most-collaborative-users-report') }}">Listar Usuários mais Colaborativos</a></div>
					<div><a href="{{ route('most-collaborative-groups-report') }}">Listar Grupos mais Colaborativos</a></div>
					{{--<div><a href="{{ route('users-who-didnt-answer-collaboration-form-report') }}">Listar Usuários com Resposta Pendente ao Formulário</a></div>--}}
				</div>		
			</div>
			<div class="row">
			<h3> </h3>
			</div>
		</div>
        
    </div>
@endsection