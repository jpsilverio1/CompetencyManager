@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    Tarefas Finalizadas
                </h2>
            </div>
            <div class="panel-body">
				<?= Lava::render('TableChart', 'Tarefas Finalizadas', 'finished_tasks_div') ?>
					<center><div id="finished_tasks_div"></div></center>
				
				<br>
					
				<?= Lava::render('ColumnChart', 'finished_tasks_chart_div', 'finished_tasks_chart_div') ?>
					<div id="finished_tasks_chart_div"></div>
			</div>
    </div>
@endsection
