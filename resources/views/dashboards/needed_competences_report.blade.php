@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    Lista de Competências Não-mapeadas (por Pessoas ou Treinamentos)
                </h2>
            </div>
            <div class="panel-body">
			
			<center>Clique na competência para ver detalhes a respeito dela</center>
			<br/>
			
			<?= Lava::render('TableChart', 'needed_competences_report', 'needed_competences_report_div') ?>
				<center><div id="needed_competences_report_div"></div></center>
				
			<br/>	

        </div>
    </div>
@endsection
