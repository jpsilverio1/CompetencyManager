@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    Tarefas Não-inicializadas
                </h2>
            </div>
            <div class="panel-body">
				<?= Lava::render('TableChart', 'not_initialized_tasks_table_div', 'not_initialized_tasks_table_div') ?>
					<center><div id="not_initialized_tasks_table_div"></div></center>
				
				<br>
					
				<?= Lava::render('ColumnChart', 'not_initialized_tasks_chart_div', 'not_initialized_tasks_chart_div') ?>
					<div id="not_initialized_tasks_chart_div"></div>
            </div>
        </div>
    </div>
@endsection
