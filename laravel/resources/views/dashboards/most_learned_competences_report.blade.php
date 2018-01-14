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
				<div id="finished_tasks_div"></div>
				
			<br/>	

				<div>
                <h4>
                    
                </h4>
                <p></p>
                <h4>
                    
                </h4>
                <p> </p>

                <div class="panel panel-default">
                    <div class="panel-heading" >
                       
                    </div>
                    <div class="panel-body">
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                    </div>
                </div>
				
            </div>
        </div>
    </div>
@endsection
