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
			
			
			
			
			<?= Lava::render('TableChart', 'most_collaborative_users_report_table', 'most_collaborative_users_report_div') ?>
				<center><div id="most_collaborative_users_report_div"></div></center>
				
			<br/>	

        </div>
    </div>
@endsection
