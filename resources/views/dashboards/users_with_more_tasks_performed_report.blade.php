@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    Lista de Usuários com maior Participação em Tarefas
                </h2>
            </div>
            <div class="panel-body">
			
			
			<center>Clique no usuário para ver as tarefas com as quais ele está associado</center>
			<br/>
			
			<?= Lava::render('TableChart', 'users_with_more_tasks_performed_table', 'users_with_more_tasks_performed_table_div') ?>
				<center><div id="users_with_more_tasks_performed_table_div"></div></center>
				
			<br/>	
        </div>
    </div>
@endsection
