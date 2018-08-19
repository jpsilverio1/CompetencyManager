@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    Tarefas Não-executáveis
                </h2>
            </div>
            <div class="panel-body">
			
				<?= Lava::render('TableChart', 'unfeasible_tasks_report_table', 'unfeasible_tasks_report_table') ?>
					<center><div id="unfeasible_tasks_report_table"></div></center>
				
            </div>
        </div>
    </div>
@endsection
