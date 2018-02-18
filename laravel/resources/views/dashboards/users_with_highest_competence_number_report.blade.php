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
			
			
			
			
			<?= Lava::render('TableChart', 'users_with_highest_competence_number_table', 'users_with_highest_competence_number_report_div') ?>
				<center><div id="users_with_highest_competence_number_report_div"></div></center>
				
			<br/>	
				
            </div>
        </div>
    </div>
@endsection
