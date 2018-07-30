@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    Lista de Competências com o Maior Nível de Aprendizado
                </h2>
            </div>
            <div class="panel-body">
			
			
			<center>Clique na competência para ver mais detalhes sobre ela</center>
			<br/>
			
			<?= Lava::render('TableChart', 'most_learned_competences_report_table', 'most_learned_competences_report_table_div') ?>
				<center><div id="most_learned_competences_report_table_div"></div></center>
				
			<br/>
        </div>
    </div>
@endsection
