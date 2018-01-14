@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading text-center text-capitalize"><h2>Dashboard</h2></div>

					<div class="panel-body">
						Aqui entram os gráficos
						<?= Lava::render('ColumnChart', 'Tarefas Finalizadas', 'finished_tasks_div') ?>
				<div id="finished_tasks_div"></div>
				
				<?= Lava::render('TableChart', 'basic_statistics_table', 'basic_statistics_table_div') ?>
				<div id="basic_statistics_table_div"></div>
					</div>
				</div>
			</div>
        </div>
        <div class="row">
            
			<div class="col-md-6">
				<div class="panel panel-default">
					
					<div class="panel-body">
						Aqui entram os gráficos
					</div>
				</div>
			</div>
        </div>
    </div>
@endsection